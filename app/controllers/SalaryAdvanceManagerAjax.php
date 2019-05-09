<?php /** @noinspection ALL */

use Simple\json;

class SalaryAdvanceManagerAjax extends Controller
{
    /**
     * ActionLists constructor.
     */
    public function __construct()
    {
        parent::__construct();
        /* if (!isLoggedIn()) {
             redirect('users/login');
         }*/
    }

    public function index()
    {
        // Get posts
        $current_user = getUserSession();
        $db = Database::getDbh();
        $ret = [];
        if (isset($_GET['id_salary_advance'])) {
            $ret = SalaryAdvanceModel::get(['id_salary_advance' => $_GET['id_salary_advance']]);
        } else {
            if ($current_user->user_id == getCurrentHR() || $current_user->user_id == getCurrentFgmr()) {
               $ret =  $db->where('user_id', $current_user->user_id, 'IS NOT')
                    ->get('salary-advancce');
            } else {
               $ret = $db->where('user_id', $current_user->user_id, 'IS NOT')
                    ->where('department_id', $current_user->department_id, 'IS NOT')
                    ->get('salary-advancce');
            }
        }
        $json = new json();
        $json->data = $ret;
        isset($_GET['callback']) ? $json->send_callback($_GET['callback']) : $json->send();
    }

    public function Create()
    {
        //$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        $json = new json();
        $payload = json_decode($_POST['payload']);
        $payload->date_raised = (new \Moment\Moment($payload->date_raised))->format(\Moment\Moment::NO_TZ_MYSQL);
        unset($payload->id_salary_advance);
        $id = SalaryAdvanceModel::insert((array)$payload);
        $ret = SalaryAdvanceModel::getOne($id);
        $json->data = $ret;
        isset($_GET['callback']) ? $json->send_callback($_GET['callback']) : $json->send();
    }

    /**
     * @param $id_salary_advance
     */
    public function Update($id_salary_advance)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST array
            //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $json = new json();
            $payload = json_decode($_POST['payload']);
            $payload->date_raised = (new \Moment\Moment($payload->date_raised))->format(\Moment\Moment::NO_TZ_MYSQL);
            $action = (new CmsActionList());
            $action->update($cms_action_list_id, (array)$payload);
            $ret = $action->fetchSingle($cms_action_list_id);
            $json->data = $ret;
            isset($_GET['callback']) ? $json->send_callback($_GET['callback']) : $json->send();
        } else {
            // Get existing post from model
            $action = (new CmsActionList(['cms_action_list_id' => $cms_action_list_id]));
            if ($action) {
                $json = new \Simple\json();
                /** @noinspection PhpUndefinedFieldInspection */
                $json->data = $action;
                isset($_GET['callback']) ? $json->send_callback($_GET['callback']) : $json->send();
            }
        }
    }

    public function Destroy($cms_action_list_id)
    {
        $json = new json();
        $action = (new CmsActionList());
        $payload = json_decode($_POST['payload']);
        $ret = $action->destroy($cms_action_list_id);
        //$ret = $action->fetchSingle($cms_action_list_id);
        $json->data = $payload;
        isset($_GET['callback']) ? $json->send_callback($_GET['callback']) : $json->send();
    }
}
<?php /** @noinspection ALL */

use Simple\json;

class EmployeesAjax extends Controller
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
        $fmgr = getCurrentFgmr();
        $hr = getCurrentHR();
        $db = Database::getDbh();
        $ret = [];
        if (isset($_GET['user_id'])) {
            $ret = User::get(['user_id' => $_GET['user_id']]);
        } else {
            $ret = $db->orderBy('first_name', 'ASC')
                ->get('users');
        }
        foreach ($ret as $key => &$value) {
            $employee = new stdClass();
            $employee->name = concatNameWithUserId($value['user_id']);
            $employee->user_id = $value['user_id'];
            $value['employee'] = $employee;
            unset($value['password']);
        }
        echo json_encode($ret);
        /*$json = new json();
        $json->data = $ret;
        isset($_GET['callback']) ? $json->send_callback($_GET['callback']) : $json->send();*/
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
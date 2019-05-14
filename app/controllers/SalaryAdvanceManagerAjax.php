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
        $fmgr = getCurrentFgmr();
        $hr = getCurrentHR();
        $db = Database::getDbh();
        $ret = [];
        if (isset($_GET['id_salary_advance'])) {
            $ret = SalaryAdvanceModel::get(['id_salary_advance' => $_GET['id_salary_advance']]);
        } else {
            if ($current_user->user_id == $hr || $current_user->user_id == $fmgr) {
                $ret = $db->where('user_id', $current_user->user_id, '!=')
                    ->get('salary_advance');
            } else {
                $ret = $db->where('user_id', $current_user->user_id, '!=')
                    ->where('department_id', $current_user->department_id)
                    ->get('salary_advance');
            }
        }
        $ret = $this->transformArrayData($ret);
        echo json_encode($ret);
        /* $json = new json();
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
        echo json_encode($ret);
    }

    /**
     * @param $id_salary_advance
     */
    public function Update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $id_salary_advance = $_POST['id_salary_advance'];
            $payload['amount_requested'] = $_POST['amount_requested'];
            $payload['hod_comment'] = $_POST['hod_comment'];
            $payload['hr_comment'] = $_POST['hr_comment'];
            $payload['fmgr_comment'] = $_POST['fmgr_comment'];
            $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                ->update('salary_advance', $payload);
            if ($ret) {
                $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                    ->get('salary_advance');
                $ret = $this->transformArrayData($ret);
            }
            echo json_encode($ret);
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

    private function transformArrayData($ret) {
        $current_user = getUserSession();
        $fmgr = getCurrentFgmr();
        $hr = getCurrentHR();
        foreach ($ret as $key => &$value) {
            $hod = getCurrentManager($value['department_id']);
            $employee = new stdClass();
            $employee->name = concatNameWithUserId($value['user_id']);
            $employee->user_id = $value['user_id'];
            $value['employee'] = $employee;
            $value['department'] = getDepartment($value['user_id']);
            unset($value['password']);
            if ($hod == $current_user->user_id) {
                $value['hod_comment_editable'] = true;
                $value['hod_approval_editable'] = true;
            } else {
                $value['hod_comment_editable'] = false;
                $value['hod_approval_editable'] = false;
            }
            if ($hr == $current_user->user_id && $value['hod_approval']) {
                $value['hr_comment_editable'] = true;
                $value['hr_approval_editable'] = true;
            } else {
                $value['hr_comment_editable'] = false;
                $value['hr_approval_editable'] = false;
            }
            if ($fmgr == $current_user->user_id && $value['hr_approval']) {
                $value['fmgr_comment_editable'] = true;
                $value['fmgr_approval_editable'] = true;
            } else {
                $value['fmgr_comment_editable'] = false;
                $value['fmgr_approval_editable'] = false;
            }
        }
        return $ret;
    }
}
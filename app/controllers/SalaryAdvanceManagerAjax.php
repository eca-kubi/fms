<?php /** @noinspection ALL */

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

    private function transformArrayData($ret)
    {
        $current_user = getUserSession();
        $fmgr = getCurrentFgmr();
        $hr = getCurrentHR();
        foreach ($ret as $key => &$value) {
            $hod = getCurrentManager($value['department_id']);
            $employee = new stdClass();
            $employee->name = concatNameWithUserId($value['user_id']);
            $employee->user_id = $value['user_id'];
            $employee->department = getDepartment($value['user_id']);
            $value['department'] = $employee->department;
            $value['employee'] = $employee;
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
                $value['amount_requested_editable'] = true;
            } else {
                $value['fmgr_comment_editable'] = false;
                $value['fmgr_approval_editable'] = false;
                $value['amount_requested_editable'] = false;
            }
        }
        return $ret;
    }

    public function Create()
    {
        /*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $ret = Database::getDbh()->insert('salary_advance', ['amount_requested' => $_POST['amount_requested']]);
            if ($ret) {
                $ret = Database::getDbh()->where('id_salary_advance', $ret)
                    ->get('salary_advance');
                $ret[0]['success'] = true;
            } else {
                $ret[0]['success'] = false;
            }
            echo json_encode($ret);
        }*/
    }

    /**
     * @param $id_salary_advance
     */
    public function Update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $payload = [];
            $current_user = getUserSession();
            $id_salary_advance = $_POST['id_salary_advance'];
            $old_ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                ->getOne('salary_advance');
            $data['reference'] = $old_ret['department_ref'];
            if ($_POST['hod_comment_editable'] === 'true') {
                $payload['hod_approval'] = $_POST['hod_approval'] === 'false' ? false : true;
                $payload['hod_comment'] = $_POST['hod_comment'];
                $data['comment'] = $_POST['hod_comment'];
                $data['approval'] = $_POST['hod_approval'];
            }
            if ($_POST['hr_comment_editable'] === 'true') {
                $payload['hr_approval'] = $_POST['hr_approval'] === 'false' ? false : true;
                $payload['hr_comment'] = $_POST['hr_comment'];
                $data['comment'] = $_POST['hr_comment'];
                $data['approval'] = $_POST['hr_approval'];
            }
            if($_POST['fmgr_comment_editable'] === 'true') {
                $payload['fmgr_approval'] = $_POST['fmgr_approval'] === 'false' ? false : true;
                $payload['fmgr_comment'] = $_POST['fmgr_comment'];
                $payload['amount_requested'] = $_POST['amount_requested'];
                $data['comment'] = $_POST['fmgr_comment'];
                $data['approval'] = $_POST['fmgr_approval'];
            }
            $data['old_amount'] = number_format($old_ret['amount_requested']);
            $data['new_amount'] = number_format($_POST['amount_requested']);
            $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                ->update('salary_advance', $payload);
            if ($ret) {
                $remarks = get_include_contents('action_log/approval', $data);
                insertLog($id_salary_advance, ACTION_SALARY_ADVANCE_UPDATE, $remarks, $current_user->user_id);
                $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                    ->get('salary_advance');
                $ret = $this->transformArrayData($ret);
                $ret[0]['success'] = true;
            } else {
                $ret[0]['success'] = false;
            }
            echo json_encode($ret);
        }
    }

    public function Destroy()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $ret = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
            ->delete('salary_advance');
        if ($ret) {
            echo json_encode(array('success' => true));
            return;
        }
        echo json_encode(array('success' => false));
    }
}
<?php /** @noinspection ALL */

class SalaryAdvanceSecretaryAjax extends Controller
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
        $ret = $salary_advances = [];
        if (isset($_GET['id_salary_advance'])) {
            $ret = SalaryAdvanceModel::get(['id_salary_advance' => $_GET['id_salary_advance']]);
        } else {
            $ret = Database::getDbh()->where('user_id', $current_user->user_id)
                ->get('salary_advance_secretary');
            foreach ($ret as $item) {
                Database::getDbh()->orWhere('department_id', $item['department_assigned']);
            }
            $salary_advances = Database::getDbh()->where('raised_by_secretary', true)
                ->get('salary_advance');
        }
        $salary_advances = $this->transformArrayData($salary_advances);
        echo json_encode($salary_advances);
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
            $employee->department = getDepartment($value['user_id']);
            $value['name'] = $employee->name;
            $value['department'] = $employee->department;
            //$value['employee'] = $employee;
            unset($value['password']);
            /*if ($hod == $current_user->user_id) {
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
            }*/
        }
        return $ret;
    }

    public function Create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $current_user = getUserSession();
            $data['user_id'] = $_POST['user_id'];
            $data['raised_by_id'] = getUserSession()->user_id;
            $data['department_id'] = $_POST['department_id'];
            $data['amount_requested'] = $_POST['amount_requested'];
            $data['department_ref'] = genDeptRef($current_user->department_id);
            $data['raised_by_secretary'] = true;
            $ret = Database::getDbh()->insert('salary_advance', $data);
            if ($ret) {
                $remarks = get_include_contents('action_log/salary_advance_raised_by_secretary', $data);
                insertLog($ret, ACTION_SALARY_ADVANCE_UPDATE, $remarks, $current_user->user_id);
                $ret = Database::getDbh()->where('id_salary_advance', $ret)
                    ->get('salary_advance');
                $ret[0]['success'] = true;
            } else {
                $ret[0]['success'] = false;
                $ret[0]['reason'] = 'An error occured';
            }
            $ret = $this->transformArrayData($ret);
            echo json_encode($ret);
        }
    }

    /**
     * @param $id_salary_advance
     */
    public function Update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $current_user = getUserSession();
            $id_salary_advance = $_POST['id_salary_advance'];
            $ret = [];
            $old_ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                ->getOne('salary_advance');
            if ($old_ret['fmgr_approval']) {
                $old_ret['success'] = false;
                $old_ret['reason'] = 'Finance manager has already reviewed this application!';
                $ret[] = $old_ret;
            } else if ($old_ret['hr_approval']) {
                $old_ret['success'] = false;
                $old_ret['reason'] = 'HR has already reviewed this application!';
                $ret[] = $old_ret;
            } else {
                $data['department_ref'] = $old_ret['department_ref'];
                $data['old_amount'] = number_format($old_ret['amount_requested']);
                $data['new_amount'] = number_format($_POST['amount_requested']);
                $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                    ->update('salary_advance', ['amount_requested' => $_POST['amount_requested']]);
                if ($ret) {
                    $remarks = get_include_contents('action_log/salary_advance_updated_by_secretary', $data);
                    insertLog($id_salary_advance, ACTION_SALARY_ADVANCE_UPDATE, $remarks, $current_user->user_id);
                    $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                        ->get('salary_advance');
                    $ret[0]['success'] = true;
                } else {
                    $ret[0]['success'] = false;
                    $ret[0]['reason'] = 'An error occured';
                }
            }
            $ret = $this->transformArrayData($ret);
            echo json_encode($ret);
        }
    }

    public function Destroy()
    {
        $ret = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
            ->update('salary_advance', ['deleted' => true]);
        $data['department_ref'] = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
            ->getValue('salary_advance', 'department_ref');
        $remarks = get_include_contents('action_log/salary_advance_deleted', $data);
        insertLog($_POST['id_salary_advance'], ACTION_SALARY_ADVANCE_RAISED, $remarks, getUserSession()->user_id);
        if ($ret) {
            $ret = [['success' => true]];
            echo json_encode($ret);
            return;
        } else {
            $ret = [['success' => false, 'reason' => 'An error occured']];
            echo json_encode($ret);
        }
    }
}
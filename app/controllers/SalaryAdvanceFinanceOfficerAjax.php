<?php /** @noinspection ALL */

class SalaryAdvanceFinanceOfficerAjax extends Controller
{
    /**
     * ActionLists constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Get posts
        $current_user = getUserSession();
        $ret = $salary_advances = [];
        if (isset($_GET['id_salary_advance'])) {
            $ret = SalaryAdvanceModel::get(['id_salary_advance' => $_GET['id_salary_advance']]);
        } else {
            $salary_advances = Database::getDbh()->where('raised_by_secretary', false)
                ->where('deleted', false)
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
            $value['raised_by'] = $value['raised_by_id']? concatNameWithUserId($value['raised_by_id']): "";
            unset($value['password']);
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
            $payload = [];
            $ret = [];
            $old_ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                ->getOne('salary_advance');
            if (!($old_ret['hod_approval'] && $old_ret['fmgr_approval'] && $old_ret['hr_approval'])) {
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
            } else if ($old_ret['hod_approval'] && $old_ret['fmgr_approval'] && $old_ret['hr_approval']) {
                $data['department_ref'] = $old_ret['department_ref'];
                $data['amount_received'] = $_POST['amount_received'];
                $data['received_by'] = $_POST['received_by'];
                if (!$old_ret['date_received']) {
                    $data['date_received'] = now();
                }
                $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                    ->update('salary_advance', $data);
                if ($ret) {
                    $remarks = get_include_contents('action_log/amount_received_updated_by_secretary', $data);
                    insertLog($id_salary_advance, ACTION_SALARY_ADVANCE_UPDATE, $remarks, $current_user->user_id);
                    $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                        ->get('salary_advance');
                    $ret[0]['success'] = true;
                } else {
                    $ret[0]['success'] = false;
                    $ret[0]['reason'] = 'An error occured';
                }
            } else{
                $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                    ->get('salary_advance');
                if ($ret) {
                    $ret[0]['success'] = true;
                }
            }
            $ret = $this->transformArrayData($ret);
            echo json_encode($ret);
        }
    }

    public function Destroy()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $ret = [];
        $old_ret = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
            ->getOne('salary_advance');
        if ($old_ret['hod_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'The HoD has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['The HoD has already reviewed this application!'];
        } else if ($old_ret['fmgr_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'Finance manager has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['Finance manager has already reviewed this application!'];
        } else if ($old_ret['hr_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'HR has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['HR has already reviewed this application!'];
        } else {
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
            }
        }
        echo json_encode($ret);
    }
}
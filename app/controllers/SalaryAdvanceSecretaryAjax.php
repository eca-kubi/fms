<?php /** @noinspection ALL */

class SalaryAdvanceSecretaryAjax extends Controller
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
            $ret = Database::getDbh()->where('user_id', $current_user->user_id)
                ->get('salary_advance_secretary');
            foreach ($ret as $item) {
                Database::getDbh()->orWhere('department_id', $item['department_assigned']);
            }
            $salary_advances = Database::getDbh()->orderBy('date_raised')
                ->where('raised_by_secretary', true)
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
            $employee->department = getDepartment((new User($value['user_id']))->department_id);
            $value['employee'] = $employee;
            $value['name'] = $employee->name;
            $value['department'] = $employee->department;
            $value['raised_by'] = $value['raised_by_id'] ? concatNameWithUserId($value['raised_by_id']) : "";
            unset($value['password']);
        }
        return $ret;
    }

    public function Create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ret = [];
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $current_user = getUserSession();
            $data = [
                'amount_requested_is_percentage' => $_POST['amount_requested_is_percentage'] === 'true' ? true : false,
                'amount_requested' => $_POST['amount_requested'] ? $_POST['amount_requested'] : null,
                'raised_by_id' => getUserSession()->user_id,
                'percentage' => $_POST['percentage'],
                'user_id' =>$_POST['user_id'],
                'department_id' => $_POST['department_id'],
                'department_ref' => genDeptRef((new User($_POST['user_id']))->department_id),
                'raised_by_secretary' => true
            ];
            if ($data['amount_requested_is_percentage']) {
                //unset($data['amount_requested']);
                $data['amount_requested'] = null;
            } else {
                //unset($data['percentage']);
                $data['percentage'] = null;
            }
            if (hasActiveApplication( $data['user_id'])) {
                $ret['success'] = false;
                $ret['reason'] = 'An active application exists for this employee!';
                $ret['has_active_application'] = true;
                $ret['errors'] = ['message' => 'An Application is Active!', 'code' => ERROR_AN_APPLICATION_ALREADY_EXISTS];
                echo json_encode($ret);
                return;
            }
            $ret = Database::getDbh()->insert('salary_advance', $data);
            if ($ret) {
                $remarks = get_include_contents('action_log/salary_advance_raised_by_secretary', $data);
                insertLog($ret, ACTION_SALARY_ADVANCE_UPDATE, $remarks, $current_user->user_id);
                $ret = Database::getDbh()->where('id_salary_advance', $ret)
                    ->get('salary_advance');
                $ret[0]['success'] = true;
                $ret[0]['has_active_application'] = hasActiveApplication($data['user_id']);
            } else {
                $ret[0]['success'] = false;
                $ret[0]['reason'] = 'An error occured';
                $ret['errors'] = ['message' => 'An error occured!', 'code' => ERROR_UNSPECIFIED_ERROR];
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
            } else {
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
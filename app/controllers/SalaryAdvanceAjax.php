<?php /** @noinspection ALL */

class SalaryAdvanceAjax extends Controller
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
        $current_user = getUserSession();
        $db = Database::getDbh();
        $ret = [];
        if (isset($_GET['id_salary_advance'])) {
            $ret = SalaryAdvanceModel::get(['id_salary_advance' => $_GET['id_salary_advance']]);
        } else {
            $ret = $db->where('user_id', $current_user->user_id)
                ->orderBy('date_raised')
                ->where('deleted', false)
                ->get('salary_advance');
        }
        //$ret = $this->transformArrayData($ret);
        echo json_encode($ret);
    }

    public function Create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $current_user = getUserSession();
            $link = '';
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $ret = [];
            if (hasActiveApplication($current_user->user_id)) {
                $ret['success'] = false;
                $ret['reason'] = 'You already have an active application for this month!';
                $ret['has_active_application'] = true;
                $ret['errors'] = ['message' => 'An Application is Active!', 'code' => ERROR_AN_APPLICATION_ALREADY_EXISTS];
                echo json_encode($ret);
                return;
            }
            $data = [
                'amount_requested_is_percentage' => $_POST['amount_requested_is_percentage'] === 'true' ? true : false,
                'amount_requested' => $_POST['amount_requested'] ? $_POST['amount_requested'] : null,
                'percentage' => $_POST['percentage'],
                'user_id' => $current_user->user_id,
                'department_id' => $current_user->department_id,
                'department_ref' => genDeptRef($current_user->department_id)
            ];
            if ($data['amount_requested_is_percentage']) {
                //unset($data['amount_requested']);
                $data['amount_requested'] = null;
            } else {
                //unset($data['percentage']);
                $data['percentage'] = null;
            }
            $ret = Database::getDbh()->insert('salary_advance', $data);
            if ($ret) {
                $ret = Database::getDbh()->where('id_salary_advance', $ret)
                    ->get('salary_advance');
                //$ret = $this->transformArrayData($ret);
                $ret[0]['success'] = true;
                $ret[0]['has_active_application'] = hasActiveApplication($current_user->user_id);
                $ref_number = genDeptRef($current_user->department_id);
                $hod = new User(getCurrentManager($current_user->department_id));
                $hr = new User(getCurrentHR());
                $fmgr = new User(getCurrentFgmr());
                $data = ['ref_number' => $ref_number, 'link' => $link];
                $body = get_include_contents('email_templates/new_application', $data);
                insertEmail("Salary Advance Application[$ref_number]", $body, $hod->email, $hod->first_name . ' ' . $hod->last_name);
                insertEmail("Salary Advance Application[$ref_number]", $body, $hr->email, $hr->first_name . ' ' . $hr->last_name);
                insertEmail("Salary Advance Application[$ref_number]", $body, $fmgr->email, $fmgr->first_name . ' ' . $fmgr->last_name);
                $remarks = get_include_contents('action_log/salary_advance_raised', $data);
                insertLog($ret[0]['id_salary_advance'], ACTION_SALARY_ADVANCE_RAISED, $remarks, $current_user->user_id);
            } else {
                $ret[0]['success'] = false;
                $ret[0]['reason'] = 'An error occurred!';
                $ret['errors'] = ['message' => 'An error occured!', 'code' => ERROR_UNSPECIFIED_ERROR];
                $ret['has_active_application'] = hasActiveApplication($current_user->user_id);
                echo json_encode($ret);
                return;
            }
            echo json_encode($ret);
        }
    }

    public function Update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $current_user = getUserSession();
            $id_salary_advance = $_POST['id_salary_advance'];
            $ret = [];
            $data = [];
            $old_ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                ->getOne('salary_advance');
            if ($old_ret['hod_approval']) {
                $old_ret['success'] = false;
                $old_ret['reason'] = 'The HoD has already reviewed this application!';
                $ret[] = $old_ret;
            } else if ($old_ret['fmgr_approval']) {
                $old_ret['success'] = false;
                $old_ret['reason'] = 'Finance manager has already reviewed this application!';
                $ret[] = $old_ret;
            } else if ($old_ret['hr_approval']) {
                $old_ret['success'] = false;
                $old_ret['reason'] = 'HR has already reviewed this application!';
                $ret[] = $old_ret;
            } else {
                $data = [
                    'amount_requested_is_percentage' => $_POST['amount_requested_is_percentage'] === 'true' ? true : false,
                    'amount_requested' => $_POST['amount_requested'] ? $_POST['amount_requested'] : null,
                    'percentage' => $_POST['percentage']
                ];
                if ($data['amount_requested_is_percentage']) {
                    //unset($data['amount_requested']);
                    $data['amount_requested'] = null;
                } else {
                    //unset($data['percentage']);
                    $data['percentage'] = null;
                }
                $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                    ->update('salary_advance', $data);
                if ($ret) {
                    //$remarks = get_include_contents('action_log/salary_advance_updated_by_employee', $data);
                    //insertLog($id_salary_advance, ACTION_SALARY_ADVANCE_UPDATE, $remarks, $current_user->user_id);
                    $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)
                        ->get('salary_advance');
                    //$ret = $this->transformArrayData($ret);
                    $ret[0]['has_active_application'] = hasActiveApplication($current_user->user_id);
                    $ret[0]['success'] = true;
                } else {
                    $ret[0]['success'] = false;
                    $ret[0]['reason'] = 'An error occurred!';
                    $ret['errors'] = ['message' => 'An error occured!', 'code' => ERROR_UNSPECIFIED_ERROR];
                    $ret['has_active_application'] = hasActiveApplication($current_user->user_id);
                    echo json_encode($ret);
                    return;
                }
            }
            echo json_encode($ret);
        }
    }

    public function Destroy()
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $current_user = getUserSession();
        $ret = [];
        $old_ret = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
            ->getOne('salary_advance');
        if ($old_ret['hod_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'The HoD has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['message' => 'The HoD has already reviewed this application!', 'code' => ERROR_APPLICATION_ALREADY_REVIEWED];
        } else if ($old_ret['fmgr_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'Finance manager has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['message' => 'Finance manager has already reviewed this application!', 'code' => ERROR_APPLICATION_ALREADY_REVIEWED];
        } else if ($old_ret['hr_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'HR has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['message' => 'HR has already reviewed this application!', 'code' => ERROR_APPLICATION_ALREADY_REVIEWED];
        } else {
            $ret = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
                ->update('salary_advance', ['deleted' => true]);
            $data['department_ref'] = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
                ->getValue('salary_advance', 'department_ref');
            $remarks = get_include_contents('action_log/salary_advance_deleted', $data);
            insertLog($_POST['id_salary_advance'], ACTION_SALARY_ADVANCE_RAISED, $remarks, getUserSession()->user_id);
            if ($ret) {
                $ret = [];
                $ret['success'] = true;
                $ret['has_active_application'] = hasActiveApplication($current_user->user_id);
                echo json_encode($ret);
                return;
            } else {
                $ret = [];
                $ret = ['success' => false, 'reason' => 'An error occured'];
                $ret['has_active_application'] = hasActiveApplication($current_user->user_id);
                $ret['errors'] = ['message' => 'An error occured!', 'code' => ERROR_UNSPECIFIED_ERROR];
            }
        }
        echo json_encode($ret);
    }

    /*    private function transformArrayData($ret)
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
        }*/
}
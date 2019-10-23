<?php /** @noinspection ALL */

class SalaryAdvanceManagerAjax extends Controller
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
        $db = Database::getDbh();
        $current_user = getUserSession();
        if (isset($_GET['id_salary_advance'])) {
            $id_salary_advance = $_GET['id_salary_advance'];
            $db->where("id_salary_advance", $id_salary_advance);
            if ($db->has('salary_advance')) {
                $record = $db->get('salary_advance');
                $transformed_record = $this->transformArrayData($record);
                echo json_encode($transformed_record);
            }
        } else {
            if (isCurrentHR($current_user->user_id) || isCurrentFmgr($current_user->user_id) || isCurrentGM($current_user->user_id)) {
                $records = $db->where('user_id', $current_user->user_id, '!=')->orderBy('date_raised')->where('deleted', false)->get('salary_advance');
                $transformed_records = $this->transformArrayData($records);
                echo json_encode($transformed_records);
            } else {
                $records = $db->where('user_id', $current_user->user_id, '!=')->orderBy('date_raised')->where('deleted', false)->where('department_id', $current_user->department_id)->get('salary_advance');
                $transformed_records = $this->transformArrayData($records);
                echo json_encode($transformed_records);
            }
        }
    }

    private function transformArrayData($ret)
    {
        $current_user = getUserSession();
        foreach ($ret as $key => &$value) {
            $employee = new stdClass();
            $employee->name = concatNameWithUserId($value['user_id']);
            $employee->user_id = $value['user_id'];
            $employee->department = getDepartment((new User($value['user_id']))->department_id);
            $value['department'] = $employee->department;
            $value['employee'] = $employee;
            unset($value['password']);
            $value['hod_comment_editable'] = $value['hod_approval_editable'] = isCurrentManagerForDepartment($value['department_id'], $current_user->user_id);
            $value['hr_comment_editable'] = $value['hr_approval_editable'] = isCurrentHR($current_user->user_id);
            $value['gm_comment_editable'] = $value['gm_approval_editable'] = isCurrentGM($current_user->user_id);
            $value['fmgr_comment_editable'] = $value['fmgr_approval_editable'] = isCurrentFmgr($current_user->user_id);
        }
        return $ret;
    }

    public function isCommentEditable($mgr, $param) {
        $current_user = getUserSession();
        $fmgr = getCurrentFgmr();
        $hr = getCurrentHR();
        $hod = getCurrentManager($param['department_id']);
        if ($mgr === 'fmgr') {
            return ($fmgr == $current_user->user_id) && $param['hr_approval'];
        } else if ($mgr  == 'hr') {
            return ($hr == $current_user->user_id) && $param['hod_approval'];
        } else {
           return $hod == $current_user->user_id;
        }
    }

    public function Create()
    {
    }

    /**
     * @param $id_salary_advance
     */
    public function Update()
    {
        $hod_remarks = $hr_remarks = $fmgr_remarks = '';
        $db = Database::getDbh();
        $post_data = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $current_user = getUserSession();
            $id_salary_advance = $_POST['id_salary_advance'];
            $db->where("id_salary_advance", $id_salary_advance);
            if ($db->has('salary_advance')) {
                // Salary Advance Update
                $old_record = $db->getOne('salary_advance');
                $applicant = new User($applicant->user_id);
                if (isCurrentManagerForDepartment($old_record->department_id, $current_user->user_id)) {
                    // Head of Department
                    $post_data['hod_approval'] =  $_POST['hod_approval'];
                    $post_data['hod_comment'] = $_POST['hod_comment'];
                } elseif (isCurrentHR($current_user->user_id)) {
                    // HR
                    $post_data['hr_approval'] =  $_POST['hr_approval'];
                    $post_data['hr_comment'] = $_POST['hr_comment'];
                    $post_data['amount_payable'] =  $_POST['amount_payable'];
                } elseif (isCurrentGM($current_user->user_id)) {
                    // GM
                    $post_data['gm_approval'] =  $_POST['gm_approval'];
                    $post_data['gm_comment'] = $_POST['gm_comment'];
                }
                elseif (isCurrentFmgr($current_user->user_id)) {
                    // Fmgr
                    $post_data['fmgr_approval'] =  $_POST['fmgr_approval'];
                    $post_data['fmgr_comment'] = $_POST['fmgr_comment'];
                    $post_data['amount_approved'] = $_POST['amount_approved'];
                }
                $record_updated = $db->update('salary_advance', $post_data);
                if ($record_updated) {
                    $updated_record = $db->getOne('salary_advance');
                    $transformed_record = $this->transformArrayData($updated_record);
                    $transformed_record[0]['success'] = true;
                    echo json_encode($transformed_record);
                } else {
                    $transformed_record = $this->transformArrayData($old_record);
                    $transformed_record[0]['success'] = false;
                    $transformed_record[0]['reason'] = 'The record failed to update.';
                    echo json_encode($transformed_record);
                }
            }
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
<?php

class SalaryAdvanceSingleRequestsAjax extends Controller
{
    public function index(): void
    {
        $current_user = getUserSession();
        if (!(isCurrentHR($current_user->user_id) || isCurrentFmgr($current_user->user_id) || isCurrentGM($current_user->user_id) || isFinanceOfficer($current_user->user_id))) {
            echo json_encode(getSalaryAdvance(['deleted' => false, 'department_id' => $current_user->department_id, 'is_bulk_request' => false]), JSON_THROW_ON_ERROR, 512);
        } else {
            echo json_encode(getSalaryAdvance(['deleted' => false, 'is_bulk_request' => false]), JSON_THROW_ON_ERROR, 512);
        }
    }

    public function Update(): void
    {
        updateSalaryAdvance();
    }

    public function Destroy(): void
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
                echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
                return;
            }
            $ret = [['success' => false, 'reason' => 'An error occurred']];
        }
        echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
    }
}
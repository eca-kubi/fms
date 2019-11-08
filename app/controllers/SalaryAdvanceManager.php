<?php

class SalaryAdvanceManager extends Controller
{
    public function index($id_salary_advance = null): void
    {
        $db = Database::getDbh();
        $payload['current_user'] = $current_user = getUserSession();
        $payload['title'] = 'Salary Advance Applications';
        $payload['select_row_id'] = $id_salary_advance;
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance-manager/index/' . $id_salary_advance);
        }
        if (!(isCurrentManager($current_user->user_id) || isCurrentFmgr($current_user->user_id) || isCurrentHR($current_user->user_id) || isCurrentGM($current_user->user_id) || isFinanceOfficer($current_user->user_id))) {
            redirect('salary-advance');
        }
        if ($id_salary_advance && !$db->where('id_salary_advance', $id_salary_advance)->has('salary_advance')) {
            redirect('errors/index/404');
        }
        $this->view('salary-advance-manager/index', $payload);
    }
}

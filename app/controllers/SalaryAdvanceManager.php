<?php

class SalaryAdvanceManager extends Controller
{
    public function index($request_number = null): void
    {
        $db = Database::getDbh();
        $payload['current_user'] = $current_user = getUserSession();
        $payload['title'] = 'Salary Advance Applications';
        $payload['request_number'] = $request_number;
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance-manager/index/' . $request_number);
        }
        if (!(isCurrentManager($current_user->user_id) || isCurrentFmgr($current_user->user_id) || isCurrentHR($current_user->user_id) || isCurrentGM($current_user->user_id) || isFinanceOfficer($current_user->user_id))) {
            redirect('salary-advance');
        }
        if ($request_number && !$db->where('request_number', $request_number)->has('salary_advance')) {
            redirect('errors/index/404');
        }
        $this->view('salary-advance-manager/index', $payload);
    }
}

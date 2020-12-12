<?php

class SalaryAdvanceFinanceOfficer extends Controller
{
    public function index($id_salary_advance = null): void
    {
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/index/' . $id_salary_advance);
        } else {
            $payload['current_user'] = $current_user = getUserSession();
            $payload['title'] = 'Salary Advance Applications';
            $payload['select_row_id'] = $id_salary_advance;
            if (!isFinanceOfficer($current_user->user_id)) {
                redirect('salary-advance/index' . $id_salary_advance);
            }
            $this->view('salary-advance-manager/index', $payload);
        }
    }

}

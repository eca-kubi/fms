<?php

class SalaryAdvance extends Controller
{
    public function index($id_salary_advance = null): void
    {
        $db = Database::getDbh();
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/index/' . $id_salary_advance);
        } else {
            $payload = [];
            $payload['current_user'] = $current_user = getUserSession();
            $payload['title'] = 'Salary Advance Applications';
            $payload['select_row_id'] = $id_salary_advance;
            if ($id_salary_advance && !$db->where('id_salary_advance', $id_salary_advance)->has('salary_advance')) {
                redirect('errors/index/404');
            }
            if ($id_salary_advance && isTheApplicant($current_user->user_id, $id_salary_advance)) {
                $payload['select_row_id'] = $id_salary_advance;
            }
            $this->view('salary-advance/all', $payload);
        }
    }


    public function single($id_salary_advance = null): void
    {
        $payload = [];
        $db = Database::getDbh();
        $payload['current_user'] = $current_user = getUserSession();
        $payload['title'] = 'View Salary Advance';
        if (!$db->where('id_salary_advance', $id_salary_advance)->has('salary_advance')) {
            redirect('errors/index/404');
        }
        if (isTheApplicant($current_user->user_id, $id_salary_advance)) {
            redirect('salary-advance');
        }
        $payload['select_row_id'] = $id_salary_advance;
        $this->view('salary-advance/all', $payload);
    }
}
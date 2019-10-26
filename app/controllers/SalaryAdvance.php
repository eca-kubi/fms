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
            $payload['current_user'] = getUserSession();
            $payload['title'] = 'Salary Advance Applications';
            $payload['select_row_id'] = $id_salary_advance;
            if ($id_salary_advance && !$db->where('id_salary_advance', $id_salary_advance)->has('salary_advance')) {
                redirect('errors/index/404');
            }
            $this->view('salary-advance/index', $payload);
        }
    }
}
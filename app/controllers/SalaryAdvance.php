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

    public function bulkRequests(string $bulk_request_number = null): void
    {
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/bulk-requests/' . $bulk_request_number);
        }
        $current_user =  $payload['current_user'] = getUserSession();

        if (!(isCurrentManager($current_user->user_id) || isAssignedAsSecretary($current_user->user_id) || isFinanceOfficer($current_user->user_id))) {
            redirect('salary-advance/index');
        }
        $payload['title'] = 'Bulk Salary Advance Applications';
        $payload['bulk_request_number'] = $bulk_request_number;
        if ($bulk_request_number) {
            $this->view('salary-advance/update_bulk_requests', $payload);
        } else {
            $this->view('salary-advance/bulk_requests', $payload);
        }
    }

    public function newBulkRequest() : void
    {
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/new-bulk-request/');
        }
        $current_user = $payload['current_user']  = getUserSession();
        if (!isAssignedAsSecretary($current_user->user_id)) {
            redirect('salary-advance/bulk-requests');
        }
        $payload['title'] = 'New Bulk Salary Advance Applications';
        $this->view('salary-advance/new_bulk_request', $payload);
    }

    public function print($id_salary_advance = null): void
    {
        $db = Database::getDbh();
        $payload['salary_advance'] = $db->where('id_salary_advance', $id_salary_advance)->objectBuilder()->getOne('salary_advance');
        $this->view('print-salary-advance', $payload);
    }
}
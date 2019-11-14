<?php

class SalaryAdvance extends Controller
{
    public function index($request_number = null): void
    {
        currentUrl($_SERVER);
        $db = Database::getDbh();
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/index/' . $request_number);
        } else {
            $payload = [];
            $payload['current_user'] = getUserSession();
            $payload['title'] = 'Salary Advance Applications';
            $payload['request_number'] = $request_number;
            $payload['role'] = ROLE_USER;
            if ($request_number && !$db->where('request_number', $request_number)->has('salary_advance')) {
                redirect('errors/index/404');
            }
            $this->view('salary-advance/index', $payload);
        }
    }

    public function bulkRequests(string $request_number = null): void
    {
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/bulk-requests/' . $request_number);
        }
        $current_user =  $payload['current_user'] = getUserSession();

        if (!(isCurrentManager($current_user->user_id) || isAssignedAsSecretary($current_user->user_id) || isFinanceOfficer($current_user->user_id))) {
            redirect('salary-advance/index');
        }
        $payload['title'] = 'Bulk Salary Advance Applications';
        $payload['bulk_request_number'] = $request_number;
        if ($request_number) {
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

    public function print($request_number = null): void
    {
        $db = Database::getDbh();
        $payload['salary_advance'] = $db->where('request_number', $request_number)->objectBuilder()->getOne('salary_advance');
        $this->view('print-salary-advance', $payload);
    }
}
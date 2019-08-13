<?php

class SalaryAdvance extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id_salary_advance = null)
    {
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance/index/' . $id_salary_advance);
        } else {
            $payload = [];
            $payload['current_user'] = $current_user = getUserSession();
            $payload['title'] = 'Salary Advance Applications';
            $payload['salary_advances'] = (new SalaryAdvanceModel())->get(['user_id' => $current_user->user_id]);
            $payload['select_row_id'] = $id_salary_advance;
            $this->view('salary-advance/all', $payload);
        }
    }

    private function all()
    {
        $payload = [];
        $payload['current_user'] = $current_user = getUserSession();
        $payload['title'] = 'Salary Advance Applications';
        $payload['salary_advances'] = (new SalaryAdvanceModel())->get(['user_id' => $current_user->user_id]);
        $this->view('salary-advance/all', $payload);
    }

    public function single($id_salary_advance)
    {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'View Salary Advance';
        $payload['salary_advance'] = new SalaryAdvanceModel($id_salary_advance);
        $this->view('salary-advance/single', $payload);
    }

    public function new()
    {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'New Salary Advance';
        $current_user = getUserSession();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $col_data['user_id'] = $current_user->user_id;
            $col_data['percentage'] = $_POST['percentage'];
            $col_data['status'] = STATUS_PENDING_HOD_APPROVAL;
            $ret = SalaryAdvanceModel::insert($col_data);
            if ($ret) {
                flash_success('single', 'Salary Advance Application Successful!');
                redirect("salary-advance/index/$ret");
            } else {
                flash_error('new');
            }
        }
        $this->view('salary-advance/new', $payload);
    }

    public function edit($id_salary_advance = null)
    {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'New Salary Advance';
        $this->view('salary-advance/edit', $payload);
    }

    public function startPage()
    {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'Start';
        $this->view('pages/start-page', $payload);
    }

    public function print ($id_salary_advance)
    {
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'Salary Advance';
        $payload['salary_advance'] = new SalaryAdvanceModel($id_salary_advance);
        $this->view('print', $payload);
    }

}

<?php

class SalaryAdvanceSecretary extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id_salary_advance = null)
    {
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance-secretary/index/' . $id_salary_advance);
        }
        if (!isSecretary(getUserSession()->user_id)) {
            redirect('salary-advance');
        }
        if (empty($id_salary_advance)) {
            $this->all();
        } else {
            $this->single($id_salary_advance);
        }
    }

    private function all()
    {
        $payload = [];
        $payload['current_user'] = $current_user = getUserSession();
        $payload['title'] = 'Salary Advance Applications';
        if (!isSecretary($current_user->user_id)) {
            redirect('salary-advance');
        }
        /*$ret = Database::getDbh()->where('user_id', $current_user->user_id)
            ->get('salary_advance_secretary');
        foreach ($ret as $item) {
            Database::getDbh()->orWhere('department_id', $item['department_assigned']);
        }
        $payload['salary_advances']  = Database::getDbh()->where('raised_by_secretary', true)
            ->get('salary_advance');*/

        $this->view('salary-advance-secretary/all', $payload);
    }

    public function single($id_salary_advance)
    {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'View Salary Advance';
        $payload['salary_advance'] = new SalaryAdvanceModel($id_salary_advance);
        $this->view('salary-advance-secretary/single', $payload);
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
                redirect("salary-advance-secretary/single/$ret");
            } else {
                flash_error('new');
            }
        }
        $this->view('salary-advance-secretary/new', $payload);
    }

    public function edit()
    {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'New Salary Advance';
        $this->view('salary-advance-secretary/edit', $payload);
    }

    public function startPage()
    {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'Start';
        $this->view('pages/start-page-manager', $payload);
    }


}

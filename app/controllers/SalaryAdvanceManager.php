<?php

class SalaryAdvanceManager extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id_salary_advance = null)
    {
        $db = Database::getDbh();
        $payload = [];
        $payload['current_user'] = $current_user =  getUserSession();
        $payload['title'] = 'Salary Advance Applications';
        $payload['select_row_id'] = $id_salary_advance;
        if (!isLoggedIn()) {
            redirect('users/login/salary-advance-manager/index/'.$id_salary_advance);
        }
        if (!(isCurrentManager($current_user->user_id) || isCurrentFmgr($current_user->user_id) || isCurrentHR($current_user->user_id))) {
            redirect('salary-advance');
        }
        if ($id_salary_advance) {
            if (!$db->where('id_salary_advance', $id_salary_advance)->has('salary_advance')) {
                redirect('errors/index/404');
            }
        }
        if (getCurrentHR() == $current_user->user_id || getCurrentFgmr() == $current_user->user_id) {
            $payload['salary_advances'] = (new SalaryAdvanceModel())->get();
        } else {
            $payload['salary_advances'] = (new SalaryAdvanceModel())->get(['department_id' => $current_user->department_id]);
        }
        $this->view('salary-advance-manager/all', $payload);
    }

    /*private function all()
    {
        $payload = [];
        $payload['current_user'] = $current_user =  getUserSession();
        $payload['title'] = 'Salary Advance Applications';

        if (!isAdmin($current_user->user_id)) {
            redirect('salary-advance');
        }
        if (getCurrentHR() == $current_user->user_id || getCurrentFgmr() == $current_user->user_id) {
            $payload['salary_advances'] = (new SalaryAdvanceModel())->get();
        } else {
            $payload['salary_advances'] = (new SalaryAdvanceModel())->get(['department_id' => $current_user->department_id]);
        }
        $this->view('salary-advance-manager/all', $payload);
    }*/

    public function single($id_salary_advance)
    {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'View Salary Advance';
        $payload['salary_advance'] = new SalaryAdvanceModel($id_salary_advance);
        $this->view('salary-advance-manager/single', $payload);
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
                redirect("salary-advance-manager/single/$ret");
            } else {
                flash_error('new');
            }
        }
        $this->view('salary-advance-manager/new', $payload);
    }

    public function edit($id_salary_advance)
    {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'New Salary Advance';
        $this->view('salary-advance-manager/edit', $payload);
    }

    public function startPage() {
        $payload = [];
        $payload['current_user'] = getUserSession();
        $payload['title'] = 'Start';
        $this->view('pages/start-page-manager', $payload);
    }


}

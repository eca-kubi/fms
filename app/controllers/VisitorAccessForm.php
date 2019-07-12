<?php
class VisitorAccessForm extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
       $this->dashboard();
    }


    public function dashboard()
    {
        $payload = [];
        $payload['title'] = 'Dashboard';
        $this->view('visitor-access-form/dashboard', $payload);
    }

    public  function startPage() {
        $payload = [];
        $payload['title'] = 'Start Page';
        $this->view('pages/start-page', $payload);
    }

	public function test(){
		echo phpinfo();
	}
}

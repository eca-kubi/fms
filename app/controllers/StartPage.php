<?php
class StartPage extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($department="")
    {
        $payload = [];
        $payload['title'] = 'Start Page';
        $this->view('pages/start-page', $payload);
    }


    public function about()
    {
        $payload = [];
        $payload['title'] = 'About us';
        $this->view('pages/about', $payload);
    }

    public function finance() {
        $payload = [];
        $payload['title'] = 'Finance';
        $this->view('pages/start-page-finance', $payload);
    }

    public function hr() {
        $payload = [];
        $payload['title'] = 'HR';
        $this->view('pages/start-page-hr', $payload);
    }
    
    public  function startPageDepartment() {
        $payload = [];
        $payload['title'] = 'Department Start Page';
        $this->view('pages/start-page-department', $payload);
    }

    public function test(){
		echo phpinfo();
	}
}

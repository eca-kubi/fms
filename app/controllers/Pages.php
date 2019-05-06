<?php
class Pages extends Controller
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
        redirect('pages/start-page');
    }


    public function about()
    {
        $payload = [];
        $payload['title'] = 'About us';
        $this->view('pages/about', $payload);
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

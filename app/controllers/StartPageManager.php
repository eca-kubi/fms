<?php
class StartPageManager extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $payload = [];
        $payload['title'] = 'Manager Start Page';
        $this->view('pages/start-page-manager', $payload);
    }


    public function about()
    {
        $payload = [];
        $payload['title'] = 'About us';
        $this->view('pages/about', $payload);
    }
	
	public function test(){
		echo phpinfo();
	}
}

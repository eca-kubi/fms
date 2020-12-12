<?php
class SafetyFlash extends Controller
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
        redirect('safety-flash/dashboard');
    }

    public function dashboard()
    {

        $payload = [];
        $payload['title'] = 'Safety Flash Dashboard';
        $this->view('safety-flash/dashboard', $payload);
    }

    public function newflash()
    {

        $payload = [];
        $payload['title'] = 'Safety Flash Dashboard';
        $this->view('safety-flash/new-flash', $payload);
    }
}

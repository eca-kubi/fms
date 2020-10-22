<?php
class StartPage extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $payload = [];
        $payload['title'] = 'Forms';
        $this->view('start-page/index', $payload);
    }


    public function about()
    {
        $payload = [];
        $payload['title'] = 'About us';
        $this->view('pages/about', $payload);
    }

    public function finance() {
        $payload = [];
        $payload['title'] = 'Forms/Finance';
        $payload['sub_page'] = 'Finance';
        $this->view('start-page/finance', $payload);
    }

    public function hr() {
        $payload = [];
        $payload['title'] = 'Forms/HR';
        $payload['sub_page'] = 'HR';
        $this->view('start-page/hr', $payload);
    }

    public function admin() {
        $payload = [];
        $payload['title'] = 'Forms/Admin';
        $payload['sub_page'] = 'Admin';
        $this->view('start-page/admin', $payload);
    }

    public function it() {
        $payload = [];
        $payload['title'] = 'Forms/IT';
        $payload['sub_page'] = 'IT';
        $this->view('start-page/it', $payload);
    }

    public function engineering() {
        $payload = [];
        $payload['title'] = 'Forms/Engineering';
        $payload['sub_page'] = 'Engineering';
        $this->view('start-page/engineering', $payload);
    }

    public function mining() {
        $payload = [];
        $payload['title'] = 'Forms/Mining';
        $payload['sub_page'] = 'Mining';
        $this->view('start-page/mining', $payload);
    }

    public function security() {
        $payload = [];
        $payload['title'] = 'Forms/Security';
        $payload['sub_page'] = 'Security';
        $this->view('start-page/security', $payload);
    }

    public function processing() {
        $payload = [];
        $payload['title'] = 'Forms/Processing';
        $payload['sub_page'] = 'Processing';
        $this->view('start-page/processing', $payload);
    }

    public function hse() {
        $payload = [];
        $payload['title'] = 'Forms/HSE';
        $payload['sub_page'] = 'HSE';
        $this->view('start-page/hse', $payload);
    }

    public function test(){
		echo phpinfo();
	}
}

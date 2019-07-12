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
        $payload['title'] = 'Dashboard';
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

    public function admin() {
        $payload = [];
        $payload['title'] = 'Admin';
        $this->view('pages/start-page-admin', $payload);
    }

    public function it() {
        $payload = [];
        $payload['title'] = 'IT';
        $this->view('pages/start-page-it', $payload);
    }

    public function engineering() {
        $payload = [];
        $payload['title'] = 'Engineering';
        $this->view('pages/start-page-engineering', $payload);
    }

    public function mining() {
        $payload = [];
        $payload['title'] = 'Mining';
        $this->view('pages/start-page-mining', $payload);
    }

    public function security() {
        $payload = [];
        $payload['title'] = 'Security';
        $this->view('pages/start-page-security', $payload);
    }

    public function processing() {
        $payload = [];
        $payload['title'] = 'Processing';
        $this->view('pages/start-page-processing', $payload);
    }

    public function hse() {
        $payload = [];
        $payload['title'] = 'HSE';
        $this->view('pages/start-page-hse', $payload);
    }

    public function test(){
		echo phpinfo();
	}
}

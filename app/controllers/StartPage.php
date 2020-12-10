<?php

use ViewModels\StartPageViewModel;

class StartPage extends Controller
{
    public StartPageViewModel $view_model;
    public function __construct()
    {
        parent::__construct();
        $this->view_model = new StartPageViewModel('Start Page');
    }

    public function index()
    {
        $view_model = new StartPageViewModel('Start Page');
        $this->view('start-page/index', ['view_model' => $view_model]);
    }


    public function about()
    {
        $view_model = new StartPageViewModel('About us');
        $this->view('pages/about', ['view_model' => $view_model]);
    }

    public function finance() {
        $view_model = new StartPageViewModel('Forms/Finance');
        $this->view_model->sub_page = 'Finance';
        $this->view('start-page/finance', ['view_model' => $view_model]);
    }

    public function hr() {
        $this->view_model->title = 'Forms/HR';
        $this->view_model->sub_page = 'HR';
        $this->view('start-page/hr', ['view_model' => $this->view_model]);
    }

    public function admin() {
        $this->view_model->title = 'Forms/Admin';
        $this->view_model->sub_page = 'Admin';
        $this->view('start-page/admin', ['view_model' => $this->view_model]);
    }

    public function it() {
        $this->view_model->title = 'Forms/IT';
        $this->view_model->sub_page = 'IT';
        $this->view('start-page/it', ['view_model' => $this->view_model]);
    }

    public function engineering() {
        $this->view_model->title = 'Forms/Engineering';
        $this->view_model->sub_page = 'Engineering';
        $this->view('start-page/engineering', ['view_model' => $this->view_model]);
    }

    public function mining() {
        $this->view_model->title = 'Forms/Mining';
        $this->view_model->sub_page = 'Mining';
        $this->view('start-page/mining', ['view_model' => $this->view_model]);
    }

    public function security() {
        $this->view_model->title = 'Forms/Security';
        $this->view_model->sub_page = 'Security';
        $this->view('start-page/security', ['view_model' => $this->view_model]);
    }

    public function processing() {
        $this->view_model->title = 'Forms/Processing';
        $this->view_model->sub_page = 'Processing';
        $this->view('start-page/processing', ['view_model' => $this->view_model]);
    }

    public function hse() {
        $this->view_model->title = 'Forms/HSE';
        $this->view_model->sub_page = 'HSE';
        $this->view('start-page/hse', ['view_model' => $this->view_model]);
    }

    public function test(){
		echo phpinfo();
	}
}

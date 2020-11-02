<?php
class Pages extends Controller
{
    public function index()
    {
        if (!isLoggedIn()) {
            redirect('users/login/');
        }
        redirect('start-page');
    }


    public function about()
    {
        $payload = [];
        $payload['title'] = 'About us';
        $this->view('about', $payload);
    }

    public  function startPage() {
        $payload = [];
        $payload['title'] = 'Dashboard';
        if (!isLoggedIn()) {
            redirect('users/login/pages/start-page');
        }
        $this->view('pages/start-page', $payload);
    }

    public function getoauthtoken()
    {
        $this->view('pages/get_oauth_token', []);
    }

    public function getprofile()
    {
        $this->view('pages/get_profile', []);
    }

    public function getaccesstoken()
    {
        $this->view('pages/get_access_token', []);
    }

    public function sendmail()
    {
        $this->view('pages/send_mail', []);
    }
}

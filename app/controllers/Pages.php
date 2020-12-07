<?php

use Spatie\Browsershot\Browsershot;
use Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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

    public function startPage()
    {
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

    public function test()
    {
        // $process = new Process(['node C:/xampp/htdocs/fms/print-to-pdf.js https://local.arlgh.com/forms/visitor-access-form/print/0']);
        $process = Process::fromShellCommandline('node print-to-pdf.js');
        $process->setTimeout(30);
        $process->setOptions(['create_new_console' => true]);
        $process->setWorkingDirectory('C:/xampp/htdocs/fms');
        try {
            $process->mustRun();
            echo $process->getOutput();
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }
}

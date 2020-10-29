<?php
class VisitorAccess extends Controller
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

    public function A()
    {
        redirect('visitor-access/section-a-visitor-details');

    }

    public function SectionAVisitorDetails()
    {
        if (!isLoggedIn()) {
            redirect('users/login/visitor-access/section-a-visitor-details');
        }
        $current_user = getUserSession();
        $payload['title'] = 'Visitor Access Form - Section A (Visitor Details)';
        $payload['ref_num'] = $payload['reference'] = getDeptRef($current_user->department_id);
        $payload['custom_js_file'] = 'visitor-access-form.js';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Trim post data to remove surrounding whitespace
            array_filter($_POST, fn&($item) => trim($item));

            //Sanitize
            //filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Validate Post
            $errors = validateVisitorAccessForm($_POST, SECTION_A_VISITOR_DETAILS);
            if (count($errors->toArray())) {
                $payload['errors'] = $errors;
                $this->view('visitor-access/section-a-visitor-details', $payload);
            }
        }
        $this->view('visitor-access/section_a_visitor_details', $payload);
    }

    public function dashboard()
    {
        $payload = [];
        $payload['title'] = 'Dashboard';
        $this->view('visitor-access/dashboard', $payload);
    }

    public  function startPage() {
        $payload = [];
        $payload['title'] = 'Start Page';
        $this->view('pages/start-page', $payload);
    }

    public function test()
    {

    }
}

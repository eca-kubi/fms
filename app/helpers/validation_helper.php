<?php

use Respect\Validation\Validator as v;
use ViewModels\FormViewModel;
use ViewModels\LMS\LeaveFormViewModel;
use ViewModels\LoginViewModel;
use ViewModels\UserRegistrationFormViewModel;

function validatePost($form)
{
    // Init data
    //$post = initData();
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        // Sanitize POST data
        //$post->error_count = 0;
        switch ($form) {
            case 'registration_form':
            case 'edit_form':
                $_POST = array_map('trim', $_POST);
                $post = new UserRegistrationFormViewModel('User Registration', $_POST);
                /*$post->first_name = trim($_POST['first_name']);
                $post->last_name = trim($_POST['last_name']);
                $post->email = trim($_POST['email']);
                $post->password = trim($_POST['password']);
                $post->confirm_password = trim($_POST['confirm_password']);
                $post->job_title = trim($_POST['job_title']);
                $post->staff_category = trim($_POST['staff_category']);
                $post->phone_number = trim($_POST['phone_number']);
                $post->staff_id = trim($_POST['staff_id']);
                $post->employment_date = formatDate(trim($_POST['employment_date']), DATE_FORMATS['front_end'], DATE_FORMATS['back_end']);
                $post->contract_start = formatDate(trim($_POST['contract_start']), DATE_FORMATS['front_end'], DATE_FORMATS['back_end']);
                $post->contract_end = formatDate(trim($_POST['contract_end']), DATE_FORMATS['front_end'], DATE_FORMATS['back_end']);
                $post->role = trim($_POST["role"]);
                $post->department_id = trim($_POST['department_id']);*/

                if ($form == 'registration_form') {
                    isUsernameAlreadyUsed($post);
                }
                // Check email
                isEmailAlreadyUsed($post);

                // Validate staff id
                validateStaffId($post);

                // Validate First Name
                validateFirstname($post);

                // Validate Last Name
                validateLastName($post);

                // Validate Password
                validatePassword($post);

                // Validate Confirm Password
                validateConfirmPassword($post);

                // Validate Profile Picture
                validateProfilePic($post);

                // Validate phone number
                validatePhoneNumber($post);

                // Validate department
                validateDepartment($post);

                return $post;
            case 'login_form':
                $post = new LoginViewModel('Login', $_POST);
                /*$post->staff_id = strtolower(trim($_POST['staff_id']));
                $post->password = $_POST['password'];*/

                // Validate Staff ID
                validateStaffId($post);

                // Validate Password
                validatePassword($post);

                // Attempt login
                if (!User::has('staff_id', $post->staff_id)) {
                    $post->staff_id_err = 'Invalid Username/Password';
                    return $post;
                }

                $loggedInUser = User::login($post->staff_id, $post->password);
                if (!$loggedInUser) {
                    $post->password_err = 'Password Incorrect!';
                    return $post;
                }
                return $post;
            case 'profile_form':
                $post = new UserRegistrationFormViewModel('Profile', $_POST);
                $post->error_count = 0;
                //$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                /*$post->first_name = trim($_POST['first_name']);
                $post->last_name = trim($_POST['last_name']);
                $post->email = trim($_POST['email']);*/
                //$post->password = trim($_POST['password']);
                //$post->confirm_password = trim($_POST['confirm_password']);
                //$post->phone_number = trim($_POST['phone_number']);
                $post->disable_leave_notif = isset($_POST['disable_leave_notif']) ?: '';
                // Validate Email
                validateEmail($post);

                validateProfilePic($post);

                validatePhoneNumber($post);

                return $post;
            case 'book_leave':
                // validate leave form
                $post = new LeaveFormViewModel('Request Leave', $_POST);
                /*   $post->start_date = formatDate($_POST["start_date"], DATE_FORMATS['front_end'], DATE_FORMATS['back_end']);
                   $post->end_date = formatDate($_POST["end_date"], DATE_FORMATS['front_end'], DATE_FORMATS['back_end']);
                   $post->type = $_POST["type"];
                   $post->vac_address = $_POST["vac_address"];
                   $post->vac_phone_no = $_POST["vac_phone_no"];
                   $post->resume_date = formatDate($_POST["resume_date"], DATE_FORMATS['front_end'], DATE_FORMATS['back_end']);
                   $post->relationship = isset($_POST["relationship"]) ? $_POST["relationship"] : '';
                   $post->leave_reason = $_POST["leave_reason"];*/
                return $post;
            default:
        }
    }
    return new FormViewModel('');
}

/*function initData()
{
    return json_decode(json_encode([
        'user_id' => null,
        'staff_id' => '',
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'password' => '',
        "contract_start" => '',
        "contract_end" => '',
        'confirm_password' => '',
        'job_title' => '',
        'staff_manager' => '',
        'staff_category' => '',
        'phone_number' => '',
        'employment_date' => '',
        'start_date' => '',
        'end_date' => '',
        'profile_pic' => '',
        'department_id' => '',
        'department_err' => '',
        'first_name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'profile_pic_err' => '',
        'staff_position_err' => '',
        'staff_category_err' => '',
        'phone_number_err' => '',
        'employment_date_err' => '',
        'start_date_err' => '',
        'uses_pc_err' => '',
        'staff_id_err' => '',
        'job_title_err' => '',
        'last_name_err' => '',
        'role' => '',
        'error_count' => 0,
        'department' => '',
        'relieved_by' => '',
        'leave_type' => '',
        'leave_reason' => '',
        'vac_address' => '',
        'vac_phone_no' => '',
        'relationship' => '',
        'resume_date' => '',
        'current_date' => (new DateTime())->format(DATE_FORMATS['front_end']),
    ]));
}*/

function validatePhoneNumber($post)
{
    // Validate phone number
    if (v::numeric()->validate($post->phone_number)) {
        if (!v::numeric()->length(10, 10)) {
            $post->phone_number_err = 'Phone number must be ten-digit long';
            $post->error_count++;
        }
    } else {
        $post->phone_number_err = 'Phone number must be numeric';
        $post->error_count++;
    }
}

function validateProfilePic($post)
{
    // Validate Profile Picture
    $result = uploadProfilePic('profile_pic', getUserSession()->staff_id);
    if (!$result['success']) {
        $post->profile_pic_err = $result['reason'];
        $post->error_count++;
    } else {
        $post->profile_pic = $result['file'];
    }
    if ($_FILES['profile_pic']['size'] < 0) {
        $post->profile_pic = DEFAULT_PROFILE_PIC;
    }
}

function validateDepartment($post)
{
    if (empty($post->department_id)) {
        $post->department_err = 'Please select a department.';
        $post->error_count++;
    }
}

function validateConfirmPassword($post)
{
    if (empty($post->confirm_password)) {
        $post->confirm_password_err = 'Please confirm password';
        $post->error_count++;
    } else {
        if ($post->password !== $post->confirm_password) {
            $post->confirm_password_err = 'Passwords do not match';
            $post->error_count++;
        }
    }
}

function validateFirstname($post)
{
    // Validate First Name
    if (empty($post->first_name)) {
        $post->first_name_err = 'Please enter first name';
        $post->error_count++;
    }
}

function validateLastName($post)
{

    // Validate Surname
    if (empty($post->last_name)) {
        $post->last_name_err = 'Please enter last name';
        $post->error_count++;
    }
}

function validatePassword($post)
{
    if (empty($post->password)) {
        $post->password_err = 'Please enter password';
        $post->error_count++;
    } elseif (strlen($post->password) < 6) {
        $post->password_err = 'Password must be at least 6 characters';
        $post->error_count++;
    }
}

function validateStaffId($post)
{
    if (empty($post->staff_id)) {
        $post->staff_id_err = 'Please enter username';
        $post->error_count++;
    }
}

function isUsernameAlreadyUsed($post)
{
    // Check staff_id
    if (User::has('staff_id', $post->staff_id)) {
        $post->staff_id_err = 'Username is already taken';
        $post->error_count++;
    }
}

function isEmailAlreadyUsed($post)
{
    if (User::has('email', $post->email)) {
        $post->email_err = 'Email is already taken';
        $post->error_count++;
    }
}

function validateEmail($post)
{
    if (!$post->email) {
        $post->email_err = 'Email is required';
        $post->error_count++;
    }
}

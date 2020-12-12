<?php
use ViewModels\LoginViewModel;
use ViewModels\UserRegistrationFormViewModel;

class Users extends Controller
{
    private array $payload;
    public function login(?string $redirect_url): void
    {
        $view_model = new LoginViewModel('FMS Login');
        $view_model->redirect_url = $redirect_url;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post = validatePost('login_form');
            if(!empty($post->staff_id_err) || !empty($post->password_err))   {
                $view_model->staff_id = $_POST['staff_id'];
                flash('flash_login', 'Invalid Username/Password', 'alert text-danger text-center');
                $this->view('users/login', ['view_model' => $view_model]);
            }
            else {
                $loggedInUser = User::login($post->staff_id, $post->password);
                if ($loggedInUser)
                {
                    $u = new User($loggedInUser->user_id);
                    createUserSession($u);
                    if (!empty($redirect_url)) {
                        redirect($redirect_url);
                    }
                    redirect('start-page');
                }
            }
        } else {
            $this->view('users/login', ['view_model' => $view_model]);
        }
    }

    public function forgotPassword(): void
    {
        $db = Database::getDbh();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $email = $_POST['email'] ?? null;
            if ($email) {
                try {
                    $ret = $db->where('email', $email)->getOne('users');
                    if ($ret) {
                        //$ret = $db->where('email', $email)->getOne('users');
                        $salt = 'archangel';
                        $md5_email = md5($ret['email'] . $salt);
                        $pass = md5($ret['password'] . $salt);
                        $reset_link = site_url("users/reset-password/$md5_email/$pass");
                        $link = "<a href='$reset_link'>Click To Reset password</a>";
                        $data['title'] = 'Password Reset';
                        $recipient = concatNameWithUserId($ret['user_id']);
                        $subject = 'Password Reset';
                        $body = 'Hello ' . $recipient . ', ' . HTML_NEW_LINE .
                            'If you have requested to change your password kindly use this link below to do so; else 
                            ignore this message.' . HTML_NEW_LINE .
                            $link;
                        if (insertEmail($subject, $body, $email, $recipient)) {
                            flash('flash_login', 'We have sent a link to your email for you to reset your password.', 'text-success alert text-sm');
                        }
                        try {
                            $db->onDuplicate(['secret_token' => $pass, 'email_hashed' => $md5_email])
                                ->insert('password_reset', [
                                    'email' => $email,
                                    'secret_token' => $pass,
                                    'email_hashed' => $md5_email
                                ]);
                        } catch (Exception $e) {
                        }
                        /*if (sendMail('Reset Password', 'Click On This Link to Reset Password. ' . $link. '<p style="color: red"><b>Please note that due to security reasons we will set this link to expire in one (1) day.</b></p>', $email, ucwords($ret['first_name']. ' '. $ret['last_name'], ' -.'))) {

                            $this->db->onDuplicate(['secret_token' => $pass, 'email_hashed'=>$md5_email])
                                ->insert('password_reset', [
                                    'email' => $email,
                                    'secret_token' => $pass,
                                    'email_hashed' => $md5_email
                                ]);

                            flash('flash', 'We have sent a link to your email for you to reset your password.', 'text-success alert');
                            redirect('users/login');
                        } else {
                            flash('flash', "Mail Error", 'alert text-danger');
                            redirect('users/login');
                        }*/
                    } else {
                        flash('flash', 'The email does not exist on our system', 'text-danger alert');
                    }
                } catch (Exception $e) {
                }

            }
        }
        redirect('users/login');
    }

    public function resetPassword($email_hashed = '', $secret_token = ''): void
    {
        $db = Database::getDbh();
        try {
            if (($_SERVER['REQUEST_METHOD'] === 'GET')
                && !empty($email_hashed)
                && !empty($secret_token)
                && $db->where('email_hashed', $email_hashed)
                    ->where('secret_token', $secret_token)
                    ->has('password_reset')) {
                $ret = $db->getOne('password_reset', 'expiry, email');
                if ($ret['expiry'] > date('Y-m-d h:i:s')) {
                    redirect('users/new-password/' . $email_hashed . '/' . $secret_token);
                } else {
                    flash('flash', 'The link has expired!', 'alert text-danger');
                    redirect('users/login');
                }
            }
        } catch (Exception $e) {
        }
        redirect('users/login');
    }

    public function newPassword($email_hashed = '', $secret_token = ''): void
    {
        $salt = 'archangel';
        $db = Database::getDbh();
        $view_model = new ViewModels\LoginViewModel('New Password');
        if (!empty($email_hashed) && !empty($secret_token)) {
            try {
                $values = $db->where('email_hashed', $email_hashed)
                    ->where('secret_token', $secret_token)
                    ->getOne('password_reset', 'expiry, email, secret_token, email_hashed');
                if ($values) {
                    //$ret = $db->getOne('password_reset', 'expiry, email, secret_token, email_hashed');

                    $view_model->email = $values['email'];
                    if ($values['expiry'] > date('Y-m-d h:i:s')) {
                        $this->view('users/new_password', ['view_model'=>$view_model]);
                        return;
                    } else {
                        flash('flash', 'The link has expired!', 'alert text-danger');
                        redirect('users/login');
                    }
                } else {
                    flash('flash', 'The link has expired or does not exist!', 'alert text-danger text-sm');
                }
            }catch (Exception $e){}
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['email'])) {
                try{
                    $email = $_POST['email'];
                    $md5_email = md5($_POST['email'] . $salt);
                    if ($db->where('email', $_POST['email'])->where('email_hashed', $md5_email)->get('password_reset')) {
                        $password = $_POST['password'];
                        $confirm_password = $_POST['confirm_password'];
                        if (!empty($password) && !empty($confirm_password) && $password === $confirm_password) {
                            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                            if ($db->where('email', $email)->update('users', ['password' => $hashed_password])) {
                                $db->where('email', $email)
                                    ->delete('password_reset');
                                flash('flash_login', 'Password changed successfully. Please login with your new password!', 'alert text-success text-sm');
                                redirect('users/login');
                            }
                        } else {
                            flash('flash_login', 'An error occurred!', 'alert text-danger');
                            redirect('users/login');
                        }
                    }
                }catch (Exception $e){}
            }
            redirect('users/login');
        }
        redirect('users/login');
    }

    public function changePassword(): void
    {
        $user = new User (getUserSession()->user_id);
        $db = Database::getDbh();
        $data = array();
        $method = the_method();
        $flash = "flash_$method";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['password'];
            $confirm = $_POST['confirm_password'];
            // verify current password
            if (password_verify($current_password, $user->password)) {
                if ($new_password === $confirm) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    try {
                        if ($hashed_password) {
                            $data['password'] = $hashed_password;
                            if ($user->default_password) {
                                // this will disable notification to set new password
                                $data['default_password'] = false;
                            }
                            $ret = $db->where('user_id', $user->user_id)
                                ->update('users', $data);
                            if ($ret) {
                                flash($flash, 'Password changed successfully!', 'text-success text-sm alert text-center');
                            } else {
                                flash($flash, 'An error occurred!', 'text-danger text-sm alert text-center');
                            }
                        }
                    } catch (Exception $e) {
                    }
                } else {
                    // Password mismatch
                    flash($flash, 'Password mismatch!', 'text-danger text-sm alert text-center');
                }
            } else {
                // Incorrect password
                flash($flash, 'Incorrect password!', 'text-danger alert text-sm text-center');
            }
        }
        goBack();
    }

    public function profile(?int $user_id = null): void
    {
        $view_model = new UserRegistrationFormViewModel('Profile');
        $current_user = getUserSession();
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        $user_id = $user_id?? $current_user->user_id;

        try {
            $user = Database::getDbh()
                ->where('u.user_id', $user_id)
                ->join('departments d', 'd.department_id=u.department_id', 'LEFT')
                ->getOne('users u');
            $view_model->initialize($user);
        } catch (Exception $e) {
        }
        $this->view('users/profile', ['view_model' => $view_model]);
    }

    public function logout(): void
    {
        logout();
    }

    public function chooseSession(): void
    {
        $payload = [
            'title' => 'Choose Session',
            'current_user' => getUserSession()
        ];

        $this->view('choose_session/index', $payload);
    }
}

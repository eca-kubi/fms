<?php

class SalaryAdvanceAjax extends Controller
{
    public function index(): void
    {
        echo json_encode(getSalaryAdvance(['u.user_id' => getUserSession()->user_id, 'deleted' => false]), JSON_THROW_ON_ERROR, 512);
    }

    public function Create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getDbh();
            $current_user = getUserSession();
            $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
            $ret = [];
            if (hasActiveApplication($current_user->user_id)) {
                $ret['has_active_application'] = true;
                $ret['errors'] = [['message' => 'You already have an active application.']];
                echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
                return;
            }
            if (!isValidAmount($_POST['amount_requested'], $current_user->basic_salary)) {
                echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
                return;
            }
            $request_number = genDeptRef($current_user->department_id, 'salary_advance');
            $data = [
                'amount_requested' => $_POST['amount_requested'],
                'percentage' => ($_POST['amount_requested'] / $current_user->basic_salary) * 100,
                'user_id' => $current_user->user_id,
                'department_id' => $current_user->department_id,
                'request_number' => $request_number
            ];
            $record_added_id = $db->insert('salary_advance', $data);
            if ($record_added_id) {
                $hod = new User(getCurrentManager($current_user->department_id));
                // Send email to HoD
                $subject = "Salary Advance Application ($request_number)";
                $data = ['ref_number' => $request_number, 'link' => URL_ROOT . '/salary-advance-manager/index/' . $request_number];
                $body = get_include_contents('email_templates/salary-advance/new_application_notify_hod', $data);
                $data['body'] = $body;
                $email = get_include_contents('email_templates/salary-advance/main', $data);
                if ($current_user->user_id !== $hod->user_id) {
                    insertEmail($subject, $email, $hod->email);
                }
                // Send email to Applicant
                $data['link'] = URL_ROOT . '/salary-advance/index/' . $request_number;
                $body = get_include_contents('email_templates/salary-advance/new_application_notify_applicant', $data);
                $data['body'] = $body;
                $email = get_include_contents('email_templates/salary-advance/main', $data);
                insertEmail($subject, $email, $current_user->email);
                echo json_encode(getSalaryAdvance(['u.user_id' => $current_user->user_id, 'sa.request_number' => $request_number]), JSON_THROW_ON_ERROR, 512);
            } else {
                $ret['errors'] = [['message' => 'Failed to add record.', 'code' => ERROR_UNSPECIFIED_ERROR]];
                echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
            }
        }
    }

    public function Update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_user = getUserSession();
            $db = Database::getDbh();
            $errors = ['errors' => [['message' => '']]];
            $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
            $_POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
            $id_salary_advance = $_POST['id_salary_advance'];
            $salary_advance = $db->where('user_id', $current_user->user_id)
                ->where('id_salary_advance', $id_salary_advance)
                ->getOne('salary_advance');
            if ($salary_advance) {
                if ($salary_advance['hod_approval'] !== null) {
                    $errors['errors'][0]['message'] = 'The HoD has already reviewed this application!';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                } else if ($salary_advance['fmgr_approval'] !== null) {
                    $errors['errors'][0]['message'] = 'The Finance manager has already reviewed this application!';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                } else if ($salary_advance['gm_approval'] !== null) {
                    $errors['errors'][0]['message'] = 'The General manager has already reviewed this application!';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                } else if ($salary_advance['hr_approval'] !== null) {
                    $errors['errors'][0]['message'] = 'HR has already reviewed this application!';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                } else {
                    $percentage = ($_POST['amount_requested'] / $current_user->basic_salary) * 100;
                    $min_percent = (MIN_PERCENTAGE / 100) * $current_user->basic_salary;
                    $max_percent = (MAX_PERCENTAGE / 100) * $current_user->basic_salary;
                    if ($percentage >= $min_percent || $percentage <= $max_percent) {
                        $data = [
                            'amount_requested' => $_POST['amount_requested'],
                            'percentage' => $percentage
                        ];
                        $success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', $data);
                        if ($success) {
                            echo json_encode(getSalaryAdvance(['u.user_id' => $current_user->user_id, 'sa.request_number' => $salary_advance['request_number']]), JSON_THROW_ON_ERROR, 512);
                        } else {
                            $errors['errors'][0]['message'] = 'Update failed!';
                            echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                            return;
                        }
                    }
                }
            }
        } else {
            echo json_encode([], JSON_THROW_ON_ERROR, 512);
        }
    }

    public function Destroy(): void
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $current_user = getUserSession();
        $db = Database::getDbh();
        $ret = [];
        $old_ret = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
            ->getOne('salary_advance');
        if ($old_ret['hod_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'The HoD has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['message' => 'The HoD has already reviewed this application!', 'code' => ERROR_APPLICATION_ALREADY_REVIEWED];
        } else if ($old_ret['fmgr_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'Finance manager has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['message' => 'Finance manager has already reviewed this application!', 'code' => ERROR_APPLICATION_ALREADY_REVIEWED];
        } else if ($old_ret['hr_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'HR has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['message' => 'HR has already reviewed this application!', 'code' => ERROR_APPLICATION_ALREADY_REVIEWED];
        } else {
            $ret = $db->where('id_salary_advance', $_POST['id_salary_advance'])
                ->update('salary_advance', ['deleted' => true]);
            $data['department_ref'] = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
                ->getValue('salary_advance', 'department_ref');
            $remarks = get_include_contents('action_log/salary_advance_deleted', $data);
            insertLog($_POST['id_salary_advance'], ACTION_SALARY_ADVANCE_RAISED, $remarks, getUserSession()->user_id);
            if ($ret) {
                $ret = [];
                $ret['success'] = true;
                $ret['has_active_application'] = hasActiveApplication($current_user->user_id);
                echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
                return;
            }

            $ret = ['success' => false, 'reason' => 'An error occurred'];
            $ret['has_active_application'] = hasActiveApplication($current_user->user_id);
            $ret['errors'] = ['message' => 'An error occurred!', 'code' => ERROR_UNSPECIFIED_ERROR];
        }
        echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
    }

    public function hasActiveSalaryAdvance(): void
    {
        echo json_encode(hasActiveApplication(getUserSession()->user_id), JSON_THROW_ON_ERROR, 512);
    }

    public function salaryAdvanceApplicationReviewed($id_salary_advance): void
    {
        echo json_encode(salaryAdvanceReviewed($id_salary_advance), JSON_THROW_ON_ERROR, 512);
    }

    public function ActiveApplicants() :void {
        echo json_encode(getActiveApplicants(), JSON_THROW_ON_ERROR, 512);
    }
}


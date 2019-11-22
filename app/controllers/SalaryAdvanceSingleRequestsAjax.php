<?php

class SalaryAdvanceSingleRequestsAjax extends Controller
{
    public function index(): void
    {
        $current_user = getUserSession();
        if (!(isCurrentHR($current_user->user_id) || isCurrentFmgr($current_user->user_id) || isCurrentGM($current_user->user_id) || isFinanceOfficer($current_user->user_id))) {
            echo json_encode(getSalaryAdvance(['deleted' => false, 'department_id' => $current_user->department_id, 'is_bulk_request' => false]), JSON_THROW_ON_ERROR, 512);
        } else {
            echo json_encode(getSalaryAdvance(['deleted' => false, 'is_bulk_request' => false]), JSON_THROW_ON_ERROR, 512);
        }
    }

    public function Update(): void
    {
        $db = Database::getDbh();
        $hr = new User(getCurrentHR());
        $fmgr = new User(getCurrentFgmr());
        $gm = new User(getCurrentGM());
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST array
            $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
            $errors = ['errors' => [['message' => '']]];
            $current_user = getUserSession();
            $id_salary_advance = $_POST['id_salary_advance'];
            $salary_advance = $db->where('id_salary_advance', $id_salary_advance)->getOne('salary_advance');
            if ($salary_advance) {
                $applicant = new User($salary_advance['user_id']);
                $hod = new User(getCurrentManager($applicant->department_id));
                $request_number = $salary_advance['request_number'];
                $subject = "Salary Advance Application ($request_number)";
                $data = ['ref_number' => $request_number, 'link' => URL_ROOT . '/salary-advance/index/' . $request_number, 'recipient_id' => $salary_advance['user_id']];
                if ($applicant->department_id === $hod->department_id && $salary_advance['hod_approval'] === null) {
                    // Current user is the hod
                    $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                        'hod_id' => $current_user->user_id,
                        'hod_approval' => $_POST['hod_approval'],
                        'hod_comment' => filter_var($_POST['hod_comment'], FILTER_SANITIZE_STRING),
                        'hod_approval_date' => now()
                    ]);
                    if ($update_success) {
                        $data['approval'] = $_POST['hod_approval'];
                        $data['comment'] = $_POST['hod_comment'];
                        $data['body'] = get_include_contents('email_templates/salary-advance/approval', $data);
                        $email = get_include_contents('email_templates/salary-advance/main', $data);
                        insertEmail($subject, $email, $applicant->email);
                        if ($hod->email !== $hr->email && $_POST['hod_approval']) {
                            $data['recipient_id'] = $hr->user_id;
                            $data['link'] =  URL_ROOT . '/salary-advance-manager/index/' . $request_number;
                            $data['body'] = get_include_contents('email_templates/salary-advance/approval', $data);
                            $email = get_include_contents('email_templates/salary-advance/main', $data);
                            insertEmail($subject, $email, $hr->email);
                        }
                    } else {
                        $errors['errors'][0]['message'] = 'The record failed to update';
                        echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                        return;
                    }
                } elseif ($salary_advance['hod_approval'] && ($salary_advance['hr_approval'] === null) && $hr->user_id === $current_user->user_id) {
                    $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                        'hr_id' => $current_user->user_id,
                        'hr_approval' => $_POST['hr_approval'],
                        'hr_comment' => $_POST['hr_comment'],
                        'hr_approval_date' => now(),
                        'amount_payable' => $_POST['amount_payable']
                    ]);
                    if ($update_success) {
                        $data['approval'] = $_POST['hr_approval'];
                        $data['comment'] = $_POST['hr_comment'];
                        $body = get_include_contents('email_templates/salary-advance/approval', $data);
                        $data['body'] = $body;
                        $email = get_include_contents('email_templates/salary-advance/main', $data);
                        insertEmail($subject, $email, $applicant->email);
                    } else {
                        $errors['errors'][0]['message'] = 'The record failed to update';
                        echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                        return;
                    }
                } elseif ($salary_advance['hr_approval'] && ($salary_advance['gm_approval'] === null)  && $gm->user_id === $current_user->user_id) {
                    $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                        'gm_id' => $current_user->user_id,
                        'gm_approval' => $_POST['gm_approval'],
                        'gm_comment' => $_POST['gm_comment'],
                        'gm_approval_date' => now()
                    ]);
                    if ($update_success) {
                        $data['approval'] = $_POST['gm_approval'];
                        $data['comment'] = $_POST['gm_comment'];
                        $body = get_include_contents('email_templates/salary-advance/approval', $data);
                        $data['body'] = $body;
                        $email = get_include_contents('email_templates/salary-advance/main', $data);
                        insertEmail($subject, $email, $applicant->email);
                    } else {
                        $errors['errors'][0]['message'] = 'The record failed to update';
                        echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                        return;
                    }
                } elseif ($salary_advance['gm_approval'] && ($salary_advance['fmgr_approval'] === null)  && $fmgr->user_id === $current_user->user_id) {
                    $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                        'fmgr_id' => $current_user->user_id,
                        'fmgr_approval' => $_POST['fmgr_approval'],
                        'fmgr_comment' => $_POST['fmgr_comment'],
                        'fmgr_approval_date' => now(),
                        'amount_approved' => $_POST['amount_approved']
                    ]);
                    if ($update_success) {
                        $data['approval'] = $_POST['fmgr_approval'];
                        $data['comment'] = $_POST['fmgr_comment'];
                        $body = get_include_contents('email_templates/salary-advance/approval', $data);
                        $data['body'] = $body;
                        $email = get_include_contents('email_templates/salary-advance/main', $data);
                        insertEmail($subject, $email, $applicant->email);
                    } else {
                        $errors['errors'][0]['message'] = 'The record failed to update';
                        echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                        return;
                    }
                }
                $updated_record = getSalaryAdvance(['id_salary_advance'=>$id_salary_advance, 'deleted' => false, 'is_bulk_request' => false]);
                echo json_encode($updated_record, JSON_THROW_ON_ERROR, 512);
            }
        }
    }

    public function Destroy(): void
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $ret = [];
        $old_ret = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
            ->getOne('salary_advance');
        if ($old_ret['hod_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'The HoD has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['The HoD has already reviewed this application!'];
        } else if ($old_ret['fmgr_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'Finance manager has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['Finance manager has already reviewed this application!'];
        } else if ($old_ret['hr_approval']) {
            $old_ret['success'] = false;
            $old_ret['reason'] = 'HR has already reviewed this application!';
            $ret[] = $old_ret;
            $ret['errors'] = ['HR has already reviewed this application!'];
        } else {
            $ret = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
                ->update('salary_advance', ['deleted' => true]);
            $data['department_ref'] = Database::getDbh()->where('id_salary_advance', $_POST['id_salary_advance'])
                ->getValue('salary_advance', 'department_ref');
            $remarks = get_include_contents('action_log/salary_advance_deleted', $data);
            insertLog($_POST['id_salary_advance'], ACTION_SALARY_ADVANCE_RAISED, $remarks, getUserSession()->user_id);
            if ($ret) {
                $ret = [['success' => true]];
                echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
                return;
            }
            $ret = [['success' => false, 'reason' => 'An error occurred']];
        }
        echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
    }
}
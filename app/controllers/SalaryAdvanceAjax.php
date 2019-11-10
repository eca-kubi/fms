<?php

class SalaryAdvanceAjax extends Controller
{
    public function index(): void
    {
        $db = Database::getDbh();
        $current_user = getUserSession();
        $records = [];
        try {
            $records = $db->where('user_id', $current_user->user_id)->orderBy('date_raised')
                ->where('is_bulk_request', false)
                ->where('deleted', false)->get('salary_advance');
        } catch (Exception $e) {
        }
        $transformed_records = transformArrayData($records);
        echo json_encode($transformed_records, JSON_THROW_ON_ERROR, 512);
    }

    public function Create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::getDbh();
            $current_user = getUserSession();
            $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
            $_POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
            $ret = [];
            if (hasActiveApplication($current_user->user_id)) {
                $ret['success'] = false;
                $ret['has_active_application'] = true;
                $ret['errors'] = [['message' => 'You already have an active application.', 'code' => ERROR_AN_APPLICATION_ALREADY_EXISTS]];
                echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
                return;
            }
            $data = [
                'amount_requested_is_percentage' => $_POST['amount_requested_is_percentage'],
                'amount_requested' => $_POST['amount_requested'],
                'percentage' => $_POST['percentage'],
                'user_id' => $current_user->user_id,
                'department_id' => $current_user->department_id,
                'department_ref' => genDeptRef($current_user->department_id, 'salary_advance')
            ];
            /*  if ($data['amount_requested_is_percentage']) {
                  $data['amount_requested'] = null;
              } else {
                  $data['percentage'] = null;
              }*/
            $record_added_id = $db->insert('salary_advance', $data);
            if ($record_added_id) {
                $new_record = $db->where('id_salary_advance', $record_added_id)->get('salary_advance');
                $ref_number = genDeptRef($current_user->department_id, 'salary_advance');
                $hod = new User(getCurrentManager($current_user->department_id));
                // Send email to HoD
                $subject = "Salary Advance Application ($ref_number)";
                $data = ['ref_number' => $ref_number, 'link' => URL_ROOT . '/salary-advance-manager/index/' . $new_record[0]['id_salary_advance']];
                $body = get_include_contents('email_templates/salary-advance/new_application_notify_hod', $data);
                $data['body'] = $body;
                $email = get_include_contents('email_templates/salary-advance/main', $data);
                // If HoD is the applicant no need to send email
                if ($hod->email !== $current_user->email) {
                    insertEmail($subject, $email, $hod->email);
                }
                // Notify applicant
                $data['link'] = URL_ROOT . '/salary-advance/index/' . $new_record[0]['id_salary_advance'];
                $body = get_include_contents('email_templates/salary-advance/new_application_notify_applicant', $data);
                $data['body'] = $body;
                $email = get_include_contents('email_templates/salary-advance/main', $data);
                insertEmail($subject, $email, $current_user->email);
                echo json_encode(transformArrayData($new_record), JSON_THROW_ON_ERROR, 512);
            } else {
                $ret['errors'] = [['message' => 'Failed to add record.', 'code' => ERROR_UNSPECIFIED_ERROR]];
                echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
            }
        }
    }

    public function Update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
            $_POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
            $id_salary_advance = $_POST['id_salary_advance'];
            $errors = ['errors' => [['message' => '']]];
            $old_ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)->getOne('salary_advance');
            if ($old_ret['hod_approval']) {
                $errors['errors'][0]['message'] = 'The HoD has already reviewed this application!';
                echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
            } else if ($old_ret['fmgr_approval']) {
                $errors['errors'][0]['message'] = 'Finance manager has already reviewed this application!';
                echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
            } else if ($old_ret['gm_approval']) {
                $errors['errors'][0]['message'] = 'General manager has already reviewed this application!';
                echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
            } else if ($old_ret['hr_approval']) {
                $errors['errors'][0]['message'] = 'HR has already reviewed this application!';
                echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
            } else {
                $data = [
                    //'amount_requested_is_percentage' => $_POST['amount_requested_is_percentage'],
                    'amount_requested' => $_POST['amount_requested'],
                    'percentage' => $_POST['percentage']
                ];
                /*if ($data['amount_requested_is_percentage']) {
                    //unset($data['amount_requested']);
                    $data['amount_requested'] = null;
                } else {
                    //unset($data['percentage']);
                    $data['percentage'] = null;
                }*/
                $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)->update('salary_advance', $data);
                if ($ret) {
                    $ret = Database::getDbh()->where('id_salary_advance', $id_salary_advance)->get('salary_advance');
                    $ret = transformArrayData($ret);
                    echo json_encode($ret, JSON_THROW_ON_ERROR, 512);
                } else {
                    $errors['errors'][0]['message'] = 'Update failed!';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                    return;
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
}
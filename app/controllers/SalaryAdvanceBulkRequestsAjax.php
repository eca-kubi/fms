<?php

class SalaryAdvanceBulkRequestsAjax extends Controller
{
    public function index($request_number = null): void
    {
        $current_user = getUserSession();
        $is_secretary = isSecretary($current_user->user_id);
        $is_finance_officer = isFinanceOfficer($current_user->user_id);
        $is_hr = isCurrentHR($current_user->user_id);
        $is_gm = isCurrentGM($current_user->user_id);
        $is_fmgr = isCurrentFmgr($current_user->user_id);
        $is_hod = isCurrentManager($current_user->user_id);
        $bulk_requests = [];
        try {
            if ($is_hod || $is_secretary) {
                if ($request_number) {
                    $bulk_requests = getSalaryAdvance(['u.department_id' => $current_user->department_id, 'sa.request_number' => $request_number, 'sa.is_bulk_request' => true]);
                } else {
                    $bulk_requests = getBulkRequests(['d.department_id' => $current_user->department_id]);
                }
            } else if ($is_finance_officer || $is_hr || $is_gm || $is_fmgr) {
                if ($request_number) {
                    $bulk_requests = getSalaryAdvance(['sa.request_number' => $request_number, 'sa.is_bulk_request' => true]);
                } else {
                    $bulk_requests = getBulkRequests([]);
                }
            }
        } catch (Exception $e) {
        }
        echo json_encode($bulk_requests, JSON_THROW_ON_ERROR, 512);
    }

    public function Create(): void
    {
        $db = Database::getDbh();
        $current_user = getUserSession();
        $insertIDs = [];
        $errors = ['errors' => [['message' => 'Request submission failed.']]];
        $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
        $request_number = genDeptRef($current_user->department_id, 'salary_advance', false);
        $models = $post['models'];
        $user_ids = [];
        $bulk_requests = [];
        foreach ($models as $model) {
            $is_valid_user = $db->where('user_id', $model['user_id'])
                ->where('department_id', $current_user->department_id)
                ->has('users');
            if ($is_valid_user) {
                $valid_user = $db->where('user_id', $model['user_id'])->getOne('users');
                if (!hasActiveApplication($valid_user['user_id']) && isValidAmount($model['amount_requested'], $valid_user['basic_salary'])) {
                    $percentage = ($model['amount_requested'] / $valid_user['basic_salary']) * 100;
                    $insertID = $db->insert('salary_advance', ['user_id' => $valid_user['user_id'], 'percentage' => $percentage, 'raised_by_id' => $current_user->user_id,
                        'department_id' => $current_user->department_id, 'is_bulk_request' => true, 'raised_by_secretary' => true,
                        'request_number' => $request_number, 'date_raised' => now(), 'amount_requested' => $model['amount_requested']]);
                    if ($insertID) {
                        $insertIDs[] = $insertID;
                        $user_ids[] = $valid_user['user_id'];
                    }
                }
            }
        }
        if (count($insertIDs) !== count($models)) {
            echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
        } else {
            $bulk_requests = [];
            if ($db->insert('salary_advance_bulk_requests', [
                'department_id' => $current_user->department_id,
                'request_number' => $request_number,
                'raised_by_id' => $current_user->user_id,
                'date_raised' => now()
            ])) {
                $hod = new User(getCurrentManager($current_user->department_id));// Send email to HoD
                $subject = "Salary Advance Application ($request_number)";
                $data = ['ref_number' => $request_number, 'link' => URL_ROOT . '/salary-advance/bulk-requests/' . $request_number, 'user_ids' => $user_ids];
                $body = get_include_contents('email_templates/salary-advance/new_bulk_request_notify_hod', $data);
                $data['body'] = $body;
                $email = get_include_contents('email_templates/salary-advance/main', $data);
                insertEmail($subject, $email, $hod->email);
                foreach ($user_ids as $user_id) {
                    $bulk_requests[] = getSalaryAdvance(['u.user_id' => $user_id, 'sa.request_number' => $request_number])[0];
                }
            }
        }
        echo json_encode($bulk_requests, JSON_THROW_ON_ERROR, 512);
    }

    public function Update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ret = [];
            $db = Database::getDbh();
            $current_user = getUserSession();
            $errors = ['errors' => [['message' => 'Update failed.']]];
            $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
            $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
            $request_number = $post['request_number'];
            $models = $post['models'];
            $user_ids = [];
            foreach ($models as $model) {
                if ($model['hod_approval'] === null) {
                    if (isAssignedAsSecretary($current_user->user_id)) {
                        $ret = $db->where('id_salary_advance', $model['id_salary_advance'])->update('salary_advance', ['percentage' => $model['percentage']]);
                    } elseif (isCurrentManagerForDepartment($model['department_id'], $current_user->user_id)) {
                        $ret = $db->where('id_salary_advance', $model['id_salary_advance'])->update('salary_advance', ['percentage' => $model['percentage'],
                            'hod_approval' => $model['hod_approval'], 'hod_id' => $current_user->user_id, 'hod_comment' => $model['hod_comment'],
                            'hod_approval_date' => $model['hod_approval_date']]);
                    }
                } elseif ($model['hr_approval'] === null && isCurrentHR($current_user->user_id)) {
                    $ret = $db->where('id_salary_advance', $model['id_salary_advance'])->update('salary_advance', ['percentage' => $model['percentage'],
                        'hr_approval' => $model['hr_approval'], 'hr_id' => $current_user->user_id, 'hr_comment' => $model['hr_comment'],
                        'hr_approval_date' => now()]);
                } elseif ($model['gm_approval'] === null && isCurrentGM($current_user->user_id)) {
                    $ret = $db->where('id_salary_advance', $model['id_salary_advance'])->update('salary_advance', ['percentage' => $model['percentage'],
                        'gm_approval' => $model['gm_approval'], 'gm_id' => $current_user->user_id, 'gm_comment' => $model['gm_comment'],
                        'gm_approval_date' => now()]);
                } elseif ($model['fmgr_approval'] === null && isCurrentFmgr($current_user->user_id)) {
                    $ret = $db->where('id_salary_advance', $model['id_salary_advance'])->update('salary_advance', ['percentage' => $model['percentage'],
                        'fmgr_approval' => $model['fmgr_approval'], 'fmgr_id' => $current_user->user_id, 'fmgr_comment' => $model['fmgr_comment'],
                        'fmgr_approval_date' => now()]);
                } elseif ($model['date_received'] === null && isFinanceOfficer($current_user->user_id)) {
                    $ret = $db->where('id_salary_advance', $model['id_salary_advance'])->update('salary_advance',
                        ['finance_officer_id' => $current_user->user_id,
                            'finance_officer_comment' => $model['finance_officer_comment'], 'date_received' => now(), 'received_by' => $model['received_by'],
                        ]);
                }
                if ($ret) {
                    $user_ids[] = $model['user_id'];
                }
            }
            if ($ret) {
                $salary_advance = $db->where('id_salary_advance', $post['id_salary_advance'])->get('salary_advance');
                echo json_encode(transformArrayData($salary_advance), JSON_THROW_ON_ERROR, 512);
            } else {
                echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
            }
        }
    }

    public function Destroy(): void
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    }

    public function init():void {
        echo json_encode([], JSON_THROW_ON_ERROR, 512);
    }
}
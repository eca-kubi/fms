<?php

class SalaryAdvanceBulkRequestsAjax extends Controller
{
    public function index($bulk_request_number = null): void
    {
        $db = Database::getDbh();
        $current_user = getUserSession();
        $is_assigned_as_secretary = isAssignedAsSecretary($current_user->user_id);
        $is_finance_officer = isFinanceOfficer($current_user->user_id);
        $is_hr = isCurrentHR($current_user->user_id);
        $is_gm = isCurrentGM($current_user->user_id);
        $is_fmgr = isCurrentFmgr($current_user->user_id);
        $is_hod = isCurrentManager($current_user->user_id);
        $bulk_requests = [];
        if ($is_hod || $is_assigned_as_secretary || $is_finance_officer || $is_hr || $is_gm || $is_fmgr) {
            if ($bulk_request_number) {
                try {
                    if (!($is_finance_officer || $is_hr || $is_gm || $is_fmgr)) {
                        $bulk_requests = $db->join('users u', 'u.user_id=sa.user_id', 'LEFT')
                            ->join('departments d', 'd.department_id=u.department_id', 'LEFT')
                            ->where('sa.bulk_request_number', $bulk_request_number)
                            ->where('u.department_id', $current_user->department_id)
                            ->where('sa.deleted', false)->orderBy('sa.date_raised', 'DESC')
                            ->get('salary_advance sa', null, '*, concat_ws(" ", u.first_name, u.last_name) as name, d.department, NULL as password, NULL as default_password');
                    } else {
                        $bulk_requests = $db->join('users u', 'u.user_id=sa.user_id', 'LEFT')
                            ->join('departments d', 'd.department_id=u.department_id', 'LEFT')
                            ->where('sa.bulk_request_number', $bulk_request_number)->where('sa.deleted', false)
                            ->orderBy('sa.date_raised', 'DESC')->get('salary_advance sa', null, '*, concat_ws(" ", u.first_name, u.last_name) as name, d.department, NULL as password, NULL as default_password');
                    }
                } catch (Exception $e) {
                }
            } else {
                try {
                    if (!($is_finance_officer || $is_hr || $is_gm || $is_fmgr)) {
                        $bulk_requests = $db->join('users u', 'u.user_id=sa.raised_by_id', 'INNER')->join('departments d', 'd.department_id=sa.department_id')
                            ->where('deleted', false)->where('sa.department_id', $current_user->department_id)
                            ->get('salary_advance_bulk_requests sa', null, 'sa.id_salary_advance_bulk_requests, concat_ws(" ", u.first_name, u.last_name) as raised_by, 
                       sa.raised_by_id, sa.bulk_request_number, sa.department_id, d.department');
                    } else {
                        $bulk_requests = $db->join('users u', 'u.user_id=sa.raised_by_id', 'INNER')->join('departments d', 'd.department_id=sa.department_id')
                            ->where('deleted', false)->get('salary_advance_bulk_requests sa', null, 'sa.id_salary_advance_bulk_requests, concat_ws(" ", u.first_name, u.last_name) as raised_by, 
                       sa.raised_by_id, sa.bulk_request_number, sa.department_id, d.department');
                    }
                } catch (Exception $e) {
                }
            }
        }
        echo json_encode($bulk_requests, JSON_THROW_ON_ERROR, 512);
    }

    public function Create(): void
    {
        echo json_encode(transformArrayData([]), JSON_THROW_ON_ERROR, 512);
    }

    public function Update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST array
            $ret = [];
            $db = Database::getDbh();
            $current_user = getUserSession();
            $errors = ['errors' => [['message' => 'Update failed.']]];
            $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
            $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
            $bulk_request_number = $post['bulk_request_number'];
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
}
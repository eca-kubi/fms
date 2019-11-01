<?php
class SalaryAdvanceBulkRequestsAjax extends Controller
{
    public function index($bulk_request_number = null): void
    {
        $db = Database::getDbh();
        $current_user = getUserSession();
        $is_assigned_as_secretary = isAssignedAsSecretary($current_user->user_id);
        $is_hr = isCurrentHR($current_user->user_id);
        $is_gm = isCurrentGM($current_user->user_id);
        $is_fmgr = isCurrentFmgr($current_user->user_id);
        $is_hod = isCurrentManager($current_user->user_id);
        $bulk_requests = [];
        if ($bulk_request_number) {
            try {
                $bulk_requests = $db->where('bulk_request_number', $bulk_request_number)->where('department_id', $current_user->department_id)
                    ->where('deleted', false)->orderBy('date_raised', 'DESC')->get('salary_advance');
            } catch (Exception $e) {
            }
        } elseif (!$bulk_request_number && ($is_assigned_as_secretary || $is_hod)){
            try {
                $bulk_requests = $db->where('department_id', $current_user->department_id)->where('deleted', false)
                    ->where('is_bulk_request', true)->orderBy('date_raised', 'DESC')->get('salary_advance');
            } catch (Exception $e) {
            }
        } elseif (!$bulk_request_number && ($is_gm || $is_hr || $is_fmgr )) {
            try {
                $bulk_requests = $db->where('deleted', false)->where('is_bulk_request', true)
                    ->orderBy('date_raised', 'DESC')->get('salary_advance');
            } catch (Exception $e) {
            }
        }
        echo json_encode(transformArrayData($bulk_requests), JSON_THROW_ON_ERROR, 512);
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
            $errors = ['errors' => [['message'=> 'Update failed.']]];
            $_POST= json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
            $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
            if (isAssignedAsSecretary($current_user->user_id)) {
                $ret = $db->where('id_salary_advance', $post['id_salary_advance'])->update('salary_advance', ['percentage' => $post['percentage']]);
            } elseif (isCurrentManagerForDepartment($post['department_id'], $current_user->user_id)) {
                $ret = $db->where('id_salary_advance', $post['id_salary_advance'])
                    ->update('salary_advance', ['percentage' => $post['percentage'],
                        'hod_approval' => $post['hod_approval'], 'hod_id' => $current_user->user_id, 'hod_comment' => $post['hod_comment'],
                        'hod_approval_date' => $post['hod_approval_date']]);
            }  elseif (isCurrentHR($current_user->user_id)) {
                $ret = $db->where('id_salary_advance', $post['id_salary_advance'])
                    ->update('salary_advance', ['percentage' => $post['percentage'],
                        'hr_approval' => $post['hr_approval'], 'hr_id' => $current_user->user_id, 'hr_comment' => $post['hr_comment'],
                        'hr_approval_date' => $post['hr_approval_date']]);
            } elseif (isCurrentGM($current_user->user_id)) {
                $ret = $db->where('id_salary_advance', $post['id_salary_advance'])
                    ->update('salary_advance', ['percentage' => $post['percentage'],
                        'gm_approval' => $post['gm_approval'], 'gm_id' => $current_user->user_id, 'gm_comment' => $post['gm_comment'],
                        'gm_approval_date' => $post['gm_approval_date']]);
            } elseif (isCurrentFmgr($current_user->user_id)) {
                $ret = $db->where('id_salary_advance', $post['id_salary_advance'])
                    ->update('salary_advance', ['percentage' => $post['percentage'],
                        'fmgr_approval' => $post['fmgr_approval'], 'fmgr_id' => $current_user->user_id, 'fmgr_comment' => $post['fmgr_comment'],
                        'fmgr_approval_date' => $post['fmgr_approval_date']]);
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
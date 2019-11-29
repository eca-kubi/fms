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
        if ($is_hod || $is_secretary) {
            $bulk_requests = getSalaryAdvance(['u.department_id' => $current_user->department_id, 'sa.is_bulk_request' => true, 'deleted' => false]);
        }
        if ($is_finance_officer || $is_hr || $is_gm || $is_fmgr) {
            $bulk_requests = getSalaryAdvance(['sa.is_bulk_request' => true, 'deleted' => false]);
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
        $request_number = genDeptRef($current_user->department_id, 'salary_advance', false);
        $models = $_POST['models'];
        $user_ids = [];
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
                $data = ['ref_number' => $request_number, 'link' => URL_ROOT . '/salary-advance/bulk-request/' . $request_number, 'user_ids' => $user_ids];
                $body = get_include_contents('email_templates/salary-advance/new_bulk_request_notify_hod', $data);
                $data['body'] = $body;
                $email = get_include_contents('email_templates/salary-advance/main', $data);
                insertEmail($subject, $email, $hod->email);
                foreach ($user_ids as $user_id) {
                    $bulk_requests[] = getSalaryAdvance(['u.user_id' => $user_id, 'sa.request_number' => $request_number])[0];
                }
            }
            echo json_encode($bulk_requests, JSON_THROW_ON_ERROR, 512);
        }
    }

    public function Update(): void
    {
        updateSalaryAdvance(false);
    }

    public function Destroy(): void
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    }

    public function init(): void
    {
        echo json_encode([], JSON_THROW_ON_ERROR, 512);
    }
}
<?php
class SalaryAdvanceNewBulkRequestsAjax extends Controller
{
    public function index($bulk_request_number = null): void
    {
        echo json_encode([], JSON_THROW_ON_ERROR, 512);
    }

    public function Create(): void
    {
        $db = Database::getDbh();
        $current_user = getUserSession();
        $insertIDs = [];
        $bulk_requests = [];
        $errors = ['errors' => [['message'=> 'Request submission failed.']]];
        $_POST= json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
        $bulk_request_number = $post['bulk_request_number'];
        $models = $post['models'];
        $user_ids = [];
        foreach ($models as $model) {
            $insertID = $db->insert('salary_advance', ['user_id' => $model['user_id'], 'percentage'=> $model['percentage'], 'raised_by_id' => $current_user->user_id,
                'department_id' => $current_user->department_id, 'is_bulk_request' => $model['is_bulk_request'], 'raised_by_secretary' => $model['raised_by_secretary'],
                'bulk_request_number' => $model['bulk_request_number']]);
            if ($insertID) {
                $insertIDs[] = $insertID;
                $user_ids[] = $model['user_id'];
            }
        }
        if (count($insertIDs) !== count($models)) {
            echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
        } else {
            foreach ($insertIDs as $insertID) {
                $bulk_requests[] = $db->where('id_salary_advance', $insertID)->getOne('salary_advance');
            }
            $db->insert('salary_advance_bulk_requests', ['department_id' => $current_user->department_id, 'bulk_request_number' => $bulk_request_number,
                'raised_by_id' => $current_user->user_id
            ]);
            $hod = new User(getCurrentManager($current_user->department_id));
            // Send email to HoD
            $subject = "Salary Advance Application ($bulk_request_number)";
            $data = ['ref_number' => $bulk_request_number, 'link' => URL_ROOT . '/salary-advance/bulk-requests/' . $bulk_request_number, 'user_ids' => $user_ids];
            $body = get_include_contents('email_templates/salary-advance/new_bulk_request_notify_hod', $data);
            $data['body'] = $body;
            $email = get_include_contents('email_templates/salary-advance/main', $data);
            insertEmail($subject, $email, $hod->email);
            echo json_encode(transformArrayData($bulk_requests), JSON_THROW_ON_ERROR, 512);
        }
    }

    public function Update(): void
    {
        echo json_encode([], JSON_THROW_ON_ERROR, 512);
    }

    public function Destroy(): void
    {
        echo json_encode([], JSON_THROW_ON_ERROR, 512);
    }
}
<?php

class SalaryAdvanceSecretaryAjax extends Controller
{
    public function index(): void
    {
        $db = Database::getDbh();
        $current_user = getUserSession();
        if (isAssignedAsSecretary($current_user->user_id)) {
            $salary_advances = $db->where('department_id', $current_user->department_id)->where('raised_by_secretary', true)->get('salary_advance');
            echo json_encode(transformArrayData($salary_advances), JSON_THROW_ON_ERROR, 512);
        }
    }

    public function Create(): void
    {
        $db = Database::getDbh();
        $current_user = getUserSession();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $post_data = ['user_id' => $_POST['user_id'], 'department_id' => $_POST['department_id'], 'raised_by_secretary' => true, 'raised_by_id' => $current_user->user_id,
                'amount_requested_is_percentage' => $_POST['amount_requested_is_percentage']? true : false, 'amount_requested' => $_POST['amount_requested'], 'percentage' => $_POST['percentage'],
                'department_ref' => genDeptRef($current_user->department_id, 'salary_advance')];
            if ($db->insert('salary_advance', $post_data)) {
                $salary_advance = $db->where('id_salary_advance', $db->getInsertId())->get('salary_advance');
                $transformed_data = transformArrayData($salary_advance);
                echo json_encode($transformed_data, JSON_THROW_ON_ERROR, 512);
            } else {
                $errors = ['errors' => [['message' => 'Failed to  submit new request!']]];
                echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
            }
        } else {
            echo json_encode([], JSON_THROW_ON_ERROR, 512);
        }
    }

    public function Update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $current_user = getUserSession();
            $id_salary_advance = $_POST['id_salary_advance'];
        }
    }

    public function Destroy(): void
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    }
}
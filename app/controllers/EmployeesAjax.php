<?php

class EmployeesAjax extends Controller
{
    public function index(): void
    {
        $current_user = getUserSession();
        $db = Database::getDbh();
        $department_members = getMembersAssignedToSecretary($current_user->user_id);
        foreach ($department_members as $key => &$value) {
            if (hasActiveApplication($value['user_id'])) {
               $value['has_active_application'] = true;
            }
            $department_id = (new User($value['user_id']))->department_id;
            $employee = new stdClass();
            $employee->name = concatNameWithUserId($value['user_id']);
            $employee->department = getDepartment( $department_id);
            $value['department_short_name'] = (new Department($department_id))->short_name;
            $employee->department_short_name = $value['department_short_name'];
            $value['name'] = $employee->name;
            $value['employee'] = $employee;
            $value['department'] = $employee->department;
            unset($value['password']);
        }
        unset($value);
        echo json_encode($department_members, JSON_THROW_ON_ERROR, 512);
    }
}
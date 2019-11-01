<?php

class EmployeesAjax extends Controller
{
    public function index(): void
    {
        $current_user = getUserSession();
        $department_members = getMembersAssignedToSecretary($current_user->user_id);
        foreach ($department_members as $key => &$value) {
            $value['has_active_application'] = hasActiveApplication($value['user_id']);
            $employee = new stdClass();
            $employee->name = concatNameWithUserId($value['user_id']);
            $employee->department = getDepartment( $value['department_id']);
            $employee->department_short_name = $value['department_short_name'] = (new Department($value['department_id']))->short_name;
            $value['name'] = $employee->name;
            $value['employee'] = $employee;
            $value['department'] = $employee->department;
            unset($value['password']);
        }
        unset($value);
        echo json_encode($department_members, JSON_THROW_ON_ERROR, 512);
    }
}
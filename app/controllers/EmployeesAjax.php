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

    public function employees(): void {
        try {
           $employees =  Database::getDbh()->join('departments d', 'd.department_id=u.department_id', 'LEFT')
               ->orderBy('name', 'ASC')
                ->get('users u', null, 'concat_ws(" ", u.first_name, u.last_name) as name, u.user_id,  d.short_name as department_short_name');
           echo json_encode($employees, JSON_THROW_ON_ERROR, 512);
           return;
        } catch (Exception $e) {
        }
        echo json_encode([], JSON_THROW_ON_ERROR, 512);
    }
}
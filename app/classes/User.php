<?php

/**
 * @version 1.0
 * @author UNCLE CHARLES
 */
class User
{
    public $first_name;
    public $last_name;
    public $staff_id;
    public $user_id;
    public $role;
    public $email;
    public $profile_pic;
    public $job_title;
    public $department;
    public $department_id;
    public $basic_salary;

    public function __construct($user_id = '')
    {
        if (!empty($user_id)) {
            try {
                $row = Database::getDbh()->where('user_id', $user_id)
                    ->join('departments d', 'd.department_id=u.department_id', 'LEFT')
                    ->objectBuilder()->getOne('users u', '*, concat_ws(" ", u.first_name, u.last_name) as name');
                foreach ($row as $var => $value) {
                    $this->$var = $value;
                }
            } catch (Exception $e) {
            }
        }
    }

    public static function has($column, $value)
    {
        $db = Database::getDbh();
        $db->where($column, $value);
        if ($db->has('users')) {
            return 'true';
        }
        return false;
    }

    public static function login($staff_id, $password)
    {
        $db = Database::getDbh();
        $ret = $db->where('staff_id', $staff_id)
            ->objectBuilder()
            ->getOne('users');
        if ($ret) {
            $hashed_password = $ret->password;
            if (password_verify($password, $hashed_password)) {
                return $ret;
            }
        }
        return false;
    }

    public static function get(array $where = null): array
    {
        $db = Database::getDbh();
        if (!empty($where)) {
            foreach ($where as $col => $value) {
                $db->where($col, $value);
            }
        }
        return $db->objectBuilder()->get('users');
    }
}
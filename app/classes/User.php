<?php

/**
 * User short summary.
 *
 * User description.
 *
 * @version 1.0
 * @author UNCLE CHARLES
 * @var Department department
 */
class User
{
    public $first_name;
    public $last_name;
    public $staff_id;
    public $user_id;
    public $password;
    public $role;
    public $email;
    public $profile_pic;
    public $job_title;
    public $department;
    public $department_id;
    public $can_assess_impact;
    public $can_change_gm;
    public $default_password;
    public $can_change_dept_mgr;
    private $db;

    public function __construct($user_id = '')
    {
        $this->db = Database::getDbh();
        if (!empty($user_id)) {
            $ret = $this->db->where('user_id', $user_id)
                ->objectBuilder()
                ->get('users');
            foreach ($ret as $row) {
                foreach ($row as $var => $value) {
                    $this->$var = $value;
                }
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

    public static function get(array $where = null)
    {
        $db = Database::getDbh();
        if (!empty($where)) {
            foreach ($where as $col => $value) {
                $db->where($col, $value);
            }
        }
        return $db->objectBuilder()
            ->get('users');
    }
}
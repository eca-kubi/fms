<?php

class User extends GenericEntity
{
    use UserTrait;

    public function __construct($user_id = '', ?array $properties = null)
    {
        parent::__construct();

        if (!empty($user_id)) {
            try {
                $row = Database::getDbh()->where('user_id', $user_id)
                    ->join('departments d', 'd.department_id=u.department_id', 'LEFT')
                    ->objectBuilder()->getOne('users u', '*, concat_ws(" ", u.first_name, u.last_name) as name, staff_id as staff_number');
                foreach ($row as $var => $value) {
                    $this->$var = $value;
                }
            } catch (Exception $e) {
            }
        } else if ($properties) {
            $this->initialize($properties);
        }
    }

    public static function has($column, $value)
    {
        $db = Database::getDbh();
        $db->where($column, $value);
        try {
            if ($db->has('users')) {
                return 'true';
            }
        } catch (Exception $e) {
        }
        return false;
    }

    public static function login($staff_id, $password)
    {
        $db = Database::getDbh();
        //$ret = [];
        try {
            $ret = new User('', $db->where('staff_id', $staff_id)
                ->getOne('users'));
            if ($ret) {
                $hashed_password = $ret->password;
                if (password_verify($password, $hashed_password)) {
                    return $ret;
                }
            }
        } catch (Exception $e) {
        }
        return false;
    }

    /**
     * @param array|null $where
     * @return self[]
     * @throws Exception
     */
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

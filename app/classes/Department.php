<?php

/**
 * Department short summary.
 *
 * Department description.
 *
 * @version 1.0
 * @author UNCLE CHARLES
 * @var department string
 * d_var_name convention to distinguish database table properties
 */
class Department
{
    public $d_department;
    public $d_department_id;
    public $d_short_name;
    public $d_current_manager;
    private $db;

    public function __construct($department_id = '')
    {
        $this->db = Database::getDbh();
        if (!empty($department_id)) {
            $ret =  $this->db->where('department_id', $department_id)
                ->objectBuilder()
                ->get('departments');
            foreach ($ret as $row) {
                foreach ($row as $var => $value) {
                    $this->{"d_".$var} = $value;
                }
            }
        }
    }
}
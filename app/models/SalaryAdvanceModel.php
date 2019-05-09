<?php


class SalaryAdvanceModel
{
    public $id_salary_advance;
    public $user_id;
    public $percentage;
    public $date_raised;
    public $amount_payable;
    public $hr_approval_date;
    public $hod_id;
    public $hr_id;
    public $finance_mgr_id;
    public $fmgr_approval_date;
    public $amount_received;
    public $date_received;
    public $amount_approved;
    public $amount_requested;
    /**
     * @var MysqliDb
     */
    private $db;

    public function __construct($id_salary_advance = null)
    {
        $this->db = Database::getDbh(); // MysqliDb
        if (!empty($id_salary_advance)) {
            $this->id_salary_advance = $id_salary_advance;
            $ret = (array)self::getOne($this->id_salary_advance);
            if (!empty($ret)) {
                foreach ($ret as $key => $value) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public static function getOne($id_salary_advance)
    {
        $db = Database::getDbh();
        return $db->objectBuilder()
            ->where('id_salary_advance', $id_salary_advance)
            ->getOne('salary_advance');
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
            ->get('salary_advance');
    }

    public static function insert($col_data)
    {
        return Database::getDbh()
            ->insert('salary_advance', $col_data);
    }

}
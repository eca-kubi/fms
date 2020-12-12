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
class Form
{
    private static $db;
    public $salary_advance_id;
    public $amount_received;
    public $date;
    public $state;

    public function __construct($salary_advance_id = '')
    {
        self::$db = Database::getDbh();
        if (!empty($user_id)) {
            $ret = self::$db->where('salary_advance_id', $salary_advance_id)
                ->objectBuilder()
                ->get('salary_advance_id');
            foreach ($ret as $row) {
                foreach ($row as $var => $value) {
                    $this->$var = $value;
                }
            }
        }
    }

    public static function getActive()
    {
        return self::$db->where('status', STATUS_ACTIVE)
            ->get('salary_advance');
    }

    public static function getRejected()
    {
        return self::$db->where('status', STATUS_REJECTED)
            ->get('salary_advance');
    }

    public static function getClosed()
    {
        return self::$db->where('state', STATUS_CLOSED)
            ->get('salary_advance');
    }
}
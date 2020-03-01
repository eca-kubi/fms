<?php

class EmailModel extends Model
{
    public static string $table = 'emails';

    public function __construct()
    {
        parent::__construct();
    }

    public static function has($column, $value)
    {
        $db = Database::getDbh();
        $db->where($column, $value);
        if ($db->has(self::$table)) {
            return 'true';
        }
        return false;
    }

    // Verify existence of column value

    /**
     * Summary of getEmail
     * @param mixed $email_id
     * @return object|false
     */
    public function getEmail(int $email_id)
    {
        return (object)
        Database::getDbh()->where('email_id', $email_id)
            ->objectBuilder()
            ->getOne(self::$table);
    }

    public function add($insertData): bool
    {
        return Database::getDbh()->insert(self::$table, $insertData);
    }
}
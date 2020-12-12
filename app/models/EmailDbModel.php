<?php

class EmailDbModel extends DbModel
{
    protected static string $table = 'emails';
    protected static string $primary_key = 'email_id';
    protected static string $entity_class = Email::class;
    use EmailTrait;

    public function __construct(?array $properties = null, ?array $where_col_val = null)
    {
        parent::__construct($properties, $where_col_val);
    }


    public static function getEntitySingle(int $id) : Email
    {
        try {
            return new Email(Database::getDbh()->where(static::$primary_key, $id)->getOne(static::$table));
        } catch (Exception $e) {
        }
        return new Email();
    }

    public static function getEntityMultiple() : EmailCollection
    {
        try {
            $array_values = Database::getDbh()->get(self::$table);
            return EmailCollection::createFromArrayValues($array_values);
        } catch (Exception $e) {
        }
        return EmailCollection::createFromArrayValues([]);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}

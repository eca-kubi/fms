<?php

abstract class DbModel implements iDbModel, JsonSerializable
{
    use InitializeProperties;

    protected static string $table = 'table';
    protected static string $primary_key = 'table_id';
    protected static string $entity_class = GenericEntity::class;
    private ?array $where_col_val;
    private ?array $properties;

    public function __construct(?array $properties = null, ?array $where_col_val = null)
    {
        $this->where_col_val = $where_col_val;
        $this->properties = $properties;

        if (!empty($properties)) {
            $this->initialize($properties);
        } elseif (is_array($this->where_col_val) && !empty($this->where_col_val)) {
            $col_val = $this->fetchSingle($this->where_col_val);
            $this->initialize($col_val);
        }
    }

    /**
     * @param array $where_col_val
     * @return array
     */
    private function fetchSingle(array $where_col_val = [])
    {
        $db = Database::getDbh();
        foreach ($where_col_val as $col => $val) {
            $db->where($col, $val);
        }
        try {
            return $db->getOne(static::$table);
        } catch (Exception $e) {
        }
        return [];
    }

    /**
     * @param array $new_record
     * @return bool
     */
    public static function insert(array $new_record)
    {
        try {
            return Database::getDbh()->insert(static::$table, $new_record);
        } catch (Exception $e) {
        }
        return false;
    }

    public function save(): bool
    {
        try {
            return Database::getDbh()->insert(static::$table, $this->jsonSerialize());
        } catch (Exception $e) {
        }
        return false;
    }

    public function jsonSerialize(): array
    {
//        return get_object_vars($this);
        return (array)new static::$entity_class((array) $this);
    }

    /**
     * @param array $new_multi_records
     * @return array
     */
    public static function insertMulti(array $new_multi_records)
    {
        try {
            return Database::getDbh()->insertMulti(static::$table, $new_multi_records);
        } catch (Exception $e) {
        }
        return [];
    }

    /**
     * @param string $column
     * @param $value
     * @return bool
     */
    public static function has(string $column, $value)
    {
        try {
            return Database::getDbh()->where($column, $value)->has(static::$table);
        } catch (Exception $e) {
        }
        return false;
    }


    public static function update(int $id, array $record): bool
    {
        try {
            unset($record[static::$primary_key]);
            return Database::getDbh()->where(static::$primary_key, $id)->update(static::$table, $record);
        } catch (Exception $e) {
        }
        return false;
    }

    public function getInsertId()
    {
        try {
            return Database::getDbh()->getInsertId();
        } catch (Exception $e) {
            return null;
        }
    }
}

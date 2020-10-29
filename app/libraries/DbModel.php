<?php

abstract class DbModel implements iDbModel, JsonSerializable
{
    protected string $table;
    protected MysqliDb $db;
    private ?array $where_col_val;

    public function __construct(?array $col_val)
    {
        $this->db = Database::getDbh(); // MysqliDb
        $this->where_col_val = $col_val;
        if (!empty($this->where_col_val)) {
            if (is_array($this->where_col_val)) {
                $ret = $this->fetchSingle($this->where_col_val);
                if ($ret) {
                    foreach ($ret as $col => $value) {
                        if (property_exists($this, $col))
                        $this->$col = $value;
                    }
                }
            }
        }
    }

    private function fetchSingle(array $where_col_val = [])
    {
        $this->db->objectBuilder();
        foreach ($where_col_val as $col => $val) {
            $this->db->where($col, $val);
        }
        return $this->db->getOne($this->table);
    }


    public function insertNew(array $new_record)
    {
        return $this->db->insert($this->table, $new_record);
    }


    public function insert()
    {
        return $this->db->insert($this->table, $this->getSingle()->jsonSerialize());
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function insertNewMulti(array $new_multi_records)
    {
        return $this->db->insertMulti($this->table, $new_multi_records);
    }

    public function insertMulti()
    {
        return $this->db->insertMulti($this->table, $this->getMultiple()->jsonSerialize());
    }
}

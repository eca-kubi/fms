<?php

abstract class DbModel implements iDbModel, JsonSerializable
{
    use InitializeProperties;

    protected string $table;
    protected MysqliDb $db;
    private ?array $where_col_val;
    private ?array $properties;

    public function __construct(?array $properties = null, ?array $where_col_val=null)
    {
        $this->db = Database::getDbh(); // MysqliDb
        $this->where_col_val = $where_col_val;
        $this->properties = $properties;
        if (!empty($properties)) {
            $this->initialize($properties);
        } elseif (!empty($this->where_col_val)) {
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

<?php


class VisitorDbModel extends DbModel
{
    protected static string $table = 'visitor';
    public int $visitor_id;
    public string $full_name;
    public string $visitor_type;
    public string $visitor_category;
    public string $company;
    public string $identification_type;
    public string $identification_num;
    public string $phone_num;

    public function __construct(?array $col_val)
    {
        parent::__construct($col_val);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function update($id, array $updated_record)
    {
        // TODO: Implement update() method.
    }

    public function has(string $column, $value)
    {
        // TODO: Implement has() method.
    }

    public function getSingle() : Visitor
    {
        return new Visitor($this->jsonSerialize());
    }

    public function getMultiple() : VisitorCollection
    {
        $array_values = $this->db->get(self::$table);
        return VisitorCollection::createFromArrayValues($array_values);
    }
}

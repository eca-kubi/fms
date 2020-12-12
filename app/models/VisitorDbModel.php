<?php


use VisitorAccessForm\Entities\Visitor;
use VisitorAccessForm\VisitorCollection;

class VisitorDbModel extends DbModel
{
    private static string $table = 'visitor';
    private static string $primary_key = 'visitor_id';
    public ?int $visitor_id = null;
    public ?string $full_name = null;
    public ?string $visitor_type = null;
    public ?string $visitor_category = null;
    public ?string $company = null;
    public ?string $identification_type = null;
    public ?string $identification_num = null;
    public ?string $phone_num = null;

    public function __construct(?array $properties = null, ?array $where_col_val = null)
    {
        parent::__construct($properties, $where_col_val);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function has(string $column, $value)
    {
        // TODO: Implement has() method.
    }

    public function getEntitySingle(int $id) : Visitor
    {
        return new Visitor($this->jsonSerialize());
    }

    public static function getEntityMultiple() : VisitorCollection
    {
        $array_values = [];
        $instance = new self();
        try {
            $array_values = $instance->db->get(self::$table);
        } catch (Exception $e) {
        }
        return VisitorCollection::createFromArrayValues($array_values);
    }

    public static function getWithId(int $id)
    {
        return (new static(null, [self::$primary_key, $id]))->getEntitySingle();
    }
}

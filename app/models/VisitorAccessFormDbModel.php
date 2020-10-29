<?php


class VisitorAccessFormDbModel extends DbModel
{
    protected string $table = 'visitor_access_form';
    public int $visitor_access_form_id;
    public int $visitor_id;
    public ?string $arrival_date;
    public ?string $departure_date;
    public ?string $reason_for_visit;
    public ?string $date_raised;
    public ?int $department_id;

    public function __construct(?array $where_col_val)
    {
        parent::__construct($where_col_val);
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

    public function add(array $new_record)
    {
        // TODO: Implement add() method.
    }

    public function getVisitor()
    {
        //return new VisitorDbModel([$col_val])->
    }

    public function getSingle() : VisitorAccessForm
    {
        return new VisitorAccessForm($this->jsonSerialize());
    }

    public function getMultiple() : VisitorAccessFormCollection
    {
        $array_values = $this->db->get($this->table);
        return VisitorAccessFormCollection::createFromArrayValues($array_values);
    }
}

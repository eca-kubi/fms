<?php


class VisitorAccessForm extends GenericEntity
{
    public ?int $visitor_access_form_id;
    public ?string $arrival_date;
    public ?string $departure_date;
    public ?int $visitor_id;
    public ?string $reason_for_visit;
    public ?string $date_raised;
    public ?int $department_id;

    public function __construct(?array $properties)
    {
        parent::__construct($properties);
    }
}

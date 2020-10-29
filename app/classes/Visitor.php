<?php


class Visitor extends GenericEntity
{
    public ?int $visitor_id;
    public ?string $full_name;
    public ?string $visitor_type;
    public ?string $visitor_category;
    public ?string $company;
    public ?string $identification_num;
    public ?string $phone_num;

    public function __construct(?array $properties)
    {
        parent::__construct($properties);
    }
}

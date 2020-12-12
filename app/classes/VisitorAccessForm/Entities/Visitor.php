<?php


namespace VisitorAccessForm\Entities;
use GenericEntity;

class Visitor extends GenericEntity
{
    public ?int $visitor_id = null;
    public ?string $full_name = null;
    public ?string $visitor_type = null;
    public ?string $visitor_category = null;
    public ?string $company = null;
    public ?string $identification_num = null;
    public ?string $phone_num = null;

    public function __construct(?array $properties = null)
    {
        parent::__construct($properties);
    }
}

<?php


namespace VisitorAccessForm;


trait AccessLevelTrait
{
    public ?int $access_level_id = null;
    public ?string $area = '';
    public ?bool $twenty_four_seven = null;
    public ?bool $bus_hrs = null;
    public ?int $visitor_access_form_id = null;
}

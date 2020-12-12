<?php


namespace TemplateModels;


use GenericEntity;

class EmailTemplateModel extends GenericEntity
{
    public ?string $subject = null;
    public ?string $message = null;
    public ?int $copyright_year = null;

    public function __construct(string $subject, string $message, ?array $properties = null)
    {
        parent::__construct($properties);
        $this->subject = $subject;
        $this->message  = $message;
        $this->copyright_year = today('Y');
    }
}

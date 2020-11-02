<?php


class Email extends GenericEntity
{
    public ?int $email_id;
    public ?string $subject;
    public ?string $content;
    public ?string $recipient_address;
    public ?string $recipient_name;
    public ?bool $sent;
    public ?string $attachment;

    public function __construct(?array $properties)
    {
        parent::__construct($properties);
    }
}

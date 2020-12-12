<?php


trait EmailTrait
{
    public ?int $email_id  = null;
    public ?string $subject = null;
    public ?string $content = null;
    public ?string $recipient_address = null;
    public ?string $recipient_name = null;
    public ?bool $sent = true;
    public ?string $attachment = null;
    public ?string $date_created = null;
    public ?string $date_sent =null;
}

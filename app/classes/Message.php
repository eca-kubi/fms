<?php


class Message extends GenericEntity
{
    public string $subject;
    public Body $body;
    /**
     * @var Recipient[]
     */
    public array $toRecipients;
    /**
     * @var Recipient[]
     */
    public array $ccRecipients;

    public function __construct(string $subject, Body $body)
    {
        parent::__construct();
        $this->subject = $subject;
        $this->body = $body;
    }

}

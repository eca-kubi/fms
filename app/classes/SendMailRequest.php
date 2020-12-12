<?php


class SendMailRequest extends GenericEntity
{
    public Message $message;
    public bool $saveToSentItems = false;

    public function __construct(Message $message, ?array $properties = null)
    {
        parent::__construct($properties);
        $this->message = $message;
    }

}

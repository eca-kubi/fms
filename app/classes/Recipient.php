<?php


class Recipient extends GenericEntity
{
    public EmailAddress $emailAddress;

    public function __construct(EmailAddress $emailAddress)
    {
        parent::__construct();
        $this->emailAddress = $emailAddress;
    }
}

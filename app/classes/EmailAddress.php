<?php


class EmailAddress extends GenericEntity
{
    public string $address;

    public function __construct(string $address)
    {
        parent::__construct();
        $this->address = $address;
    }
}

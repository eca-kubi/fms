<?php


class Email extends GenericEntity
{
   use EmailTrait;

    public function __construct(?array $properties = null)
    {
        parent::__construct($properties);
        $this->date_created = now();
    }
}

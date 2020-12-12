<?php


class ValidationError
{
     public string $field;
     public string $description;

    public function __construct(string $field, string $description)
    {
        $this->field = $field;
        $this->description = $description;
     }

}

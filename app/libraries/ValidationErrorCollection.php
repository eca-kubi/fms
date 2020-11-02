<?php


class ValidationErrorCollection extends GenericCollection
{
    /**
     * @var ValidationError[]
     */
    private array $values;
    public function __construct(ValidationError ...$errors)
    {
        $this->values = $errors;
    }

    public function setValidationErrors(ValidationError ...$errors)
    {
        $this->values = $errors;
    }

    /**
     * @return ValidationError[]
     * */
    public function getValidationErrors()
    {
        return $this->values;
    }

    public static function createFromArrayValues(array $array_values)
    {
        // TODO: Implement createFromArrayValues() method.
    }
}

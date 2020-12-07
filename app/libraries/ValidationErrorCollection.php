<?php


class ValidationErrorCollection extends GenericCollection
{
    /**
     * @var ValidationError[]
     */
    protected array $values;
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
        $errors = [];
        foreach ($array_values as $value) {
            $errors[] = new ValidationError($value['field'], $value['description']);
        }
        return new self(...$errors);
    }
}

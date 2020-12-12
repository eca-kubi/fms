<?php


class EmailCollection extends GenericCollection
{
    /**
     * @var Email[]
     */
    protected array $values;
    public function __construct(Email ...$emails)
    {
        $this->values = $emails;
    }

    public function setEmails(Email ...$emails)
    {
        $this->values = $emails;
    }

    /**
     * @return Email[]
     * */
    public function getEmails()
    {
        return $this->values;
    }

    public static function createFromArrayValues(array $array_values)
    {
        $emails = [];
        foreach ($array_values as $array_value) {
            $emails[] = new Email($array_value);
        }
        return new self(...$emails);
    }
}

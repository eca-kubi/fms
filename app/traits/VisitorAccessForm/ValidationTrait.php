<?php


namespace VisitorAccessForm;

use DateFormat;
use Respect\Validation\Validator as v;
use ValidationError;
use ValidationErrorCollection;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

trait ValidationTrait
{
    private ValidationErrorCollection $errors;
    public VisitorAccessFormEntity $visitor_access_form;

    // todo: Write validation logic
    public function validate(){
        $errors = [];
        $date_validator = v::date(DateFormat::BACKEND_FORMAT);
        if (!$date_validator->validate($this->visitor_access_form->departure_date)) {
            $errors[] = new ValidationError('departure_date', 'The value provided is not a valid date in the format YYYY/MM/DD');
        }
        if (!$date_validator->validate($this->visitor_access_form->arrival_date)) {
            $errors[] = new ValidationError('departure_date', 'The value provided is not a valid date in the format YYYY/MM/DD');
        }
        $this->errors = new ValidationErrorCollection(...$errors);
        return $this;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}

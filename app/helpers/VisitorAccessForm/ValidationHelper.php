<?php


namespace VisitorAccessForm;


use DateFormat;
use Respect\Validation\Validator as v;
use ValidationError;
use ValidationErrorCollection;
use VisitorAccessForm\Entities\AccessLevel;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

class ValidationHelper
{
    private ValidationErrorCollection $errors;

    public function __construct()
    {
        $this->errors = new ValidationErrorCollection();
    }

    public function validateSectionA(VisitorAccessFormEntity $visitorAccessForm)
    {
        $errors = [];
        $date_validator = v::date(DateFormat::BACKEND_FORMAT);
        if (!$date_validator->validate($visitorAccessForm->departure_date)) {
            $errors[] = new ValidationError('departure_date', 'The value provided is not a valid date in the format YYYY/MM/DD');
        }
        if (!$date_validator->validate($visitorAccessForm->arrival_date)) {
            $errors[] = new ValidationError('departure_date', 'The value provided is not a valid date in the format YYYY/MM/DD');
        }
        $this->errors = new ValidationErrorCollection(...$errors);
        return $this;
    }

    public function validateSectionB(AccessLevelCollection $accessLevelCollection)
    {
        $filtered = $accessLevelCollection->filterCollection(fn(AccessLevel $accessLevel) => $accessLevel->bus_hrs == 1 || $accessLevel->twenty_four_seven == 1);
        if (count($filtered) === 0)
            $this->errors = new ValidationErrorCollection(new ValidationError('access_level', 'Please indicate the level of access'));
        return $this;
    }

    public function validateSectionC()
    {
        $errors = [];
        if (!isset($_POST['approved_by_site_sponsor']) || is_null($_POST['approved_by_site_sponsor'])) {
            $errors[] = new ValidationError('approved_by_site_sponsor', 'A value must be provided.');
        }
        $this->errors = new ValidationErrorCollection(...$errors);
        return $this;
    }

    public function validateSectionD( )
    {
        $errors = [];
        if (!isset($_POST['approved']) || is_null($_POST['approved'])) {
            $errors[] = new ValidationError('approved', 'A value must be provided.');
        }
        /*switch ($current_user->user_id) {
            case  $visitorAccessFormEntity->hr_approver_id:
            case getCurrentHR():
                if (!isset($_POST['approved']) || is_null($_POST['approved'])) {
                    $errors[] = new ValidationError('approved_by_hr', 'A value must be provided.');
                }
                break;
            case $visitorAccessFormEntity->gm_id:
            case getCurrentGM():
                if (!isset($_POST['approved_by_gm']) || is_null($_POST['approved_by_gm'])) {
                    $errors[] = new ValidationError('approved_by_gm', 'A value must be provided.');
                }
                break;
            case $visitorAccessFormEntity->security_approver_id:
            case getCurrentSecurityManager():
                if (!isset($_POST['approved_by_security']) || is_null($_POST['approved_by_security'])) {
                    $errors[] = new ValidationError('approved_by_security', 'A value must be provided.');
                }
                break;
            default:
                $this->errors = new ValidationErrorCollection(...$errors);
        }*/
        $this->errors = new ValidationErrorCollection(...$errors);
        return $this;
    }

    /**
     * @return ValidationErrorCollection
     */
    public function getErrors(): ValidationErrorCollection
    {
        return $this->errors;
    }
}

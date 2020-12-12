<?php


namespace ViewModels;


use UserTrait;

class UserRegistrationFormViewModel extends FormViewModel
{
    use UserTrait;

    public ?string $email_err = null;
    public ?string $staff_id_err = null;
    public ?string $first_name_err = null;
    public ?string $last_name_err = null;
    public ?string $password_err = null;
    public ?string $confirm_password_err = null;
    public ?string $profile_pic_err = null;
    public ?int $error_count = 0;
    public ?string $phone_number_err = null;
    public ?string $department_err = null;
    public ?string $disable_leave_notif = null;

    public function __construct(string $title, ?array $properties = null)
    {
        parent::__construct($title, $properties);
    }
}

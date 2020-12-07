<?php


namespace ViewModels;


use UserTrait;

class LoginViewModel extends FormViewModel
{
    use UserTrait;
    public ?string $redirect_url = null;
    public ?string $staff_id_err = null;
    public ?int $error_count = 0;
    public ?string $password_err = null;

    public function __construct(string $title, ?array $properties = null)
    {
        parent::__construct($title, $properties);
    }

    public function initializeRequiredProperties(string $title)
    {
        $this->title = $title;
    }
}

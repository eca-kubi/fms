<?php


namespace ViewModels;


class ErrorViewModel extends ViewModel
{

    public int $error_code;
    public string $message;

    public function __construct($error_code, $message, string $title, ?array $properties = null)
    {
        parent::__construct($title, $properties);
        $this->message = $message;
        $this->error_code = $error_code;
    }

    public function initializeRequiredProperties(string $title)
    {
        $this->title = $title;
    }
}

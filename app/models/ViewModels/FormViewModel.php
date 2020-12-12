<?php


namespace ViewModels;


class FormViewModel extends ViewModel
{

    public function initializeRequiredProperties(string $title)
    {
        $this->title = $title;
    }
}

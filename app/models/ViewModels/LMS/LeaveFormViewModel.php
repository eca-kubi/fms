<?php


namespace ViewModels\LMS;


use ViewModels\FormViewModel;

class LeaveFormViewModel extends FormViewModel
{

    public ?string $start_date = '';
    public ?string $end_date = '';
    public ?string $type = '';
    public ?string $vac_address = '';
    public ?string $vac_phone_no = '';
    public ?string $resume_date = '';
    public ?string $relationship = '';
    public ?string $leave_reason = '';
}

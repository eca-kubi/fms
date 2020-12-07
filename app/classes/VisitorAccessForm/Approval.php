<?php


namespace VisitorAccessForm;


use User;

class Approval
{

    public ?User $approver;
    public ?string $date;
    public ?string $comment;
    public ?bool $approved;
    public ?bool $completed;

    public function __construct(?User $approver, ?string $date, ?string $comment, ?bool $approved, ?bool $completed)
    {
        $this->comment = $comment;
        $this->approved = $approved;
        $this->approver = $approver;
        $this->date = $date;
        $this->completed = $completed;
    }

    public static function getStatusAsString(?bool $status) : string
    {
        return is_null($status) ? 'Pending' : ($status == true ? 'Approved' : 'Rejected');
    }
}

<?php


class Approver extends GenericEntity
{
    use UserTrait;
    public string $approval_role;

    public function __construct(int $approver_id, string $approval_role, ?array $properties = null)
    {
        parent::__construct($properties);
        $user = new User($approver_id);
        $this->approval_role = $approval_role;
        $this->initialize($user->jsonSerialize());
    }
}

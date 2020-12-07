<?php

namespace VisitorAccessForm\Entities;

use Approver;
use GenericEntity;
use VisitorAccessForm\ApproverRole;
use VisitorAccessForm\VisitorAccessFormModelTrait;

class VisitorAccessFormEntity extends GenericEntity
{
    use VisitorAccessFormModelTrait;

    public function __construct(?array $properties)
    {
        parent::__construct($properties);
    }

    public function getSecurityMgr() : Approver
    {
        return new Approver($this->security_approver_id, ApproverRole::SECURITY_MANAGER);
    }

    public function getHrManager() : Approver
    {
        return new Approver($this->hr_approver_id, ApproverRole::HR_MANAGER);
    }

    public function getGM() : Approver {
        return new Approver($this->gm_id, ApproverRole::GENERAL_MANAGER);
    }

    public function getSiteSponsor() : Approver {
        return new Approver($this->site_sponsor_id, 'HoD');
    }
}

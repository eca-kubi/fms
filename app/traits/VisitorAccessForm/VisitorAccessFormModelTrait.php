<?php
namespace VisitorAccessForm;

trait VisitorAccessFormModelTrait
{
    public ?bool   $approved_by_site_sponsor        = null;
    public ?string $arrival_date           = '';
    public ?string $company                = '';
    public ?string $date_raised            = '';
    public ?int    $department_id          = null;
    public ?string $departure_date         = '';
    public ?string $full_name              = '';
    public ?string $identification_num     = '';
    public ?string $identification_type    = '';
    public ?int    $originator_id          = null;
    public ?string $other_visitor_type     = '';
    public ?string $phone_num              = '';
    public ?string $reason_for_visit       = '';
    public ?string $uploaded_documents     = '';
    public ?int    $visitor_access_form_id = null;
    public ?string $visitor_category       = null;
    public ?string $visitor_type           = null;
    public ?int $site_sponsor_id     = null;
    public ?int $hr_approver_id = null;
    public ?int $security_approver_id = null;
    public ?bool $approved_by_security = null;
    public ?bool $approved_by_hr = null;
    public ?bool $details_confirmed = null;
    public ?int $access_level_id = null;
    public ?string $document_id = null;
    public ?bool $section_a_completed = null;
    public ?bool $section_b_completed = null;
    public ?bool $section_c_completed = null;
    public ?bool $section_d_completed = null;
    public ?string $site_sponsor_approval_date = null;
    public ?string $security_approval_date = null;
    public ?string $hr_approval_date = null;
    public ?string $gm_approval_date = null;
    public ?bool $approved_by_gm = null;
    public ?int $gm_id = null;
    public ?string $gm_comment = null;
    public ?string $hr_comment = null;
    public ?string $site_sponsor_comment = null;
    public ?string $security_approver_comment = null;
    public ?bool $hr_completed = null;
    public ?bool $gm_completed = null;
    public ?bool $security_manager_completed = null;
    //public ?bool $site_sponsor_completed = null;
    public ?bool $hr_notified_for_approval = null;
    public ?bool $gm_notified_for_approval = null;
    public ?bool $security_manager_notified_for_approval = null;
    public ?bool $hod_notified_for_approval = null;
    public ?bool $originator_notified_of_hod_approval = null;
    public ?bool $originator_notified_of_hr_approval = null;
    public ?bool $originator_notified_of_sec_mgr_approval = null;
    public ?bool $originator_notified_of_gm_approval = null;
    public ?bool $request_completed = null;
    public ?bool $request_cancelled = null;
    public ?bool $approval_completed = false;


}

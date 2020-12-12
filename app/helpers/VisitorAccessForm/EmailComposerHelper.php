<?php


namespace VisitorAccessForm;


use Email;
use EmailDbModel;
use FileHelper;
use HTMLHelper;
use TemplateModels\EmailTemplateModel;
use User;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

class EmailComposerHelper
{
    /**
     * Called when an originator sends a request
     * @param User $originator The originator
     * @param VisitorAccessFormEntity $visitor_access_form The form
     * @param string $formUrl The user clicks this link to view the form
     * @return array|bool
     */
    public static function sectionA(User $originator, VisitorAccessFormEntity $visitor_access_form, string $formUrl)
    {
        $subject = "Visitor Access Form ($visitor_access_form->document_id)";
        $originatorMessage = 'You have submitted a visitor access form. Kindly click the link below to complete the form.'
            . HTMLHelper::LINEBREAK . "<b>Link:</b> " . $formUrl;
        return self::insertEmail($originator, $originatorMessage, $subject);
    }

    public static function sectionB(User $originator, VisitorAccessFormEntity $visitor_access_form, string $formUrl)
    {
        $originator_message = 'You have completed a visitor access form. Your HoD has been notified to approve your request.'
            . HTMLHelper::LINEBREAK . 'You may use the link below to track the progress of your request.'
            . HTMLHelper::LINEBREAK . "<b>Link:</b> " . $formUrl;
        $hod_message = $originator->name . ' has submitted a visitor access form for your approval.' . HTML_NEW_LINE . 'Kindly click link below to approve it.'
            . HTML_NEW_LINE . "<b>Link: </b>" . $formUrl;
        $subject = "Visitor Access Form ($visitor_access_form->document_id)";
        $ret = self::insertEmail($originator, $originator_message, $subject);

        // notify hod
        if (!$visitor_access_form->hod_notified_for_approval) {
            $hod = new User(getCurrentManager($originator->department_id));
            $ret = $ret && self::insertEmail($hod, $hod_message, $subject);
            $visitor_access_form->hod_notified_for_approval = true;
        }
        return $ret;
    }

    public static function sectionC(VisitorAccessFormEntity $visitor_access_form, Approval $approval, string $formUrl)
    {
        $approver_message = "You have " . ($approval->approved ? 'approved' : 'rejected') . " the visitor access form request with ID " . $visitor_access_form->document_id  .
            HTML_NEW_LINE . "You may use the link below to review your action." . HTML_NEW_LINE . "<b>Link: </b>" . $formUrl;

        $originator_message = "Your HoD has " . ($approval->approved ? 'approved' : 'rejected') . " your visitor access form request (" . $visitor_access_form->document_id . ")" .
            HTML_NEW_LINE . "Click the link below for details" . HTML_NEW_LINE . "<b>Link: </b>" . $formUrl;

        $hr_message  = $approval->approver->name . " (" . $approval->approver->job_title .")" . ' has ' . ($approval->approved ? 'approved' : 'rejected') . " a visitor access form request (" . $visitor_access_form->document_id . ")" .
            HTML_NEW_LINE . "Click the link below for details" . HTML_NEW_LINE . "<b>Link: </b>" . $formUrl;

        $originator = new User($visitor_access_form->originator_id);
        $subject = "Visitor Access Form ($visitor_access_form->document_id)";
        $ret = self::insertEmail($approval->approver, $approver_message, $subject);

        if (!$visitor_access_form->originator_notified_of_hod_approval) {
            $ret = $ret && self::insertEmail($originator, $originator_message, $subject);
            $visitor_access_form->originator_notified_of_hod_approval = true;
        }
        // notify next approver (HR)
        if ($approval->approved && !$visitor_access_form->hr_notified_for_approval) {
            $hr = new User(getCurrentHR());
            $ret = $ret && self::insertEmail($hr, $hr_message, $subject);
            $visitor_access_form->hr_notified_for_approval = true;
        }
        return $ret;
    }

    public static function SectionD(VisitorAccessFormEntity $visitor_access_form, Approval $approval, string $formUrl)
    {
        $subject = "Visitor Access Form ($visitor_access_form->document_id)";
        $originator = new User($visitor_access_form->originator_id);
        $security_manager = new User(getCurrentSecurityManager());
        $gm = new User(getCurrentGM());

        $approval_role = isCurrentSecurityManager($approval->approver->user_id) ? ApproverRole::SECURITY_MANAGER : (isCurrentHR($approval->approver->user_id) ? ApproverRole::HR_MANAGER : ApproverRole::GENERAL_MANAGER);

        $originator_message = $approval_role . ' has ' . ($approval->approved ? 'approved' : 'rejected') . " your visitor access form request (" . $visitor_access_form->document_id . ")" .
            HTML_NEW_LINE . "Click the link below for details" . HTML_NEW_LINE . "<b>Link: </b>" . $formUrl;

        $approver_message = "You have " . ($approval->approved ? 'approved' : 'rejected') .  " the visitor access form request with ID " . $visitor_access_form->document_id .
            HTML_NEW_LINE . "You may use the link below to review your action." . HTML_NEW_LINE . "<b>Link: </b>" . $formUrl;

        $next_approver_message =   $approval->approver->name . " (" . $approval_role .")" .  ' has ' . ($approval->approved ? 'approved' : 'rejected') . " a visitor access form request (" . $visitor_access_form->document_id . ")" .
            HTML_NEW_LINE . "You may use the link below to review it." . HTML_NEW_LINE . "<b>Link: </b>" . $formUrl;


        $ret = self::insertEmail($approval->approver, $approver_message, $subject);

        switch ($approval->approver->user_id) {
            case getCurrentHR():
                // notify originator
                if (!$visitor_access_form->originator_notified_of_hr_approval) {
                    $ret = $ret && self::insertEmail($originator, $originator_message, $subject);
                    $visitor_access_form->originator_notified_of_hr_approval = true;
                }
                if ($approval->approved && !$visitor_access_form->security_manager_notified_for_approval) {
                    // notify next approver (security manager)
                    $ret = $ret && self::insertEmail($security_manager, $next_approver_message, $subject);
                    $visitor_access_form->security_manager_notified_for_approval = true;
                }
                break;
            case getCurrentSecurityManager():
                if ($approval->approved && !$visitor_access_form->gm_notified_for_approval) {
                    // notify next approver (gm)
                    $ret = $ret && self::insertEmail($gm, $next_approver_message, $subject);
                    $visitor_access_form->gm_notified_for_approval = true;
                }
                // notify originator
                if (!$visitor_access_form->originator_notified_of_sec_mgr_approval) {
                    $ret = $ret && self::insertEmail($originator, $originator_message, $subject);
                    $visitor_access_form->originator_notified_of_sec_mgr_approval = true;
                }
                break;
            case getCurrentGM():
                // approval flow completed: notify originator
                if (!$visitor_access_form->originator_notified_of_gm_approval) {
                    $ret = $ret && self::insertEmail($originator, $originator_message, $subject);
                    $visitor_access_form->originator_notified_of_gm_approval = true;
                }
                break;
            default:
        }
        return $ret;
    }

    public static function applyTemplate(EmailTemplateModel $template_model)
    {
        $template_file = APP_ROOT . '/templates/emails/template.php';
        return FileHelper::getFileContents($template_file, ['template_model' => $template_model]);
    }

    private static function insertEmail(User $recipient, $content, $subject)
    {
        $email = new Email();
        $email->subject = $subject;
        $email->recipient_address = $recipient->email;
        $email->recipient_name = $recipient->name;
        $email->content = $content;
        return EmailDbModel::insert($email->jsonSerialize());
    }
}

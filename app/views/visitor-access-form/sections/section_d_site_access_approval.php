<?php
/**
 * @var SectionsViewModel $view_model ;
 */

/**
 * @var User $current_user ;
 */

/**
 * @var SectionDViewModel $section_d;
 *
 * @var VisitorAccessFormEntity $visitor_access_form;
 */

$current_user = $current_user ?? getUserSession();
$visitor_access_form = $view_model->visitor_access_form;
$section_d = $view_model->section_d;

$hr_approver = new User($visitor_access_form->hr_approver_id? : getCurrentHR()) ;
$gm_approver = new User($visitor_access_form->gm_id? : getCurrentGM());
$security_approver = new User($visitor_access_form->security_approver_id? : getCurrentSecurityManager());

use ViewModels\VisitorAccessForm\SectionDViewModel;
use ViewModels\VisitorAccessForm\SectionsViewModel;
use VisitorAccessForm\Approval;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

?>
<div class="row p-2">
    <div class="row w-100 border ml-0 p-1">
        <h6 class="text-bold font-italic col m-1">
            <a href="#<?php echo $section_d->description_code ?>" data-toggle="collapse">
                <i class="fa fa-plus-circle"
                   data-target="#<?php echo $section_d->description_code ?>"></i> <?php echo $section_d->title; ?> <?php echo $visitor_access_form->section_d_completed? echoCompleted() : echoInComplete(''); ?>
            </a>

            <span class="mx-2"></span>
            <?php if ( isCurrentHR($current_user->user_id) || (isCurrentSecurityManager($current_user->user_id) && $visitor_access_form->hr_completed) || (isCurrentGM($current_user->user_id) && $visitor_access_form->security_manager_completed)) : ?>

                <a data-toggle="modal" href="#modal_<?php echo $section_d->description_code . '_approver_id_'. $current_user->user_id?>">
                    <span class="text-sm badge badge-success"> <i
                            class="fa fa-edit"></i> Edit</span>
                </a>
            <?php endif ?>
        </h6>
    </div>
    <div class="w-100 section collapse" id="<?php echo $section_d->description_code; ?>">
        <div class="d-sm-block">
            <?php
            // hr
            $section_d->approval = new Approval($hr_approver, $visitor_access_form->hr_approval_date, $visitor_access_form->hr_comment, $visitor_access_form->approved_by_hr, $visitor_access_form->hr_completed);
            require APP_ROOT . '/views/includes/visitor-access-form/section_d_post.php'
            ?>

            <?php
            // security manager
                    $section_d->approval = new Approval($security_approver, $visitor_access_form->security_approval_date, $visitor_access_form->security_approver_comment, $visitor_access_form->approved_by_security, $visitor_access_form->security_manager_completed);
                    require APP_ROOT . '/views/includes/visitor-access-form/section_d_post.php';

            ?>

            <?php
            // general manager
                $section_d->approval = new Approval($gm_approver, $visitor_access_form->gm_approval_date, $visitor_access_form->gm_comment, $visitor_access_form->approved_by_gm, $visitor_access_form->gm_completed);
                require APP_ROOT . '/views/includes/visitor-access-form/section_d_post.php';

            ?>
        </div>
    </div>
</div>


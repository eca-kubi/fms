<?php use ViewModels\VisitorAccessForm\SectionsViewModel;
use VisitorAccessForm\Approval;

require_once APP_ROOT . '\views\includes\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\visitor-access-form\navbar.php'; ?>
<?php require_once APP_ROOT . '\views\includes\visitor-access-form\sidebar.php'; ?>

<?php
/**
 * @var SectionsViewModel $view_model;
 */
$section_a = $view_model->section_a;
$section_b = $view_model->section_b;
$section_d = $view_model->section_d;
$visitor_access_form = $view_model->visitor_access_form;
$current_user = $current_user?? getUserSession();
$hr_approver = new User($visitor_access_form->hr_approver_id? : getCurrentHR()) ;
$gm_approver = new User($visitor_access_form->gm_id? : getCurrentGM());
$security_approver = new User($visitor_access_form->security_approver_id? : getCurrentSecurityManager());

?>
<!-- .content-wrapper -->
<div class="content-wrapper animated fadeInRight" style="margin-top: <?php echo NAVBAR_MT; ?>">
    <!-- .content-header-->
    <section class="content-header d-none">
        <!-- .container-fluid -->
        <div class="container-fluid">
            <!-- .row -->
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <a href="javascript: window.history.back();" class="btn w3-btn bg-gray">
                                <i class="fa fa-backward"></i> Go Back
                            </a>
                        </li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <h1>
                        <?php echo APP_NAME; ?>
                    </h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content-header-->
    <!-- content -->
    <section class="content">
        <div class="box p-2">
            <div class="box-header">
                <h5>
                    <?php flash('flash_sections'); ?>
                </h5>
                <h3 class="box-title text-bold w-100">
                    <?php echo $view_model->title ?>
                </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body pt-0">
                <?php

                require_once APP_ROOT . "/views/visitor-access-form/sections/section_a_visitor_details.php";

                if ($visitor_access_form->section_a_completed) {
                    require_once APP_ROOT . "/views/visitor-access-form/sections/section_b_access_level.php";
                } else {
                    alert('Access Level Pending...', 'alert text-primary font-weight-bold');
                }

                if ($visitor_access_form->section_b_completed) {
                    require_once APP_ROOT . "/views/visitor-access-form/sections/section_c_site_sponsors_approval.php";
                } else {
                    alert("Site Sponsor's Approval Pending...", 'alert text-primary font-weight-bold');
                }

                if ($visitor_access_form->section_c_completed) {
                    require_once APP_ROOT . "/views/visitor-access-form/sections/section_d_site_access_approval.php";
                    if (!$visitor_access_form->hr_completed)
                        alert("HR Approval Pending...", 'alert text-primary font-weight-bold');
                    if(!$visitor_access_form->security_manager_completed)
                        alert("Security Manager's Approval Pending...", 'alert text-primary font-weight-bold');
                    if(!$visitor_access_form->gm_completed)
                        alert("General Manager's Approval Pending...", 'alert text-primary font-weight-bold');
                } else {
                    alert("HR Approval Pending...", 'alert text-primary font-weight-bold');
                    alert("Security Manager's Approval Pending...", 'alert text-primary font-weight-bold');
                    alert("General Manager's Approval Pending...", 'alert text-primary font-weight-bold');
                }
                $print_url = site_url("visitor-access-form/print/$visitor_access_form->visitor_access_form_id");
                ?>
                <?php if ($visitor_access_form->section_d_completed): ?>
                    <div class="alert text-success container-fluid text-bold text-center">
                        <p class="text-center tada">Approval Workflow Complete!</p>
                        <p><small><a class="text-bold flash animated print-it" href="<?php echo $print_url ?>"
                                     style="color: #007bff; animation-iteration-count: infinite;">Click here to print the completed form.</a></small></p>
                    </div>
                <?php endif ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer"></div>
            <!-- /.box-footer-->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modals -->
<?php $view_model->display_form_controls = true; ?>
<?php require_once APP_ROOT . '/views/modals/visitor-access-form/section_a_visitor_details_modal.php'?>
<?php require_once APP_ROOT . '/views/modals/visitor-access-form/section_b_access_level_modal.php'?>
<?php require_once APP_ROOT . '/views/modals/visitor-access-form/section_c_modal.php'?>
<?php
// hr
$section_d->approval = new Approval($hr_approver, $visitor_access_form->hr_approval_date, $visitor_access_form->hr_comment, $visitor_access_form->approved_by_hr, $visitor_access_form->hr_completed);
?>
<?php require APP_ROOT . '/views/modals/visitor-access-form/section_d_modal.php'?>

<?php
// security manager
$section_d->approval = new Approval($security_approver, $visitor_access_form->security_approval_date, $visitor_access_form->security_approver_comment, $visitor_access_form->approved_by_security, $visitor_access_form->security_manager_completed);
?>
<?php require APP_ROOT . '/views/modals/visitor-access-form/section_d_modal.php'?>

<?php
// general manager
$section_d->approval = new Approval($gm_approver, $visitor_access_form->gm_approval_date, $visitor_access_form->gm_comment, $visitor_access_form->approved_by_gm, $visitor_access_form->gm_completed);
?>
<?php require APP_ROOT . '/views/modals/visitor-access-form/section_d_modal.php'?>

<?php $view_model->display_form_controls = false; ?>
<!-- /.Modals -->

<?php require_once APP_ROOT . '\views\includes\footer.php'; ?>

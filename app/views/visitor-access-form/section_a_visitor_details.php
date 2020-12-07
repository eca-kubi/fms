<?php
require_once APP_ROOT . '\views\includes\header.php';
require_once APP_ROOT . '\views\includes\visitor-access-form\navbar.php';
require_once APP_ROOT . '\views\includes\visitor-access-form\sidebar.php';

use ViewModels\VisitorAccessForm\SectionsViewModel;

/**
 * @var SectionsViewModel $view_model
 */
$section_a = $view_model->section_a;
$section_a->setHtmlFormId();
$visitor_access_form = $view_model->visitor_access_form;
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
        <div class="box p-3">
            <div class="box-header">
                <h5>
                    <?php flash('flash_' . $section_a->description_code); ?>
                </h5>
                <h3 class="box-title text-bold">
                    <?php echo $section_a->title ?>
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
                    $section_a->display_form_default_submit_button = true;
                    $view_model->display_form_controls = true;
                ?>
                <?php require_once APP_ROOT . '/views/includes/visitor-access-form/section_a_visitor_details_form_post.php'; ?>
                <?php
                    $section_a->display_form_default_submit_button = false;
                    $view_model->display_form_controls = false;
                ?>

            </div>
            <!-- /.box-body -->
            <div class="box-footer"></div>
            <!-- /.box-footer-->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once APP_ROOT . '\views\includes\footer.php'; ?>

<?php use ViewModels\VisitorAccessForm\DashboardViewModel;

require_once APP_ROOT . '\views\includes\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\visitor-access-form\navbar.php'; ?>
<?php require_once APP_ROOT . '\views\includes\visitor-access-form\sidebar.php'; ?>
<?php
/**
 * @var $view_model DashboardViewModel
 */
$current_user = getUserSession();
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
                    <h1>
                        <?php echo APP_NAME; ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <a href="#">Dashboard</a>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content-header-->
    <!-- content -->
    <section class="content">
        <div class="box-group" id="box_group">
            <div class="box collapsed">
                <div class="box-header">
                    <h5>
                        <?php flash('flash_dashboard'); ?>
                    </h5>
                    <h3 class="box-title text-bold d-none"></h3>
                    <div class="box-tools pull-right d-none">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <?php
                        foreach ($view_model->view_filters as $view_filter) { ?>
                            <div class="col-12">
                                <div class="box box-<?php echo $view_filter->color ?> <!--collapsed-box-->">
                                    <div class="box-header with-border">
                                        <h5 class="box-title cursor-pointer "
                                            onclick="$('#btn_collapse_<?php echo $view_filter->id ?>').trigger('click');">
                                            <b class="text-<?php echo $view_filter->color ?>"><?php echo $view_filter->name ?></b>
                                        </h5>
                                        <div class="box-tools pull-right">
                                            <!--<div class="btn-group">
                                                <button type="button"
                                                        class="btn btn-box-tool dropdown-togglee p-1 search-button"
                                                        data-toggle="dropdown" aria-expanded="true">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <ul class="dropdown-menu px-2" role="menu">
                                                    <li>
                                                        <input type="text" class="form-control search"
                                                               id="list_search_<?php /*echo $view_filter->id; */?>"
                                                               data-list-id="list_<?php /*echo $view_filter->id; */?>"
                                                               placeholder="Search..."
                                                               onkeydown="searchList(this);">
                                                    </li>
                                                </ul>
                                            </div>-->
                                            <button type="button" id="btn_collapse_<?php echo $view_filter->id ?>"
                                                    class="btn btn-box-tool p-0"
                                                    data-widget="collapse">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <?php
                                        if (!$view_filter->form_collection->isEmpty()) { ?>
                                            <div id="list_container_<?php echo $view_filter->id ?>">
                                                <div class="form-inline mb-3 col-sm-8 col-lg-6 col-xl-4">
                                                    <input class="search form-control-sm form-control col font-weight-bold" id="search_<?php echo $view_filter->id; ?>"
                                                          placeholder="Search" style="border-radius: 3px!important;"/>
                                                    <span class="ml-2 text-sm ">
                                                        <button class='btn btn-sm border sort font-weight-bold'
                                                                data-sort='date_raised' data-default-order='desc'>Sort by Date
                                                        </button>
                                                    </span>
                                                </div>

                                                <ul class="row list p-0" style="list-style-type: none">
                                                    <?php
                                                    $forms = $view_filter->form_collection->getVisitorAccessForms();
                                                    $count = count($forms);
                                                    foreach ($forms as $form) {
                                                        $originator = new User($form->originator_id); ?>
                                                        <li class="item col-md-5  <?php echo $count  > 1 ? 'mx-auto' : ''; ?>"
                                                             onclick="//window.location.href = '<?php echo site_url("cms-forms/view-change-process/$form->visitor_access_form_id"); ?>'">
                                                            <dl class="callout">
                                                                <div class="row pl-4">
                                                                    <dt class="d-none invisible">Document ID:</dt>
                                                                    <dd class="ml-2 product-description document_id"><span
                                                                                class="badge badge-outline-<?php echo $view_filter->color; ?>"><?php echo $form->document_id; ?></span>
                                                                    </dd>
                                                                </div>
                                                                <div class="row pl-4">
                                                                    <dt class=" text-sm-right">Department:</dt>
                                                                    <dd class="ml-2 product-description department">
                                                                        <span><?php echo getDepartment($form->department_id); ?></span>
                                                                    </dd>
                                                                </div>
                                                                <div class="row pl-4">
                                                                    <dt class=" text-sm-right">Originator:</dt>
                                                                    <dd class="ml-2 product-description originator"><?php echo ucwords($originator->name); ?></dd>
                                                                </div>
                                                                <div class="row pl-4">
                                                                    <dt class=" text-sm-right">Date Raised:</dt>
                                                                    <dd class="ml-2 product-description date_raised">
                                                                        <?php echo toJSDate($form->date_raised); ?>
                                                                    </dd>
                                                                </div>
                                                                <?php if ($form->section_d_completed) { ?>
                                                                    <div class="row pl-4">
                                                                        <dt class=" text-sm-right">Date
                                                                            Completed:
                                                                        </dt>
                                                                        <dd class="ml-2 product-description date_completed">
                                                                            <?php echo toJSDate($form->gm_approval_date); ?>
                                                                        </dd>
                                                                    </div>
                                                                <?php } ?>
                                                                <div class="row pl-3 mt-1">
                                                                    <dt class=" invisible d-sm-block d-none">
                                                                        ..
                                                                    </dt>
                                                                    <dd>
                                                                        <a href="<?php echo site_url("visitor-access-form/sections/$form->visitor_access_form_id"); ?>"
                                                                           class="btn w3-btn badge badge-info">
                                                                            <i class=" badge small"> <i class="fa fa-info-circle"></i> Details</i>
                                                                        </a>

                                                                        <a href="<?php echo site_url("visitor-access-form/print/$form->visitor_access_form_id"); ?>"
                                                                           title="Print" target="_blank"
                                                                           class="btn w3-btn badge bg-aqua-gradient">
                                                                            <i class="badge small"><i
                                                                                        class="fa fa-print"></i> Print</i>
                                                                        </a>

                                                                        <a href="<?php echo PDF_DOWNLOAD_ENDPOINT . '/'. $form->visitor_access_form_id ?>" download="<?php echo 'Visitor-Access-Form-'. $form->document_id . '.pdf' ?>" target="_blank"
                                                                           class="btn w3-btn badge bg-fuchsia-active">
                                                                            <i class="badge"><i class="fa fa-file-download"></i> Download</i>
                                                                        </a>
                                                                        <?php
                                                                        if (isCurrentManagerForDepartment($form->department_id, $current_user->user_id) || $form->originator_id === $current_user->user_id) {
                                                                            ?>
                                                                            <a href="#cancelRequest" data-toggle="modal"
                                                                               title="Cancel Request"
                                                                               data-href="<?php
                                                                               echo site_url("visitor-access-form/cancel-request/$form->visitor_access_form_id"); ?>"
                                                                               class="btn w3-btn badge btn-danger bg-danger-gradient d-none">
                                                                                <i class="badge small"><i class="fa fa-times"></i> Cancel</i>
                                                                            </a>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </dd>
                                                                </div>
                                                            </dl>
                                                        </li>
                                                    <?php }
                                                    ?>
                                                    <!-- /.item -->
                                                </ul>
                                                <!--/ .list -->
                                                <ul class="pagination"></ul>
                                            </div>
                                            <!-- / . list_container-->

                                        <?php } else { ?>
                                            <p class="text-center text-muted">
                                                No <?php echo ucwords($view_filter->name) ?></p>
                                        <?php }
                                        ?>
                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer text-center d-none">
                                    </div>
                                    <!-- /.box-footer -->
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer d-none"></div>
                <!-- /.box-footer-->
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
modal('visitor-access-form/cancel_request');

require_once APP_ROOT . '\views\includes\footer.php'; ?>

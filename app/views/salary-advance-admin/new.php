<?php require_once APP_ROOT . '\views\includes\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\navbar.php'; ?>
<?php require_once APP_ROOT . '\views\includes\sidebar.php'; ?>
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
                        <?php flash('flash_new'); ?>
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
                    <form action="<?php site_url('salary-advance/new') ?>" method="POST" data-toggle="validator" enctype="multipart/form-data"
                          id="salary_advance_form">
                        <div class="row p-2 border">
                            <fieldset class="w-100">
                                <h6 class="text-bold font-italic">
                                    <a href="#section_1" data-toggle="collapse">
                                        <i class="fa fa-minus"></i> Employee Details
                                        <span class="text-muted">(To be Completed by - Employee)</span>
                                    </a>
                                </h6>
                                <div id="section_1" class="collapse show section">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group form-row">
                                                <label for="name" class="col-sm-4 text-sm-right">Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="name"
                                                           class="form-control"
                                                           value="<?php echo ucwords($current_user->first_name . ' ' . $current_user->last_name, '-. '); ?>"
                                                           aria-describedby="helpId" placeholder="" readonly/>
                                                    <small id="helpId" class="form-text with-errors help-block"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group form-row">
                                                <label for="department" class="col-sm-4 text-sm-right">Grade</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="department"
                                                           class="form-control"
                                                           value="<?php echo $current_user->job_title; ?>"
                                                           aria-describedby="helpId" placeholder="" readonly/>
                                                    <small id="helpId" class="form-text with-errors help-block"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group form-row">
                                                <label for="department"
                                                       class="col-sm-4 text-sm-right">Department</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="department"
                                                           class="form-control"
                                                           value="<?php echo $current_user->department->department; ?>"
                                                           aria-describedby="helpId" placeholder="" readonly/>
                                                    <small id="helpId" class="form-text with-errors help-block"></small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group form-row">
                                                <label for="date_raised" class="col-sm-4 text-sm-right">Date</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="date_raised"
                                                           class="form-control"
                                                           value="<?php try {
                                                               echo (new DateTime())->format(DFF);
                                                           } catch (Exception $e) {
                                                           } ?>"
                                                           aria-describedby="helpId" placeholder="" readonly/>
                                                    <small id="helpId" class="form-text with-errors help-block"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group form-row">
                                                <label for="emp_no" class="col-sm-4 text-sm-right">Employee No.</label>
                                                <div class="col-sm-8">
                                                    <input type="text" id="emp_no"
                                                           class="form-control" value="" aria-describedby="helpId"
                                                           placeholder=""/>
                                                    <small id="helpId" class="form-text with-errors help-block"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group form-row">
                                                <label class="col-sm-4 text-sm-right">% of Salary</label>
                                                <div class="col-sm-8 radio">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               id="inlineCheckbox1" name="percentage" value="10">
                                                        <label class="form-check-label"
                                                               for="inlineCheckbox1">10%</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               id="inlineCheckbox2" name="percentage" value="20">
                                                        <label class="form-check-label"
                                                               for="inlineCheckbox2">20%</label>
                                                    </div>
                                                    <div class="form-check form-check-inline radio">
                                                        <input class="form-check-input" type="radio"
                                                               id="inlineCheckbox3" name="percentage" value="30">
                                                        <label class="form-check-label"
                                                               for="inlineCheckbox3">30%</label>
                                                    </div>
                                                    <small class="help-block with-errors"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 pl-sm-5">
                                            <div class="form-group form-row">
                                                <div class="checkbox ml-md-2 pl-1 pl-md-4">
                                                    <label>
                                                        <input type="checkbox" name="certify_details" required>
                                                        <small class="text-bold">I hereby certify that the information I
                                                            have provided above is true, complete and accurate.
                                                        </small>
                                                    </label>
                                                    <small id="helpId" class="with-errors help-block"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" id="button_container">
                                            <div class="float-right pr-sm-2">
                                                <a href="javascript: window.history.back();"
                                                   class="btn bg-danger w3-btn">Cancel</a>
                                                <button type="submit" class="btn bg-success w3-btn"
                                                        form="salary_advance_form">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </form>
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
<?php require_once APP_ROOT . '\views\includes\footer.php'; ?>

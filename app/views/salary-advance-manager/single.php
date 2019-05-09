<?php require_once APP_ROOT . '\views\includes\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\navbar-manager.php'; ?>
<?php require_once APP_ROOT . '\views\includes\sidebar-manager.php'; ?>
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
                        <?php flash('flash_single'); ?>
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
                    <div class="w-100">
                        <div class="d-sm-block d-none">
                            <table class="table table-bordered table-user-information font-raleway table-striped table-active">
                                <thead class="thead-default d-none">
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-sm-right" style="width:17%"><b>Name: </b></td>
                                    <td style="width:83%">
                                        <?php /** @var SalaryAdvanceModel $salary_advance */
                                        echo concatNameWithUserId($salary_advance->user_id) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm-right" style="width:17%"><b>Department: </b></td>
                                    <td style="width:83%">
                                        <?php /** @var SalaryAdvanceModel $salary_advance */
                                        echo getDepartment($salary_advance->user_id); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm-right" style="width:17%"><b>Grade: </b></td>
                                    <td style="width:83%">
                                        <?php /** @var SalaryAdvanceModel $salary_advance */
                                        echo getJobTitle($salary_advance->user_id); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm-right" style="width:17%"><b>Date Raised: </b></td>
                                    <td style="width:83%">
                                        <?php echo echoDate($salary_advance->date_raised); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm-right" style="width:17%"><b>Amount Requested</b></td>
                                    <td style="width:83%">
                                        <?php echo $salary_advance->amount_requested; ?>
                                    </td>
                                </tr>
                                <!--HoD Approval-->
                                <tr>
                                    <td class="text-sm-right" style="width:17%"><b>HoD Approval: </b></td>
                                    <td style="width:83%">
                                        <div class="row text-no-decoration">
                                            <a href=""><span class="col-sm-6 text-success text-sm ">Approve</span></a>
                                            <a href=""><span class="col-sm-6 text-sm text-danger">Reject</span></a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-sm-none d-block">
                            <table class="table table-bordered table-user-information font-raleway table-striped table-active">
                                <thead class="thead-default d-none">
                                <tr>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-4 text-sm-right"><b>Name: </b></div>
                                            <div class="col-sm-8"><?php /** @var SalaryAdvanceModel $salary_advance */
                                                echo concatNameWithUserId($salary_advance->user_id) ?></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-sm-4 text-sm-right"><b>Date Raised: </b></div>
                                            <div class="col-sm-8">
                                                <?php echo echoDate($salary_advance->date_raised); ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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
<?php require_once APP_ROOT . '\views\includes\footer.php'; ?>

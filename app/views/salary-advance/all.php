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
                <div class="box-body table-responsive">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>ID #</th>
                            <th>Date Raised</th>
                            <th>% of Salary</th>
                            <th>HoD Approval</th>
                            <th>HR Approval</th>
                            <th>Finance Manager Approval</th>
                            <th></th>
                        </tr>
                        <?php
                        /** @var array $salary_advances */
                        if (count($salary_advances) < 1) { ?>
                            <tr class="text-center">
                                <td colspan="5">No Salary Advance Applications!</td>
                            </tr>
                        <?php } else {
                            $count = 1;
                            foreach ($salary_advances as $salary_advance) {
                                ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo echoDateOfficial($salary_advance->date_raised, true) ?></td>
                                    <td><?php echo $salary_advance->percentage; ?>%</td>
                                    <td><?php echo $salary_advance->hod_approval; ?></td>
                                    <td><?php echo $salary_advance->hr_approval; ?></td>
                                    <td><?php echo $salary_advance->fmgr_approval; ?></td>
                                    <td><a href="<?php echo site_url('salary-advance/single/' . $salary_advance->id_salary_advance); ?>"><i class="fas fa-edit"></i></a></td>
                                </tr>
                            <?php }
                        }
                        ?>
                        </tbody>
                    </table>
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

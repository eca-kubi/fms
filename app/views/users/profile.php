<?php use ViewModels\UserRegistrationFormViewModel;

require_once APP_ROOT . '\views\includes\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\visitor-access-form\navbar.php'; ?>
<?php require_once APP_ROOT . '\views\includes\visitor-access-form\sidebar.php'; ?>
<?php
/**
 * @var UserRegistrationFormViewModel $view_model
 */
?>
<style>
    .table-user-information > tbody > tr {
        border-top: 1px solid rgb(221, 221, 221);
    }

    .table-user-information > tbody > tr:first-child {
        border-top: 0;
    }


    .table-user-information > tbody > tr > td {
        border-top: 0;
    }

    .table-user-information > tbody > tr > td {
        width: 50%;
        font-weight: 500;
    }

    .table-user-information > tbody > tr > td {
        text-transform: capitalize;
    }

    .table-user-information >tbody >tr >td:first-child {
        text-align: right;
    }
</style>
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
                        <?php flash('flash_profile'); ?>
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
                    <div class="container">
                        <h1 class="d-none">Edit Profile</h1>
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-3">
                                <div class="text-center">
                                    <?php
                                
                                    $full_name = $view_model->first_name . ' ' . $view_model->last_name;
                                    if ($view_model->profile_pic === DEFAULT_PROFILE_PIC) {
                                        $initials = $view_model->first_name[0] . $view_model->last_name[0];
                                        echo "<img avatar=\"$full_name\" class=\"img-fluid p-1 img-thumbnail img-size-32\" alt=\"$initials\" >";
                                    } else { ?>
                                        <img src="<?php echo PROFILE_PIC_DIR . $view_model->profile_pic; ?>"
                                             class="img-fluid img-thumbnail img-size-32" alt="avatar"/>
                                    <?php } ?>
                                    <h6 class="invisible">Profile Picture</h6>

                                    <input type="file" class="form-control d-none"/>
                                </div>
                            </div>

                            <!-- edit form column -->
                            <div class="col-md-6 personal-info">
                                <h3>Profile info</h3>
                                <div class="w-100">
                                    <table class="table table-user-information font-raleway">
                                        <thead class="thead-default d-none">
                                        <tr>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Name:</td>
                                            <td>
                                                <?php echo $view_model->first_name . ' ' . $view_model->last_name; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Staff Number (ID):</td>
                                            <td>
                                                <?php echo strtoupper($view_model->staff_id); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Department:</td>
                                            <td>
                                                <?php echo $view_model->department; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Position:</td>
                                            <td>
                                                <?php echo $view_model->job_title; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Staff Category:</td>
                                            <td>
                                                <?php echo $view_model->staff_category; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Email:</td>
                                            <td class="text-lowercase">
                                                <?php echo $view_model->email; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Mobile Number:</td>
                                            <td>
                                                <?php echo $view_model->phone_number; ?>
                                            </td>
                                        </tr>

                                        <tr class="">
                                            <td>Password</td>
                                            <td>
                                                <a href="#change_password_modal" data-toggle="modal">Set New
                                                    Password</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer"></div>
                <!-- /.box-footer-->
            </div>

        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php modal(APP_ROOT. '/views/modals/change_password', []); ?>
<?php require_once APP_ROOT . '\views\includes\footer.php'; ?>
</body>
</html>

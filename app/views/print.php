<?php /** @var array $payload */
$salary_advance = $payload['salary_advance'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salary Advance</title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/w3.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte-miscellaneous.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/box-widget.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/all.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/v4-shims.min.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT ?>/public/assets/css/shards.min.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/fonts.css"/>
    <link rel="stylesheet"
          href="<?php echo URL_ROOT; ?>/public/custom-assets/css/custom.css?<?php echo microtime(); ?>"/>
    <link rel="stylesheet" media="all"
          href="<?php echo URL_ROOT; ?>/public/custom-assets/css/print.css?<?php echo microtime(); ?>"/>
</head>
<body>
<section class="content p-5" id="content">
    <header class="row container-fluid pt-3 border-bottom mb-5">
        <div class="col-11">
            <h5 class="font-raleway font-weight-bold mb-0">Salary Advance
            </h5>
            <small class="text-bold">[<?php echo $payload['salary_advance']->department_ref; ?>]</small>
        </div>
        <div class="col-1">
            <img class="img-size-64" src="<?php echo site_url("public/assets/images/adamus.jpg") ?>" alt="">
        </div>
    </header>
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
                                        <td class="text-sm-right" style="width:17%"><b>Amount Requested: </b></td>
                                        <td style="width:83%">
                                            <?php echo $salary_advance->amount_requested; ?> ghs
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm-right" style="width:17%"><b>Status: </b></td>
                                        <td style="width:83%">
                                            <?php echo $salary_advance->status; ?>
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
</section>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jspdf.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/html2canvas.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/filesaver.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/dom2img.min.js"></script>
</body>
</html>
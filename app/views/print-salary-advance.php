<?php /** @var array $payload */
$salary_advance = $payload['salary_advance'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salary Advance</title>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/w3.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte-miscellaneous.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/box-widget.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-free/css/all.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-pro/css/all.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/v4-shims.min.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT ?>/public/assets/css/shards.min.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/fonts.css"/>
    <link rel="stylesheet"
          href="<?php echo URL_ROOT; ?>/public/custom-assets/css/custom.css?<?php echo microtime(); ?>"/>
    <link rel="stylesheet" media="all"
          href="<?php echo URL_ROOT; ?>/public/custom-assets/css/print.css?<?php echo microtime(); ?>"/>
    <style>
        input {
            background: transparent;
            border: none;
            border-bottom: 1px solid #000000;
            outline: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
<section class="content" id="main">
    <header class="row container-fluid pt-3 border-bottom mb-5">
        <div class="col-11">
            <h5 class="font-raleway font-weight-bold mb-0 text-uppercase h3">Salary Advance
            </h5>
            <small class="text-bold">[<?php echo $salary_advance->department_ref; ?>]</small>
        </div>
        <div class="col-1">
            <img class="img-size-64" src="<?php echo site_url("public/assets/images/adamus.jpg") ?>" alt="">
        </div>
    </header>
    <!-- .content-wrapper -->
    <div class="content-wrapper ml-0 bg-gray">
        <!-- content -->
        <section class="content">
            <div class="p-2">
                <div class="row">
                    <div class="col-8">
                        <div class="row form-group">
                            <label for="name" class="col-3">Employee Name: </label>
                            <input id="name" class="col-9"
                                   value="<?php echo concatNameWithUserId($salary_advance->user_id); ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row form-group">
                            <label for="grade" class="col-3">Grade: </label>
                            <input id="grade" class="col-9" readonly type="text" value="<?php ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="row form-group">
                            <label for="employee_number" class="col-3">Employee Number: </label>
                            <input id="employee_number" class="col-9"
                                   value="<?php ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row form-group">
                            <label for="department" class="col-5">Department: </label>
                            <input class="col-7" id="department" readonly type="text"
                                   value="<?php echo getDepartment($salary_advance->department_id) ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="">
                            <p class="text-bold font-italic">Please pay fixed amount
                                of <?php echo CURRENCY_GHS . number_format($salary_advance->amount_approved, 2); ?> of
                                my basic salary as
                                advance against the month of <u><?php try {
                                        echo (new \Moment\Moment($salary_advance->date_raised))->addMonths(1)->format('MMMM', new \Moment\CustomFormats\MomentJs());
                                    } catch (Exception $e) {
                                    } ?></u></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="employee_approval_date" class="mr-2">Employee Approval Date: </label>
                            <input id="employee_approval_date" class="col-6 ml-5"
                                   value="<?php echo echoDateOfficial($salary_advance->date_raised, true) ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="hod_approval_date" class="mr-5">HoD Approval Date: </label>
                            <input class="col-6 ml-5" id="hod_approval_date" readonly type="text"
                                   value="<?php echo echoDateOfficial($salary_advance->hod_approval_date, true); ?>">
                        </div>
                    </div>
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salary Advance</title>
    <link rel="stylesheet" media="all" href="<?php use Moment\CustomFormats\MomentJs;
    use Moment\Moment;

    echo URL_ROOT; ?>/public/assets/css/w3.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte.css"/>
    <link rel="stylesheet" media="screen" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte-miscellaneous.css"/>
    <link rel="stylesheet" media="screen" href="<?php echo URL_ROOT; ?>/public/assets/css/box-widget.css"/>
    <link rel="stylesheet" media="screen" href="<?php echo URL_ROOT ?>/public/assets/css/shards.min.css">
    <link rel="stylesheet" media="all"
          href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-pro/css/all.min.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/v4-shims.min.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/fonts.css"/>
    <link rel="stylesheet" media="screen"
          href="<?php echo URL_ROOT; ?>/public/custom-assets/css/custom.css?<?php echo microtime(); ?>"/>
    <link rel="stylesheet" media="print"
          href="<?php echo URL_ROOT; ?>/public/custom-assets/css/print.css?<?php echo microtime(); ?>"/>
    <style>
        input {
            background: transparent;
            border: none;
            border-bottom: 1px solid #000000;
            outline: none;
            box-shadow: none;
        }

        .bg-dark {
            background-color: #343a40 !important;
        }
    </style>
</head>
<body>
<section class="content pt-5 w3-text-black" id="main">
    <div class="row container-fluid p-0 border-bottom m-0">
        <div class="col-10">
            <h5 class="font-raleway font-weight-bold mb-0 text-uppercase">Salary Advance <span
                        class="text-bold text-black">[<?php /** @var SalaryAdvanceModel $salary_advance */
                    echo $salary_advance->request_number; ?>]</span></h5>

        </div>
        <div class="col-2 text-right">
            <img class="img-size-64" src="<?php echo site_url('public/assets/images/adamus.jpg') ?>" alt="">
        </div>
    </div>
    <!-- .content-wrapper -->
    <div class="content-wrapper ml-0 bg-gray">
        <!-- content -->
        <section class="content">
            <div class="p-2">
                <div class="row">
                    <div class="col-8">
                        <div class="row form-group">
                            <label class="col-4 m-0 pt-1">Employee Name: </label>
                            <input class="col-8 mb-3"
                                   value="<?php echo concatNameWithUserId($salary_advance->user_id); ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row form-group">
                            <label class="m-0 pt-1">Grade: </label>
                            <input class="col-8 mb-3" readonly type="text" value="<?php ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="row form-group">
                            <label class="ml-1 mr-2 m-0 pl-1 pt-1">Employee Number: </label>
                            <input class="col mb-3" value="<?php ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row form-group">
                            <label  class="m-0 pt-1">Department: </label>
                            <input class="col-7 mb-3"  readonly type="text"
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
                                        echo (new Moment($salary_advance->date_raised))->addMonths(1)->format('MMMM', new MomentJs());
                                    } catch (Exception $e) {
                                    } ?></u></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="ml-1 mr-2 pt-1">Employee Approval Date: </label>
                            <input class="col-6 ml-5 mb-3"
                                   value="<?php echo echoDateOfficial($salary_advance->date_received, true) ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6 row pl-3 row">
                        <label class="pt-1">HoD Approval: </label>
                        <input class="col mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->hod_approval, 'Pending', 'Approved', 'Rejected'); ?>">
                    </div>
                    <div class="form-group col-6 row pl-3 row">
                        <label class="pt-1">Date: </label>
                        <input class="col mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->hod_approval_date, '', echoDateOfficial($salary_advance->hod_approval_date, true), ''); ?>">
                    </div>
                </div>
                <div class="row">
                    <p class="text-sm text-bold m-0">Administrative Use</p>
                    <div class="bg-dark w-100 mb-3" style="height: 20px">&nbsp;</div>
                    <div class="form-group col-12">
                        <label class="pt-1">Amount Payable: </label>
                        <input class="col-6 mb-3" readonly type="text"
                               value="<?php echo CURRENCY_GHS . $salary_advance->amount_payable; ?>">
                    </div>
                    <div class="form-group col-8 pl-3 row">
                        <label class="pt-1">Approval by HR: </label>
                        <input class="col ml-2 mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->hr_approval, 'Pending', 'Approved', 'Rejected'); ?>">
                    </div>
                    <div class="form-group col-4 row">
                        <label class="pl-2 pt-1">Date: </label>
                        <input class="col ml-2 mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->hr_approval_date, '', echoDateOfficial($salary_advance->hr_approval_date, true), ''); ?>">
                    </div>
                </div>
                <div class="row">
                    <p class="text-sm text-bold m-0">General Manager</p>
                    <div class="bg-dark w-100 mb-3" style="height: 20px"></div>
                    <div class="form-group col-8 row pl-3">
                        <label class="pt-1">Action by General Manager: </label>
                        <input class="col ml-2 mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->gm_approval, 'Pending', 'Approved', 'Rejected'); ?>">
                    </div>
                    <div class="form-group col-4 row">
                        <label class="pl-2 pt-1">Date: </label>
                        <input class="col ml-2 mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->gm_approval_date, '', echoDateOfficial($salary_advance->gm_approval_date, true), ''); ?>">
                    </div>
                </div>
                <div class="row">
                    <p class="text-sm text-bold m-0">Finance Use</p>
                    <div class="bg-dark w-100 mb-3" style="height: 20px"></div>
                    <div class="form-group col-8 row pl-3">
                        <label class="pt-1">Action by Finance Manager: </label>
                        <input class="col ml-2 mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->hr_approval, 'Pending', 'Approved', 'Rejected'); ?>">
                    </div>
                    <div class="form-group col-4 row">
                        <label class="pl-2 pt-1">Date: </label>
                        <input class="col ml-2 mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->fmgr_approval_date, '', echoDateOfficial($salary_advance->fmgr_approval_date, true), ''); ?>">
                    </div>
                    <div class="form-group col-12">
                        <label class="pt-1">Amount Approved: </label>
                        <input class="col-6 mb-3" readonly type="text"
                               value="<?php echo CURRENCY_GHS .$salary_advance->amount_approved; ?>">
                    </div>
                </div>
                <div class="row">
                    <p class="text-sm text-bold m-0">Receiver</p>
                    <div class="bg-dark w-100 mb-3" style="height: 20px"></div>
                    <div class="form-group col-8">
                        <label class="pt-1">Received By:</label>
                        <input class="col-6 mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->received_by, '', $salary_advance->received_by, ''); ?>">
                    </div>
                    <div class="form-group col-4 row">
                        <label class="pt-1">Date: </label>
                        <input class="col mb-3" readonly type="text"
                               value="<?php echo nullableStringConverter($salary_advance->date_received, '', echoDateOfficial($salary_advance->date_received, true), ''); ?>">
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
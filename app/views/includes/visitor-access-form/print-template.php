<?php
/**
 * @var PrintViewModel $view_model ;
 */
$visitor_access_form = $view_model->visitor_access_form;
$access_levels = $view_model->access_levels;
$originator = new User($visitor_access_form->originator_id);

$hod = new User($visitor_access_form->site_sponsor_id);
$gm = new User($visitor_access_form->gm_id);
$hr = new User($visitor_access_form->hr_approver_id);
$security_mgr = new User($visitor_access_form->security_approver_id);

use ViewModels\VisitorAccessForm\PrintViewModel;
use VisitorAccessForm\Approval;
use VisitorAccessForm\VisitorCategory;
use VisitorAccessForm\VisitorType;

?>

<!DOCTYPE HTML>

<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Visitor Access Form</title>
    <?php if ($view_model->print) { ?>
        <script>
            window.printEnabled = true
        </script>
    <?php } ?>
    <script>
        window.PagedConfig = {
            auto: true,
            after: (flow) => {
                console.log("after", flow)
                if (printEnabled) window.setTimeout(() => window.print(), 1500)
            }
        };
    </script>

    <script src="<?php echo URL_ROOT ?>/public/assets/paged-js/paged.polyfill.js"></script>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/bootstrap.min.css"/>
    <script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.min.js"></script>
    <script src="<?php echo URL_ROOT; ?>/public/assets/kendo-ui/kendo.all.min.js"></script>
    <script src="<?php echo URL_ROOT; ?>/public/assets/pako-deflate/pako-deflate.min.js"></script>
    <script src="<?php echo URL_ROOT; ?>/public/assets/jszip/jszip.min.js"></script>
    <script src="<?php echo URL_ROOT; ?>/public/assets/pdfjs/pdf.js"></script>
    <script src="<?php echo URL_ROOT; ?>/public/assets/export-as-pdf/export-as-pdf.js"></script>
    <link rel="stylesheet"
          href="<?php echo URL_ROOT ?>/public/assets/paged-js/interface.css?<?php echo microtime(); ?>">


    <style>
        html {
            height: auto;
            font-size: 12pt;
        }

        body {
            font-family: Georgia, 'Times New Roman', serif;
        }

        * {
            color-adjust: exact;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .header p, .header h4, .header h6 {
            margin-bottom: 1px;
        }


        @media print {

            div.header {
                display: block;
                position: running(header);
            }

            div.footer-center {
                display: block;
                position: running(footerCenter);
            }

            div.footer-right {
                display: block;
                position: running(footerRight);
            }

            .custom-page-number:after {
                content: counter(page) " of " counter(pages);
            }

            @page {
                size: A4;
                margin: 25mm 10mm 15mm;

                @top-center {
                    content: element(header);
                }

                @bottom-center {
                    content: element(footerCenter);
                }

                @bottom-right {
                    content: element(footerRight);
                }
            }
        }

        @media print and (min-height: 120vw) and (max-height: 150vw) {
            /* legal-size styling */
            .chrome.container::before {
                content: "LEGAL";
            }
        }

        @media print and (min-height: 100vw) and (max-height: 120vw) {
            /* A4 styling */
            body {
                transform: scale(1.48);
                transform-origin: 0 0;
            }
        }

        @media print and (min-height: 80vw) and (max-height: 100vw) {
            /* letter-size styling */
            .chrome.container::before {
                content: "LETTER";
            }
        }
    </style>

</head>

<body class="pt-2">
<div class="header" style="font-size: medium; width: 100%!important; display: none">
    <table class="page-template-header-logo w-100"
           style="border-collapse: collapse; border: 1px solid lightgray; margin: auto; ">
        <colgroup>
            <col style="width:20%;"/>
            <col style="width:53%;"/>
            <col style="width:12%;"/>
            <col style="width:15%;"/>
        </colgroup>
        <tbody>
        <tr>
            <td style="text-align: center;">
                        <span><img class="header-logo" src="<?php echo URL_ROOT . '/public/assets/images/adamus.jpg' ?>"
                                   width="150px" height="50px" alt=""></span>
            </td>
            <td style="font-size: x-small; text-align: center; border-left: 1px solid lightgray; border-right: 1px solid lightgray; padding-left: 10px">
                <h4>NZEMA OPERATIONS</h4>
                <h6>Form</h6>
            </td>
            <td style="font-size: x-small; text-align: right!important; padding-right: 10px!important; border-right: 1px solid lightgray;">
                <p>Document No: </p>
                <p>Version No: </p>
                <p>Issue Date: </p>
                <p>Page No: </p>
            </td>
            <td style="font-size: x-small; text-align: left; padding-left: 10px">
                <p><?php echo $visitor_access_form->document_id ?></p>
                <p>2.0</p>
                <p>December, 2020</p>
                <p><span class="custom-page-number"></span></p>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div class="footer-center text-nowrap" style="font-size: small; display: none">
    <p class="text-sm font-weight-bold">Property of Adamus Security Department</p>
</div>

<div class="footer-right text-sm" style="display: none"><span class="custom-page-number"></span></div>

<div class="content container-fluid">
    <h6 class="text-center text-bold">Visitor Access Form</h6>
    <div style="border-top: solid black"></div>
    <div class="border">
        <div class="border-bottom px-2">
            <p class="text-justify text-sm">Escort must meet Visitors at the Main Gate. Sponsor (HoD) will be
                responsible
                for providing Visitors with Mess Hall badge if they require meals.
                Visitor must have proper Identification (Voter ID, SSNIT Card, Driver's License or Passport) to be
                registered in the security system.
                <span class="text-danger">No High Risk activities including but not limited to excavation, hot work, working at heights, crane
                        operation, isolation or electrical works may
                        be performed without the express permission of the HSE Manager granted by completing Visitor Safety
                        Induction.
                    </span>
            </p>
        </div>

        <div class="border-bottom px-2 bg-gray-light bg-gray-light">
            <h6 class="text-bold">Section A : Visitor Details</h6>
        </div>

        <div class="border-bottom px-2">
            <div class="row">
                <div class="col border-right">
                    <span><b>Arrival Date:</b> <?php echo $visitor_access_form->arrival_date; ?></span></div>
                <div class="col"><span><b>Departure Date:</b> <?php echo $visitor_access_form->departure_date ?></span>
                </div>
            </div>
        </div>

        <div class="border-bottom px-2">
            <div class="row">
                <div class="col-5"><span class="text-bold">Name: </span><span><?php echo $originator->name ?></span>
                </div>
                <div class="pr-2"><span
                            class="text-bold">Identification No: </span><span><?php echo $visitor_access_form->identification_num ?></span>
                </div>
                <div class="col-4"><span
                            class="text-bold">Company: </span><span><?php echo $visitor_access_form->company ?></span>
                </div>
            </div>
        </div>

        <div class="border-bottom px-2">
            <div class="row">
                <div class="col">
                        <span><b>Visitor Type:</b>
                            <span class="mx-3 text-nowrap"><?php echo $visitor_access_form->visitor_type === VisitorType::ADAMUS_EMPLOYEE_FROM_OTHER_SITE ? HTMLHelper::BALLOT_BOX_CHECK : HTMLHelper::BALLOT_BOX ?> (ADAMUS) employee from other site</span>
                            <span class="mx-5 text-nowrap"><?php echo $visitor_access_form->visitor_type === VisitorType::CONSULTANT ? HTMLHelper::BALLOT_BOX_CHECK : HTMLHelper::BALLOT_BOX ?> Consultant</span>
                            <span class="mx-5 text-nowrap"><?php echo $visitor_access_form->visitor_type === VisitorType::VIP ? HTMLHelper::BALLOT_BOX_CHECK : HTMLHelper::BALLOT_BOX ?> VIP</span>
                            <span class="mx-5 text-nowrap"><?php echo $visitor_access_form->visitor_type === VisitorType::CONTRACTOR ? HTMLHelper::BALLOT_BOX_CHECK : HTMLHelper::BALLOT_BOX ?> Contractor</span>
                            <span class="mx-5 text-nowrap"><?php echo $visitor_access_form->visitor_type === VisitorType::OTHER ? HTMLHelper::BALLOT_BOX_CHECK : HTMLHelper::BALLOT_BOX ?> Other specify: <?php echo $visitor_access_form->other_visitor_type ?></span>
                        </span>
                </div>
            </div>
        </div>

        <div class="border-bottom px-2">
            <div class="row">
                <div class="col-7"><span
                            class="text-bold">Reason for Visit: </span><span><?php echo $visitor_access_form->reason_for_visit ?></span>
                </div>
                <div class="col-5"><span
                            class="text-bold">Cell Phone: </span><span><?php echo $visitor_access_form->phone_num ?></span>
                </div>
            </div>
        </div>

        <div class="border-bottom px-2">
            <div class="row">
                <div class="col">
                            <span><b>Visitor Category:</b>
                                <span class="mx-3 text-nowrap"><?php echo $visitor_access_form->visitor_category === VisitorCategory::DAY_VISITOR ? HTMLHelper::BALLOT_BOX_CHECK : HTMLHelper::BALLOT_BOX ?> Day Visitor</span>
                                <span class="mx-3 text-nowrap"><?php echo $visitor_access_form->visitor_category === VisitorCategory::SHORT_TERM_VISITOR ? HTMLHelper::BALLOT_BOX_CHECK : HTMLHelper::BALLOT_BOX ?> Short Term Visitor (7 Days Max)</span>
                            </span>
                </div>
            </div>
        </div>

        <div class="border-bottom px-2 bg-gray-light">
            <h6 class="text-bold">Section B: Access Level</h6>
        </div>

        <div class="border-bottom">
            <table class="w-100">
                <colgroup>
                    <col class="border-right">
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                <tr class="border-bottom">
                    <th style="width: 35%"></th>
                    <th class="text-center" style="width: 10%"><b>Bus-hrs</b></th>
                    <th class="text-center" style="width: 5%"><b>24x7</b></th>
                    <th style="width: 30%"><b>Area</b></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($access_levels->getAccessLevels() as $accessLevel) { ?>
                    <tr class="border-bottom">
                        <td></td>
                        <td class="text-center"><?php echo $accessLevel->bus_hrs ? HTMLHelper::BALLOT_BOX_CHECK
                                : HTMLHelper::BALLOT_BOX ?></td>
                        <td class="text-center"><?php echo $accessLevel->twenty_four_seven ? HTMLHelper::BALLOT_BOX_CHECK
                                : HTMLHelper::BALLOT_BOX ?></td>
                        <td><?php echo $accessLevel->area ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="border-bottom px-2 bg-gray-light">
            <h6 class="text-bold">Section C: Site Sponsor's Approval (Only persons as per specimen signature list)</h6>
        </div>

        <div class="border-bottom px-2">
            <div class="row">
                <div class="col-4"><span class="text-bold">Name: </span><span><?php echo $hod->name ?></span></div>
                <div class="col-4"><span class="text-bold">HR ID #: </span><span><?php echo $hod->staff_number ?></span>
                </div>
                <div class="col-4"><span
                            class="text-bold">Cell Phone: </span><span><?php echo $hod->phone_number ?></span></div>
            </div>
            <div class="row">
                <div class="col-4"><span
                            class="text-bold">Approval: </span><span><?php echo Approval::getStatusAsString($visitor_access_form->approved_by_site_sponsor) ?></span>
                </div>
                <div class="col-4"><span
                            class="text-bold">Date: </span><span><?php echo $visitor_access_form->site_sponsor_approval_date ?></span>
                </div>
            </div>
        </div>

        <div class="border-bottom px-2 bg-gray-light">
            <h6 class="text-bold">Section D: Site Access Approval (Only persons as per specimen signature list)</h6>
        </div>

        <div class="border-bottom px-2">
            <div class="row">
                <div class="col-5"><span class="text-bold">HR Manager: </span><span><?php echo $hr->name ?></span></div>
                <div class=""><span
                            class="text-bold">Approval: </span> <?php echo Approval::getStatusAsString($visitor_access_form->approved_by_hr) ?>
                </div>
                <div class="col-4 px-4 ml-4"><span
                            class="text-bold">Date: </span> <?php echo $visitor_access_form->hr_approval_date ?></div>
            </div>
            <div class="row">
                <div class="col-5"><span class="text-bold">Security Manager: </span> <?php echo $security_mgr->name ?>
                </div>
                <div class=""><span
                            class="text-bold">Approval: </span> <?php echo Approval::getStatusAsString($visitor_access_form->approved_by_security) ?>
                </div>
                <div class="col-4 px-4 ml-4"><span
                            class="text-bold">Date: </span> <?php echo $visitor_access_form->security_approval_date ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5"><span class="text-bold">General Manager: </span><?php echo $gm->name ?></div>
                <div class=""><span
                            class="text-bold">Approval: </span> <?php echo Approval::getStatusAsString($visitor_access_form->approved_by_gm) ?>
                </div>
                <div class="col-4 px-4 ml-4"><span
                            class="text-bold">Date: </span> <?php echo $visitor_access_form->gm_approval_date ?></div>
            </div>
        </div>

        <div class="border-bottom px-2 bg-gray-light">
            <h6 class="text-bold">Section E: Visitor Signature</h6>
        </div>

        <div class="border-bottom px-2">
            <p class="text-justify">
                Declaration: I hereby declare that the information provided by me is true to the best of my
                knowledge
                and understand that it is my responsibility to notify Adamus Resources Limited of any change(s). I
                will
                provide appropriate documentation outlining my qualifications to drive/operate equipment and will
                not do
                so unless legally authorized.
            </p>
            <div class="row">
                <div class="col">Signature: ................................................</div>
                <div class="col">Date: ........../........../......................</div>
            </div>
        </div>

        <div class="border-bottom-0 bg-gray-light px-2">
            <div><span>Security Office Use Only - HR ID #: ...............................</span></div>
            <div class="row">
                <div class="col">Date Received:.........../............/........................</div>
                <div class="col">Date Issued:............./............/......................</div>
                <div class="col">Supervisor's Signature:...............................................</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

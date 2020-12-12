<?php
/**
 * @var SectionsViewModel $view_model ;
 */

/**
 * @var User $current_user ;
 */

/**
 * @var SectionAVisitorDetailsViewModel $section_a;
 *
 * @var VisitorAccessFormEntity $visitor_access_form;
 */

$current_user = $current_user ?? getUserSession();

use ViewModels\VisitorAccessForm\SectionAVisitorDetailsViewModel;
use ViewModels\VisitorAccessForm\SectionsViewModel;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

?>
<div class="row p-2">
    <div class="row w-100 border ml-0 p-1">
        <h6 class="text-bold font-italic col m-1">
            <a href="#<?php echo $section_a->description_code ?>" data-toggle="collapse">
                <i class="fa fa-plus-circle"
                   data-target="#<?php echo $section_a->description_code ?>"></i> <?php echo $section_a->title; ?> <?php echo echoCompleted(); ?>
            </a>

            <span class="mx-2"></span>
            <?php if ($visitor_access_form->uploaded_documents): ?>
                <a href="<?php echo URL_ROOT . '/visitor-access/download-additional-info/' . $visitor_access_form->form_id; ?>"
                   target="_blank"
                   title="Download Attached Documents">
                    <span class="text-sm badge badge-success"> <i class="fa fa-file-download"></i> Download Attached Documents</span>
                </a>
            <?php endif ?>
            <?php
            if ($visitor_access_form->originator_id === $current_user->user_id) { ?>
                <span class="mx-2"></span>
                <a data-toggle="modal" href="#uploadAddDoc" target="_blank" title="Upload More Documents">
                    <span class="text-sm badge badge-info"> <i
                                class="fa fa-file-upload"></i> Upload More Documents</span>
                </a>


                <?php if (($visitor_access_form->originator_id === $current_user->user_id) && is_null($visitor_access_form->approved_by_site_sponsor)): ?>
                    <a data-toggle="modal" href="#modal_<?php echo $section_a->description_code ?>">
                    <span class="text-sm badge badge-success"> <i
                                class="fa fa-edit"></i> Edit</span>
                    </a>
                <?php endif ?>

            <?php }
            ?>
        </h6>
    </div>
    <div class="w-100 section collapse" id="<?php echo $section_a->description_code; ?>">
        <div class="d-sm-block">
            <table class="table table-bordered table-user-information font-raleway table-striped">
                <thead class="thead-default d-none">
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-sm-right" style="width:17%"><b>Full Name: </b></td>
                    <td style="width:83%">
                        <?php echo $visitor_access_form->full_name; ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right" style="width:17%"><b>Company: </b></td>
                    <td style="width:83%">
                        <?php echo $visitor_access_form->company ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right" style="width:17%"><b>Visitor Type: </b></td>
                    <td style="width:83%">
                        <?php echo $visitor_access_form->other_visitor_type ?: $visitor_access_form->visitor_type; ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right"><b>Visitor Category: </b></td>
                    <td class="">
                        <?php echo $visitor_access_form->visitor_category ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right"><b>Arrival Date: </b></td>
                    <td>
                        <?php echo $visitor_access_form->arrival_date ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right"><b>Departure Date:</b></td>
                    <td>
                        <?php echo $visitor_access_form->departure_date ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right"><b>Identification Type: </b></td>
                    <td>
                        <?php echo $visitor_access_form->identification_type ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right"><b>Identification Number: </b></td>
                    <td>
                        <?php echo $visitor_access_form->identification_num ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right" style="width:17%"><b>Phone Number: </b></td>
                    <td style="width:83%">
                        <?php echo $visitor_access_form->phone_num ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right" style="width:17%"><b>Reason for Visit: </b></td>
                    <td style="width:83%">
                        <?php echo $visitor_access_form->reason_for_visit ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-sm-right" style="width:17%"><b>Submitted By: </b></td>
                    <td style="width:83%">
                        <?php echo getNameJobTitleAndDepartment($visitor_access_form->originator_id); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

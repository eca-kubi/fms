<?php
/**
 * @var SectionsViewModel $view_model
 */

/**
 * @var SectionCViewModel $section_c ;
 */

use ViewModels\VisitorAccessForm\SectionBAccessLevelViewModel;
use ViewModels\VisitorAccessForm\SectionCViewModel;
use ViewModels\VisitorAccessForm\SectionsViewModel;

$visitor_access_form = $view_model->visitor_access_form;
$site_sponsor = $section_c->site_sponsor;
$section_c->setHtmlFormId();
?>

<form
      action="<?php echo URL_ROOT . '/visitor_access_form/' . SECTION_C_SITE_SPONSORS_APPROVAL . "/$visitor_access_form->visitor_access_form_id" ?>"
      id="<?php echo $section_c::getHtmlFormId() ?>" enctype="multipart/form-data" method="post"
      data-toggle="<?php echo $view_model->display_form_controls ? 'validator' : '' ?>" role="form">
    <fieldset>
        <table class="table table-bordered table-user-information font-raleway table-striped mb-0"><!--
            <thead class="thead-default">
            <tr>
                <th class="text-center"></th>
                <th class="text-center"></th>
            </tr>
            </thead>-->
            <tbody>
            <?php if (!$view_model->display_form_controls): ?>
                <tr>
                    <td class="text-sm-right">
                        <b>Name:</b>
                    </td>
                    <td style="width:83%">
                        <?php echo $site_sponsor->name; ?>
                    </td>
                </tr>
                <!--<tr>
                    <td class="text-sm-right" style="width:17%">
                        <b>Staff ID#:</b>
                    </td>
                    <td>
                        <?php /*echo $site_sponsor->staff_id */?>
                    </td>
                </tr>-->
                <tr>
                    <td class="text-sm-right" style="width:17%"><b>Phone: </b></td>
                    <td style="width:83%">
                        <?php echo $site_sponsor->phone_number; ?>
                    </td>
                </tr>
            <?php endif ?>

            <?php if (!$view_model->display_form_controls): ?>
                <tr>
                    <td class="text-sm-right">
                        <b>Approval:</b>
                    </td>
                    <td style="width:83%">
                        <?php echo is_null($visitor_access_form->approved_by_site_sponsor) ? '<span class="text-danger">Pending </span>' : ($visitor_access_form->approved_by_site_sponsor ? '<span class="text-success">Approved</span>' : '<span class="text-danger">Rejected</span>'); ?>
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td class="text-sm-right">
                       Approve Request
                    </td>
                    <td style="width:83%">
                        <div class="form-group form-row">
                            <!--<label for="approved_by_site_sponsor" class="col-sm-4 text-sm-right">
                                Approve?
                            </label>-->
                            <div class="col-sm-8">
                                <select class="form-control bs-select" tabindex="7" data-live-search="false"
                                        data-none-selected-text="Select to approve request."
                                        name="approved_by_site_sponsor" id="approved_by_site_sponsor"
                                        aria-describedby="helpId"
                                        required>
                                    <option value="" class="d-none"></option>
                                    <option value="1" <?php echo $visitor_access_form->approved_by_site_sponsor == true? 'selected' : ''?>>Approve</option>
                                    <option value="0" <?php echo $visitor_access_form->approved_by_site_sponsor === false? 'selected' : ''?>>Reject</option>
                                </select>
                                <small class="form-text with-errors help-block"></small>
                            </div>
                        </div>
                        <!--<label class="w-100" for="">
                            <input type="checkbox"
                                   class="form-check m-auto cursor-pointer require-at-least-one" <?php /*echo $visitor_access_form->approved_by_site_sponsor ? 'checked' : ''; */?>
                                   name="approved_by_site_sponsor" form="<?php /*echo $section_c::getHtmlFormId() */?>" value="1" <?php /*echo $visitor_access_form->approved_by_site_sponsor? 'checked=true' : '' */?> required/>

                        </label>-->
                    </td>
                </tr>
            <?php endif ?>
             <?php if ($view_model->display_form_controls): ?>
                <tr>
                    <td class="text-sm-right">Comment:</td>
                    <td>
                        <div class="form-group form-row">
                            <div class="col-sm-8">
                                    <textarea class="form-control" name="site_sponsor_comment" id="site_sponsor_comment" cols="30"
                                              rows="5" ><?php echo $visitor_access_form->site_sponsor_comment?: ''; ?></textarea>
                                <small id="helpId" class="form-text with-errors help-block"></small>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php elseif($visitor_access_form->site_sponsor_comment): ?>
                <tr>
                    <td class="text-sm-right"><b>Comment: </b></td>
                    <td><?php echo $visitor_access_form->site_sponsor_comment; ?></td>
                </tr>
            <?php endif ?>
            <?php if ($visitor_access_form->section_c_completed && !$view_model->display_form_controls): ?>
                <tr>
                    <td class="text-sm-right"><b>Date: </b></td>
                    <td><?php echo $visitor_access_form->site_sponsor_approval_date; ?></td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>
    </fieldset>
</form>

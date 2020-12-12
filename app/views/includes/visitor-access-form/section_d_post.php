<?php
/**
 * @var SectionsViewModel $view_model
 */

/**
 * @var SectionDViewModel $section_d ;
 *
 */

use ViewModels\VisitorAccessForm\SectionDViewModel;
use ViewModels\VisitorAccessForm\SectionsViewModel;
use VisitorAccessForm\ApproverRole;

$current_user = getUserSession();
$visitor_access_form = $view_model->visitor_access_form;
$section_d->setHtmlFormId();
$approval = $section_d->approval;
$approver = $approval->approver;
?>

<form
      action="<?php echo URL_ROOT . '/visitor_access_form/' . SECTION_D_SITE_ACCESS_APPROVAL . "/$visitor_access_form->visitor_access_form_id" ?>"
      id="<?php echo $section_d::getHtmlFormId() ?>" enctype="multipart/form-data" method="post"
      data-toggle="<?php echo $view_model->display_form_controls ? 'validator' : '' ?>" role="form">
    <fieldset>
        <table class="table table-bordered table-user-information font-raleway table-striped mb-0">
            <caption>
                <?php echo isCurrentHR($approver->user_id)? ApproverRole::HR_MANAGER : (isCurrentSecurityManager($approver->user_id)? ApproverRole::SECURITY_MANAGER : ApproverRole::GENERAL_MANAGER) ?>
            </caption>
            <!--
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
                        <?php echo $approver->name; ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-sm-right" style="width:17%"><b>Phone: </b></td>
                    <td style="width:83%">
                        <?php echo $approver->phone_number; ?>
                    </td>
                </tr>
            <?php endif ?>

            <?php if (!$view_model->display_form_controls): ?>
                <tr>
                    <td class="text-sm-right">
                        <b>Approval:</b>
                    </td>
                    <td style="width:83%">
                        <?php echo is_null($approval->approved) ? '<span class="text-danger">Pending</span>' : ($approval->approved ? '<span class="text-success">Approved</span>' : '<span class="text-danger">Rejected</span>'); ?>
                    </td>
                </tr>
            <?php endif ?>


             <?php if ($view_model->display_form_controls): ?>
                 <tr>
                     <td class="text-sm-right">
                         Approve Request
                     </td>
                     <td style="width:83%">
                         <div class="form-group form-row">
                             <!--<label for="approved_by_site_sponsor" class="col-sm-4 text-sm-right">
                                 Approve?
                             </label>-->
                             <?php $disabled_message = 'data-toggle="tooltip" data-html="true" title="<i class=\'fa fa-warning text-warning\' ></i> You <b>can only edit your comment</b> but <b>not the approval status</b> because another approver has already reviewed this visitor access request."' ?>
                             <div class="col-sm-8" <?php echo ((isCurrentHR($current_user->user_id) && $visitor_access_form->security_manager_completed)
                                 || (isCurrentSecurityManager($current_user->user_id) && $visitor_access_form->gm_completed))? $disabled_message : '';  ?> >
                                 <select class="form-control bs-select" tabindex="7" data-live-search="false" form="<?php echo SectionDViewModel::getHtmlFormId() ?>"
                                         data-none-selected-text="Select to approve request."
                                     <?php echo ((isCurrentHR($current_user->user_id) && $visitor_access_form->security_manager_completed)
                                         || (isCurrentSecurityManager($current_user->user_id) && $visitor_access_form->gm_completed))? 'disabled' : '';  ?>
                                         name="approved" id="approved<?php echo SectionDViewModel::getHtmlFormId() ?>" data-disabled-message="This field cannot be edited because another approver has already reviewed this visitor access request."
                                         aria-describedby="helpId"
                                         required>
                                     <option value="" class="d-none"></option>
                                     <option value="1" <?php echo $approval->approved == true? 'selected' : ''?>>Approve</option>
                                     <option value="0" <?php echo $approval->approved === false? 'selected' : ''?>>Reject</option>
                                 </select>
                                 <small class="form-text with-errors help-block"></small>
                                 <?php  ?>
                                 <input type="hidden" value="<?php echo $approval->approved ?>" name="approved">
                             </div>
                         </div>
                         <!--<label class="w-100" for="">
                            <input type="checkbox"
                                   class="form-check m-auto cursor-pointer require-at-least-one" <?php /*echo $visitor_access_form->approved_by_site_sponsor ? 'checked' : ''; */?>
                                   name="approved_by_site_sponsor" form="<?php /*echo $section_d::getHtmlFormId() */?>" value="1" <?php /*echo $visitor_access_form->approved_by_site_sponsor? 'checked=true' : '' */?> required/>

                        </label>-->
                     </td>
                 </tr>
                <tr>
                    <td class="text-sm-right">Comment:</td>
                    <td>
                        <div class="form-group form-row">
                            <div class="col-sm-8">
                                    <textarea class="form-control" name="comment" id="comment_<?php echo SectionDViewModel::getHtmlFormId() ?>" cols="30" form="<?php echo SectionDViewModel::getHtmlFormId() ?>"
                                              rows="5"><?php echo $approval->comment?: ''; ?></textarea>
                                <small id="helpId" class="form-text with-errors help-block"></small>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php elseif($approval->comment): ?>
                <tr>
                    <td class="text-sm-right"><b>Comment: </b></td>
                    <td><?php echo $approval->comment; ?></td>
                </tr>
            <?php endif ?>
            <?php if ($approval->comment): ?>
                <tr>
                    <td class="text-sm-right"><b>Date: </b></td>
                    <td><?php echo $approval->comment; ?></td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>
    </fieldset>
</form>

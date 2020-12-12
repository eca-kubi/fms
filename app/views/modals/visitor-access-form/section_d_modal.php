<?php
/**
 * @var SectionsViewModel $view_model;
 */

use ViewModels\VisitorAccessForm\SectionsViewModel;

$section_d = $view_model->section_d;
$approval = $section_d->approval;
?>
<div class="modal fade draggable" id="modal_<?php echo $section_d->description_code . '_approver_id_' . $approval->approver->user_id; ?>" tabindex="-1" role="dialog" aria-labelledby="modal_title_<?php echo $section_d->description_code .  '_approver_id_' . $approval->approver->user_id?>"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content fa font-weight-normal">
            <div class="modal-header">
                <h6 class="modal-title" id="modal_title_<?php echo $section_d->description_code . '_approver_id_' . $approval->approver->user_id ?>"><?php echo $section_d->title ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php flash("flash_$section_d->description_code"); ?>
                <?php require APP_ROOT . '/views/includes/visitor-access-form/section_d_post.php'; ?>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn bg-danger w3-btn" data-dismiss="modal">Cancel</a>

                <button type="submit" class="btn btn-success" form="<?php echo $section_d::getHtmlFormId() ?>">Submit</button>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * @var SectionsViewModel $view_model;
 */

use ViewModels\VisitorAccessForm\SectionsViewModel;

$section_b = $view_model->section_b;
?>
<div class="modal fade draggable" id="modal_<?php echo $section_b->description_code; ?>" tabindex="-1" role="dialog" aria-labelledby="modal_title_<?php echo $section_b->description_code?>"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content fa font-weight-normal">
            <div class="modal-header">
                <h6 class="modal-title" id="modal_title_<?php echo $section_b->description_code ?>"><?php echo $section_b->title ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php flash("flash_$section_b->description_code"); ?>
                <?php require APP_ROOT . '/views/includes/visitor-access-form/section_b_access_level_post.php'; ?>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn bg-danger w3-btn" data-dismiss="modal">Cancel</a>

                <button type="submit" class="btn btn-success" form="<?php echo $section_b::getHtmlFormId() ?>">Submit</button>
            </div>
        </div>
    </div>
</div>

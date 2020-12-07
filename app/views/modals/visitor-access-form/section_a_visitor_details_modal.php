<?php
/**
 * @var SectionsViewModel $view_model;
 */

use ViewModels\VisitorAccessForm\SectionsViewModel;

$section_a = $view_model->getSectionA();
$visitor_access_form = $view_model->visitor_access_form;

?>
<div class="modal fade draggable" id="modal_<?php echo $section_a->description_code; ?>" tabindex="-1" role="dialog" aria-labelledby="modal_title_<?php echo $section_a->description_code?>"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content fa font-weight-normal">
            <div class="modal-header">
                <h6 class="modal-title" id="modal_title_<?php echo $section_a->description_code ?>"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pb-3">
                <?php flash("flash_$section_a->description_code"); ?>
                <?php require_once APP_ROOT . '/views/includes/visitor-access-form/section_a_visitor_details_form_post.php'; ?>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn bg-danger w3-btn" data-dismiss="modal">Cancel</a>

                <button type="submit" class="btn bg-success w3-btn" form="<?php echo $section_a::getHtmlFormId() ?>">Submit
                </button>
            </div>
        </div>
    </div>
</div>

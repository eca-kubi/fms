<?php
/**
 * @var SectionsViewModel $view_model ;
 */

/**
 * @var User $current_user ;
 */

/**
 * @var SectionBAccessLevelViewModel $section_b;
 *
 * @var VisitorAccessFormEntity $visitor_access_form;
 */

$current_user = $current_user ?? getUserSession();

use ViewModels\VisitorAccessForm\SectionBAccessLevelViewModel;
use ViewModels\VisitorAccessForm\SectionsViewModel;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

?>
<div class="row p-2">
    <div class="row w-100 border ml-0 p-1">
        <h6 class="text-bold font-italic col m-1">
            <a href="#<?php echo $section_b->description_code ?>" data-toggle="collapse">
                <i class="fa fa-plus-circle"
                   data-target="#<?php echo $section_b->description_code ?>"></i> <?php echo $section_b->title; ?> <?php echo $visitor_access_form->section_b_completed? echoCompleted() : echoInComplete(''); ?>
            </a>

            <span class="mx-2"></span>
            <?php if (($visitor_access_form->originator_id === $current_user->user_id) && is_null($visitor_access_form->approved_by_site_sponsor)): ?>
                <a data-toggle="modal" href="#modal_<?php echo $section_b->description_code ?>">
                    <span class="text-sm badge badge-success"> <i
                            class="fa fa-edit"></i> Edit</span>
                </a>
            <?php endif ?>
        </h6>
    </div>
    <div class="w-100 section collapse" id="<?php echo $section_b->description_code; ?>">
        <div class="d-sm-block">
            <?php require APP_ROOT . '/views/includes/visitor-access-form/section_b_access_level_post.php'?>
        </div>
    </div>
</div>

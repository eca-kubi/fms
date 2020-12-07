<?php
/**
 * @var SectionsViewModel $view_model ;
 */

/**
 * @var User $current_user ;
 */

/**
 * @var SectionCViewModel $section_c;
 *
 * @var VisitorAccessFormEntity $visitor_access_form;
 */

$current_user = $current_user ?? getUserSession();
$visitor_access_form = $view_model->visitor_access_form;
$section_c = $view_model->section_c;
use ViewModels\VisitorAccessForm\SectionCViewModel;
use ViewModels\VisitorAccessForm\SectionsViewModel;
use VisitorAccessForm\Entities\VisitorAccessFormEntity;

?>
<div class="row p-2">
    <div class="row w-100 border ml-0 p-1">
        <h6 class="text-bold font-italic col m-1">
            <a href="#<?php echo $section_c->description_code ?>" data-toggle="collapse">
                <i class="fa fa-plus-circle"
                   data-target="#<?php echo $section_c->description_code ?>"></i> <?php echo $section_c->title; ?> <?php echo $visitor_access_form->section_c_completed? echoCompleted() : echoInComplete(''); ?>
            </a>

            <span class="mx-2"></span>
            <?php if (($visitor_access_form->site_sponsor_id === $current_user->user_id) && is_null($visitor_access_form->approved_by_security)): ?>
                <a data-toggle="modal" href="#modal_<?php echo $section_c->description_code ?>">
                    <span class="text-sm badge badge-success"> <i
                            class="fa fa-edit"></i> Edit</span>
                </a>
            <?php endif ?>
        </h6>
    </div>
    <div class="w-100 section collapse" id="<?php echo $section_c->description_code; ?>">
        <div class="d-sm-block">
            <?php require APP_ROOT . '/views/includes/visitor-access-form/section_c_post.php'?>
        </div>
    </div>
</div>

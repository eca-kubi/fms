<?php
/**
 * @var SectionsViewModel $view_model
 */

/**
 * @var SectionBAccessLevelViewModel $section_b;
 */

use ViewModels\VisitorAccessForm\SectionBAccessLevelViewModel;
use ViewModels\VisitorAccessForm\SectionsViewModel;

$access_level_collection = $section_b->access_levels;
$visitor_access_form = $view_model->visitor_access_form;
$section_b->setHtmlFormId();
?>

<form class="require-at-least-one" action="<?php echo URL_ROOT .'/visitor_access_form/' . SECTION_B_ACCESS_LEVEL . "/$visitor_access_form->visitor_access_form_id" ?>" id="<?php echo $section_b::getHtmlFormId() ?>" enctype="multipart/form-data" method="post" data-toggle="<?php echo $view_model->display_form_controls? 'validator' : '' ?>" role="form">
    <fieldset>
        <table class="table table-bordered table-user-information font-raleway table-striped mb-0">
            <thead class="thead-default">
            <tr>
                <th class="">Area</th>
                <th class="text-center">Bus-Hrs</th>
                <th class="text-center">24X7</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($access_level_collection->getAccessLevels() as $index => $access_level){ ?>
            <?php
//                $filter_collection_callback = function(AccessLevel $item) use ($defaultArea) { return $item->area === $defaultArea};

                //$access_level = $access_level_collection->filterCollection(fn(AccessLevel $item)  => $item->bus_hrs == true || $item->twenty_four_seven == true);
            ?>
                <tr>
                    <td>
                        <span><?php echo $access_level->area ?></span>
                        <input type="hidden" name='<?php echo "access_level[$index][area]" ?>'
                               value="<?php echo $access_level->area ?>">
                        <input type="hidden" name="<?php echo "access_level[$index][visitor_access_form_id]" ?>" value="<?php echo $visitor_access_form->visitor_access_form_id ?>">

                    </td>
                    <td class="text-center">
                        <label class="w-100">

                            <?php if ($view_model->display_form_controls): ?>
                                <input type="checkbox"
                                       class="form-check m-auto cursor-pointer require-at-least-one" <?php echo $access_level->bus_hrs ? 'checked' : ''; ?>
                                       name="<?php echo "access_level[$index][bus_hrs]" ?>" form="<?php echo $section_b::getHtmlFormId() ?>" value="1" <?php echo $access_level->bus_hrs? 'checked=true' : '' ?> required/>
                            <?php else: ?>
                                <span> <i class="fa <?php echo $access_level->bus_hrs ? 'fa-check' : '' ?>"></i></span>
                            <?php endif ?>
                        </label>
                    </td>
                    <td class="text-center">
                        <label class="w-100">
                            <?php if ($view_model->display_form_controls): ?>
                                <input type="checkbox"
                                       class="form-check m-auto cursor-pointer require-at-least-one" <?php echo $access_level->twenty_four_seven ? 'checked=true' : ''; ?>
                                       name="<?php echo "access_level[$index][twenty_four_seven]" ?>" form="<?php echo $section_b::getHtmlFormId() ?>" value="1" <?php echo $access_level->twenty_four_seven? 'checked' : '' ?>  required />

                            <?php else: ?>
                                <span> <i class="fa <?php echo $access_level->twenty_four_seven ? 'fa-check': '' ?>"></i></span>
                            <?php endif; ?>
                        </label>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </fieldset>
</form>

<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var string $reference */
/** @var string $new_amount */
/** @var string $old_amount */
/** @var string $comment */
/** @var string $approval */
$body = getNameJobTitleAndDepartment($current_user->user_id) . " has updated the Salary Advance application with reference $reference. <br> ".
    "Old Amount Requested: $old_amount<br>New Amount Requested: $new_amount<br>Approved?: " .($approval==='true'? 'Yes' : 'No'). "<br>Comment: $comment";
echo $body;

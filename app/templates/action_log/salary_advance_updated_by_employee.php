<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var string $department_ref */
/** @var string $new_amount */
/** @var string $old_amount */
$body = getNameJobTitleAndDepartment($current_user->user_id) . " has updated the amount requested for the Salary Advance application with reference $department_ref. <br> ".
    "Old Amount Requested: GH₵ " . number_format($old_amount, 2). "<br>New Amount Requested: GH₵ " .number_format($new_amount, 2) . "<br>";
echo $body;
$old_ret['success'] = false;
$old_ret['reason'] = 'Finance manager has already reviewed this application!';
$ret[] = $old_ret;
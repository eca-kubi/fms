<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var string $department_ref */
/** @var string $fmgr_approval */
/** @var string $amount_approved */

$body = (getNameJobTitleAndDepartment($current_user->user_id) . " has reviewed the Salary Advance application with reference $department_ref. <br> Approved?: ")
    . ($fmgr_approval ? "Yes" . "</br>Amount Payable: " . number_format($amount_approved, 2) : "No");
echo $body;

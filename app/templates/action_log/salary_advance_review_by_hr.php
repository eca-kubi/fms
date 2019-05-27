<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var string $department_ref */
/** @var string $hr_approval */
/** @var string $amount_payable */
/** @var string $hr_approval_date */

$body = (getNameJobTitleAndDepartment($current_user->user_id) . " has reviewed the Salary Advance application with reference $department_ref. <br> Approved?: ")
    . ($hr_approval ? "Yes" . "</br>Amount Payable: " . number_format($amount_payable, 2) : "No");
echo $body;

<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var string $department_ref */
/** @var string $received_by */
/** @var string $amount_received */
$body = getNameJobTitleAndDepartment($current_user->user_id) . " has updated the Salary Advance application with reference $department_ref. <br> ".
    "Amount Received: GH₵ " . number_format($amount_received, 2). "<br> Received by: $received_by<br>";
echo $body;
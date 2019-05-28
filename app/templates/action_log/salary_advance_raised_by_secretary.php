<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var string $department_ref */
/** @var string $amount_requested */
$body = getNameJobTitleAndDepartment($current_user->user_id) . " has raised the Salary Advance application with reference $department_ref. <br> ".
    "Amount Requested: GH₵ " . number_format($amount_requested, 2) ."<br>";
echo $body;

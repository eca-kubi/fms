<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var string $department_ref */
$body = getNameJobTitleAndDepartment($current_user->user_id) . " has deleted the Salary Advance application with reference $department_ref. <br>";
echo $body;

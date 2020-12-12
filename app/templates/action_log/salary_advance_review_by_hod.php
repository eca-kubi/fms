<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var string $department_ref */
/** @var string $hod_approval */
$body = getNameJobTitleAndDepartment($current_user->user_id) . " has reviewed the Salary Advance application with reference $department_ref. <br> ".
    "Approved?: " . ($hod_approval? "Yes" : "No");
echo $body;

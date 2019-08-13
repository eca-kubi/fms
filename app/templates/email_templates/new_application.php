<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var boolean $ref_number */
/** @var string $link */
$body = getNameJobTitleAndDepartment($current_user->user_id) . " has raised a Salary Advance application with reference number "
    .$ref_number. "<br>Click the following link for more information: <a href='$link'>$link</a> ";

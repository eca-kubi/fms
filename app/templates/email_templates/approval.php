<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var boolean $approval */
/** @var string $link */
$body = concatNameWithUserId($current_user->user_id) . " has " . ($approval? "approved" : "rejected") .
    " your Salary Advance application. <br>Click the following link for more information: <a href='$link'>$link</a> ";

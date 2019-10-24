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
/** @var string $ref_number */
/** @var string $comment */
$body = concatNameWithUserId($current_user->user_id) . " has approved" .
    " the Salary Advance application with reference number " . $ref_number . ". <br/>You can find the comment below.  Please click the following link to review the application: <a href='$link'>$link</a> ".
"<br/>Comment: $comment";
echo $body;
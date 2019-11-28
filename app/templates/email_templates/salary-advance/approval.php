<?php
/**
 * Created by PhpStorm.
 * User: IT
 * Date: 2/25/2019
 * Time: 9:32 AM
 */
$current_user = getUserSession();
/** @var string $applicant_id */
$applicant = new User($applicant_id);
/** @var string $recipient_id */
$recipient = new User($recipient_id);
/** @var boolean $approval */
/** @var string $link */
/** @var string $ref_number */
/** @var string $comment */
$body = ($recipient->user_id === $current_user->user_id? 'You have ': $current_user->first_name . ' '. $current_user->last_name . ' has ') . ($approval? 'approved' : 'rejected') .
    ($recipient->user_id === $applicant->user_id? ' your ' : ' the ') .
    'Salary Advance application with reference number ' . $ref_number . ". <br/>You can find the comment below or Click the following link for more information: <a href='$link'>$link</a> ".
"<br/>Comment: $comment";
echo $body;
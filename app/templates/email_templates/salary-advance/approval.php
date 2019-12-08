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
    'Salary Advance application with reference number, <b>' . $ref_number . "</b>. Find the link and the comment below for more information. <br/><br/><b>Link:</b> <a href='$link'>$link</a> ".
"<br/><div style='margin: 0.75rem'></div><div style='border: 1px solid black; padding: 0.25rem !important;'><b>Comment:</b> $comment</div>";
echo $body;
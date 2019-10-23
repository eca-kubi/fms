<?php
/** @var string $applicant_is_the_recipient */
/** @var string $ref_number */
/** @var string $link */
$body = ($applicant_is_the_recipient? 'You have' : getNameJobTitleAndDepartment($current_user->user_id) . " has") . " raised a Salary Advance application with reference number "
. $ref_number . ".<br/>Click the following link for more information: <a href='$link'>$link</a> ";
echo $body;
<?php
/** @var string $applicant_is_the_recipient */
/** @var string $ref_number */
/** @var string $link */
$current_user = getUserSession();
$body = "You have requested for a Salary Advance with reference number, <b>$ref_number</b>. <br/>Click the following link for more information. </br> <b>Link: </b> <a href='$link'>$link</a> ";
echo $body;
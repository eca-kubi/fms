<?php
/** @var string $ref_number */
/** @var string $link */
/** @var array $user_ids */
/** @var boolean $approval */
$current_user = getUserSession();
$reviewer = concatNameWithUserId($current_user->user_id);
$department = getDepartment($current_user->department_id);
$body = $reviewer . ' has reviewed salary advance requests for the following ' . (count($user_ids) > 1? 'employees:' : 'employee:') . '<br>';
$name_list = '';
foreach ($user_ids as $user_id) {
    $name_list .= '<li style=" background: #cce5ff; margin: 5px;">' . getFullName($user_id) . '</li>';
}

$body .= '<ul>' . $name_list . '</ul>' . '<br>' . 'Kindly click the link below for details and further review.<br>' . $link;

echo $body;
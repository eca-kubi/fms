<?php
/** @var string $ref_number */
/** @var string $link */
/** @var array $user_ids */
$current_user = getUserSession();
$secretary = getNameJobTitleAndDepartment($current_user->user_id);
$department = getDepartment($current_user->department_id);
$body = $secretary . ' has submitted salary advance requests on behalf of the following ' . (count($user_ids) > 1? 'employees:' : 'employee:') . '<br>';
$name_list = '';
foreach ($user_ids as $user_id) {
    $name_list .= '<li style=" background: #cce5ff; margin: 5px;">' . getFullName($user_id) . '</li>';
}

$body .= '<ul>' . $name_list . '</ul>' . '<br>' . 'Kindly click the link below to review the bulk request.<br>' . $link;

echo $body;
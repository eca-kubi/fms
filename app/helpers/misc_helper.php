<?php

use Moment\CustomFormats\MomentJs;
use Moment\Moment;
use Moment\MomentException;

function arrToObj($arr)
{
    return json_decode(json_encode($arr, JSON_THROW_ON_ERROR, 512), true, 512, JSON_THROW_ON_ERROR);
}

function objToArr($obj)
{
    return json_decode(json_encode($obj, JSON_THROW_ON_ERROR, 512), true, 512, JSON_THROW_ON_ERROR);
}

// format date
function formatDate($date, $from, $to)
{
    if (empty($date)) {
        return '';
    }
    $d = DateTime::createFromFormat($from, $date);
    if ($d) {
        $ret = $d->format($to);
        if ($ret) {
            return $ret;
        }
    }

    return '';
}

function reArrayFiles(&$file_post)
{
    $file_ary = array();
    $multiple = is_array($file_post['name']);

    $file_count = $multiple ? count($file_post['name']) : 1;
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; ++$i) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $multiple ? $file_post[$key][$i] : $file_post[$key];
        }
    }

    return $file_ary;
}

function userExists($user_id)
{
    $db = Database::getDbh();

    return $db->where('user_id', $user_id)
        ->has('users');
}

function getRandomString()
{
    return substr(md5(mt_rand()), 0, 5);
}

/**
 * Summary of filterPost
 * It returns filtered POST array.
 *
 * @return array
 */
function filterPost()
{
    return filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
}

function today()
{
    try {
        echo (new DateTime())->format(DFF);
    } catch (Exception $e) {
    }
}

/**
 * Summary of removeEmptyVal.
 *
 * @param array|object $value
 *
 * @return array
 */
function removeEmptyVal($value)
{
    $value = (array)$value;
    foreach ($value as $key => $item) {
        if (empty($item)) {
            unset($value[$key]);
        }
    }

    return $value;
}

function isCurrentGM(string $user_id)
{
    return $user_id === getCurrentGM();
}

/**
 *Concats array elements with $symbol and $symbolForlastElem.
 *
 * @param string $symbol
 * @param $symbolForLastElem
 * @param array $array
 *
 * @return string
 */
function concatWith(string $symbol, $symbolForLastElem, array $array)
{
    if (count($array) === 1) {
        return $array[0];
    }

    if (count($array) < 1) {
        return '';
    }

    $array = array_filter($array, static function ($value) {
        return !empty($value);
    });

    $lastElem = end($array);
    $lastElemKey = key($array);

    unset($array[$lastElemKey]);
    $result = implode($symbol, $array);

    return $result . ', ' . $symbolForLastElem . $lastElem;
}

function genEmailSubject($id_salary_advance)
{
    $salary_advance = new SalaryAdvanceModel($id_salary_advance);
    $ref = $salary_advance->request_number;
    return "Salary Advance - [$ref]";
}

if (!function_exists('array_key_last')) {
    /**
     * Polyfill for array_key_last() function added in PHP 7.3.
     *
     * Get the last key of the given array without affecting
     * the internal array pointer.
     *
     * @param array $array An array
     *
     * @return mixed the last key of array if the array is not empty; NULL otherwise
     */
    function array_key_last($array)
    {
        $key = null;

        if (is_array($array)) {
            end($array);
            $key = key($array);
        }

        return $key;
    }
}

function echoDate($date, $return = false)
{
    try {
        $d = (new Moment($date))->calendar(false);
        $t = (new Moment($date))->format('hh:mma', new MomentJs());
        if ($return) {
            return $d . ' at ' . $t;
        }
        echo $d . ' at ' . $t;
    } catch (MomentException $e) {
    }
    return '';
}

function echoDateOfficial($date, $official = false)
{
    try {
        if (!$official) {
            $d = (new Moment($date))->calendar(false);
            $t = (new Moment($date))->format('hh:mm a', new MomentJs());
            return $d . ' at ' . $t;
        }

        return (new Moment($date))->format('ddd, MMM D YYYY', new MomentJs());
    } catch (MomentException $e) {
    }
    return '';
}


/**
 * @param $department_id
 * @return mixed
 */
function getDepartmentHod($department_id)
{
    $db = Database::getDbh();
    $ret = $db->where('department_id', $department_id)
        ->where('role', ROLE_MANAGER)
        ->objectBuilder()
        ->getOne('users');
    return $ret;
}


/**
 * @param $subject string
 * @param $body string
 * @param $recipient_address string
 * @param $recipient_name string
 * @return bool
 */
function insertEmail($subject, $body, $recipient_address, $recipient_name = '')
{
    $email_model = new EmailModel();
    return $email_model->add([
        'subject' => $subject,
        'body' => $body,
        'recipient_address' => $recipient_address,
        'recipient_name' => $recipient_name,
    ]);
}

/**
 * @return string $id
 */
function getCurrentGM()
{
    return Database::getDbh()->where('prop', 'current_gm')->getValue('settings', 'value');
}

function getFinanceOfficer()
{
    return Database::getDbh()->where('prop', 'finance_officer')->getValue('settings', 'value');
}

function its_logged_in_user($user_id)
{
    return getUserSession()->user_id === $user_id;
}


/**
 * @param $department_id
 * @return string
 */
function getDepartment($department_id)
{
    $department = (new Department($department_id))->department;
    if (!empty($department)) {
        return $department;
    }
    return 'N/A';
}

function isCurrentHR(string $user_id)
{
    return getCurrentHR() === $user_id;
}

function isCurrentFmgr(string $user_id)
{
    return getCurrentFgmr() === $user_id;
}

function getCurrentHR()
{
    return
        Database::getDbh()
            ->where('prop', 'current_hr')
            ->getValue('settings', 'value');
}

function getCurrentFgmr()
{
    return Database::getDbh()
        ->where('prop', 'current_fmgr')
        ->getValue('settings', 'value');
}

/**
 * @param $user_id
 * @param $id_salary_advance
 * @return bool
 */
function isTheApplicant($user_id, $id_salary_advance)
{
    $db = Database::getDbh();
    $salary_advance = $db->where('id_salary_advance', $id_salary_advance)->objectBuilder()->getOne('salary_advance');
    return $salary_advance->user_id === $user_id;
}

function transformArrayData(array $ret)
{
    $current_user = new User(getUserSession()->user_id);
    foreach ($ret as $key => &$value) {
        $applicant = new User($value['user_id']);
        $employee = new stdClass();
        $employee->name = $applicant->first_name . ' ' . $applicant->last_name;
        $employee->user_id = $applicant->user_id;
        $employee->department = getDepartment($applicant->department_id);
        $value['department'] = $employee->department;
        $value['employee'] = $employee;
        $value['basic_salary'] = $applicant->basic_salary;
        unset($value['password']);
        $value['hod_comment_editable'] = $value['hod_approval_editable'] = isCurrentManagerForDepartment($applicant->department_id, $current_user->user_id);
        $value['hr_comment_editable'] = $value['hr_approval_editable'] = isCurrentHR($current_user->user_id);
        $value['gm_comment_editable'] = $value['gm_approval_editable'] = isCurrentGM($current_user->user_id);
        $value['fmgr_comment_editable'] = $value['fmgr_approval_editable'] = isCurrentFmgr($current_user->user_id);
    }
    return $ret;
}

/**
 * @param $user_id
 * @return string
 */
function getJobTitle($user_id)
{
    return (new User($user_id))->job_title;

}

function getFullName($user_id)
{
    $db = Database::getDbh();
    $first_name = $db->where('user_id', $user_id)->getValue('users', 'first_name');
    $last_name = $db->where('user_id', $user_id)->getValue('users', 'last_name');
    return $first_name . ' ' . $last_name;
}

function getNameJobTitleAndDepartment($user_id)
{
    $user = new User($user_id);
    return concatNameWithUserId($user_id) .
        ' from ' .
        getDepartment($user->department_id);
}

function concatNameWithUserId($user_id)
{
    $user = new User($user_id);
    return $user->first_name . ' ' . $user->last_name;
}


/**Return current date & time
 * @return string
 */
function now()
{
    $date_time = '';
    try {
        $date_time = (new DateTime())->format(DFB_DT);
    } catch (Exception $e) {
    } finally {
        return $date_time;
    }
}


function genDeptRef($department_id, $table, $single = true)
{
    $db = Database::getDbh();
    $m = new Moment();
    $m_format = '';
    $type = $single ? '-SGL-' : '-BLK-';
    try {
        $m_format = $m->format('MMYY', new MomentJs());
    } catch (MomentException $e) {
    }
    $count = $db->where('department_id', $department_id)->getValue($table, 'count(*)') + 1;
    $department = new Department($department_id);
    $short_name = $department->short_name;
    if (strlen($count) === 1) {
        return $short_name . $type . $m_format . '-00' . $count;
    }
    if (strlen($count) === 2) {
        return $short_name . $type . $m_format . '-0' . $count;
    }
    return $short_name . $type . $m_format . '-' . $count;
}

function site_url($url = '')
{
    return URL_ROOT . '/' . $url;
}

function modal($modal)
{
    // todo: extract payload
    //extract($payload);
    if (file_exists(APP_ROOT . '/views/modals/' . $modal . '.php')) {
        require_once APP_ROOT . '/views/modals/' . $modal . '.php';
    } else {
        // Modal does not exist
        die('Modal is missing.');
    }
}

function goBack()
{

    if (!empty($_SERVER['HTTP_REFERER'])) {
        $referer = filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL);
        // echo '<p><a href="'. $referer .'" title="Return to the previous page">&laquo; Go back</a></p>';
        header("Location: $referer");

    } else {

        //echo '<p><a href="javascript:history.go(-1)" title="Return to the previous page">&laquo; Go back</a></p>';
        echo '<script>history.go(-1)</script>';
        exit;
    }
}

function the_method($url = ''): string
{
    $method = '';
    if (!$url) {
        $url = the_url();
    }
    $parts = explode('/', $url);
    if (!empty($parts[5])) {
        $method = str_replace('-', '_', $parts[5]);
    }
    return $method;
}

/**
 * @return string | bool
 */
function the_url()
{
    $referer = '';
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = filter_var($_SERVER['HTTP_REFERER'], FILTER_VALIDATE_URL);
    }
    return $referer;
}

function getDepartmentMembers($department_id)
{
    $db = Database::getDbh();
    return $db->where('department_id', $department_id)
        ->objectBuilder()
        ->get('users');
}

function get_include_contents($filename, $variablesToMakeLocal): string
{
    extract($variablesToMakeLocal, EXTR_OVERWRITE);
    $file = APP_ROOT . "/templates/$filename.php";
    if (is_file($file)) {
        ob_start();
        require($file);
        return ob_get_clean();
    }
    return '';
}


function insertLog($id_salary_advance, $action, $remarks, $performed_by)
{
    $db = Database::getDbh();
    $data = array(
        'id_salary_advance' => $id_salary_advance,
        'action' => $action,
        'remarks' => $remarks,
        'performed_by' => $performed_by
    );
    return $db->insert('salary_advance_action_log', $data);
}

function flash_success($method = '', $message = 'Success!')
{
    if (empty($method)) {
        $method = the_method();
    }
    flash($flash = 'flash_' . $method, $message, 'text-sm text-center text-success alert');
}

function flash_error($method = '', $message = 'An error occurred!')
{
    if (empty($method)) {
        $method = the_method();
    }
    flash($flash = 'flash_' . $method, $message, 'text-sm text-center text-danger alert');
}

function array_unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        $val = (array)$val;
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function isCurrentManagerForDepartment(string $department_id, string $user_id)
{
    return getCurrentManager($department_id) === $user_id;
}

function getCurrentManager($department_id)
{
    $db = Database::getDbh();
    return $db->where('department_id', $department_id)->getValue('departments', 'current_manager');
}

/**
 * @param $array array
 * @param $prop
 * @param $filterBy
 * @param $fn callable
 * @return array
 */
function array_filter_multidim_by_obj_prop($array, $prop, $filterBy, $fn)
{
    return array_filter($array, static function ($value) use ($prop, $filterBy, $fn) {
        if (is_object($value)) {
            $a = $value->$prop;
        } else {
            $a = $value[(string)$prop];
        }
        $b = $filterBy;
        return $fn($a, $b);
    });
}

function isCurrentManager(string $user_id)
{
    $db = Database::getDbh();
    return $db->where('current_manager', $user_id)->has('departments');
}

function isITAdmin($user_id)
{

}

function isSecretary($user_id)
{
    $user_role = (new User($user_id))->role;
    return $user_role === ROLE_SECRETARY;
}

function isAssignedAsSecretary($user_id)
{
    $db = Database::getDbh();
    return $db->where('user_id', $user_id)->has('assigned_as_secretary');
}


function isSuperintendent($user_id)
{
    $user_role = (new User($user_id))->role;
    return in_array($user_role, ADMIN, true);
}

function getMembersAssignedToSecretary($user_id): array
{
    $db = Database::getDbh();
    $department_members = [];
    $department_id = $db->where('user_id', $user_id)->getValue('assigned_as_secretary', 'department_id');
    try {
        $department_members = $db->where('department_id', $department_id)->orderBy('first_name', 'ASC')->get('users');
    } catch (Exception $e) {
    }
    return $department_members;
}


function hasActiveApplication($user_id)
{
    // $ret = Database::getDbh()->rawQuery("SELECT COUNT(*) total from salary_advance WHERE user_id = $user_id AND deleted = false AND YEAR(date_raised) = YEAR(CURRENT_DATE()) AND MONTH(date_raised) = MONTH(CURRENT_DATE())");
    return Database::getDbh()->where('user_id', $user_id)
        ->where('deleted', false)
        ->where('YEAR(date_raised) = YEAR(CURRENT_DATE())')
        ->where('MONTH(date_raised) = MONTH(CURRENT_DATE())')
        ->has('salary_advance');
}

function isFinanceOfficer($user_id)
{
    return Database::getDbh()->where('value', $user_id)->where('prop', 'finance_officer')->has('settings');
}

/* Get the HTTP(S) URL of the current page.
 *
 * @param $server The $_SERVER superglobals array.
 * @return string The URL.
 */
function currentUrl($server)
{
    //Figure out whether we are using http or https.
    $http = 'http';
    //If HTTPS is present in our $_SERVER array, the URL should
    //start with https:// instead of http://
    if (isset($server['HTTPS'])) {
        $http = 'https';
    }
    //Get the HTTP_HOST.
    $host = $server['HTTP_HOST'];
    //Get the REQUEST_URI. i.e. The Uniform Resource Identifier.
    $requestUri = $server['REQUEST_URI'];
    //Finally, construct the full URL.
    //Use the function htmlentities to prevent XSS attacks.
    return $http . '://' . htmlentities($host) . htmlentities($requestUri);
}

/**
 * Joins salary advance table with users, and departments and returns the records.
 * @param array $key_value
 * @return array
 */
function getSalaryAdvance($key_value)
{
    $db = Database::getDbh();
    $records = [];
    foreach ($key_value as $key => $value) {
        $db->where((string)$key, $value);
    }
    try {
        $records = $db->join('users u', 'u.user_id=sa.user_id', 'LEFT')
            ->join('departments d', 'd.department_id=u.department_id', 'LEFT')
            ->orderBy('sa.date_raised', 'DESC')
            ->get('salary_advance sa', null, '*,  concat_ws(" ", u.first_name, u.last_name) as name, d.department, NULL as password, NULL as default_password');
    } catch (Exception $e) {
    }
    return $records;
}

/**
 * Joins salary advance table with users, departments, and bulk requests table and returns the records.
 * @param array $key_value
 * @return array
 */
function getBulkRequestsParent($key_value)
{
    $db = Database::getDbh();
    $bulk_requests = [];
    foreach ($key_value as $key => $value) {
        $db->where((string)$key, $value);
    }
    try {
        $bulk_requests = $db->join('users u', 'u.user_id=sa.raised_by_id', 'INNER')
            ->join('departments d', 'd.department_id=sa.department_id')
            ->get('salary_advance_bulk_requests sa', null,
                'sa.id_salary_advance_bulk_requests, concat_ws(" ", u.first_name, u.last_name) as raised_by, sa.raised_by_id, sa.request_number, sa.department_id, d.department');
    } catch (Exception $e) {
    }
    return $bulk_requests;
}

function salaryAdvanceReviewed($id_salary_advance)
{
    return Database::getDbh()->where('user_id', getUserSession()->user_id)->where('id_salary_advance', $id_salary_advance)->where('hod_approval', true)->has('salary_advance');
}

/**
 * @param float $amount
 * @param float $basic_salary
 * @return bool
 */
function isValidAmount($amount, $basic_salary)
{
    $percentage = ($amount / $basic_salary) * 100;
    $min_percent = (MIN_PERCENTAGE / 100) * $basic_salary;
    $max_percent = (MAX_PERCENTAGE / 100) * $basic_salary;
    return $percentage >= $min_percent || $percentage <= $max_percent;
}

function getActiveApplicants()
{
    $active_applicants = [];
    try {
        $active_applicants = Database::getDbh()->where('sa.deleted', false)
            ->where('YEAR(sa.date_raised) = YEAR(CURRENT_DATE())')
            ->where('MONTH(sa.date_raised) = MONTH(CURRENT_DATE())')
            ->join('users u', 'u.user_id=sa.user_id', 'LEFT')
            ->join('departments d', 'd.department_id=u.department_id', 'LEFT')
            ->getValue('salary_advance sa', 'concat_ws(" ", first_name, last_name)', null);
    } catch (Exception $e) {
    }
    return $active_applicants?: [];
}

function nullableStringConverter($nullableString, $nullOutput, $trueOutput, $falseOutput)
{
    if ($nullableString === null) {
        return $nullOutput;
    }
    if ($nullableString) {
        return $trueOutput;
    }

    return $falseOutput;
}

function requestApprovalNotification($applicant, $manager, $subject, $data)
{
    $data['body'] = get_include_contents('email_templates/salary-advance/approval', $data);
    $email = get_include_contents('email_templates/salary-advance/main', $data);
    insertEmail($subject, $email, $applicant->email);
    if ($applicant->email !== $manager->email) {
        $data['recipient_id'] = $manager->user_id;
        $data['body'] = get_include_contents('email_templates/salary-advance/approval', $data);
        $email = get_include_contents('email_templates/salary-advance/main', $data);
        insertEmail($subject, $email, $manager->email);
    }
}

function updateSalaryAdvance($is_bulk = false)
{
    $db = Database::getDbh();
    $current_user = getUserSession();
    $is_secretary = isSecretary($current_user->user_id);
    $hr = new User(getCurrentHR());
    $fmgr = new User(getCurrentFgmr());
    $gm = new User(getCurrentGM());
    $finance_officer = new User(getFinanceOfficer());
    $errors = ['errors' => [['message' => 'Update failed.']]];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        $id_salary_advance = $_POST['id_salary_advance'];
        $salary_advance = $db->where('id_salary_advance', $id_salary_advance)->getOne('salary_advance');
        if ($salary_advance) {
            $applicant = new User($salary_advance['user_id']);
            $hod = new User(getCurrentManager($applicant->department_id));
            $request_number = $salary_advance['request_number'];
            $subject = "Salary Advance Application ($request_number)";
            $link = $is_bulk?  URL_ROOT . '/salary-advance/bulk-requests/' . $request_number :  URL_ROOT . '/salary-advance/single-requests/' . $request_number;
            $data = ['ref_number' => $request_number, 'link' => $link, 'recipient_id' => $salary_advance['user_id'], 'applicant_id' => $applicant->user_id];
            if ($is_secretary) {
                $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                    'amount_requested' => $_POST['amount_requested']
                ]);
                if (!$update_success) {
                    $errors['errors'][0]['message'] = 'The record failed to update';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                    return;
                }
            } elseif ($current_user->user_id === $hod->user_id && $salary_advance['hod_approval'] === null) {
                // Current user is the hod
                $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                    'hod_id' => $current_user->user_id,
                    'hod_approval' => $_POST['hod_approval'],
                    'hod_comment' => filter_var($_POST['hod_comment'], FILTER_SANITIZE_STRING),
                    'hod_approval_date' => now()
                ]);
                if ($update_success) {
                    $data['approval'] = $_POST['hod_approval'];
                    $data['comment'] = $_POST['hod_comment'];
                    requestApprovalNotification($applicant, $hr, $subject, $data);
                } else {
                    $errors['errors'][0]['message'] = 'The record failed to update';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                    return;
                }
            } elseif ($salary_advance['hod_approval'] && ($salary_advance['hr_approval'] === null) && $hr->user_id === $current_user->user_id) {
                $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                    'hr_id' => $current_user->user_id,
                    'hr_approval' => $_POST['hr_approval'],
                    'hr_comment' => filter_var($_POST['hr_comment'], FILTER_SANITIZE_STRING),
                    'hr_approval_date' => now(),
                    'amount_payable' => $_POST['amount_payable']
                ]);
                if ($update_success) {
                    $data['approval'] = $_POST['hr_approval'];
                    $data['comment'] = $_POST['hr_comment'];
                    requestApprovalNotification($applicant, $gm, $subject, $data);
                } else {
                    $errors['errors'][0]['message'] = 'The record failed to update';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                    return;
                }
            } elseif ($salary_advance['hr_approval'] && ($salary_advance['gm_approval'] === null) && $gm->user_id === $current_user->user_id) {
                $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                    'gm_id' => $current_user->user_id,
                    'gm_approval' => $_POST['gm_approval'],
                    'gm_comment' => filter_var($_POST['gm_comment'], FILTER_SANITIZE_STRING),
                    'gm_approval_date' => now()
                ]);
                if ($update_success) {
                    $data['approval'] = $_POST['gm_approval'];
                    $data['comment'] = $_POST['gm_comment'];
                    requestApprovalNotification($applicant, $fmgr, $subject, $data);
                } else {
                    $errors['errors'][0]['message'] = 'The record failed to update';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                    return;
                }
            } elseif ($salary_advance['gm_approval'] && ($salary_advance['fmgr_approval'] === null) && $fmgr->user_id === $current_user->user_id) {
                $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                    'fmgr_id' => $current_user->user_id,
                    'fmgr_approval' => $_POST['fmgr_approval'],
                    'fmgr_comment' => filter_var($_POST['fmgr_comment'], FILTER_SANITIZE_STRING),
                    'fmgr_approval_date' => now(),
                    'amount_approved' => $_POST['amount_approved']
                ]);
                if ($update_success) {
                    $data['approval'] = $_POST['fmgr_approval'];
                    $data['comment'] = $_POST['fmgr_comment'];
                    $data['body'] = get_include_contents('email_templates/salary-advance/approval', $data);
                    $email = get_include_contents('email_templates/salary-advance/main', $data);
                    insertEmail($subject, $email, $applicant->email);
                } else {
                    $errors['errors'][0]['message'] = 'The record failed to update';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                    return;
                }
            } elseif ($salary_advance['fmgr_approval'] && $finance_officer->user_id === $current_user->user_id) {
                $update_success = $db->where('id_salary_advance', $id_salary_advance)->update('salary_advance', [
                    'finance_officer_id' => $current_user->user_id,
                    'finance_officer_comment' => filter_var($_POST['finance_officer_comment'], FILTER_SANITIZE_STRING),
                    'date_received' => now(),
                    'amount_received' => $_POST['amount_received'],
                    'received_by' => filter_var($_POST['received_by'], FILTER_SANITIZE_STRING)
                ]);
                if (!$update_success) {
                    $errors['errors'][0]['message'] = 'The record failed to update';
                    echo json_encode($errors, JSON_THROW_ON_ERROR, 512);
                    return;
                }
            }
            $updated_record = getSalaryAdvance(['id_salary_advance' => $id_salary_advance, 'deleted' => false]);
            echo json_encode($updated_record, JSON_THROW_ON_ERROR, 512);
        }
    }
}

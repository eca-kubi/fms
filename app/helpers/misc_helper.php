<?php

use diversen\sendfile;
use FileUpload\File;
use FileUpload\PathResolver;
use FileUpload\FileSystem;
use FileUpload\FileUploadFactory;
use Moment\CustomFormats\MomentJs;
use Moment\Moment;
use Moment\MomentException;

$current_user = getUserSession();

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

function getTime($date)
{
    try {
        return (new Moment($date))->format('hh:mm a', new MomentJs());
    } catch (MomentException $e) {
    }
    return "";
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
 * @param string $attachment
 * @return bool
 */
function insertEmail($subject, $body, $recipient_address, $recipient_name = '', $attachment = "")
{
    $email_model = new EmailModel();
    return $email_model->add([
        'subject' => $subject,
        'body' => $body,
        'recipient_address' => $recipient_address,
        'recipient_name' => $recipient_name,
        'attachment' => $attachment
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
    $file = APP_ROOT . "/$filename.php";
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

function isITAdmin($user_id): bool
{
    return Database::getDbh()->where('user_id', $user_id)->getValue('users', 'is_it_admin');
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
    return $active_applicants ?: [];
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

function sendFile(string $file_name)
{
    if (file_exists($file_name)) {
        $s = new sendfile();
        try {
            $s->send($file_name);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}

function readFileMetaData($directory)
{
    $file_metadata = [];
    if (is_dir($directory)) {
        $files = array_slice(scandir($directory), 2);
        foreach ($files as $file) {
            $file_name = $directory . "/$file";
            $file_type = filetype($file_name) === 'file' ? 'f' : 'd';
            $file_metadata[] = ['name' => $file, 'type' => $file_type, 'size' => filesize($file_name)];
        }
    }
    return json_encode($file_metadata);
}

function uploadFile(string $path): File
{
    if (!is_dir($path)) mkdir($path, 0777, true);
    $factory = new FileUploadFactory(
        new PathResolver\Simple($path),
        new FileSystem\Simple()
    );
    $file_upload_instance = $factory->create($_FILES['file'], $_SERVER);
    $file_upload_instance->setFileNameGenerator(new CustomFileGenerator(true));
    [$files] = @$file_upload_instance->processAll();
    return $files[0];
}

function createThumbnail($image)
{
    $thumbnail_path = THUMBNAIL_PATH . '/' . uniqueId();
    if (!is_dir($thumbnail_path)) mkdir($thumbnail_path, 0777, true);
    $image_mgr = new Intervention\Image\ImageManager();
    $image = $image_mgr->make($image);
    $image->fit(240, 120);
    $image->save($thumbnail_path . '/' . $image->basename);
}

function uniqueId()
{
    return getUserSession()->staff_id;
}

function monthName($monthNum)
{
    return (DateTime::createFromFormat('!m', $monthNum))->format('F');
}

function monthNumber($timeString)
{
    return date("m", strtotime($timeString));
}

function year($timeString)
{
    return date("Y", strtotime($timeString));
}


function isPowerUser($user_id)
{
    $power_users = Database::getDbh()->where('prop', 'nmr_power_user')->getValue('settings', 'value', null);
    return in_array($user_id, $power_users);
}

function getTargetMonthYearClosedStatus($table_prefix)
{
    $target_month_years = Database::getDbh()->get($table_prefix . '_target_month_year');
    $mapped = [];
    array_map(function ($element) use (&$mapped) {
        return $mapped[$element['target_month'] . ' ' . $element['target_year']] = $element['closed_status'];
    }, $target_month_years);
    return $mapped;
}

function isSubmissionOpened($target_month = '', $target_year = '', $table_prefix = 'nmr')
{
    return !isSubmissionClosedByPowerUser($target_month ?: currentSubmissionMonth(), $target_year ?: currentSubmissionYear(), $table_prefix);
    //return Database::getDbh()->where('prop', 'nmr_submission_opened')->getValue('settings', 'value');
}

function isSubmissionClosedByPowerUser($target_month, $target_year, $table_prefix = 'nmr')
{
    return Database::getDbh()->where('target_month', $target_month)->where('target_year', $target_year)->getValue($table_prefix . '_target_month_year', 'closed_status');
}

function currentSubmissionMonth()
{
    //return Database::getDbh()->where('prop', 'nmr_current_submission_month')->getValue('settings', 'value');
    //return date('F');
    return DEFAULT_DRAFT_MONTH;
}

function currentSubmissionYear()
{
    return DEFAULT_DRAFT_YEAR;
    //return date('Y');
    //return Database::getDbh()->where('prop', 'nmr_current_submission_year')->getValue('settings', 'value');
}

function getSubmittedReports($target_month, $target_year, $table_prefix = 'nmr')
{
    try {
        return Database::getDbh()->where('s.target_month="' . $target_month . '"')
            ->where('s.target_year="' . $target_year . '"')
            ->join('departments d', 'd.department_id=s.department_id')
            ->join($table_prefix . '_report_order r', 'r.department_id=d.department_id')
            ->orderBy('r.order_no', 'ASC')
            ->get($table_prefix . '_report_submissions s', null, 's.content');
    } catch (Exception $e) {
    }
    return [];
}

function generateFinalReport(string $target_month, $target_year, $table_prefix = 'nmr')
{
    $db = Database::getDbh();
    $cover_page = $db->where('name', 'cover_page')->getValue($table_prefix . '_report_parts', 'content');
    $cover_page = str_replace('#: monthYear #', strtoupper($target_month) . ' ' . $target_year, $cover_page);
    $distribution_list = $db->where('name', 'distribution_list')->getValue($table_prefix . '_report_parts', 'content');
    $callback = function ($array) {
        return $array['content'];
    };

    $join = function ($content, $separator) {
        $content .= $separator;
        return $content . getPageBreak();
    };

    $content =  /*"<coverpage>$cover_page</coverpage>" .*/
        /* "<distributionlist>$distribution_list</distributionlist>".*/
        "<div class='content'>" . array_reduce(array_map($callback, getSubmittedReports($target_month, $target_year, $table_prefix)), $join, "") . "</div>";
    return substr($content, 0, -strlen(getPageBreak()));
}

function fetchFinalReportAsHtml(string $target_month, $target_year, $table_prefix = 'nmr')
{
    /*$db = Database::getDbh();
    return $db->where('target_year', $target_year)->where('target_month', $target_month)
        ->getValue($table_prefix . '_final_report', 'html_content');*/
    return generateFinalReport($target_month, $target_year, $table_prefix);
}

function flashOrFull($table_prefix)
{
    return $table_prefix === 'nmr' ? 'Flash' : 'Full';
}


function getReportSubmissions(string $target_month = "", $target_year = "", $department_id = "", $table_prefix = 'nmr')
{
    try {
        $db = Database::getDbh();
        if ($target_month) {
            if ($department_id) $db->where('d.department_id', $department_id);
            return $db->where('monthname(date_submitted) = "' . $target_month . '"')->where('year(date_submitted)=' . $target_year)
                ->join('users u', 'u.user_id=n.user_id')
                ->join('departments d', 'u.department_id=d.department_id')
                ->join($table_prefix . '_final_report f', 'f.target_year=n.target_year and f.target_month=n.target_month', 'Left')
                ->get($table_prefix . '_report_submissions n', null, 'n.report_submissions_id,d.department, d.department_id, n.content, n.spreadsheet_content, n.date_submitted, n.target_month, n.target_year, n.date_modified,f.download_url, u.first_name, u.last_name');
        } else {
            return $db->join('users u', 'u.user_id=n.user_id')
                ->join('departments d', 'u.department_id=d.department_id')
                ->join($table_prefix . '_final_report f', 'f.target_year=n.target_year and f.target_month=n.target_month', 'Left')
                ->get($table_prefix . '_report_submissions n', null, 'n.report_submissions_id, d.department, d.department_id, n.content, n.spreadsheet_content, n.date_submitted, n.target_month, n.target_year, n.date_modified, f.download_url, u.first_name, u.last_name');
        }

    } catch (Exception $e) {
    }
    return [];
}


function groupedReportSubmissions(array $report_submissions)
{
    $grouped = [];
    foreach ($report_submissions as $key => $item) {
        $grouped[$item['target_month'] . " " . $item['target_year']][$key] = $item;
    }

    ksort($grouped, SORT_NUMERIC);
    return $grouped;
}

function groupedMyReports(array $my_reports)
{
    $grouped = [];
    foreach ($my_reports as $key => $item) {
        $grouped[$item['target_year']][$key] = $item;
    }

    // ksort($grouped, SORT_NUMERIC);
    return $grouped;
}

function getReportMonthYears($table_prefix = 'nmr')
{
    return Database::getDbh()->get($table_prefix . "_report_month_year");
}


function fetchGetParams() : string
{
    $get_params = "";
    foreach ($_GET as $key => $value) {
        if ($key == 'url') continue;
        $get_params = $get_params . $key . "=" . $value . "&";
    }
    return rtrim($get_params, '&');
}

function isReportSubmitted(string $target_month, $target_year, $department_id, $table_prefix = "nmr")
{
    $db = Database::getDbh();
    return $db->where('target_month', $target_month)->where('target_year', $target_year)
        ->where('department_id', $department_id)->has($table_prefix . "_report_submissions");
    /*  return $db->where('monthname(date_submitted)="' . $target_month . '"')->where('year(date_submitted)=' . $target_year)
          ->where('department_id', $department_id)->has($table_prefix . "_report_submissions");*/
}

function getJsonEncodedHtml($html)
{
    return json_encode($html, JSON_UNESCAPED_SLASHES);
}

function getDepartments()
{
    return Database::getDbh()->get('departments');
}

function getDepartmentsAsTextValue()
{
    return Database::getDbh()->get('departments', null, 'department as text, department_id as value');
}

function hasDraftForTargetMonthYear($target_month, $target_year, $user_id, $table_prefix = 'nmr')
{
    return Database::getDbh()->where('target_year', $target_year)
        ->where('target_month', $target_month)->where('user_id', $user_id)
        ->has($table_prefix . '_editor_draft');
}

function getDraftForTargetMonthYear($target_month, $target_year, $user_id, $table_prefix = 'nmr')
{
    return Database::getDbh()->where('target_year', $target_year)
        ->where('target_month', $target_month)->where('user_id', $user_id)
        ->getOne($table_prefix . '_editor_draft');
}

function getPreviousMonthYear($current_month)
{
    return Date('F Y', strtotime($current_month . " last month"));
}

function getNotSubmittedDepartments($target_month, $target_year, $table_prefix = 'nmr')
{
    $db = Database::getDbh();
    $departments = $db->getValue('departments', 'department', null);
    $exempted = $table_prefix == 'nmr' ? NO_FLASH__REPORT_DEPT : NO_FULL_REPORT_DEPT;
    try {
        $submitted_departments = $db->where('s.target_month="' . $target_month . '"')
            ->where('s.target_year="' . $target_year . '"')
            ->join('departments d', 'd.department_id=s.department_id')
            ->getValue($table_prefix . '_report_submissions s', 'department', null);
        $callback = function ($needle) use ($exempted, $submitted_departments) {
            return !in_array($needle, $submitted_departments) && !in_array($needle, $exempted);
        };
        return array_filter($departments, $callback);

    } catch (Exception $e) {
    }
    return [];
}

function getSubmittedDepartments($target_month, $target_year, $table_prefix = 'nmr')
{
    try {
        return Database::getDbh()->where('s.target_month="' . $target_month . '"')
            ->where('s.target_year="' . $target_year . '"')
            ->join('departments d', 'd.department_id=s.department_id')
            ->getValue($table_prefix . '_report_submissions s', 'department', null);
    } catch (Exception $e) {
    }
    return [];
}

function getSpreadsheetTemplate()
{
    try {
        return Database::getDbh()->orderBy('description', 'ASC')->get('nmr_spreadsheet_templates');
    } catch (Exception $e) {
    }
    return [];
}

function regenerateBeforePreview($value = null)
{
    if ($value === null)
        return Database::getDbh()->where('prop', 'nmr_regenerate_before_preview')->getValue('settings', 'value');
    return $value && Database::getDbh()->where('prop', 'nmr_regenerate_before_preview')->update('settings', ['value' => $value]);
}

function file_get_contents_curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function canEditReport($user_id)
{
    return isITAdmin($user_id) || isPowerUser($user_id);
}

function getPageBreak()
{
    return "<div class=\"page-break\" style=\"page-break-after:always;\"><span style=\"display:none;\">&nbsp;</span></div><p></p>";
}

function currentUser()
{
    $current_user = getUserSession();
    return [
        'name' => $current_user->first_name . ' ' . $current_user->last_name,
        'email' => $current_user->email,
        'department' => $current_user->department,
        'department_id' => $current_user->department_id,
        'is_it_admin' => isITAdmin($current_user->user_id)
    ];
}

function getDmr($department, $date)
{
    $db = Database::getDbh();
    return $db->where("(department = ? and date=?)", [$department, $date])->getOne('dmr_report') ?? [];
}

function nowDate()
{
    return date('Y-m-d', strtotime(now()));
}

function getSubmittedDmrs($date)
{
    $db = Database::getDbh();
    $arr = [];
    $dmrs = $db->where("(date=? and submitted=?)", [$date, 1])->get('dmr_report', null, 'department, content');
    foreach ($dmrs as $dmr) {
        $arr[$dmr['department']] = $dmr['content'];
    }
    return $arr;
}

function getMinDmrDate()
{
    $db = Database::getDbh();
    return $db->orderBy('date', 'ASC')->getValue('dmr_report','date');
}

function toJSDate($time) {
    return (DateTime::createFromFormat('Y-m-d', $time))->format('d-m-Y');
}

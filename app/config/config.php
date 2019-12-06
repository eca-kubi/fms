<?php
// DB Params
define('DB_HOST', 'localhost');
define('DB_USER', 'appiahmakuta');
define('DB_PASS', 'gmail300');
define('DB_NAME', 'sms');
define('NAVBAR_MT', '109.516px');
define('APP_ROOT', dirname(__FILE__, 2));
define('SITE_NAME', 'Form Management System');
define('APP_NAME', 'FMS');
define('APP_VERSION', '3.0.0');
define('PROFILE_PIC_DIR', URL_ROOT . '/public/assets/images/profile_pics/');
define('DATE_FORMATS', [
    'back_end' => 'Y-m-d',
    'front_end' => 'd-m-Y',
    'num_sm' => 'Y/m/d',
    'num_xs' => 'Y/n/j',
]);
define('BUTTONS', [
    'back' => '<a class="btn w3-btn bg-gray w3-small" href="javascript:history.go(-1)" role="button"><i class="fa fa-arrow-alt-circle-left"></i> Go back</a>'
]);
define('MY_PRIVATE_KEY', md5('my-private-key-daemon'));

const NO_PROFILE = 'no_profile.jpg';
const DEFAULT_PROFILE_PIC = 'no_profile.jpg';
const INTRANET = 'http://intranet.arlgh.com';
const DFF = 'd-m-Y';
const DFB = 'Y-m-d';
const DFF_DT = 'd-m-Y h:i a';
const DFB_DT = 'Y-m-d H:i:s';
const MEDIA_FILE_TYPES = 'image/*,  video/*, audio/*';
const PHOTO_FILE_TYPES = 'image/*';
const VIDEO_FILE_TYPES = 'video/*';
const AUDIO_FILE_TYPES = 'audio/*';
const DOC_FILE_TYPES = '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, application/msword, application/pdf, text/plain, text/html, application/vnd.openxmlformats-officedocument.wordprocessingml.document';
const EXCEL_FILE_TYPES = '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel';
const HTML_NEW_LINE = '<br/>';
const EMAIL_SUBJECT = 'Salary Advance';
const PATH_SALARIES = APP_ROOT . '/uploads/';
const SALT = 'archangel';
const REMINDER_LIMIT = 3;
const REMINDER_INTERVAL = '1H'; // 1 hour
const ROLE_MANAGER = 'Manager';
const ROLE_SUPERINTENDENT = 'Superintendent';
const CURRENCY_GHS = 'GH₵';
const ADMIN = [
    'Superintendent',
    'Manager'
];
const ROLE_SECRETARY = 'Secretary';
const ROLE_USER = 'User';
const MIN_PERCENTAGE = 10;
const MAX_PERCENTAGE = 30;
include_once(APP_ROOT. '\helpers\error_codes.php');
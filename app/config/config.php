<?php
const OAUTH_APP_ID = '8655f59d-68a8-45b0-a2b0-027a2712eedf';
const OAUTH_APP_PASSWORD = 'L-bQ0tq~38t~a96ii7EU-U.dKGJWPLM8YB';
const OAUTH_REDIRECT_URI = 'https://' . DOMAIN . '/nmr/pages/getoauthtoken';
const OAUTH_SCOPES = 'openid profile email offline_access Mail.Send Mail.Send.Shared SMTP.Send User.Read';
const OAUTH_AUTHORITY = 'https://login.microsoftonline.com/common';
const OAUTH_AUTHORIZE_ENDPOINT = '/oauth2/v2.0/authorize';
const OAUTH_TOKEN_ENDPOINT = '/oauth2/v2.0/token';

// DB Params
define('DB_HOST', 'localhost');
define('DB_USER', 'sms_db_admin');
define('DB_PASS', 'Gmail@3000');
define('DB_NAME', 'sms');
define('DB_PARAMS', ['db' => DB_NAME, 'host' => DB_HOST, 'username' => DB_USER, 'password' => DB_PASS, 'charset' => 'utf8mb4']);
/*define('APP_ROOT', dirname(__FILE__, 2));*/
define('APP_ROOT', dirname(dirname(__FILE__)));
define('SITE_NAME', 'Adamus Apps');
define('APP_NAME', 'Forms');
define('APP_VERSION', '3.0.0');
define('NAVBAR_MT', '109.516px');
define('PROFILE_PIC_DIR', URL_ROOT . '/public/assets/images/profile_pics/');
define('DATE_FORMATS', [
    'back_end' => 'Y-m-d',
    'front_end' => 'd-m-Y',
    'num_sm' => 'Y/m/d',
    'num_xs' => 'Y/n/j',
]);
const DEFAULT_DRAFT_MONTH = 'July';
const DEFAULT_DRAFT_YEAR = '2020';
const IT_MANAGER_EMAIL = 'kmat@adamusgh.com';
const IT_ADMIN_EMAILS = 'ecakubi@adamusgh.com;eadompre@adamusgh.com;jsackey@adamusgh.com';
const NO_FLASH__REPORT_DEPT = ['Accra Office', 'IT', 'Supply', 'Commercial', 'SRD', 'Security'];
const NO_FULL_REPORT_DEPT = ['Accra Office', 'IT', 'Supply', 'Commercial'];
const DISTRIBUTION_LIST_EMAILS = [['ecakubi@adamusgh.com', 'Eric'], ['kmat@adamusgh.com', 'Krisz'],/* ['sopoku@adamusgh.com', 'Seth' ]*/
    ['anyamekye@adamusgh.com', 'Anthony']];
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
const DOC_FILE_TYPES = ['.csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'application/msword', 'application/pdf', 'text/plain', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
const HTML_NEW_LINE = '<br/>';
const SALT = 'archangel';
const ROLE_MANAGER = 'Manager';
const ROLE_SUPERINTENDENT = 'Superintendent';
const CURRENCY_GHS = 'GH₵';
const ADMIN = [
    'Superintendent',
    'Manager'
];
const ROLE_SECRETARY = 'Secretary';
const FILE_UPLOAD_PATH = APP_ROOT . '/../public/uploads';
const IMAGE_UPLOAD_PATH = APP_ROOT . '/uploads/images';
const THUMBNAIL_PATH = APP_ROOT . '/uploads/thumbnails';

// DB Table Names
const TABLE_NMR_SPREADSHEET_TEMPLATES = 'nmr_spreadsheet_templates';
const TABLE_NMR_SAVED_SPREADSHEETS = 'nmr_saved_spreadsheets';
const ICON_PATH = URL_ROOT . '/public/assets/images/icons/icons.svg';
const PAGE_TITLE_DRAFT_REPORT = 'Draft Report';
const CKFILEFINDER_CONNECTOR_PATH = "/public/assets/js/ckfinder/core/connector/php/connector.php";

const GMs = ['General Manager', 'Mining Manager', 'Process Manager'];

const AZURE_TENANT_ID = '3a652cac-c74f-4bd7-9144-a937afc5b541';
const AZURE_ADAMUS_APPS_CLIENT_ID = 'b72205b0-8882-4bb0-96b8-386132efbeab';
const AZURE_ADAMUS_APPS_CLIENT_SECRET = 'aif-A9bXM9L8Hn3TDyOC~3~bNaDhO69_m3';
const USER_ID_ECAKUBI = '590dc027-8292-45a7-b76d-16d36c0d2e13';
const USER_ID_WEBSERVICES = '163bbe6b-8953-43d3-acc8-4f615efada89';

const DATE_FORMAT_YMD = 'Y-m-d';
const DATE_FORMAT_DMY = 'd-m-Y';

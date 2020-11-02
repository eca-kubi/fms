<?php
/**
 * Created by PhpStorm.
 * User: UNCLE CHARLES
 * Date: 1/7/2019
 * Time: 1:07 PM
 */
spl_autoload_register(function ($class_name) {
    $dirs = array(
        '../models/',
        '../libraries/',
        '../classes/'
    );
    foreach ($dirs as $dir) {
        if (!file_exists('../app/' . $dir . $class_name . '.php')) {
            continue;
        } else {
            require_once('../app/' . $dir . $class_name . '.php');
        }
    }
});
require_once '../../vendor/autoload.php';
require_once '../config/config.php';
require_once '../config/host.php';
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;

class MyMicrosoft extends Microsoft {
    /**
     * Default scopes
     *
     * @var array
     */
    public $defaultScopes = ['mail.send', 'mail.readwrite'];

    /**
     * Base url for authorization.
     *
     * @var string
     */
    //protected $urlAuthorize = 'https://login.live.com/oauth20_authorize.srf';
    protected $urlAuthorize = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize';

    /**
     * Base url for access token.
     *
     * @var string
     */
    //protected $urlAccessToken = 'https://login.live.com/oauth20_token.srf';
    protected $urlAccessToken = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';

    /**
     * Base url for resource owner.
     *
     * @var string
     */
    protected $urlResourceOwnerDetails = 'https://apis.live.net/v5.0/me';
}

define('EMAIL_TABLE', 'emails');
try {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    //Server settings
    $mail->SMTPDebug = 4; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->AuthType = 'XOAUTH2';
    $mail->Host = 'smtp.office365.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'webservices@adamusgh.com'; // SMTP username
    $mail->Password = '!123456ab'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; // TCP port to connect to
    $mail->setFrom('webservices@adamusgh.com', 'Adamus Web Services');
    $mail->SMTPAutoTLS = false;
    $clientId = 'b72205b0-8882-4bb0-96b8-386132efbeab';
    $clientSecret = 'aif-A9bXM9L8Hn3TDyOC~3~bNaDhO69_m3';
    $accessToken = AZURE_APP_ACCESS_TOKEN;
    //Create a new OAuth2 provider instance
    $provider = new MyMicrosoft(
        [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
        ]
    );

    $options = [
        'provider' => $provider,
        'clientId' => $clientId,
        'clientSecret' => $clientSecret,
        'refreshToken' => $accessToken,
        'userName' =>$mail->Username
    ];

//Pass the OAuth provider instance to PHPMailer
    $mail->setOAuth(
        new \PHPMailer\PHPMailer\OAuth($options)
    );
    while (true) {
        $emails = fetch_email();
        foreach ($emails as $email) {
            $mail->addAddress($email->recipient_address, $email->recipient_name);     // Add a recipient
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $email->subject;
            //$mail->MessageID = '<' . $mail->Subject . '@cms>';
            //$mail->addCustomHeader('In-Reply-To', '<' . $email->subject . '@cms>');
            //$mail->addCustomHeader('References', '<' . $email->subject . '@cms>');
            $mail->Body = $email->body;
            if ($email->attachment) $mail->addAttachment($email->attachment);

            if ($mail->send()) {
                update_status($email->email_id);
            }
            $mail->clearAddresses();
        }
        sleep(10);
    }
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

function fetch_email(): array
{
    $db = Database::getDbh();
    return $db->where('sent', false)
        ->objectBuilder()
        ->get(EMAIL_TABLE);
}

function update_status($id)
{
    Database::getDbh()->where('email_id', $id)->update(EMAIL_TABLE, ['sent' => true]);
}

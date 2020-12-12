<?php
/**
 * @var EmailTemplateModel $template_model;
 */

$content = <<<html
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title></title>
</head>
<body style="margin: 0; padding: 0;">
<table align="center" cellpadding="10" cellspacing="0" width="600" style="border-collapse: collapse;">
    <tr bgcolor="#70bbd9" style="padding: 30px 30px 30px 30px;">
        <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; border-bottom: #f8f8f8 2px solid;">
            <b>$template_model->subject </b>
        </td>
    </tr>
    <tr bgcolor="#70bbd9" style="padding: 30px 30px 30px 30px;">
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="color: #153643;font-family: Arial, sans-serif; font-size: 14px;">
                        $template_model->message
                    </td>
                </tr>
                <tr><td><b style="text-decoration: underline;">Disclaimer: The link in this email will only work on the local development machine. It is solely for testing.</b></td></tr>
            </table>
        </td>
    </tr>
    <tr bgcolor="#ee4c50" style="padding: 30px 30px 30px 30px;">
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 10px; font-weight: bold">
                        &copy; $template_model->copyright_year - Developed by Adamus IT<br/>
                        Email: ithelpdesk@adamusgh.com <br/>
                        Ext: 1000
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
html;

use TemplateModels\EmailTemplateModel;
echo $content;

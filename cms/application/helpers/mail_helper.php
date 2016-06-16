<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/PHPMailer/class.phpmailer.php';
require_once APPPATH.'third_party/PHPMailer/class.smtp.php';
/**
 * Send Email
 * @param  String $to    UserEmail
 * @param  String $title EmailTitle
 * @param  String $body  EmailMessage
 * @return Boolean       TrueOrFalse
 */
function send_mail($data = array())
{
    static $EMAILNAME = 'CF_EmailName';
    static $EMAILPWD  = 'CF_EmailPwd';

    $CI =& get_instance();
    $CI->load->config('email');

    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    $mail->IsSMTP(); // telling the class to use SMTP
    try {
        // For 163
        // $mail->Host       = 'smtp.163.com';
        // $mail->SMTPDebug  = false;
        // $mail->SMTPAuth   = true;
        // $mail->Port       = 25;

        // For Gmail
        $mail->Host       = $CI->config->item('smtp_host');
        $mail->SMTPDebug  = false;
        $mail->SMTPAuth   = true;
        $mail->Port       = $CI->config->item('smtp_port');
        // ↓ Very important
        $mail->SMTPSecure = "ssl";

        // Please change your username and password to your own !
        $mail->Username   = $CI->config->item('smtp_user');
        $mail->Password   = $CI->config->item('smtp_pass');

        $mail->AddAddress($data['to'], $data['to_name']);
        $mail->SetFrom($data['from'], $data['from_name']);

        $mail->Subject = $data['subject'];
        $mail->MsgHTML($data['message']);

        $mail->Send();
        return true;
    } catch (phpmailerException $e) {
        log_message('error', 'mail_exception:'.$e->errorMessage());
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
        return false;
    } catch (Exception $e) {
        log_message('error', 'mail_exception:'.$e->errorMessage());
        echo $e->getMessage(); //Boring error messages from anything else!
        return false;
    }
}

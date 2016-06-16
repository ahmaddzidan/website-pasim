<?php
// namespace Ahmad;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Email Class
 *
 * Permits email to be sent using Mail, Sendmail, or SMTP.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/email.html
 */

require_once APPPATH.'third_party/PHPMailer/class.phpmailer.php';
require_once APPPATH.'third_party/PHPMailer/class.smtp.php';


class Semail
{
	/**
	 * Used as the User-Agent and X-Mailer headers' value.
	 *
	 * @var	string
	 */
	public $useragent	= 'CodeIgniter';

	/**
	 * Path to the Sendmail binary.
	 *
	 * @var	string
	 */
	public $mailpath	= '/usr/sbin/sendmail';	// Sendmail path

	/**
	 * Which method to use for sending e-mails.
	 *
	 * @var	string	'mail', 'sendmail' or 'smtp'
	 */
	public $protocol	= 'smtp';		// mail/sendmail/smtp

	/**
	 * STMP Debug
	 *
	 * @var	boolean
	 */
	public $smtp_debug	= false;

	/**
	 * STMP Auth
	 *
	 * @var	boolean
	 */
	public $smtp_auth	= true;

	/**
	 * STMP Server host
	 *
	 * @var	string
	 */
	public $smtp_host	= '';

	/**
	 * SMTP Username
	 *
	 * @var	string
	 */
	public $smtp_user	= '';

	/**
	 * SMTP Password
	 *
	 * @var	string
	 */
	public $smtp_pass	= '';

	/**
	 * SMTP Server port
	 *
	 * @var	int
	 */
	public $smtp_port	= 25;

	/**
	 * SMTP connection timeout in seconds
	 *
	 * @var	int
	 */
	public $smtp_timeout	= 5;

	/**
	 * SMTP persistent connection
	 *
	 * @var	bool
	 */
	public $smtp_keepalive	= FALSE;

	/**
	 * SMTP Encryption
	 *
	 * @var	string	empty, 'tls' or 'ssl'
	 */
	public $smtp_crypto	= '';

	/**
	 * Whether to apply word-wrapping to the message body.
	 *
	 * @var	bool
	 */
	public $wordwrap	= TRUE;

	/**
	 * Number of characters to wrap at.
	 *
	 * @see	CI_Email::$wordwrap
	 * @var	int
	 */
	public $wrapchars	= 76;

	/**
	 * Message format.
	 *
	 * @var	string	'text' or 'html'
	 */
	public $mailtype	= 'html';

	/**
	 * Character set (default: utf-8)
	 *
	 * @var	string
	 */
	public $charset		= 'utf-8';

	/**
	 * Multipart message
	 *
	 * @var	string	'mixed' (in the body) or 'related' (separate)
	 */
	public $multipart	= 'mixed';		// "mixed" (in the body) or "related" (separate)

	/**
	 * Alternative message (for HTML messages only)
	 *
	 * @var	string
	 */
	public $alt_message	= '';

	/**
	 * Whether to validate e-mail addresses.
	 *
	 * @var	bool
	 */
	public $validate	= FALSE;

	/**
	 * X-Priority header value.
	 *
	 * @var	int	1-5
	 */
	public $priority	= 3;			// Default priority (1 - 5)

	/**
	 * Newline character sequence.
	 * Use "\r\n" to comply with RFC 822.
	 *
	 * @link	http://www.ietf.org/rfc/rfc822.txt
	 * @var	string	"\r\n" or "\n"
	 */
	public $newline		= "\n";			// Default newline. "\r\n" or "\n" (Use "\r\n" to comply with RFC 822)

	/**
	 * CRLF character sequence
	 *
	 * RFC 2045 specifies that for 'quoted-printable' encoding,
	 * "\r\n" must be used. However, it appears that some servers
	 * (even on the receiving end) don't handle it properly and
	 * switching to "\n", while improper, is the only solution
	 * that seems to work for all environments.
	 *
	 * @link	http://www.ietf.org/rfc/rfc822.txt
	 * @var	string
	 */
	public $crlf		= "\n";

	/**
	 * Whether to use Delivery Status Notification.
	 *
	 * @var	bool
	 */
	public $dsn		= FALSE;

	/**
	 * Whether to send multipart alternatives.
	 * Yahoo! doesn't seem to like these.
	 *
	 * @var	bool
	 */
	public $send_multipart	= TRUE;

	/**
	 * Whether to send messages to BCC recipients in batches.
	 *
	 * @var	bool
	 */
	public $bcc_batch_mode	= FALSE;

	/**
	 * BCC Batch max number size.
	 *
	 * @see	CI_Email::$bcc_batch_mode
	 * @var	int
	 */
	public $bcc_batch_size	= 200;

	static $EMAILNAME = 'CF_EmailName';
    static $EMAILPWD  = 'CF_EmailPwd';

	public function __construct()
	{
		$this->mail = new PHPMailer(TRUE);
		$this->initialize();
	}

	public function initialize()
	{
		// telling the class to use SMTP
		$this->mail->IsSMTP();

		$this->mail->Host       = $this->smtp_host;
		$this->mail->Port       = $this->smtp_port;
		$this->mail->SMTPDebug  = $this->smtp_debug;
		$this->mail->SMTPAuth   = $this->smtp_auth;
		$this->mail->SMTPSecure = $this->smtp_crypto;
		$this->mail->Username   = $this->smtp_user;
		$this->mail->Password   = $this->smtp_pass;

		if($this->mailtype == 'html')
		{
			$this->mail->isHTML(true);
		}
	}

	public function from($from, $name = '')
	{
		$this->mail->SetFrom($from, $name);

		return $this;
	}

	public function to($to, $name = '')
	{
		$this->mail->AddAddress($to, $name);

		return $this;
	}

	public function reply_to($to, $name = '')
	{
		$this->mail->AddAddress($to, $name);

		return $this;
	}

	public function cc($cc)
	{
		$this->mail->addCC($cc);

		return $this;
	}

	public function bcc($bcc)
	{
		$this->mail->addBCC($bcc);

		return $this;
	}

	public function subject($subject)
	{
		$this->mail->Subject($subject);

		return $this;
	}

	public function message($body)
	{
		$this->mail->Body($body);

		return $this;
	}

	public function altbody($body)
	{
		$this->mail->AltBody($body);

		return $this;
	}

	public function send()
	{
		try {
			$this->mail->send();
			return true;
		}catch (phpmailerException $e) {
			log_message('error', 'mail_exception:'.$e->errorMessage());
	        echo $e->errorMessage(); //Pretty error messages from PHPMailer
	        return false;
		} catch (Exception $e) {
			log_message('error', 'mail_exception:'.$e->errorMessage());
	        echo $e->getMessage(); //Boring error messages from anything else!
	        return false;
		}
	}

}

// END MY_Form_valdiation class

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */

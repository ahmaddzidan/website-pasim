<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Email Protocol
|--------------------------------------------------------------------------
|
|  The mail sending protocol.
|
|	http://example.com/
|
| WARNING: You MUST set this value!
|
| Options : mail, sendmail, or smtp
| Default : mail
|
*/

$config['protocol']  = 'smtp';

/*
|--------------------------------------------------------------------------
| SMTP Server Address
|--------------------------------------------------------------------------
|
|
*/

$config['smtp_host'] = 'ssl://smtp.gmail.com';

/*
|--------------------------------------------------------------------------
| SMTP Username
|--------------------------------------------------------------------------
| WARNING: You MUST set this value!
*/

$config['smtp_user'] = 'asanusi007@gmail.com';

/*
|--------------------------------------------------------------------------
| SMTP Password
|--------------------------------------------------------------------------
|
| WARNING: You MUST set this value!
|
*/

$config['smtp_pass'] = 'kixgynuomrhsqqif';

/*
|--------------------------------------------------------------------------
| SMTP Port
|--------------------------------------------------------------------------
|
| WARNING: You MUST set this value!
|
*/

$config['smtp_port'] = '465';

/*
|--------------------------------------------------------------------------
| Wordwrap
|--------------------------------------------------------------------------
|
| Character count to wrap at.
|
*/

$config['wordwrap']  = TRUE;

/*
|--------------------------------------------------------------------------
| Mailtype
|--------------------------------------------------------------------------
|
|  Type of mail. If you send HTML email you must send it as a complete web page.
|  Make sure you don’t have any relative links or relative image paths otherwise they will not work.
|
|
| WARNING: You MUST set this value!
|
| Options : text or html
| Default : text
|
*/

$config['mailtype']  = 'html';

/*
|--------------------------------------------------------------------------
| Charset
|--------------------------------------------------------------------------
|
| Character set (utf-8, iso-8859-1, etc.).
|
*/

$config['charset']   = 'iso-8859-1';

/*
|--------------------------------------------------------------------------
| New Line
|--------------------------------------------------------------------------
|
| Character set (utf-8, iso-8859-1, etc.).
| use double quotes to comply with RFC 822 standard
|
*/

$config['newline']   = "\r\n";
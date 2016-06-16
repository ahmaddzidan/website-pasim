<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		Ahmad Sanusi
 * @copyright	Copyright (c) 2014 Ahmad Sanusi
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Ahmad Sanusi
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class Setting_m extends MY_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	protected $_table_name = 'tbl_setting';
	protected $_order_by   = 'id';
	public $_rules         = array(
        'site_title'=> array(
			'field'=>'title',
			'label'=>'Site Title',
			'rules'=>'trim|required|max_length[100]'),
		'site_domain'=> array(
			'field'=>'domain',
			'label'=>'Domain',
			'rules'=>'trim|required'),
		'site_description'=> array(
			'field'=>'description',
			'label'=>'Site Description',
			'rules'=>'trim|required'),
		'meta_keyword'=> array(
			'field'=>'metakeyword',
			'label'=>'Meta Keyword',
			'rules'=>'trim|required'),
		'owner'=> array(
			'field'=>'owner',
			'label'=>'Owner',
			'rules'=>'trim|required'),
		'support'=> array(
			'field'=>'support',
			'label'=>'Support',
			'rules'=>'trim|required'),
		'support_email'=> array(
			'field'=>'support_email',
			'label'=>'Support Email',
			'rules'=>'trim|required'),

		'smtp_host'=> array(
			'field'=>'smtphost',
			'label'=>'SMTP Host',
			'rules'=>'trim|required'),
		'smtp_port'=> array(
			'field'=>'smtpport',
			'label'=>'SMTP Port',
			'rules'=>'trim|required'),
		'smtp_username'=> array(
			'field'=>'smtpuser',
			'label'=>'SMTP Username',
			'rules'=>'trim|required'),
		'smtp_password'=> array(
			'field'=>'smtppass',
			'label'=>'SMTP Password',
			'rules'=>'trim|required'),
		'facebook_address'=> array(
			'field'=>'facebook',
			'label'=>'Facebook Address',
			'rules'=>'trim|required'),
		'twitter_address'=> array(
			'field'=>'twitter',
			'label'=>'Twitter Address',
			'rules'=>'trim|required'),
		'telephone'=> array(
			'field'=>'telephone',
			'label'=>'Telephone',
			'rules'=>'trim|required'),
		'mobile_contact'=> array(
			'field'=>'mobilecontact',
			'label'=>'Mobile Contact',
			'rules'=>'trim|required'),
		'address'=> array(
			'field'=>'address',
			'label'=>'Address',
			'rules'=>'trim|required'),
		'status'=> array(
			'field'=>'status',
			'label'=>'Status',
			'rules'=>'trim'),
		'unavailable_message'=> array(
			'field'=>'unavailablemessage',
			'label'=>'Unavailable Message',
			'rules'=>'trim|required')
		);

	public function __construct()
	{
		parent::__construct();
	}
}
// END Setting_m Class

/* End of file Setting_m.php */
/* Location: ./Application/modules/setting/models/setting_m.php */
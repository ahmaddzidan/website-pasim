<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		Ahmad Sanusi
 * @copyright	Copyright (c) 2016, Ahmad Sanusi.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application MY_Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Ahmad Sanusi
 * @link		http://codeigniter.com/user_guide/general/MY_Controller.html
 */
class MY_Controller extends MX_Controller {

	public $data = array();

	public function __construct()
	{
		parent::__construct();

		$this->load->model('setting/setting_m');
		$this->load->model('message/message_m');

		$this->data['environment'] = ENVIRONMENT;
		$this->data['ci_version']  = CI_VERSION;

		$this->data['site_setting'] = $this->setting_m->get(1);
		$this->data['date_format']  = $this->data['site_setting']->dateformat;
		$this->data['now']  = time();

		// URI Assignment
		$this->data['fulldomain'] = $this->config->base_url();
		$this->data['segment_1'] = $this->uri->segment(1);
		$this->data['segment_2'] = $this->uri->segment(2);
		$this->data['segment_3'] = $this->uri->segment(3);
		$this->data['segment_4'] = $this->uri->segment(4);

		$exceptions_uris = array(
			'panel',
			'auth/login',
			'auth/logout',
			'auth/forgot_password',
			'auth/reset_password/[^/]+',
		);

		if (in_array(uri_string(), $exceptions_uris) ==  FALSE)
		{
			if ($this->ion_auth->logged_in() == FALSE)
			{
				redirect('auth/login');
			}
			else
			{
				// Session Data Assignment
				$this->data['new_msg'] = $this->message_m->get_by(array('type'=>'in','read'=>'0'));
				$this->data['session_data'] = $this->ion_auth->user()->row();
			}
		}

		$this->lang->load('general');
 	}
}
// END MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
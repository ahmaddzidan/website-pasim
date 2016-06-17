<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		Ahmad Sanusi
 * @copyright	Copyright (c) 2014, Ahmad Sanusi.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Application Frontend_controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Ahmad Sanusi
 * @link		http://codeigniter.com/user_guide/general/Frontend_controller.html
 */
class Frontend_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		// Load page model
		$this->load->model(array('pages/pages_m','setting/setting_m','post/post_m'));
		$this->data['file_path'] = base_url() . 'panel/themes/default/uploads/';

		$this->data['navigation'] = $this->pages_m->_get_nested();

		// get recent 2, for 'berita' & 3 for 'informasi'
		$this->data['recent']['berita'] = $this->post_m->_get_recent(3,2);
		$this->data['recent']['informasi'] = $this->post_m->_get_recent(3,3);

		$this->data['websetting'] = $this->setting_m->get(1, TRUE);

		$exceptions_uris = array(
			'pages/error_503',
		);

		if (in_array(uri_string(), $exceptions_uris) ==  FALSE)
		{
			if ($this->data['websetting']->status <= 0)
			{
				redirect('pages/error_503');
			}
		}

		if ($this->data['websetting']->status > 0) {
			if (uri_string() == 'pages/error_503')
			{
				redirect('/');
			}
		}
	}
}
// END Frontend_controller class

/* End of file Frontend_controller.php */
/* Location: ./application/libraries/Frontend_controller.php */
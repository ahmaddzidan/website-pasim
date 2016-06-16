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


		$this->data['environment'] = ENVIRONMENT;
		$this->data['ci_version']  = CI_VERSION;

 	}
}
// END MY_Controller class

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */
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
class Gallery_m extends MY_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	protected $_table_name = 'tbl_gallery';
	protected $_order_by   = 'id';

	public function __construct()
	{
		parent::__construct();
	}

	public function _get($id = NULL, $single = FALSE)
	{
		$this->db->select('tbl_gallery.*, CONCAT (users.first_name) as author, tbl_gallery_category.name as category');
		$this->db->join('users','users.id = tbl_gallery.createdby','left');
		$this->db->join('tbl_gallery_category','tbl_gallery_category.id = tbl_gallery.catid','left');
		$this->db->where('status',1);

		return parent::get($id, $single);
	}

}
// END slider_m Class

/* End of file slider_m.php */
/* Location: ./Application/modules/slider/models/slider_m.php */
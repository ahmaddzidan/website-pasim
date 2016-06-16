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
 * @subpackage	Modules - Pages
 * @category	Model
 * @author		Ahmad Sanusi
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class Pages_m extends MY_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	protected $_table_name = 'tbl_pages';
	protected $_order_by   = 'parent_id, order';

	public function __construct()
	{
		parent::__construct();
	}


	public function _get_page($where, $single = FALSE)
	{
		$this->db->select('tbl_pages.*, t.name as template, t.template_file as template_file');
		$this->db->join('tbl_pages_template as t','tbl_pages.template_id=t.id','left');
		return parent::get_by($where, $single);
	}

	public function _get_nested()
	{
		$this->db->select('id, parent_id, title, status, slug');
		$pages = $this->db->order_by($this->_order_by)->where('status',1);
		$pages = $this->db->get('tbl_pages')->result_array();

		$array = ordered_menu($pages,0);
		return $array;

	}
	public function _get_quick_navigation()
	{
		$this->db->select('id, parent_id, title, status, slug');
		$pages = $this->db->order_by($this->_order_by)->where(array('parent_id'=>'0','status'=>'1'))->limit('7');
		$pages = $this->db->get('tbl_pages')->result_array();

		$array = ordered_menu($pages,0);
		return $array;

	}

	public function _get_subnested($id)
	{
		$pages = $this->db->order_by($this->_order_by)->where(array('parent_id'=> $id, 'status'=> 1));
		$pages = $this->db->get('tbl_pages')->result_array();

		$array = array();
		foreach ($pages as $page) {
			if($page['parent_id'])
			{
				$array[$page['id']] = $page;
			}
		}
		return $array;
	}

	public function get_recent( $table = NULL ,$limit = 3)
	{
		if ($table != NULL)
		{
			$this->_table_name = $table;
		}
		$limit = (int) $limit;
		$this->set_published();
		$this->db->limit($limit);
		return parent::get();
	}

}
// END pages_m Class

/* End of file pages_m.php */
/* Location: ./Application/modules/pages/models/pages_m.php */
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
class Post_m extends MY_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	protected $_table_name = 'tbl_post';
	protected $_order_by   = 'id';

	public function __construct()
	{
		parent::__construct();
	}

	public function _get($id = NULL, $single = FALSE)
	{
		$this->db->select('tbl_post.*, CONCAT (users.first_name) as author, tbl_post_category.name as categories, tbl_post_category.slug as category_slug');
		$this->db->join('users','users.id = tbl_post.createdby','left');
		$this->db->join('tbl_post_category','tbl_post_category.id = tbl_post.catid','left');
		if ($id != NULL && $single == TRUE)
		{
			$this->db->where('tbl_post.id', $id);
			return $this->db->get($this->_table_name)->row();
		}
		else
		{
			return parent::get($id, $single);
		}

	}

	public function _get_by($where, $single = FALSE)
	{
		$this->db->select('tbl_post.*, CONCAT (users.first_name) as author, tbl_post_category.name as categories');
		$this->db->join('users','users.id = tbl_post.createdby','left');
		$this->db->join('tbl_post_category','tbl_post_category.id = tbl_post.catid','left');

		return parent::get_by($where, $single);
	}

	public function _get_recent( $limit = 3, $id, $table = NULL)
	{
		if ($table != NULL)
		{
			$this->_table_name = $table;
		}
		$limit = (int) $limit;
		$this->db->where(array('catid'=> $id, 'status'=> '1'));
		$this->db->limit($limit);
		return parent::get();
	}
}
// END post_m Class

/* End of file post_m.php */
/* Location: ./Application/modules/post/models/post_m.php */
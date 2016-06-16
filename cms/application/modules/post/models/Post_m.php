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
	public $_rules         = array(
        'title'=> array(
			'field'=>'title',
			'label'=>'Title',
			'rules'=>'trim|required|max_length[100]'),

		'slug'=> array(
			'field'=>'slug',
			'label'=>'Slug',
			'rules'=>'trim|required|max_length[100]|url_title|callback__unique_slug'),

		'status'=> array(
			'field'=>'status',
			'label'=>'Status',
			'rules'=>'trim|is_natural'),

		'intro'=> array(
			'field'=>'intro',
			'label'=>'Intro',
			'rules'=>'trim|max_length[300]'),

		'body'=> array(
			'field'=>'body',
			'label'=>'Body',
			'rules'=>'required'),

		'catid'=> array(
			'field'=>'catid',
			'label'=>'Category',
			'rules'=>'trim|is_natural'),

		'tags'=> array(
			'field'=>'tags',
			'label'=>'Tags',
			'rules'=>'trim'),

		'comment'=> array(
			'field'=>'comment',
			'label'=>'Comment',
			'rules'=>'trim|is_natural'),
		);

	public function __construct()
	{
		parent::__construct();
	}

	public function _get($id = NULL, $single = FALSE)
	{
		$this->db->select('tbl_post.*, CONCAT (users.first_name) as author, tbl_post_category.name as categories');
		$this->db->join('users','users.id = tbl_post.createdby','left');
		$this->db->join('tbl_post_category','tbl_post_category.id = tbl_post.catid','left');
		return parent::get($id, $single);
	}

	public function _save_category($data, $id = NULL)
	{
		$this->_table_name = 'tbl_post_category';
		return parent::save($data, $id);
	}

	 public function _get_category($id = NULL, $single = FALSE)
	{
		$this->_table_name = 'tbl_post_category';
		$this->db->select('id, name');
		return parent::get($id, $single);
	}
	public function _new()
	{
		$post             = new stdClass();
		$post->catid      = '';
		$post->title      = '';
		$post->slug       = '';
		$post->tags       = '';
		$post->intro      = '';
		$post->body       = '';
		$post->category   = '';
		$post->images     = '';
		$post->created    = '';
		$post->createdby  = '';
		$post->modified   = '';
		$post->modifiedby = '';
		$post->status     = '';
		$post->pubdate    = '';
		$post->views      = '';
		$post->comment    = '';

		return $post;
	}

	public function set_published()
	{
		$this->db->where('created <=', gmdate('Y-m-d', time()+60*60*7));
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

	public function get_latest($limit = 6)
	{
		$this->db->select('tbl_post.id, tbl_post.title, tbl_post.intro, tbl_post.images, tbl_post.created, CONCAT (users.first_name) as author, tbl_post_category.name as category, UNIX_TIMESTAMP(tbl_post.created) as created_timestamp');
		$this->db->join('users','users.id = tbl_post.createdby','left');
		$this->db->join('tbl_post_category','tbl_post_category.id = tbl_post.catid','left');

		$this->db->limit($limit);
		$this->db->order_by($this->_order_by, 'DESC');
		return parent::get();
	}
}
// END post_m Class

/* End of file post_m.php */
/* Location: ./Application/modules/post/models/post_m.php */
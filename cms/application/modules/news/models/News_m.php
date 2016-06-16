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
class News_m extends MY_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	protected $_table_name = 'tbl_news';
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
		$this->db->select('tbl_news.*, CONCAT (users.first_name) as author, tbl_news_category.name as categories');
		$this->db->join('users','users.id = tbl_news.createdby','left');
		$this->db->join('tbl_news_category','tbl_news_category.id = tbl_news.catid','left');
		return parent::get($id, $single);
	}

	public function _save_category($data, $id = NULL)
	{
		$this->_table_name = 'tbl_news_category';
		return parent::save($data, $id);
	}

	 public function _get_category($id = NULL, $single = FALSE)
	{
		$this->_table_name = 'tbl_news_category';
		$this->db->select('id, name');
		return parent::get($id, $single);
	}
	public function _new()
	{
		$news             = new stdClass();
		$news->catid      = '';
		$news->title      = '';
		$news->slug       = '';
		$news->tags       = '';
		$news->intro      = '';
		$news->body       = '';
		$news->category   = '';
		$news->images     = '';
		$news->created    = '';
		$news->createdby  = '';
		$news->modified   = '';
		$news->modifiedby = '';
		$news->status     = '';
		$news->pubdate    = '';
		$news->views      = '';
		$news->comment    = '';

		return $news;
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
}
// END News_m Class

/* End of file news_m.php */
/* Location: ./Application/modules/news/models/news_m.php */
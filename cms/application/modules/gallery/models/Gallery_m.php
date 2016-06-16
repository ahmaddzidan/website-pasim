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

		'caption'=> array(
			'field'=>'caption',
			'label'=>'caption',
			'rules'=>'trim|max_length[500]'),
		);

	public $_rules_album   = array(

        'title'=> array(
			'field'=>'title',
			'label'=>'Title',
			'rules'=>'trim|required|max_length[100]'),

		'slug'=> array(
			'field'=>'slug',
			'label'=>'Slug',
			'rules'=>'trim|required|max_length[100]|url_title|callback__unique_slug'),

		'description'=> array(
			'field'=>'caption',
			'label'=>'caption',
			'rules'=>'trim|max_length[300]'),
		);

	public function __construct()
	{
		parent::__construct();
	}

	public function _get($id = NULL, $single = FALSE)
	{
		$this->db->select('tbl_gallery.*, CONCAT (users.first_name) as author, tbl_gallery_category.name as category');
		$this->db->join('users','users.id = tbl_gallery.createdby','left');
		$this->db->join('tbl_gallery_category','tbl_gallery_category.id = tbl_gallery.catid','left');
		return parent::get($id, $single);
	}


	public function _new()
	{
		$gallery             = new stdClass();
		$gallery->catid      = '1';
		$gallery->title      = '';
		$gallery->slug       = '';
		$gallery->caption    = '';
		$gallery->images     = '';
		$gallery->created    = '';
		$gallery->createdby  = '';
		$gallery->modified   = '';
		$gallery->modifiedby = '';
		$gallery->status     = '';
		$gallery->pubdate    = '';

		return $gallery;


	}

	public function _new_album()
	{
		$album              = new stdClass();
		$album->name        = '';
		$album->description = '';
		$album->slug        = '';
		$album->created     = '';
		$album->createdby   = '';
		$album->modified    = '';
		$album->modifiedby  = '';


		return $album;
	}

	public function set_published()
	{
		$this->db->where('created <=', gmdate('Y-m-d', time()+60*60*7));
	}

	public function _get_category($id = NULL, $single = FALSE)
	{
		$this->_table_name = 'tbl_gallery_category';
		$this->db->select('tbl_gallery_category.*, CONCAT (users.first_name) as author');
		$this->db->join('users','users.id = tbl_gallery_category.createdby','left');
		return parent::get($id, $single);
	}

	public function _get_album($id = NULL, $single = FALSE)
	{
		$this->_table_name = 'tbl_gallery_category';
		return parent::get($id, $single);
	}

	public function _get_albums($id = NULL)
	{
		$this->db->select('tbl_gallery.*, CONCAT (users.first_name) as author, tbl_gallery_category.name as category');
		$this->db->join('users','users.id = tbl_gallery.createdby','left');
		$this->db->join('tbl_gallery_category','tbl_gallery_category.id = tbl_gallery.catid','left');
		return parent::get_by(array('catid'=>$id));
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
// END gallery_m Class

/* End of file gallery_m.php */
/* Location: ./Application/modules/gallery/models/gallery_m.php */
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
class Pages_m extends MY_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	protected $_table_name = 'tbl_pages';
	protected $_order_by   = 'parent_id, order';
	public $_rules         = array(
        'title'=> array(
			'field'=>'title',
			'label'=>'Title',
			'rules'=>'trim|required|max_length[100]'),

		'template_id'=> array(
			'field'=>'template_id',
			'label'=>'Template',
			'rules'=>'trim|required'),

		'parent_id'=> array(
			'field'=>'parent_id',
			'label'=>'Parent',
			'rules'=>'trim|intval'),

		'slug'=> array(
			'field'=>'slug',
			'label'=>'Slug',
			'rules'=>'trim|required|max_length[100]|url_title|callback__unique_slug'),
		'body'=> array(
			'field'=>'body',
			'label'=>'Body',
			'rules'=>'trim|required')
		);
	public function __construct()
	{
		parent::__construct();
	}

	public function _new()
	{
		$pages             = new stdClass();
		$pages->title      = '';
		$pages->parent_id      = '';
		$pages->slug       = '';
		$pages->body       = '';
		$pages->template_id   = '';
		$pages->created    = '';
		$pages->createdby  = '';
		$pages->modified   = '';
		$pages->modifiedby = '';
		$pages->status     = '';
		$pages->pubdate    = '';

		return $pages;
	}

	public function _get($id = NULL, $single = FALSE)
	{
		$this->db->select('tbl_pages.*, CONCAT (users.first_name) as author');
		$this->db->join('users','users.id = tbl_pages.createdby','left');
		$this->db->join('tbl_pages as p','tbl_pages.parent_id=p.id','left');
		return parent::get($id, $single);
	}

	public function _get_order($id = NULL, $single = FALSE)
	{
		$this->db->select('tbl_pages.id, tbl_pages.parent_id, tbl_pages.order');
		$this->db->join('tbl_pages as p','tbl_pages.parent_id=p.id','left');
		return parent::get($id, $single);
	}

	public function _get_parent($id = NULL, $single = FALSE)
	{
		// Fetch no no parent pages

		$this->db->select('id, title');
		$pages = parent::get();

		// Return key => value pair array
		$array = array(0=>' -- No Parent --');
		if (count($pages)) {
			foreach($pages as $page)
			{
				$array[$page->id] = $page->title;
			}
		}
		return $array;
	}

	public function _get_template($id = NULL, $single = FALSE)
	{
		$this->_order_by = 'id';
		$this->_table_name = 'tbl_pages_template';
		$this->db->select('id, name');

		$template = parent::get();

		$array = array(0 =>' -- Default --');
		if (count($template)) {
			foreach($template as $item)
			{
				$array[$item->id] = $item->name;
			}
		}
		return $array;
	}

	public function set_published()
	{
		$this->db->where('created <=', gmdate('Y-m-d', time()+60*60*7));
	}

	public function _get_nested()
	{
		$this->db->select('id, parent_id, title, status, slug');
		$pages = $this->db->order_by($this->_order_by)->where('status',1);
		$pages = $this->db->get('tbl_pages')->result_array();

		$array = ordered_menu($pages,0);
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

	public function delete($id)
	{
		// Delete page
		parent::delete($id);

		// Reset parent ID for its children
		$this->db->set(array('parent_id'=>0))->where('parent_id',$id)->update($this->_table_name);
	}

	public function save_order($pages)
	{

		if (isset($pages)) {
			foreach ($pages as $order => $page) {
				if($page['id'] !='')
				{
					if (isset($page['children']))
					{
						foreach ($page['children'] as $key => $value) {
							$data = array('parent_id'=>$page['id'],'order'=>$key);
							$this->db->set($data)->where($this->_primary_key,$value['id'])->update($this->_table_name);
						}
					}
					else
					{
						$data = array('parent_id'=> 0,'order'=>$order);
						$this->db->set($data)->where($this->_primary_key,$page['id'])->update($this->_table_name);
					}
				}
			}
		}
	}
}
// END pages_m Class

/* End of file pages_m.php */
/* Location: ./Application/modules/pages/models/pages_m.php */
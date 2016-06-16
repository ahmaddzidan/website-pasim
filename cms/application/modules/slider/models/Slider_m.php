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
class slider_m extends MY_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	protected $_table_name = 'tbl_slider';
	protected $_order_by   = 'id';
	public $_rules         = array(

        'title'=> array(
			'field'=>'title',
			'label'=>'Title',
			'rules'=>'trim|required|max_length[100]'),

		'url'=> array(
			'field'=>'url',
			'label'=>'url',
			'rules'=>'trim|required|max_length[100]'),

		'status'=> array(
			'field'=>'status',
			'label'=>'Status',
			'rules'=>'trim|is_natural'),

		'caption'=> array(
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
		$this->db->select('tbl_slider.*, CONCAT (users.first_name) as author');
		$this->db->join('users','users.id = tbl_slider.createdby','left');
		return parent::get($id, $single);
	}


	public function _new()
	{
		$slider             = new stdClass();
		$slider->title      = '';
		$slider->caption    = '';
		$slider->url        = '';
		$slider->images     = '';
		$slider->bgcolor    = 'rgba(255,255,255,0)';
		$slider->color      = '#000000';
		$slider->created    = '';
		$slider->createdby  = '';
		$slider->modified   = '';
		$slider->modifiedby = '';
		$slider->status     = '';
		$slider->pubdate    = '';

		return $slider;
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
// END slider_m Class

/* End of file slider_m.php */
/* Location: ./Application/modules/slider/models/slider_m.php */
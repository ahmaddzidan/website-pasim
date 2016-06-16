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
class Event_m extends MY_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	protected $_table_name = 'tbl_event';
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

		'location'=> array(
			'field'=>'location',
			'label'=>'Location',
			'rules'=>'trim'),

		'radius'=> array(
			'field'=>'radius',
			'label'=>'Radius',
			'rules'=>'trim|max_length[300]'),


		'latitude'=> array(
			'field'=>'latitude',
			'label'=>'Latitude',
			'rules'=>'max_length[300]'),

		'longitude'=> array(
			'field'=>'longitude',
			'label'=>'Longitude',
			'rules'=>'max_length[300]'),

		'contact'=> array(
			'field'=>'contact',
			'label'=>'Contact',
			'rules'=>'trim|max_length[300]'),

		'commite'=> array(
			'field'=>'commite',
			'label'=>'Commite',
			'rules'=>'trim|max_length[300]'),
		'website'=> array(
			'field'=>'website',
			'label'=>'Commite',
			'rules'=>'max_length[300]'),

		'body'=> array(
			'field'=>'body',
			'label'=>'Body',
			'rules'=>'required'),

		'tags'=> array(
			'field'=>'tags',
			'label'=>'Tags',
			'rules'=>'trim')
		);

	public function __construct()
	{
		parent::__construct();
	}

	public function _get($id = NULL, $single = FALSE)
	{
		$this->db->select('tbl_event.*, CONCAT (users.first_name) as author');
		$this->db->join('users','users.id = tbl_event.createdby','left');
		return parent::get($id, $single);
	}

	public function _new()
	{
		$event               = new stdClass();
		$event->title        = '';
		$event->slug         = '';
		$event->contact      = '';
		$event->commite      = '';
		$event->location     = '';
		$event->map_location = 'Gg. Kebonpisang, Kb. Pisang, Sumur Bandung, Kota Bandung, Jawa Barat, Indonesia';
		$event->latitude     = '-6.917463899999999';
		$event->longitude    = '107.61912280000001';
		$event->radius       = '200';
		$event->website      = '';
		$event->tags         = '';
		$event->start_date   = '';
		$event->end_date     = '';
		$event->intro        = '';
		$event->body         = '';
		$event->images       = '';
		$event->created      = '';
		$event->createdby    = '';
		$event->modified     = '';
		$event->modifiedby   = '';
		$event->status       = '';
		$event->pubdate      = '';
		$event->views        = '';

		return $event;
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
// END Event_m Class

/* End of file event_m.php */
/* Location: ./Application/modules/event/models/event_m.php */
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
class Message_m extends MY_Model {

	/**
	 * Constructor
	 *
	 * @access public
	 */
	protected $_table_name = 'tbl_message';
	protected $_order_by   = 'id';
	public $_rules         = array(
        'subject'=> array(
			'field'=>'subject',
			'label'=>'Subject',
			'rules'=>'trim|required|max_length[100]'),

		'name'=> array(
			'field'=>'name',
			'label'=>'Name',
			'rules'=>'trim'),

		'email'=> array(
			'field'=>'email',
			'label'=>'Email',
			'rules'=>'trim|max_length[300]'),

		'body'=> array(
			'field'=>'body',
			'label'=>'Body',
			'rules'=>'required'),
		);

	public function __construct()
	{
		parent::__construct();
	}

	public function _new()
	{
		$message             = new stdClass();
		$message->subject    = '';
		$message->name       = '';
		$message->website    = '';
		$message->company    = '';
		$message->mobile     = '';
		$message->body       = '';
		$message->email      = '';
		$message->created    = '';
		$message->createdby  = '';
		$message->modified   = '';
		$message->modifiedby = '';
		$message->status     = '';
		$message->type       = '';
		$message->read       = '';
		$message->reply      = '';

		return $message;
	}

	public function get_sent($id = NULL, $single = FALSE)
	{
		$this->db->select('tbl_message.*, CONCAT (users.first_name) as sender');
		$this->db->join('users','users.id = tbl_message.createdby','left');
		$this->db->where(array('type'=>'out'));
		return parent::get($id, $single);
	}

	public function get_draft($id = NULL, $single = FALSE)
	{
		$this->db->select('tbl_message.*, CONCAT (users.first_name) as sender');
		$this->db->join('users','users.id = tbl_message.createdby','left');
		$this->db->where(array('type'=>'draft'));
		return parent::get($id, $single);
	}

	public function set_published()
	{
		$this->db->where('created <=', gmdate('Y-m-d', time()+60*60*7));
	}

	public function get_recent( $table = NULL ,$limit =4)
	{
		if ($table != NULL)
		{
			$this->_table_name = $table;
		}
		$this->db->where(array('type'=>'in'));
		$limit = (int) $limit;
		$this->set_published();
		$this->db->limit($limit);
		return parent::get();
	}


}
// END message_m Class

/* End of file message_m.php */
/* Location: ./Application/modules/message/models/message_m.php */
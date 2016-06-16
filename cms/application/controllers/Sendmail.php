<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class Sendmail extends MY_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->helper('mail');
	}

	function index()
	{
		$mailData = array();

		$mailData['to']        = 'dzidanthea@gmail.com';
		$mailData['to_name']   = 'Dzidan';
		$mailData['from']      = 'asanusi007@gmail.com';
		$mailData['from_name'] = 'Ahmad Sanusi';
		$mailData['subject']   = 'Test 2?';
		$mailData['message']   = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum';


		// send_mail($mailData);

	}
}
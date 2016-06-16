<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('message/message_m');
		$this->load->model('post/post_m');
	}
	public function index()
	{
		$this->data['subtitle'] = $this->lang->line('dashboard_heading');

		$this->data['inbox'] = $this->message_m->get_recent();
		$this->data['posts'] = $this->post_m->get_latest();

		$this->parser->parse('dashboard.html',$this->data);
	}
}

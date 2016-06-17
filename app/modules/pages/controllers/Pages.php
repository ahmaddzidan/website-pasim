<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends Frontend_Controller {

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
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	}
	public function index()
	{
		$uri = (($this->uri->segment(1) == '') ? '/' : $this->uri->segment(1));
		$this->data['page'] = $this->pages_m->_get_page(array('slug'=> $uri),TRUE);

		count($this->data['page']) || redirect('pages/error_404');
		$method = '_' . strtolower($this->data['page']->template);

		if(method_exists($this, $method))
		{
			$this->$method();
		}
		else
		{
			log_message('error', 'Could not load template ' . $method .' in file ' . __FILE__ . ' at line ' . __LINE__);
            show_error('Could not load template ' . $method);
		}

		$this->data['subnavigation'] = $this->pages_m->_get_subnested($this->data['page']->parent_id);
        $this->parser->parse($this->data['page']->template_file, $this->data);
	}

	public function _default()
	{

	}
	public function _home()
	{
		$this->load->model(array('slider/slider_m','gallery/gallery_m'));
		$this->data['slider'] = $this->slider_m->get_by(array('status'=> '1'));
		$this->data['gallery'] = $this->gallery_m->get_by(array('status'=> '1'));
	}
	public function _submenu()
	{

	}

	public function _submenu_cover()
	{

	}

	public function _gallery()
	{
		$this->load->model('gallery/gallery_m');
		$this->data['gallery'] = $this->gallery_m->_get();
	}

	public function _registration()
	{

	}

	public function _contact()
	{
		$this->load->model('message/message_m');
		$this->load->helper('captcha');

		$message = $this->message_m->_new();
		$rules   = $this->message_m->_rules;

		$this->form_validation->set_rules($rules);

		$userCaptcha = $this->input->post('captcha');
		$word        = $this->session->userdata('captchaWord');
		$stat 	= strcmp(strtolower($userCaptcha),strtolower($word));

		if ($this->form_validation->run($this) == TRUE && $stat == 0)
		{

			$this->session->unset_userdata('captchaWord');

			$_POST['read']      = '0';
			$_POST['type']      = 'in';
			$_POST['reply']      = '0';
			$_POST['ip']        = $this->input->ip_address();
			$_POST['ua']        = $this->input->user_agent();
			$_POST['created']   = gmdate('Y-m-d H:i:s',time()+60*60*7);
			$_POST['createdby'] = '5'; // 5 untuk tamu

			$data  = $this->message_m->array_form_post(array('subject','name','website','email','company','mobile','body','type','read','reply','replyid','ip','ua','created','createdby'));

			$this->message_m->save($data);

			$this->session->set_flashdata('message', '<div class="alert alert-success"> <b>Pesan berhasil dikirim! </b>, tunggu beberapa saat untuk mendaptkan balasan. terimakasih atas partisipasinya.</div>');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			redirect('kontak');
		}elseif ($this->form_validation->run($this) == TRUE && $stat !== 0) {
			# code...
			$this->session->set_flashdata('message', '<div class="alert alert-danger"> <b>Captcha Salah! </b>, Silahkan coba lagi.</div>');
		}


		$vals = array(
            'img_path' => './captcha/',
            'img_url' => base_url() . 'captcha/',
            'font_path'  => './themes/default/fonts/raleway-bold-webfont.ttf',
            'img_width' => '150',
            'img_height' => 33,
            'expiration' => 7200
            );

		$this->data['captcha_val'] = create_captcha($vals);
		$this->session->set_userdata('captchaWord', $this->data['captcha_val']['word']);

		$this->data['name'] = array(
			'name'  => 'name',
			'id'    => 'name',
			'type'  => 'text',
			'title' => 'Please fill in first name min. 3 characters',
			'pattern' => '^.{3,}$',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> 'Nama',
			'value' => $this->form_validation->set_value('name', $message->name)
		);

		$this->data['subject'] = array(
			'name'  => 'subject',
			'id'    => 'subject',
			'type'  => 'text',
			'title' => 'Please fill in first name min. 3 characters',
			'pattern' => '^.{3,}$',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> 'Subjek',
			'value' => $this->form_validation->set_value('subject', $message->subject)
		);

		$this->data['mobile'] = array(
			'name'  => 'mobile',
			'id'    => 'mobile',
			'type'  => 'text',
			'title' => 'Please fill in first name min. 3 characters',
			'pattern' => '^.{3,}$',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> 'No. Handphone',
			'value' => $this->form_validation->set_value('mobile', $message->mobile)
		);

		$this->data['email'] = array(
			'name'  => 'email',
			'id'    => 'email',
			'type'  => 'email',
			'title' => 'Email address is not valid!',
			'pattern' => '^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> 'Valid email address',
			'value' => $this->form_validation->set_value('email', $message->email)
		);

		$this->data['body'] = array(
			'name'  => 'body',
			'id'    => 'editor-full',
			'rows' => '7',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('body', $message->body),
		);

		$this->data['captcha'] = array(
			'name'  => 'captcha',
			'id'    => 'captcha',
			'type'  => 'text',
			'title' => 'Captcha',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> 'Captcha',
			'value' => $this->form_validation->set_value('captcha', $message->captcha)
		);

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	}

	public function _location()
	{

	}

	public function error_404()
	{
		$this->output->set_status_header('404');
        $this->data['content'] = 'error_404'; // View name
        $this->parser->parse('errors/error_404', $this->data);
	}

	public function error_503()
	{
		$this->output->set_status_header('503');
        $this->data['content'] = 'error_503'; // View name
        $this->parser->parse('errors/error_503', $this->data);
	}
}

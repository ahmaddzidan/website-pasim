<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/message
	 *	- or -
	 * 		http://example.com/index.php/message/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/message/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('message_m');
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	}

	public function index()
	{
		$this->data['subtitle'] = $this->lang->line('message_heading');
		$this->data['msg_data'] = $this->message_m->get_by(array('type'=>'in'));

		$this->parser->parse('message_list',$this->data);

	}

	public function detail($id = NULL)
	{
		$this->data['subtitle'] = $this->lang->line('message_heading');
		$this->data['msg_data'] = $this->message_m->get($id);
		$this->data['msg_reply'] = $this->message_m->get_by(array('type'=>'out','replyid'=> $id));

		$this->parser->parse('message_detail',$this->data);
	}

	public function all()
	{
		$this->data['subtitle'] = $this->lang->line('message_all_heading');
		$this->data['msg_data'] = $this->message_m->get();

		$this->parser->parse('message_all',$this->data);

	}

	public function sent()
	{
		$this->data['subtitle'] = $this->lang->line('message_sent_heading');
		$this->data['msg_data'] = $this->message_m->get_sent();

		$this->parser->parse('message_sent',$this->data);
	}

	public function draft()
	{
		$this->data['subtitle'] = $this->lang->line('message_draft_heading');
		$this->data['msg_data'] = $this->message_m->get_draft();

		$this->parser->parse('message_draft',$this->data);
	}

	public function create($id = NULL)
	{
		$userdata =  $this->ion_auth->user()->row();

		if ($id == NULL)
		{
			$this->data['subtitle'] = $this->lang->line('message_create_heading');;
			$message  = $this->message_m->_new();
			$_POST['replyid']   = '';
		}
		else
		{
			$this->data['subtitle'] = ((isset($this->data['segment_2']) && $this->data['segment_2'] == 'edit-message') ? 'Edit Message': 'Reply Message');
			$message  = $this->message_m->get($id);
			$_POST['replyid']   = $message->id;
		}

		$rules = $this->message_m->_rules;

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run($this) == TRUE)
		{
			$this->load->helper('mail');

			$_POST['website']   = $this->data['site_setting']->domain;
			$_POST['company']   = $userdata->company;
			$_POST['mobile']    = $userdata->phone;
			$_POST['read']      = '1';
			$_POST['type']      = (isset($_POST['type']) && !empty($_POST['type']) ? $_POST['type'] : 'out');
			$_POST['ip']        = $this->input->ip_address();
			$_POST['ua']        = $this->input->user_agent();
			$_POST['created']   = gmdate('Y-m-d H:i:s',time()+60*60*7);
			$_POST['createdby'] = $userdata->id;

			$data = array(
					'to'        => $_POST['email'], // address
					'to_name'   => $_POST['name'], // name's of receiver
					'from'      => $userdata->email, // sender's address
					'from_name' => $userdata->first_name, // sender's name
					'subject'   => $_POST['subject'],
					'message'   => $_POST['body']
				);
			if($_POST['type'] == 'out')
			{
				if (send_mail($data))
				{
					$_POST['reply']     = '1'; // Mark as Sent

					if ($id != NULL)
					{
						$this->message_m->save(array('reply'=>'1'), $id); // Mark as Replied
					}
				}
				else
				{
					$_POST['reply']     = '0'; // Mark as Failed
				}
			}
			else
			{
				$_POST['reply']     = '1'; // Mark as draft if mail type is draft
			}

	    	$data  = $this->message_m->array_form_post(array('subject','name','website','email','company','mobile','body','type','read','reply','replyid','ip','ua','created','createdby'));

			$this->message_m->save($data);

			$this->session->set_flashdata('message', '<p>'. $this->lang->line('setting_general_success_message') .'</p>');
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			if ($this->data['segment_2'] == 'edit-message' or $_POST['type'] == 'draft')
			{
				redirect('message/draft');
			}
			else if($this->data['segment_2'] == 'reply-message')
			{
				redirect('message/sent');
			}
			else
			{
				redirect('message/inbox');
			}
		}

		$this->data['name'] = array(
			'name'        => 'name',
			'id'          => 'name',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control',
			'placeholder' => $this->lang->line('message_receiver_name'),
			'value'       => $this->form_validation->set_value('name', $message->name),
		);

		$this->data['email'] = array(
			'name'        => 'email',
			'id'          => 'email',
			'type'        => 'email',
			'pattern'	  => '^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$',
			'required'    => 'required',
			'class'       => 'form-control',
			'placeholder' => $this->lang->line('message_to'),
			'value'       => $this->form_validation->set_value('email', $message->email),
		);

		$this->data['subject'] = array(
			'name'        => 'subject',
			'id'          => 'subject',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control bg-slate',
			'placeholder' => $this->lang->line('message_subject_placeholder'),
			'value'       => $this->form_validation->set_value('subject', $message->subject),
		);

		if ($id != NULL && $this->data['segment_2'] != 'edit-message')
		{
			$this->data['name']['readonly']    = 'readonly';
			$this->data['email']['readonly']   = 'readonly';
		}

		$this->data['body'] = array(
			'name'        => 'body',
			'id'          => 'editor-full',
			'required'    => 'required',
			'class'       => 'form-control',
			'placeholder' => $this->lang->line('message_body'),
			'value'		  => ((isset($this->data['segment_2']) && $this->data['segment_2'] == 'edit-message') ? $this->form_validation->set_value('body',$message->body) : $this->form_validation->set_value('body',''))
		);

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->parser->parse('message_create',$this->data);

	}

	public function send($id = NULL)
	{
		$this->load->helper('mail');
		$message  = $this->message_m->get($id);
		$userdata = $this->ion_auth->user()->row();

		$data = array(
					'to'        => $message->email, // address
					'to_name'   => $message->name, // name's of receiver
					'from'      => $userdata->email, // sender's address
					'from_name' => $userdata->first_name, // sender's name
					'subject'   => $message->subject,
					'message'   => $message->body
				);

		if (send_mail($data))
		{

			if ($id != NULL)
			{
				$this->message_m->save(array('reply'=>'1'), $message->replyid); // Mark as Replied
				$this->message_m->save(array('type'=>'out'), $id); // Mark as Replied
				$this->session->set_flashdata('message', '<p>'. $this->lang->line('message_send_success') .'</p>');
			}
		}
		else
		{
				$this->session->set_flashdata('message', '<p>'. $this->lang->line('message_send_failed') .'</p>');
		}
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		redirect('message/sent');
	}

	public function delete($id)
	{

		if ($this->message_m->delete($id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_delete_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_delete_message') .'</p>');
		}

		redirect('message');
	}

	public function set_read($status,$id)
	{
		$data['read'] = (($status == 'read') ? '1' : '0');

		if ($this->message_m->save($data, $id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_message') .'</p>');
		}

		redirect('message');
	}

	public function bulk()
	{
		// Fetch data from post
		$id  = $this->input->post('id');
		$act = $this->input->post('action');

		// Action id 0 = unpublish all, 1 = Publish all, 2 = Delete all

		if (count($act) && $act == 0)
		{
			$data['read']  = 1; // for unpublish and set status = 0
			$this->message_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 1)
		{
			$data['read']  = 0; // for publish and set status = 1
			$this->message_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 2)
		{
			$this->message_m->delete_all($id);
		}
	}


}

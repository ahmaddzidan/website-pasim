<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pages extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/pages
	 *	- or -
	 * 		http://example.com/index.php/pages/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/pages/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pages_m');
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	}

	public function index()
	{

		$this->data['subtitle'] = 'Pages';
		$this->data['pages'] = $this->pages_m->_get();

		$this->parser->parse('pages_list',$this->data);


	}

	public function order()
	{
		$this->data['subtitle'] = 'Menu Order';
		$this->data['sortable'] = TRUE;

		$this->data['pages']   = $this->pages_m->_get_nested();

		$this->parser->parse('pages_order',$this->data);
	}

	public function order_ajax()
	{
		if (isset($_POST['sortable']))
		{
			$oSortable = json_decode($_POST['sortable'],true);
			$this->pages_m->save_order($oSortable);
		}

		echo get_ol($this->pages_m->_get_nested());
	}

	public function edit($id = NULL)
	{
		if ($id == NULL)
		{
			$this->data['subtitle'] = 'Create Page';
			$pages                   = $this->pages_m->_new();
			$pages->images           = 'placeholder.jpg';

			$_POST['created']    = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['createdby']  = $this->data['session_data']->id;

		}
		else
		{
			$this->data['subtitle'] = 'Edit Page';
			$pages = $this->pages_m->get($id);

			$_POST['created']    = $pages->created;
            $_POST['createdby']  = $pages->createdby;

			$_POST['modified']   = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['modifiedby'] = $this->data['session_data']->id;

		}

		$rules = $this->pages_m->_rules;

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run($this) == TRUE)
		{
	    	$data  = $this->pages_m->array_form_post(array('parent_id','title','slug','body','template_id','created','createdby','modified','modifiedby','status','pubdate'));

			$this->pages_m->save($data, $id);

			$this->session->set_flashdata('message', '<p>'. $this->lang->line('setting_general_success_message') .'</p>');

			redirect('pages');
		}

		$this->data['pages_title'] = array(
			'name'  => 'title',
			'id'    => 'title',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> $this->lang->line('pages_title_placeholder'),
			'value' => $this->form_validation->set_value('title', $pages->title),
		);

		$this->data['parent_id'] = array(
			'name'  => 'parent_id',
			'id'    => 'parent',
			'class' => 'select2-search',
		);
		$this->data['parent_option'] = $this->pages_m->_get_parent();

		$this->data['parent_selected'] = $pages->parent_id;

		$this->data['template_id'] = array(
			'name'  => 'template_id',
			'id'    => 'template',
			'class' => 'select2-search',
		);
		$this->data['template_option'] = $this->pages_m->_get_template();
		$this->data['template_selected'] = $pages->template_id;

		$this->data['slug'] = array(
			'name'  => 'slug',
			'id'    => 'slug',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control bg-slate',
			'placeholder'=> $this->lang->line('pages_slug'),
			'value' => $this->form_validation->set_value('slug', $pages->slug),
		);

		$this->data['body'] = array(
			'name'  => 'body',
			'id'    => 'editor-full',
			'required' => 'required',
			'value' => $this->form_validation->set_value('body', $pages->body),
		);

		$this->data['publication_date'] = array(
			'name'  => 'pubdate',
			'id'    => 'pubdate',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control datetime-picker',
			'placeholder'=> $this->lang->line('pages_publish_date'),
			'value' =>  $pages->pubdate,
		);

		$this->data['status'] = array(
			'name'  => 'status',
			'id'    => 'status',
			'class' => 'select2',
		);

		$this->data['status_option'] = array('0' => $this->lang->line('general_draft'), '1' => $this->lang->line('general_publish'));
		$this->data['status_selected'] = $pages->status;

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->parser->parse('pages_edit',$this->data);
	}

	public function delete($id)
	{
		$img = $this->pages_m->get($id);

		if ($img->images)
		{
			$filename = './themes/default/uploads/pages/' . $img->images;
			if (file_exists($filename) && $img->images != 'placeholder.jpg') {
			    unlink($filename);
			}
		}

		if ($this->pages_m->delete($id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_delete_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_delete_message') .'</p>');
		}

		redirect('pages');
	}

	public function publish( $status,$id)
	{
		$data['status'] = (($status == 'active') ? '1' : '0');

		if ($this->pages_m->save($data, $id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_message') .'</p>');
		}

		redirect('pages');
	}

	public function bulk()
	{
		// Fetch data from post
		$id  = $this->input->post('id');
		$act = $this->input->post('action');

		// Action id 0 = unpublish all, 1 = Publish all, 2 = Delete all

		if (count($act) && $act == 0)
		{
			$data['status']  = 0; // for unpublish and set status = 0
			$this->pages_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 1)
		{
			$data['status']  = 1; // for publish and set status = 1
			$this->pages_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 2)
		{
			$this->pages_m->delete_all($id);
		}
	}

	public function _unique_slug($str)
	{

		$id = $this->uri->segment(3);

		$page = $this->pages_m->check_exists('slug','slug',$id);

		if (count($page))
		{
			$this->form_validation->set_message('_unique_slug','%s should be unique');
			return FALSE;
		}
		return TRUE;
	}

}

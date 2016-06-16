<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/news
	 *	- or -
	 * 		http://example.com/index.php/news/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/news/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('news_m');
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	}

	public function index()
	{

		$this->data['subtitle'] = 'News';
		$this->data['news'] = $this->news_m->_get();

		$this->parser->parse('news_list',$this->data);

	}

	public function edit($id = NULL)
	{
		$img_folder = './themes/default/uploads/news/';

		if ($id == NULL)
		{
			$this->data['subtitle'] = 'Create News';
			$news                   = $this->news_m->_new();
			$news->images           = 'placeholder.jpg';

			$_POST['created']    = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['createdby']  = $this->data['session_data']->id;

		}
		else
		{
			$this->data['subtitle'] = 'Edit News';
			$news = $this->news_m->get($id);

			$_POST['created']    = $news->created;
            $_POST['createdby']  = $news->createdby;

			$_POST['modified']   = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['modifiedby'] = $this->data['session_data']->id;

		}

		$rules = $this->news_m->_rules;

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run($this) == TRUE)
		{

           	$fieldname = 'images';

            if (!empty($_FILES[$fieldname]['name']))
           	{

				$config['upload_path']          = $img_folder;
	            $config['allowed_types']        = 'gif|jpg|png';
	            $config['max_size']             = 1000;
	            $config['max_width']            = 1024;
	            $config['max_height']           = 768;
	            $config['overwrite']			= true;
	            $config['file_name']			= img_filename(9);

	            $this->load->library('upload', $config);

	            if (!$this->upload->do_upload($fieldname))
		        {
		           $this->session->set_flashdata('message', '<p>'. $this->upload->display_errors() .'</p>');
		        }
		        else
		        {
		            $_POST[$fieldname] = $this->upload->data('file_name');
		        }
		    }
		    else
		    {
		    	$_POST[$fieldname] = $news->images;
		    }

		    if (!empty($_POST[$fieldname])) {

		    	$data  = $this->news_m->array_form_post(array('catid','title','slug','intro','body','tags','images','created','createdby','modified','modifiedby','status','pubdate','comment'));

				$this->news_m->save($data, $id);

				$this->session->set_flashdata('message', '<p>'. $this->lang->line('setting_general_success_message') .'</p>');

				redirect('news');
		    }
		}


		$this->data['news_title'] = array(
			'name'  => 'title',
			'id'    => 'title',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> $this->lang->line('news_title_placeholder'),
			'value' => $this->form_validation->set_value('title', $news->title),
		);

		$this->data['slug'] = array(
			'name'  => 'slug',
			'id'    => 'slug',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control bg-slate',
			'placeholder'=> $this->lang->line('news_slug'),
			'value' => $this->form_validation->set_value('slug', $news->slug),
		);

		$this->data['intro'] = array(
			'name'  => 'intro',
			'required' => 'required',
			'class'    => 'form-control',
			'rows' => '5',
			'value' => $this->form_validation->set_value('intro', $news->intro),
		);

		$this->data['body'] = array(
			'name'  => 'body',
			'id'    => 'editor-full',
			'required' => 'required',
			'value' => $this->form_validation->set_value('body', $news->body),
		);

		$this->data['publication_date'] = array(
			'name'  => 'pubdate',
			'id'    => 'pubdate',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control datetime-picker',
			'placeholder'=> $this->lang->line('news_publish_date'),
			'value' =>  $news->pubdate,
		);

		$this->data['status'] = array(
			'name'  => 'status',
			'id'    => 'status',
			'class' => 'select2',
		);

		$this->data['status_option'] = array('0' => $this->lang->line('general_draft'), '1' => $this->lang->line('general_publish'));
		$this->data['status_selected'] = $news->status;

		$this->data['catid'] = array(
			'name'  => 'catid',
			'id'    => 'category',
			'class' => 'select2-search',
		);
		$this->data['cat_option'] = array();
		foreach ($this->news_m->_get_category() as $key => $value) {
		 	$this->data['cat_option'][$value->id] = $value->name;
		 };
		$this->data['cat_selected'] = $news->catid;

		$this->data['tags'] = array(
			'name'  => 'tags',
			'id'    => 'tags',
			'type'  => 'text',
			'data-role' => 'tagsinput',
			'class' => 'tagsinput-typeahead',
			'placeholder'=> $this->lang->line('general_tags'),
			'value' => $this->form_validation->set_value('tags', $news->tags),
		);

		$this->data['featured_images'] = array(
			'name'  => 'images',
			'id'    => 'featured-images',
			'type'  => 'file',
			'class' => 'file-input',
			'placeholder'=> ($news->images == 'placeholder.jpg' ? $this->lang->line('general_no_file') : $news->images) ,
			'data-src' => $this->config->item('base_url') . $img_folder . $news->images,
			'value' => $this->form_validation->set_value('images', $news->images),
		);

		$this->data['comment'] = array(
			'name'  => 'comment',
			'id'    => 'comment',
			'class' => 'select2',
		);

		$this->data['comment_option'] = array('0' => $this->lang->line('general_disable'), '1' => $this->lang->line('general_enable'));
		$this->data['comment_selected'] = $news->comment;

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->parser->parse('news_edit', $this->data);
	}

	public function delete($id)
	{
		$img = $this->news_m->get($id);

		if ($img->images)
		{
			$filename = './themes/default/uploads/news/' . $img->images;
			if (file_exists($filename) && $img->images != 'placeholder.jpg') {
			    unlink($filename);
			}
		}

		if ($this->news_m->delete($id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_delete_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_delete_message') .'</p>');
		}

		redirect('news');
	}

	public function create_category()
	{
		$_POST['created']   = gmdate('Y-m-d H:i:s',time()+60*60*7);
		$_POST['createdby'] = $this->data['session_data']->id;

		$data  = $this->news_m->array_form_post(array('name','description','slug','created','createdby'));

		if ($this->news_m->_save_category($data))
		{
			$msg = array("msg" => $this->lang->line('news_add_category_success'));
			echo json_encode($msg);
		}
		else
		{
			$msg = $this->lang->line('news_add_category_failed');
			echo json_encode($msg);
		}
	}

	public function publish( $status,$id)
	{
		$data['status'] = (($status == 'active') ? '1' : '0');

		if ($this->news_m->save($data, $id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_message') .'</p>');
		}

		redirect('news');
	}

	public function _unique_slug($str)
	{

		$id = $this->uri->segment(3);

		$page = $this->news_m->check_exists('slug','slug',$id);

		if (count($page))
		{
			$this->form_validation->set_message('_unique_slug','%s should be unique');
			return FALSE;
		}
		return TRUE;
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
			$this->news_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 1)
		{
			$data['status']  = 1; // for publish and set status = 1
			$this->news_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 2)
		{
			$this->news_m->delete_all($id);
		}
	}

}

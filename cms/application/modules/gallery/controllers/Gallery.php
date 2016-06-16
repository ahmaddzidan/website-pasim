<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/gallery
	 *	- or -
	 * 		http://example.com/index.php/gallery/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/gallery/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('gallery_m');
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	}

	public function index()
	{

		$this->data['subtitle'] = 'Gallery';
		$this->data['gallery'] = $this->gallery_m->_get();

		$this->parser->parse('gallery_list',$this->data);

	}

	public function album($id = NULL)
	{

		if ($id == NULL)
		{
			$this->data['subtitle'] = 'Gallery Album';
			$this->data['albums'] = $this->gallery_m->_get_category();

			$this->parser->parse('gallery_album',$this->data);
		}
		else
		{
			$this->data['subtitle'] = 'Gallery Album';
			$this->data['gallery'] = $this->gallery_m->_get_albums($id);

			$this->parser->parse('gallery_list',$this->data);
		}


	}

	public function add_album($id = NULL)
	{

		$this->data['subtitle'] = 'Add Gallery Album';
		$this->data['gallery'] = $this->gallery_m->_get_albums($id);

		if ($id == NULL)
		{
			$this->data['subtitle'] = 'Add Album';
			$album                   = $this->gallery_m->_new_album();

			$_POST['created']    = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['createdby']  = $this->data['session_data']->id;

		}
		else
		{
			$this->data['subtitle'] = 'Edit Album';
			$album = $this->gallery_m->_get_album($id);

			$_POST['created']    = $album->created;
            $_POST['createdby']  = $album->createdby;

			$_POST['modified']   = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['modifiedby'] = $this->data['session_data']->id;

		}

		$rules = $this->gallery_m->_rules_album;

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run($this) == TRUE)
		{

		}

		$this->data['album_title'] = array(
			'name'        => 'name',
			'id'          => 'title',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control',
			'placeholder' => $this->lang->line('gallery_title_placeholder'),
			'value'       => $this->form_validation->set_value('name', $album->name),
		);

		$this->data['slug'] = array(
			'name'        => 'slug',
			'id'          => 'slug',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control bg-slate',
			'placeholder' => $this->lang->line('gallery_slug'),
			'value'       => $this->form_validation->set_value('slug', $album->slug),
		);


		$this->data['description'] = array(
			'name'        => 'description',
			'class'       => 'form-control',
			'placeholder' => 'description',
			'rows'        => '5',
			'required'    => 'required',
			'value'       => $this->form_validation->set_value('description', $album->description),
		);

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->parser->parse('gallery_add_album', $this->data);


	}

	public function edit($id = NULL)
	{
		$img_folder = './themes/default/uploads/gallery/';

		if ($id == NULL)
		{
			$this->data['subtitle'] = 'Add Image';
			$gallery                   = $this->gallery_m->_new();
			$gallery->images           = 'placeholder.jpg';

			$_POST['created']    = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['createdby']  = $this->data['session_data']->id;

		}
		else
		{
			$this->data['subtitle'] = 'Edit Image';
			$gallery = $this->gallery_m->get($id);

			$_POST['created']    = $gallery->created;
            $_POST['createdby']  = $gallery->createdby;

			$_POST['modified']   = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['modifiedby'] = $this->data['session_data']->id;

		}

		$rules = $this->gallery_m->_rules;

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run($this) == TRUE)
		{

           	$fieldname = 'images';

            if (!empty($_FILES[$fieldname]['name']))
           	{

				$config['upload_path']          = $img_folder;
	            $config['allowed_types']        = 'gif|jpg|png';
	            $config['max_size']             = 5000;
	            $config['max_width']            = 2560;
	            $config['max_height']           = 1097;
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
		            $_POST['image_size'] = $this->upload->data('file_size');
		            $_POST['image_format'] = $this->upload->data('image_type');
		            $_POST['full_path'] = $this->upload->data('full_path');
		        }
		    }
		    else
		    {
		    	$_POST[$fieldname] = $gallery->images;
		    }

		    if (!empty($_POST[$fieldname])) {

		    	$data  = $this->gallery_m->array_form_post(array('catid','title','caption','slug','images','image_size','image_format','full_path','created','createdby','modified','modifiedby','status','pubdate'));

				$this->gallery_m->save($data, $id);

				$this->session->set_flashdata('message', '<p>'. $this->lang->line('setting_general_success_message') .'</p>');

				redirect('gallery');
		    }
		}


		$this->data['gallery_title'] = array(
			'name'        => 'title',
			'id'          => 'title',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control',
			'placeholder' => $this->lang->line('gallery_title_placeholder'),
			'value'       => $this->form_validation->set_value('title', $gallery->title),
		);

		$this->data['slug'] = array(
			'name'        => 'slug',
			'id'          => 'slug',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control bg-slate',
			'placeholder' => $this->lang->line('gallery_url_placeholder'),
			'value'       => $this->form_validation->set_value('slug', $gallery->slug),
		);


		$this->data['caption'] = array(
			'name'        => 'caption',
			'class'       => 'form-control',
			'placeholder' => 'Caption',
			'rows'        => '5',
			'required'    => 'required',
			'value'       => $this->form_validation->set_value('caption', $gallery->caption),
		);

		$this->data['publication_date'] = array(
			'name'        => 'pubdate',
			'id'          => 'pubdate',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control datetime-picker',
			'placeholder' => $this->lang->line('gallery_publish_date'),
			'value'       => $gallery->pubdate,
		);

		$this->data['status'] = array(
			'name'  => 'status',
			'id'    => 'status',
			'class' => 'select2',
		);

		$this->data['status_option'] = array('0' => $this->lang->line('general_draft'), '1' => $this->lang->line('general_publish'));
		$this->data['status_selected'] = $gallery->status;

		$this->data['catid'] = array(
			'name'  => 'catid',
			'id'    => 'category',
			'class' => 'select2-search',
		);
		$this->data['cat_option'] = array();
		foreach ($this->gallery_m->_get_category() as $key => $value) {
		 	$this->data['cat_option'][$value->id] = $value->name;
		 };
		$this->data['cat_selected'] = $gallery->catid;


		$this->data['gallery_images'] = array(
			'name'        => 'images',
			'id'          => 'gallery-images',
			'multiple'    => 'multiple',
			'type'        => 'file',
			// 'required'    => 'required',
			'class'       => 'file-input-ajax',
			'placeholder' => ($gallery->images == 'placeholder.jpg' ? $this->lang->line('general_no_file') : $gallery->images) ,
			'data-src'    => $this->config->item('base_url') . $img_folder . $gallery->images,
			'value'       => $this->form_validation->set_value('images', $gallery->images),
		);

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->parser->parse('gallery_edit', $this->data);
	}

	public function delete($id)
	{
		$img = $this->gallery_m->get($id);

		if ($img->images)
		{
			$filename = './themes/default/uploads/gallery/' . $img->images;
			if (file_exists($filename) && $img->images != 'placeholder.jpg') {
			    unlink($filename);
			}
		}

		if ($this->gallery_m->delete($id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_delete_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_delete_message') .'</p>');
		}

		redirect('gallery');
	}

	public function publish( $status,$id)
	{
		$data['status'] = (($status == 'active') ? '1' : '0');

		if ($this->gallery_m->save($data, $id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_message') .'</p>');
		}

		redirect('gallery');
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
			$this->gallery_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 1)
		{
			$data['status']  = 1; // for publish and set status = 1
			$this->gallery_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 2)
		{
			$this->gallery_m->delete_all($id);
		}
	}

	public function _unique_slug($str)
	{

		$id = $this->uri->segment(3);

		$page = $this->gallery_m->check_exists('slug','slug',$id);

		if (count($page))
		{
			$this->form_validation->set_message('_unique_slug','%s should be unique');
			return FALSE;
		}
		return TRUE;
	}

}

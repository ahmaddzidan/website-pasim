<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/slider
	 *	- or -
	 * 		http://example.com/index.php/slider/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/slider/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('slider_m');
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	}

	public function index()
	{

		$this->data['subtitle'] = 'Slider';
		$this->data['slider'] = $this->slider_m->_get();

		$this->parser->parse('slider_list',$this->data);

	}

	public function edit($id = NULL)
	{
		$img_folder = './themes/default/uploads/slider/';

		if ($id == NULL)
		{
			$this->data['subtitle'] = 'Add Slider';
			$slider                   = $this->slider_m->_new();
			$slider->images           = 'placeholder.jpg';

			$_POST['created']    = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['createdby']  = $this->data['session_data']->id;

		}
		else
		{
			$this->data['subtitle'] = 'Edit Slider';
			$slider = $this->slider_m->get($id);

			$_POST['created']    = $slider->created;
            $_POST['createdby']  = $slider->createdby;

			$_POST['modified']   = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['modifiedby'] = $this->data['session_data']->id;

		}

		$rules = $this->slider_m->_rules;

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
		        }
		    }
		    else
		    {
		    	$_POST[$fieldname] = $slider->images;
		    }

		    if (!empty($_POST[$fieldname])) {

		    	$data  = $this->slider_m->array_form_post(array('title','caption','url','images','bgcolor','color','created','createdby','modified','modifiedby','status','pubdate'));

				$this->slider_m->save($data, $id);

				$this->session->set_flashdata('message', '<p>'. $this->lang->line('setting_general_success_message') .'</p>');

				redirect('slider');
		    }
		}


		$this->data['slider_title'] = array(
			'name'        => 'title',
			'id'          => 'title',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control',
			'placeholder' => $this->lang->line('slider_title_placeholder'),
			'value'       => $this->form_validation->set_value('title', $slider->title),
		);

		$this->data['url'] = array(
			'name'        => 'url',
			'id'          => 'url',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control bg-slate',
			'placeholder' => $this->lang->line('slider_url_placeholder'),
			'value'       => $this->form_validation->set_value('url', $slider->url),
		);


		$this->data['caption'] = array(
			'name'        => 'caption',
			'class'       => 'form-control',
			'placeholder' => 'Caption',
			'rows'        => '5',
			'required'    => 'required',
			'value'       => $this->form_validation->set_value('caption', $slider->caption),
		);

		$this->data['publication_date'] = array(
			'name'        => 'pubdate',
			'id'          => 'pubdate',
			'type'        => 'text',
			'required'    => 'required',
			'class'       => 'form-control datetime-picker',
			'placeholder' => $this->lang->line('slider_publish_date'),
			'value'       => $slider->pubdate,
		);

		$this->data['text_color'] = array(
			'name'        => 'color',
			'id'          => 'text-color',
			'type'        => 'text',
			'data-preferred-format'=>'hex',
			'class'       => 'form-control colorpicker-palette',
			'value'       => $slider->color,
		);

		$this->data['bg_color'] = array(
			'name'        => 'bgcolor',
			'id'          => 'background-color',
			'type'        => 'text',
			'data-preferred-format'=>'hex',
			'class'       => 'form-control colorpicker-palette',
			'value'       => $slider->bgcolor,
		);

		$this->data['status'] = array(
			'name'  => 'status',
			'id'    => 'status',
			'class' => 'select2',
		);

		$this->data['status_option'] = array('0' => $this->lang->line('general_draft'), '1' => $this->lang->line('general_publish'));
		$this->data['status_selected'] = $slider->status;



		$this->data['slider_images'] = array(
			'name'        => 'images',
			'id'          => 'slider-images',
			'multiple'    => 'multiple',
			'type'        => 'file',
			// 'required'    => 'required',
			'class'       => 'file-input-ajax',
			'placeholder' => ($slider->images == 'placeholder.jpg' ? $this->lang->line('general_no_file') : $slider->images) ,
			'data-src'    => $this->config->item('base_url') . $img_folder . $slider->images,
			'value'       => $this->form_validation->set_value('images', $slider->images),
		);

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->parser->parse('slider_edit', $this->data);
	}

	public function delete($id)
	{
		$img = $this->slider_m->get($id);

		if ($img->images)
		{
			$filename = './themes/default/uploads/slider/' . $img->images;
			if (file_exists($filename) && $img->images != 'placeholder.jpg') {
			    unlink($filename);
			}
		}

		if ($this->slider_m->delete($id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_delete_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_delete_message') .'</p>');
		}

		redirect('slider');
	}

	public function publish( $status,$id)
	{
		$data['status'] = (($status == 'active') ? '1' : '0');

		if ($this->slider_m->save($data, $id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_message') .'</p>');
		}

		redirect('slider');
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
			$this->slider_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 1)
		{
			$data['status']  = 1; // for publish and set status = 1
			$this->slider_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 2)
		{
			$this->slider_m->delete_all($id);
		}
	}

}

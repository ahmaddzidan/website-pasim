<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/event
	 *	- or -
	 * 		http://example.com/index.php/event/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/event/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('event_m');
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	}

	public function index()
	{

		$this->data['subtitle'] = 'Event';
		$this->data['event'] = $this->event_m->_get();

		$this->parser->parse('event_list',$this->data);

	}

	public function edit($id = NULL)
	{
		$img_folder = './themes/default/uploads/event/';

		if ($id == NULL)
		{
			$this->data['subtitle'] = 'Add Event';
			$event                   = $this->event_m->_new();
			$event->images           = 'placeholder.jpg';

			$_POST['created']    = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['createdby']  = $this->data['session_data']->id;
            $daterange = '';
		}
		else
		{
			$this->data['subtitle'] = 'Edit Event';
			$event = $this->event_m->get($id);

			$_POST['created']    = $event->created;
            $_POST['createdby']  = $event->createdby;

			$_POST['modified']   = gmdate('Y-m-d H:i:s',time()+60*60*7);
            $_POST['modifiedby'] = $this->data['session_data']->id;
            $daterange = date('j F Y g:i A', strtotime($event->start_date)) . ' - ' . date('j F Y g:i A', strtotime($event->end_date));
		}

		if(isset($_POST['daterange']))
    	{
    		$date                = explode('-', $_POST['daterange']);

    		$_POST['start_date'] = date('Y-m-d H:i:s', strtotime($date[0]));
    		$_POST['end_date']   = date('Y-m-d H:i:s', strtotime($date[1]));
    	}

		$rules = $this->event_m->_rules;

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
		    	$_POST[$fieldname] = $event->images;
		    }

		    if (!empty($_POST[$fieldname])) {

		    	$data  = $this->event_m->array_form_post(array('title','slug','intro','body','tags','images','commite','location','map_location','latitude','longitude','radius','contact','website','start_date','end_date','created','createdby','modified','modifiedby','status','pubdate'));

				$this->event_m->save($data, $id);

				$this->session->set_flashdata('message', '<p>'. $this->lang->line('setting_general_success_message') .'</p>');

				redirect('event');
		    }
		}


		$this->data['event_title'] = array(
			'name'  => 'title',
			'id'    => 'title',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> $this->lang->line('event_title_placeholder'),
			'value' => $this->form_validation->set_value('title', $event->title),
		);

		$this->data['slug'] = array(
			'name'  => 'slug',
			'id'    => 'slug',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control bg-slate',
			'placeholder'=> $this->lang->line('event_slug'),
			'value' => $this->form_validation->set_value('slug', $event->slug),
		);

		$this->data['location'] = array(
			'name'  => 'location',
			'id'    => 'location',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> $this->lang->line('event_location'),
			'value' => $this->form_validation->set_value('location', $event->location),
		);

		$this->data['radius'] = array(
			'name'  => 'radius',
			'id'    => 'us3-radius',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('radius', $event->radius),
		);

		$this->data['map_location'] = array(
			'name'  => 'map_location',
			'id'    => 'us3-address',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('location', $event->location),
		);

		$this->data['latitude'] = array(
			'name'  => 'latitude',
			'id'    => 'us3-lat',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('latitude', $event->latitude),
		);

		$this->data['longitude'] = array(
			'name'  => 'longitude',
			'id'    => 'us3-lon',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('longitude', $event->longitude),
		);

		$this->data['contact'] = array(
			'name'  => 'contact',
			'id'    => 'contact',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> $this->lang->line('event_contact'),
			'value' => $this->form_validation->set_value('contact', $event->contact),
		);

		$this->data['commite'] = array(
			'name'  => 'commite',
			'id'    => 'commite',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> $this->lang->line('event_commite'),
			'value' =>$this->form_validation->set_value('commite', $event->commite),
		);

		$this->data['website'] = array(
			'name'  => 'website',
			'id'    => 'website',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control',
			'placeholder'=> $this->lang->line('event_website'),
			'value' =>$this->form_validation->set_value('website', $event->website),
		);

		$this->data['daterange'] = array(
			'name'  => 'daterange',
			'id'    => 'daterange',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control daterange-time',
			'value' => $daterange
		);

		$this->data['intro'] = array(
			'name'  => 'intro',
			'required' => 'required',
			'class'    => 'form-control',
			'rows' => '5',
			'value' => $this->form_validation->set_value('intro', $event->intro),
		);

		$this->data['body'] = array(
			'name'  => 'body',
			'id'    => 'editor-full',
			'required' => 'required',
			'value' => $this->form_validation->set_value('body', $event->body),
		);

		$this->data['publication_date'] = array(
			'name'  => 'pubdate',
			'id'    => 'pubdate',
			'type'  => 'text',
			'required' => 'required',
			'class' => 'form-control datetime-picker',
			'placeholder'=> $this->lang->line('event_publish_date'),
			'value' => $this->form_validation->set_value('pubdate', $event->pubdate)
		);

		$this->data['status'] = array(
			'name'  => 'status',
			'id'    => 'status',
			'class' => 'select2',
		);

		$this->data['status_option'] = array('0' => $this->lang->line('general_draft'), '1' => $this->lang->line('general_publish'));
		$this->data['status_selected'] = $event->status;


		$this->data['tags'] = array(
			'name'  => 'tags',
			'id'    => 'tags',
			'type'  => 'text',
			'data-role' => 'tagsinput',
			'class' => 'tagsinput-typeahead',
			'placeholder'=> $this->lang->line('general_tags'),
			'value' => $this->form_validation->set_value('tags', $event->tags),
		);

		$this->data['featured_images'] = array(
			'name'  => 'images',
			'id'    => 'featured-images',
			'type'  => 'file',
			'class' => 'file-input',
			'placeholder'=> ($event->images == 'placeholder.jpg' ? $this->lang->line('general_no_file') : $event->images) ,
			'data-src' => $this->config->item('base_url') . $img_folder . $event->images,
			'value' => $this->form_validation->set_value('images', $event->images),
		);

		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		$this->parser->parse('event_edit', $this->data);
	}

	public function delete($id)
	{
		$img = $this->event_m->get($id);

		if ($img->images)
		{
			$filename = './themes/default/uploads/event/' . $img->images;
			if (file_exists($filename) && $img->images != 'placeholder.jpg') {
			    unlink($filename);
			}
		}

		redirect('event');
	}

	public function publish( $status,$id)
	{
		$data['status'] = (($status == 'active') ? '1' : '0');

		if ($this->event_m->save($data, $id))
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_success_message') .'</p>');
		}
		else
		{
			$this->session->set_flashdata('message', '<p>'. $this->lang->line('general_failed_message') .'</p>');
		}

		redirect('event');
	}

	public function _unique_slug($str)
	{

		$id = $this->uri->segment(3);

		$page = $this->event_m->check_exists('slug','slug',$id);

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
			$this->event_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 1)
		{
			$data['status']  = 1; // for publish and set status = 1
			$this->event_m->save_all($data, $id);
		}
		elseif (count($act) && $act == 2)
		{
			$this->event_m->delete_all($id);
		}
	}

}

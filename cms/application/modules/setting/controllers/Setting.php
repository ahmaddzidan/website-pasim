<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {

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
		$this->load->model('setting_m');
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
	}

	public function index()
	{
		$this->data['subtitle'] = 'General Setting';
		$img_path = './themes/default/uploads/setting';

		$id                    = 1;
		$site                  = $this->setting_m->get($id);
		$rules                 = $this->setting_m->_rules;

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run($this) == TRUE)
		{
			$config['upload_path']          = $img_path;
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 100;
            $config['max_width']            = 1024;
            $config['max_height']           = 768;
            $config['overwrite']			= true;

            $this->load->library('upload');


            foreach ($_FILES as $fieldname => $fileObject)  //fieldname is the form field name
			{
			    if (!empty($fileObject['name']))
			    {
			        $this->upload->initialize($config);
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
			    	if ($fieldname == 'headerlogo')
			    	{
			    		$_POST[$fieldname] = $site->headerlogo;
			    	}
			    	else
			    	{
			    		$_POST[$fieldname] = $site->footerlogo;
			    	}
			    }
			}

             $data  = $this->setting_m->array_form_post(array('title','domain','description','metakeyword','owner','support','support_email','issmtp','smtphost','smtpport','smtpuser','smtppass','location','radius','latitude','longitude','timezone','dateformat','language','facebook','twitter','telephone','mobilecontact','address','headerlogo','footerlogo','status','unavailablemessage'));

			$this->setting_m->save($data, $id);

			$this->session->set_flashdata('message', '<p>'. $this->lang->line('setting_general_success_message') .'</p>');

			redirect('setting');
		}

		$this->data['site_title'] = array(
			'name'  => 'title',
			'id'    => 'site_title',
			'type'  => 'text',
			'required' => 'required',
			'pattern' => '.{7,}',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('site_title', $site->title),
		);
		$this->data['site_domain'] = array(
			'name'  => 'domain',
			'id'    => 'site_domain',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('site_domain', $site->domain),
		);
		$this->data['site_description'] = array(
			'name'  => 'description',
			'id'    => 'site_description',
			'class' => 'form-control',
			'required' => 'required',
			'rows' => '5',
			'value' => $this->form_validation->set_value('site_description', $site->description),
		);
		$this->data['meta_keyword'] = array(
			'name'  => 'metakeyword',
			'id'    => 'meta_keyword',
			'class' => 'form-control',
			'required' => 'required',
			'rows' => '5',
			'value' => $this->form_validation->set_value('meta_keyword', $site->metakeyword),
		);
		$this->data['owner'] = array(
			'name'  => 'owner',
			'id'    => 'owner',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('owner', $site->owner),
		);
		$this->data['support'] = array(
			'name'  => 'support',
			'id'    => 'support',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('support', $site->support),
		);

		$this->data['radius'] = array(
			'name'  => 'radius',
			'id'    => 'us3-radius',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('radius', $site->radius),
		);

		$this->data['location'] = array(
			'name'  => 'location',
			'id'    => 'us3-address',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('location', $site->location),
		);

		$this->data['latitude'] = array(
			'name'  => 'latitude',
			'id'    => 'us3-lat',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('latitude', $site->latitude),
		);

		$this->data['longitude'] = array(
			'name'  => 'longitude',
			'id'    => 'us3-lon',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('longitude', $site->longitude),
		);

		$this->data['timezone_option'] = timezone_list();
		$this->data['timezone_selected'] = $site->timezone;
		$this->data['timezone'] = array(
			'name'  => 'timezone',
			'id'    => 'timezone',
			'class' => 'form-control',
		);

		$this->data['dateformat'] = array(
			'name'  => 'dateformat',
			'id'    => 'dateformat',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('dateformat', $site->dateformat),
		);

		$this->data['language_option'] = array('id'=>'Indonesia','en'=>'English','jp'=>'Japan');
		$this->data['language_selected'] = $site->language;
		$this->data['language'] = array(
			'name'  => 'language',
			'id'    => 'language',
			'class' => 'form-control',
		);

		$this->data['support_email'] = array(
			'name'  => 'support_email',
			'id'    => 'support_email',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('support_email', $site->support_email),
		);

		$this->data['option'] = array('0'=>'Local Email','1'=>'Remote SMTP');
		$this->data['selected'] = $site->issmtp;

		$this->data['smtp_host_type'] = array(
			'name'  => 'issmtp',
			'id'    => 'smtp_host_type',
			'class' => 'form-control',
		);
		$this->data['smtp_host'] = array(
			'name'  => 'smtphost',
			'id'    => 'smtp_host',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('smtp_host', $site->smtphost),
		);
		$this->data['smtp_port'] = array(
			'name'  => 'smtpport',
			'id'    => 'smtp_port',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('smtp_port', $site->smtpport),
		);
		$this->data['smtp_username'] = array(
			'name'  => 'smtpuser',
			'id'    => 'smtp_username',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('smtp_username', $site->smtpuser),
		);
		$this->data['smtp_password'] = array(
			'name'  => 'smtppass',
			'id'    => 'smtp_password',
			'type'  => 'password',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('smtp_password', $site->smtppass),
		);
		$this->data['facebook_address'] = array(
			'name'  => 'facebook',
			'id'    => 'facebook_address',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('facebook_address', $site->facebook),
		);
		$this->data['twitter_address'] = array(
			'name'  => 'twitter',
			'id'    => 'twitter_address',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('twitter_address', $site->twitter),
		);
		$this->data['telephone'] = array(
			'name'  => 'telephone',
			'id'    => 'telephone',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('telephone', $site->telephone),
		);
		$this->data['mobile_contact'] = array(
			'name'  => 'mobilecontact',
			'id'    => 'mobile_contact',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('mobile_contact', $site->mobilecontact),
		);
		$this->data['address'] = array(
			'name'  => 'address',
			'id'    => 'address',
			'class' => 'form-control',
			'rows' => '5',
			'required' => 'required',
			'value' => $this->form_validation->set_value('address', $site->address),
		);
		$this->data['logo_header'] = array(
			'name'  => 'headerlogo',
			'id'    => 'headerlogo',
			'type'  => 'file',
			'class' => 'file-input',
			'data-caption' => $site->headerlogo,
			'data-src' => $img_path . '/'. $site->headerlogo,
		);
		$this->data['logo_footer'] = array(
			'name'  => 'footerlogo',
			'id'    => 'footerlogo',
			'type'  => 'file',
			'class' => 'file-input',
			'data-caption' => $site->footerlogo,
			'data-src' => $img_path . '/'.$site->footerlogo,
		);

		$this->data['status'] = array(
			'name'  => 'switch-state',
			'id'    => 'status',
			'type'  => 'checkbox',
			'class' => 'form-control switch-checkbox',
			'data-on-color' => 'success',
			'data-off-color' => 'danger',
			'data-on-text' =>'Online',
			'data-off-text' => 'Offline',
			'value' => $site->status
		);

		if ($site->status == 1){
			$checked = array('checked'=>'checked');
			$this->data['status']['checked'] = 'checked';
		}

		$this->data['status_id'] = array(
			'name'  => 'status',
			'id'    => 'status-id',
			'type'  => 'hidden',
			'value' =>  $site->status
		);

		$this->data['unavailable_message'] = array(
			'name'  => 'unavailablemessage',
			'id'    => 'unavailable_message',
			'type'  => 'text',
			'class' => 'form-control',
			'required' => 'required',
			'value' => $this->form_validation->set_value('unavailable_message', $site->unavailablemessage),
		);

		$this->parser->parse('setting_general',$this->data);

	}
}

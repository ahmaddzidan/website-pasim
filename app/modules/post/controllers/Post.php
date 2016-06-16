<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends Frontend_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/post
	 *	- or -
	 * 		http://example.com/index.php/post/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/post/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('post_m');
	}

	private function _pagination($baseurl, $segment, $where = NULL)
	{
		// set pagination
		if ($where != NULL)
		{
			$this->db->where($where);
		}

		$count   = $this->db->count_all_results('tbl_post');
		$perpage = 5;
		if ($count > $perpage)
		{
			$this->load->library('pagination');

			$config['base_url']    = $baseurl;
			$config['total_rows']  = $count;
			$config['per_page']    = $perpage;
			$config['per_page']    = $perpage;
			$config['uri_segment'] = $segment;

			$this->pagination->initialize($config);
			$this->data['pagination'] = $this->pagination->create_links();
			$offset = $this->uri->segment(4);

			$start = (($this->pagination->cur_page-1) * $this->pagination->per_page)+1;
			$end   = ($this->pagination->cur_page * $this->pagination->per_page);
			$total = $count;

			$this->data['pager_message'] = '<div class="pull-left mt-20 text-muted"><i>Showing ' . $start . ' - ' . $end . ' of '. $count . ' items </i></div>';
		}
		else
    	{
    		$this->data['pagination']    = '';
    		$this->data['pager_message'] = '';
			$offset = 0;
    	}

		// Load the view data
		$this->db->limit($perpage,$offset);

	}

	public function index()
	{
		$baseurl = site_url($this->uri->segment(1) . '/index/hal/');
		$segment = 4;
		$where   = array('status'=>'1');

		self::_pagination($baseurl, $segment);

		$this->data['post'] = $this->post_m->_get();
		$this->data['post_category'] = 'Semua Kategori';

		$this->parser->parse('post_list',$this->data);

	}

	public function read($id, $slug)
	{
		$this->data['post'] = $this->post_m->_get($id, TRUE);

		// Redirect 404 if not found
		count($this->data['post']) || show_404(uri_string());

		// Redirect if slug was incorret
		$requested_slug = $this->uri->segment(4);
		$set_slug	= $this->data['post']->slug;

		if ($requested_slug != $set_slug)
		{
			redirect('post/read/' . $this->data['post']->id . '/' . $this->data['post']->slug, 'location', '301');
		}

		$this->parser->parse('post_read',$this->data);
	}

	public function berita()
	{
		$baseurl = site_url('post/berita/hal/');
		$segment = 4;
		$where   = array('status'=>'1', 'catid'=> '2');

		self::_pagination($baseurl, $segment, $where);

		$this->data['post'] = $this->post_m->_get_by(array('status'=>'1','catid'=> '2'));
		$this->data['post_category'] = 'Berita';
		$this->parser->parse('post_list',$this->data);
	}

	public function informasi()
	{
		$baseurl = site_url('post/informasi/hal/');
		$segment = 4;
		$where   = array('status'=>'1', 'catid'=> '3');
		self::_pagination($baseurl, $segment, $where);

		$this->data['post'] = $this->post_m->_get_by(array('status'=>'1','catid'=> '3'));
		$this->data['post_category'] = 'Informasi';
		$this->parser->parse('post_list',$this->data);
	}

	public function artikel_lppm()
	{
		$baseurl = site_url('post/artikel_lppm/hal/');
		$segment = 4;
		$where   = array('status'=>'1', 'catid'=> '4');
		self::_pagination($baseurl, $segment, $where);

		$this->data['post'] = $this->post_m->_get_by(array('status'=>'1','catid'=> '4'));
		$this->data['post_category'] = 'Artikel LPPM';
		$this->parser->parse('post_list',$this->data);
	}
}

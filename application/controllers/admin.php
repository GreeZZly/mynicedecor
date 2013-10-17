<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('nice');
	
	}
	public function common($url)
	{
		// $data['ctg_array'] = $this->nice->product_categories();
		$this->load->view('admin/htmlheader');
		$this->load->view('admin/header');
		$this->load->view('admin/bar');
		$this->load->view('admin/'.$url);
		$this->load->view('admin/htmlfooter');	
	}
	public function index()
	{
		$this->common('content');
		
	}

	public function add_product(){
		$this->common('add_product', $data);
	}
}
?>
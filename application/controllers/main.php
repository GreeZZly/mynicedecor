<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller
{
	private $count;
	function __construct()
	{
		parent:: __construct();
		$this->load->model('nice');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('cart');
		$this->count = $this->cart->total_items();
	}
	private function common($url, $data=array()) {
		$data['rev_records'] = $this->nice->reviews();
		$data['prod_records'] = $this->nice->products();
		if (!isset($data['count'])) $data['count'] = $this->count;
		$this->load->view('main/htmlheader', $data);
		$this->load->view('main/header');
		$this->load->view('main/menu');
		$this->load->view('main/'.$url);
		$this->load->view('main/banner');
		// $this->load->view('main/news_begin');
		$this->load->view('main/reviews');
		$this->load->view('main/leftbar');
		$this->load->view('main/soc_likes');
		$this->load->view('main/social_plugins');
		$this->load->view('main/content');
		$this->load->view('main/counter');
		$this->load->view('main/minimap');
		$this->load->view('main/footer');
		$this->load->view('main/htmlfooter');
	}
	public function index()
	{
		$this->common('gallery');

	}
	

	public function search() {
		$query =  $this->input->post('query');
		$query = trim($query);
		$query = mysql_real_escape_string($query);
		$query = htmlspecialchars($query);
		$data = array("query" => $query);
		$data['prod_records'] = $this->nice->products();
		$this->common('search_result', $data);
		// print_r($data['prod_records']);
		// $row = $data['prod_records']->row_array();
		// echo $row['id'];
		
	}

	public function view_cart() 
	{

		$this->common('cart');
	}

	public function update_cart()
	{	
		$data = array();
		for ($i=1; $i <= $_POST['update_id'] ; $i++) { 
			$temp =  array(
                   'rowid' => $_POST['rowid_'.$i],
		 			'qty' => $_POST['qty_'.$i]
                    );
		array_push($data, $temp);
	}
		$this->cart->update($data);
		redirect('/main/view_cart', 'refresh');
	}
	public function insert_to_cart()
	{
		$id = $this->input->post('product_id');
        $has = $this->cart->contents();
        $bool = false;
        foreach ($has as $key => $value) {
        	if ($value['id'] == $id) {
        		$bool = true;
        		$qty = $value['qty'];
        		$rowid = $value['rowid'];
        		break;
        	}
        }
        if (!$bool) {
			$t = $this->nice->getProductById($id);
	                $product =$t[0];
	        $data = array(
				
				'id' => $product['id'],
	        	'qty' =>1,
	        	'price' => $product['price'], 
	        	'name' => $product['name']
				
				);
			$this->cart->insert($data);
        }
        else {
        	$this->cart->update(array('rowid'=>$rowid, 'qty' => (int)$qty+1));
        }

		$this->output->set_content_type('apllication/json')
					 ->set_output($this->cart->total_items());
	}

	public function destroy_cart()
	{
		$this->cart->destroy();
		redirect('/main/view_cart', 'refresh');
	}

	public function temp() 
	{
		echo "<pre>";
		print_r($this->nice->getProductById(1));
		echo "</pre>";
	}

	public function viewProduct()
	{	
		$data['int_prod'] = $this->nice->interest_products();
		if (!isset($data['count'])) $data['count'] = $this->count;
		$this->common('view_product', $data);
		// $this->load->view('main/htmlheader', $data);
		// $this->load->view('main/header');
		// $this->load->view('main/menu');
		// $this->load->view('main/leftbar');
		// $this->load->view('main/view_product');
		
		// $this->load->view('main/htmlfooter');
		
	}

	public function register() 
	{	$this->load->view('main/htmlheader');
		 $this->load->view('auth/register');
		$this->load->view('main/htmlfooter');
	}
}

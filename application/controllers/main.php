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
		$this->load->library('ion_auth');
		$this->load->library('session');
		$this->count = $this->cart->total_items();
		$id_registred_company = 2;

	}
	private function common($url, $data=array()) {
		if ( $this->ion_auth->logged_in() ) 
			{
				$log_on = 1;
			}
		else {
			$log_on = 0;
		}
		//$user_id = $this->ion_auth->get_user_info_is();
		// $user_id = 7;

		//$data['user'] = $this->ion_auth_model->getUserIs($user_id);
		$user_id = $this->session->userdata('user_id');
		$data['username'] = $this->ion_auth_model->getUserIs($user_id);
		
		$data['log_on'] = $log_on;

		$data['category'] = $this->nice->getCategory();
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
	public function get_json(){
		$variable = $this->input->post('variable');
		echo json_encode(array('got' => $variable));
	}

	public function search() {
		$query =  $this->input->post('query');
		$query = trim($query);
		$query = mysql_real_escape_string($query);
		$query = htmlspecialchars($query);
		$data = array("query" => $query);
		// $data['prod_records'] = $this->nice->get_smth($query);
		$this->common('search_result', $data);
		// print_r($data['prod_records']);
		// $row = $data['prod_records']->row_array();
		// echo $row['id'];
		
	}

	public function view_cart() 
	{

		$this->allpages('cart');
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
	        	'price' => $product['cost'], 
	        	'name' => $product['product']
				
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


	public function register() 
	{	$this->load->view('main/htmlheader');
		 $this->load->view('auth/register');
		$this->load->view('main/htmlfooter');
	}

	public function allpages($url, $data=array()){
			if ( $this->ion_auth->logged_in() ) 
			{
				$log_on = 1;
			}
		else {
			$log_on = 0;
		}
		//$user_id = $this->ion_auth->get_user_info_is();
		// $user_id = 7;

		//$data['user'] = $this->ion_auth_model->getUserIs($user_id);
		$user_id = $this->session->userdata('user_id');
		$data['username'] = $this->ion_auth_model->getUserIs($user_id);
		
		$data['log_on'] = $log_on;
		$data['category'] = $this->nice->getCategory();
		if (empty($data['cat_id'])) $data['cat_id'] = 0;
		// $data['cat_id'] = $data['cat_id'] or 0;
		$data['rev_records'] = $this->nice->reviews();
		$data['prod_records'] = $this->nice->products();
		if (!isset($data['count'])) $data['count'] = $this->count;
		$this->load->view('main/htmlheader', $data);
		$this->load->view('main/header');
		$this->load->view('main/menu');
		// $this->load->view('main/gallery');
		$this->load->view('main/'.$url);
		$this->load->view('main/banner');

		$this->load->view('main/minimap');
		$this->load->view('main/footer');
		$this->load->view('main/htmlfooter');
	}

	public function products($cat_id){
		$data['prodByCategory'] = $this->nice->products($cat_id);
		$data['propParent'] =$this->nice->getPropertyParent($cat_id);
		$data['propChild'] = $this->nice->getPropertyChild($cat_id);
		$data['cat_id'] = $cat_id;

		$this->allpages('products_view', $data);
	}

	public function getProdBySelect() {
		$id_array= $this->input->post('id_array');
		$cid= $this->input->post('category_id');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->nice->getProdBySelect($id_array, $cid)));

	}

	public function viewProduct($id_product = -8)
	{	
		if ($id_product == -8) redirect('/', 'refresh');
		$data['int_prod'] = $this->nice->interest_products();
		$temp = $this->nice->getProductData($id_product);
		$data['prod_data'] = $temp['productData'];
		$data['prod_prop'] = $temp['productProperties'];
		$cat_id = $data['prod_data'][0]['cid'];
		$data['prodByCategory'] = $this->nice->products($cat_id);
		// $this->output->set_content_type('application/json')->set_output(json_encode($this->nice->getProdBySelect($id_array, $cid)));		
		if (!isset($data['count'])) $data['count'] = $this->count;

		$this->allpages('view_product', $data);

	}
	
	
}


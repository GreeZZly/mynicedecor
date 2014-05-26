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
		$this->load->helper('cookie');
		$this->count = $this->cart->total_items();
		$id_registred_company = 2;

	}
	 function mb_ucfirst($text) {
        $text = mb_strtolower($text);
        return mb_strtoupper(substr($text, 0, 2)) . substr($text, 2);
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
		$data['prod_records'] = $this->nice->interesting_products();
		if (!isset($data['count'])) $data['count'] = $this->count;
		$this->load->view('main/htmlheader', $data);
		$this->load->view('main/header');
		$this->load->view('main/menu');
		$this->load->view('main/'.$url);
		$this->load->view('main/banner');
		// $this->load->view('main/news_begin');
		// $this->load->view('main/reviews');
		// $this->load->view('main/leftbar');
		$this->load->view('main/content');
		$this->load->view('main/soc_likes');
		$this->load->view('main/social_plugins');
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
		// echo "$data";
		// $data['prod_records'] = $this->nice->get_smth($query);
		$this->allpages('search_result', $data);
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
	if($this->ion_auth->logged_in()){
		
		$order = $this->_prepare();
		$this->load->model('heroin');
		$id_order = $this->heroin->start($order['id_order'],$order,'cart');
		
		if($id_order)
			$this->session->set_userdata('id_order', $id_order);
	}	
	
		$this->output->set_content_type('apllication/json')
					 ->set_output($this->cart->total_items());
	}


	public function insert_to_like_cart()
	{
		$id = $this->input->post('product_id');
        if (!$this->ion_auth->logged_in()){

        }

		$this->output->set_content_type('application/json')->set_output(json_encode($this->nice->getProdBySelect($id_array, $cid)));
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
		// $cookie_data = $this->nice->getLikeFromBd($user_id);
		// $cookie = array(
  //   	'name'   => 'The Cookie Name',
  //   	'value'  => 'The Value',
  //  		'expire' => '86500',
  //   	'domain' => 'nicedecor.loc',
  //   	'path'   => '/',
  //   	'prefix' => 'myprefix_',
  //   	);
		// $this->input->set_cookie($cookie);
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

	public function products($cat_id, $since = 0){
		$this->load->library('pagination');



		// echo $this->pagination->create_links();

		$this->load->helper('file');
		// $since = 0;
		$lim = 10;
		$data['prodByCategory'] = $this->nice->products($cat_id, $since, $lim);
		$data['propParent'] =$this->nice->getPropertyParent($cat_id);
		$data['count_prod'] = $this->nice->product_count($cat_id);
		$data['propChild'] = $this->nice->getPropertyChild($cat_id);
		$data['cat_id'] = $cat_id;

//		$d = 'Hello, World!';
//		write_file('/include/files/file.txt', $d);
		$config['base_url'] = '/main/products/'.$cat_id;
		$config['total_rows'] = count($data['count_prod']);
		$config['per_page'] = $lim; 


		$this->pagination->initialize($config); 
		$data['pagi'] = $this->pagination->create_links();
		if($this->input->is_ajax_request()){
			$output = array('products'=>$data['prodByCategory'], 'pagi'=>$data['pagi']);
			$this->output->set_content_type('application/json')->set_output(json_encode($output));
		}
		else{
			$this->allpages('products_view', $data);
		}
		// echo 
	}
	public function raw_category($since=0){
		$cat_id= $this->input->post('category_id');

		$data['count_prod'] = $this->nice->product_count($cat_id);

		$lim=10;
		$this->load->library('pagination');
		$config['base_url'] = '/main/raw_category/';
		$config['total_rows'] = count($data['count_prod']);
		$config['uri_segment'] = 3;
		$config['per_page'] = $lim; 



		//$since= ((int)$since)*$lim-$lim;
                $products = $this->nice->products($cat_id, $since, $lim);
                 foreach ($products as $key=>$value){
                    if((int)$products[$key]['price']==0)$products[$key]['cost']='Уточните цену';
                    else $products[$key]['price'].= $products[$key]['currency'];
                }
		$this->pagination->initialize($config); 

		$data['pagi'] = $this->pagination->create_links();
        $output = array('products'=>$products, 'pagi'=>$data['pagi']);
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}
	public function getProdBySelect($since='0') {

		$cat_id= $this->input->post('category_id');

		$data['count_prod'] = $this->nice->product_count($cat_id);

		$lim=10;
		$this->load->library('pagination');
		

		$id_array= $this->input->post('id_array');
		//$since= ((int)$since)*$lim-$lim;
		// $cid= $this->input->post('category_id');
                $products = $this->nice->getProdBySelect($id_array, $cat_id, $since, $lim);
                foreach ($products as $key=>$value){
                    if((int)$products[$key]['cost']==0)$products[$key]['cost']='Уточните цену';
                    else $products[$key]['cost'].=' руб.';
                }

        $config['base_url'] = '/main/getProdBySelect/';
		$config['total_rows'] = count($this->nice->getProdBySelect($id_array, $cat_id));
		$config['uri_segment'] = 3;
		$config['per_page'] = $lim; 


		$this->pagination->initialize($config); 

		$data['pagi'] = $this->pagination->create_links();

        $output = array('products'=>$products, 'pagi'=>$data['pagi']);        
                $this->output->set_content_type('application/json')->set_output(json_encode($output));

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
	
	public function order(){
		
		$this->allpages('order');
	}	

	public function order_pay(){
		// $this->load->helper('cookie');
		// $data['name'] = $this->input->post('name');
		$this->load->model('heroin');
		$order = $this->_prepare();
		$data = array();
		if(!$this->ion_auth->logged_in()){
		$this->load->library('form_validation');
		    $this->form_validation->set_rules('name', 'Имя', 'required|xss_clean');
			$this->form_validation->set_rules('surname','Фамилия' , 'required|xss_clean');
			$this->form_validation->set_rules('email','email', 'required|valid_email');
			$this->form_validation->set_rules('phone','Телефон', 'required|xss_clean|min_length[11]|max_length[15]');
			if ($this->form_validation->run() == true) {
				
				$data['customer'] = array(
					'name'  => $this->mb_ucfirst($this->input->post('name')),                            
					'surname'=> $this->mb_ucfirst($this->input->post('surname')),
					'email'=> $this->input->post('email'),
					'phone'  => $this->mb_ucfirst($this->input->post('phone')),
					'type' => 'individual',
					// 'second_name'=> $this->mb_ucfirst($this->input->post('second_name')),
	                                'id_registred_company'=>$this->config->item('id_company')
				);
				$order['customer'] = $data['customer'];
				//$this->heroin->start(null,$data,'Оформление');
			}
			else
			{
				$data['message'] = $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
				$this->allpages('order', $data);
			}
			
		}
		
		
	        if( $id = $this->heroin->start($order['id_order'],$order,'registration')){
			$this->session->set_userdata('id_order',$id);
		}
		else{
			$data['message']='Возникла ошибка при оформлении покупки';
			$this->allpages('order', $data);
		}
		// set_cookie($client_cookie, $data['client_data']);
		redirect('/pay/', 'refresh');	
		//$this->allpages('pay', $data);
	}	

	public function design_service(){
		$this->allpages('design_service');
	}
	private function _prepare(){
		$cart = $this->cart->contents();
		$temp = array();
		foreach($cart as $value){
			$temp[] = array('quantity'=>$value['qty'],
					'cost'=>$value['price'],
					'id'=>$value['id'],
					'total_sum'=>$value['subtotal'],
					'product'=>$value['name']);
		}
		$order['description'] = json_encode($temp);
		$order['price'] = $this->cart->total();
		$order['discount']=''; // скидка
		$order['date']=date('d-m-Y');
		$order['time']=date('G:i');
		$order['reg']= $this->ion_auth->logged_in();
		$order['id_order'] =($order['reg'])? $this->session->userdata('id_order'):null;
		$order['name'] = 'Заказ_'.$order['date']."_".$order['time'];
		//$order['id'] = $id_order;
		$order['customer']['user_id'] = $this->session->userdata('user_id');
		
		return $order;
	}
	public function view_like_cart(){
		$lk = json_decode(get_cookie('like_array'));
		// print_r($lk);
		$data['like_products'] = $this->nice->getLikeProduct($lk);
		// echo "<pre>";
		// print_r($data['like_products']);
		// echo "</pre>";
		$this->allpages('like_cart', $data);
	}

	function setLikeToBd(){
		if ($this->ion_auth->logged_in()) {
			$like_array = $this->input->post('id_like_array');
			$user_id = $this->session->userdata('user_id');
			
			$this->output->set_content_type('application/json')->set_output(json_encode($this->nice->setLikeToBd($like_array, $user_id)));
		}
	}

	public function invoice_confirmation(){
		$this->load->helper('file');
		$data = $this->input->post();
		write_file('/include/files/file.txt', $data);
		// $fp = fopen('invoice.txt', 'w+');
		// fwrite($fp, $data);
		// fclose($fp);
		// $this->nice->
		echo "YES";
	}

	public function about_us(){
		$this->allpages('about');
	}
}


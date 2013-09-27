<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('nice');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('cart');
	}

	private function common($url, $data=array()) {
		$data['rev_records'] = $this->nice->reviews();
		$data['prod_records'] = $this->nice->products();
		$this->load->view('main/htmlheader');
		$this->load->view('main/header');
		$this->load->view('main/menu');
		$this->load->view('main/banner');
		$this->load->view('main/leftbar');
		$this->load->view('main/rightbar');
		$this->load->view('main/'.$url, $data);
		$this->load->view('main/banner');
		$this->load->view('main/content', $data);
		$this->load->view('main/banner');
		$this->load->view('main/minimap');
		$this->load->view('main/footer');
		$this->load->view('main/htmlfooter');
	}
	public function index()
	{
		$this->common('news_content');

	}
	public function reg() 
	{
		$this->load->view('main/reg');
	}
	public function save_user()
	{
		if ($_POST['password'] != $_POST['repassword']) 
		{
        	exit('Пароль не совпадает!');
    	}
	    if (isset($_POST['login'])) 
	    { 
	        $data['login'] = $_POST['login']; 
	        if ($data['login'] == '') 
	            { 
	                unset($data['login']);
	            } 
	    } 
	    if (isset($_POST['password'])) 
	        { 
	            $data['password']=$_POST['password']; 
	            if ($data['password'] =='') 
	                { 
	                    unset($data['password']);
	                } 
	        }
 		if (empty($data['login']) or empty($data['password']))
	    {
	    exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля!");
	    }

		
	    $data['login'] = stripslashes($data['login']);
	    $data['login'] = htmlspecialchars($data['login']);
	 	$data['password'] = stripslashes($data['password']);
	    $data['password'] = htmlspecialchars($data['password']);
	    $data['login'] = trim($data['login']);
	    $data['password'] = trim($data['password']);
	    $data['password'] = sha1($data['password']);
		

		$query = $this->nice->check_user($data);


		if (!empty($query[0]['id'])) {
			exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин.");
		}

		$ins_user = $this->nice->save_user($data);

		  if ($ins_user=='TRUE')
			    {
			    echo "Вы успешно зарегистрированы! Теперь вы можете зайти на сайт. <a href='/'>Главная страница</a>";
			    }
	      else {
			    echo "Ошибка! Вы не зарегистрированы.";
			    }
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
		$t = $this->nice->getProductById($id);
                $product =$t[0];
        $data = array(
			
			'id' => $product['id'],
        	'qty' =>1,
        	'price' => $product['price'],
        	'name' => $product['name']
			
			// 'id' => $product['id'],
			// 'qty' => 1,
			// 'price' => $product['price'],
			// 'name' => $product['name']
			// 'options' => array(
			// 		'categories' => $product['categories'],
			// 		'type' => $product['type'],
			// 		'country' => $product['country'],
			// 		'colour' => $product['colour'])
        	// 'id' => '123',
        	// 'qty' =>1,
        	// 'price' => 35.25,
        	// 'name' => 'nameo'
			);
		// array_push($data, $ins);
		$this->cart->insert($data);
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		redirect('/main/view_cart', 'refresh');


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
}

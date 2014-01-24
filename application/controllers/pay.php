<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends CI_Controller{
    
    
    function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->library('cart');
        $this->load->library('ion_auth');
	$this->load->model('payment');
	$this->load->helper('url');
        $this->load->helper('language');
    }
    
    function index(){
        ///$this->allpages('pay');
        $id_order = $this->session->userdata('id_order');
	if($id_order){
	    echo $this->model->startPayment($id_order);
	    
	}else{
	    show_404();
	}
    }
    
    
    
    
    function invoice_confirmation(){
	$post = $this->input->post();
	echo $this->payment->checkInvoice($post)?"YES":"NO";
	//todo проверка данных
	 
    }
    function payment_notification(){
	$post = $this->input->post();
	if($this->payment->checkNotification($notice)){
	    echo "Hoorey!";
	}
	else echo "Shits happens";
	//todo 1. Создать таблицу paymant_notification, куда складировать данные об успешной оплате
	//2. проверка хеша и запись в таблицу
	//3. 
    }
    function success(){
	$post = $this->input->post();
	if(count($post)>0){
	    $notice  = $this->getNotification($post['LMI_PAYMENT_NO']);
	    var_dump($notice);
	    //echo "Если вы это читаете то платеж успешно прошел. Или нет";
	}
	else{
	    show_404();
	}
	
	 
    }
    function failure(){
	$post = $this->input->post();
	if(count($post)>0){
	    echo "Если вы это читаете то платеж успешно провалился.";
	}
	else show_404();
    }
    function getNotificationCompany(){
	print_r($this->payment->getNotificationCompany());
    }
    function payment_setting(){
	$setting = $this->payment->getSettings();
	if(!$setting){
	    $setting  = array('LMI_MERCHANT_ID'=>'','LMI_CURRENCY'=>'','LMI_SIM_MODE'=>'');
	}
	$data['setting'] =$setting;
	$this->allpages('setting_payment',$data);
    }
    public function allpages($url, $data=array()){
			
		// $this->input->set_cookie($cookie);
		//$data['username'] = $this->ion_auth_model->getUserIs($user_id);
		
		$data['log_on'] = 0;
		$data['category'] = 0;
		if (empty($data['cat_id'])) $data['cat_id'] = 0;
		// $data['cat_id'] = $data['cat_id'] or 0;
		$data['rev_records'] = 0;
		$data['prod_records'] =0;
		if (!isset($data['count'])) $data['count'] = 0;
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
//   LMI_PREREQUEST
//Значение всегда 1.
//Идентификатор продавца
//LMI_MERCHANT_ID
//Идентификатор сайта в системе PayMaster
//Внутренний номер счета продавца
//LMI_PAYMENT_NO
//Номер счета, заданный в запросе платежа
//Сумма платежа, заказанная продавцом
//LMI_PAYMENT_AMOUNT
//Дробное число с разделителем ".", не более 2 знаков после точки.
//Валюта платежа, заказанная продавцом
//LMI_CURRENCY
//Это всегда 3-буквенный код валюты (http://www.currency-iso.org/iso_index/iso_tables/iso_tables_a1.htm)
//Сумма платежа в валюте, в которой покупатель производит платеж
//LMI_PAID_AMOUNT
//Дробное число с разделителем ".", не более 2 знаков после точки.
//Валюта, в которой производится платеж
//LMI_PAID_CURRENCY
//Строковый код валюты (не обязательно ISO).
//Идентификатор платежной системы, выбранной покупателем
//LMI_PAYMENT_SYSTEM
//Список платежных систем и их идентификаторы доступны на сайте PayMaster в разделе "Учетная запись" личного кабинета.
//Флаг тестового режима
//LMI_SIM_MODE
//Это поле присутствует только если платеж производится в тестовом режиме. Значения - те же, что и в форме заказа платежа.
//Назначение платежа
//LMI_PAYMENT_DESC
//Описание платежа, как оно показывается пользователю. То есть, если в форме заказа платежа было указано LMI_PAYMENT_DESC64, то в этом запросе придет уже раскодированное из Base64 описание.
//Внешний идентификатор магазина в платежной системе
//LMI_SHOP_ID

}
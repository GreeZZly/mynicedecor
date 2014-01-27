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
        $this->load->helper('file');
    }
    
    function index(){
        ///$this->allpages('pay');
        $id_order = $this->session->userdata('id_order');
        if($id_order){
            $this->payment->createPaymentNotification();
            echo "<script type='text/javascript' src='https://paymaster.ru/widget/Basic/1?".$this->payment->startPayment($id_order)."'>
//                </script>";

        }else{
            show_404();
        }
    }
    
    
    
    
    function invoice_confirmation(){
        $post = json_decode('{"LMI_MERCHANT_ID":"7e8a97c5-6b34-4ae2-9d14-29c7c7112748","LMI_PAYMENT_SYSTEM":"3","LMI_CURRENCY":"RUB","LMI_PAYMENT_AMOUNT":"2532.00","LMI_PAYMENT_NO":"3","LMI_PAYMENT_DESC":"u0422u043eu0432u0430u0440 - 10401, u043au043eu043bu0438u0447u0435u0441u0442u0432u043e - 1, u0446u0435u043du0430 u0437u0430 u0448u0442 - 2532","LMI_PAID_AMOUNT":"2532.00","LMI_PAID_CURRENCY":"RUB","LMI_PREREQUEST":"1","LMI_PAYMENT_METHOD":"Test"}',true);
        //$this->input->post();
        $this->_setLog(array('инвойс'=>$post));

        $answer =($this->payment->checkInvoice($post))?"YES":"NO";
        $this->_setLog(array('инвойс_ответ'=>$answer));
        echo $answer;

        //todo проверка данных
	 
    }
    function payment_notification(){
        $post  = $this->input->post();//json_decode('{"LMI_MERCHANT_ID":"7e8a97c5-6b34-4ae2-9d14-29c7c7112748","LMI_PAYMENT_SYSTEM":"3","LMI_CURRENCY":"RUB","LMI_PAYMENT_AMOUNT":"2532.00","LMI_PAYMENT_NO":"3","LMI_PAYMENT_DESC":"u0422u043eu0432u0430u0440 - 10401, u043au043eu043bu0438u0447u0435u0441u0442u0432u043e - 1, u0446u0435u043du0430 u0437u0430 u0448u0442 - 2532","LMI_SYS_PAYMENT_DATE":"2014-01-27T10:53:17","LMI_SYS_PAYMENT_ID":"13164486","LMI_PAID_AMOUNT":"2532.00","LMI_PAID_CURRENCY":"RUB","LMI_SIM_MODE":"3","LMI_PAYER_IDENTIFIER":"394738333193","LMI_PAYMENT_METHOD":"Test","LMI_HASH":"bBIspCkqKNcPVztwUpEgVQ=="}',true);

        //
        $this->_setLog(array('notice'=>$post));
        if($this->payment->checkNotification($post)){
            echo "Hoorey!<br>";
        }
        else echo "Shits happens";
        //todo
        //2. проверка хеша и запись в таблицу
        //3.
    }
    function success(){
        $post = $this->input->post();
        if(count($post)>0){
            $notice  = $this->payment->getNotification($post['LMI_PAYMENT_NO']);
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
    function paymentSetting(){
        $setting = $this->payment->getSettings();
        if(isset($setting) and !$setting->settings){
            $data['settings']  = array('LMI_MERCHANT_ID'=>'','LMI_CURRENCY'=>'','LMI_SIM_MODE'=>'','SECRET_KEY'=>'');
        }
        else  $data['settings'] =$setting->settings;
        $this->allpages('setting_payment',$data);
    }
    function setSettings(){

        $post = $this->input->post();
        $this->_setLog($post);
        $settings = new stdClass();
        $settings->id_payment = 1;
        $settings->settings = $post;
        $this->payment->createPaymentSettings();
        $this->payment->setSettings($settings);

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
    function getLogs(){
        echo "<pre>";
       print_r($this->payment->getLog());
        echo "</pre>";
    }
    private function _setLog($data){
        $this->payment->setLog($data);
       // return write_file('application/logs/logs.txt',date('d-m-Y H:i:s')." ".json_encode($data)."\n\n\r","a+");
    }

//

}
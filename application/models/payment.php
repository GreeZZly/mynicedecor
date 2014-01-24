<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Payment extends CI_Model{
    private $id_company;
    private $db;
    function __construct(){
        
        parent::__construct();
        $this->id_company =$this->config->item('id_company');
        $this->db = $this->load->database('default', TRUE);
    }


        function createPaymentNotification(){
            $sql = "CREATE TABLE IF NOT EXISTS `payment_notification` (
                    `id_order` int(15) NOT NULL,
                    `payment_status` tinyint(1) NOT NULL,
                    `id_payment` varchar(30) NOT NULL,
                    `adata` text NOT NULL,
                    UNIQUE KEY `id_order` (`id_order`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        }
        
         function createPaymentSettings(){
            $sql = "CREATE TABLE IF NOT EXISTS `paymant_settings` (
                    `id_payment` int(15) NOT NULL,
                    `settings` text NOT NULL,
                    `id_registred_company` int(15) NOT NULL,
                    UNIQUE KEY `id_payment` (`id_payment`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        }
        // возвращает настройки в виде обьекта
        function getSettings(){
            $settings = $this->db->query("Select id_payment,settings from paymant_settings where id_registred_company = '$this->id_company'")->row();
            if(isset($settings) and !empty($settings)){
                $settings->settings  = json_decode($settings->settings);
                return $settings;
            }
            return false;
        }
        //$settings -> обьект, где $settings->settings - массив основных настроек
        function setSettings($settings){
            $settings->settings  = json_encode($settings->settings);
            $this->db->query("insert into paymant_settings (id_payment,settings,id_registred_company) values ('$settings->id_payment','$settings->settings',$id_company)
                             on duplicate key update settings='$settings->settings'");
            
        }
        
        function getNotification($id_order){
            $notis = $this->db->query("select id_order,payment_status,id_payment,adata where id_order='$id_order'")->row();
            if(isset($notis)){
                $notis->adata  = json_decode($notis->adata);
                return $notis;
            }
            return false;
        }
        function setNotification($id_order,$notis){
            $notis->adata = json_encode($notis->adata);
            if(!empty($id_order)){
                $this->db->where('id_order',$id_order)->update('payment_notification',$notis);
            }
            else{
                $this->db->insert('payment_notification',$notis);
            }
            return $this->db->affected_rows>0;
        }
        //$data массив настроек по плтаежной системе и по корзине
        //по идее эта строка должна записаться в таблицу payment_notification
        function formation_payment($cart){
           $success = false;
           $key_payment=array('LMI_PAYMENT_AMOUNT'=>'total','LMI_PAYMENT_NO'=>'id_order','LMI_PAYMENT_DESC'=>'desc');
           $key_cart  = array_keys($cart);
           $payment=array();
           foreach($key_payment as $key=>$value){
                if(in_array($value,$key_cart)){
                    $payment[$key] = $cart[$value];
                }
           }
           $key_not_allow=array('SECRET_KEY');
           if(isset($payment)){
                $settings = $this->getSettings();
                if($settings){
                    $temp = array();
                    foreach($settings->setting as $key=>$value){
                        if(!in_array($key,$key_not_allow)){
                            $temp[$key]=$value;
                        }
                    }
                    $notis = new stdClass;
                    $notis->id_order = $cart['id_order'];
                    $notis->payment_status =0;
                    $notis->id_payment=0;
                    $notis->adata = array_merge($temp,$payment);
                    $success = true;
                }
           }
           
           
           return $success?$notis:false;
        }
        //массив обьедененый с настройками и корзиной
        function constructWidget($data){
            //тут в идеале можно устроить проверку на входящие данные, типа на необходимые поля и значения
            $temp = array();
            foreach($data as $key=>$value){
                $temp[].=$key."=".$value;   
            }
            $string="";
            if(!empty($temp)){
                return "<script type='text/javascript' src='https://paymaster.ru/widget/Basic/1?".implode('$',$temp)."'>
                </script>"; ;
            }
            return false;
        }
        
        function startPayment($id_order){
            $cart = $this->getOrder($id);
            if(!$cart) return FALSE;
            $notice = $this->formation_payment($cart);
            if($notice){
                $widget = $this->constructWidget($notice->adata);
                if($widget){
                    $this->db->setNotification($notice->id_order,$notice);
                    return $widget;
                }
            }
            return false;
        }
        //очевидно что функция сравнивает хеши. Это необходимо чтоб АНБ не подделало запрос
        function compareHash($hash,$data){
            
            $temp = array();
            $allow_key = array('LMI_MERCHANT_ID', 'LMI_PAYMENT_NO', 'LMI_SYS_PAYMENT_ID', 'LMI_SYS_PAYMENT_DATE',
                               'LMI_PAYMENT_AMOUNT', 'LMI_CURRENCY', 'LMI_PAID_AMOUNT', 'LMI_PAID_CURRENCY', 'LMI_PAYMENT_SYSTEM',
                               'LMI_SIM_MODE');
            $check = $this->into_arraY($allow_key,$data);
            //надо бы учесть в будущем какая хеш функция задана в настройках на paymaster.ru
            $string_hash = base64_encode(md5(implode(';',$data), true));
            return $hash==$string_hash;
        }
        function into_arraY($keys_array, $array,$assoc=FALSE){
            $temp = array();
            if($assoc){
                $temp_key_array = array_keys($array);
                foreach ($keys_array as $key=>$value) {
                   if(in_array($key,$temp_key_array)){
                       $temp[$value]=$array[$key];
                   }
                }
                
            }else{
                foreach ($array as $key=>$value) {
                     if( in_array($key, $keys_array))
                         $temp[$key] = $array[$key];
                }
            }
            return $temp;
        }
        
        function checkInvoice($data){
            if(isset($data['LMI_PAYMENT_NO'])){
                $notice = $this->getNotification($data['LMI_PAYMENT_NO']);
            }
            if(!isset($notice) or  empty($notice)){
                return FALSE;
            }
            foreach($data as $key=>$value){
                if($key!='LMI_PREREQUEST' and $value != $notice->adata[$key]){
                    return FALSE;
                }
            }
            return TRUE;
        }
        
        function checkNotification($data){
            if(isset($data['LMI_PAYMENT_NO']) and isset($data['LMI_SYS_PAYMENT_ID']) and $this->compareHash($data['LMI_HASH'],$data)){
                $notice = new stdClass;
                $notice->id_order = $data['LMI_PAYMENT_NO'];
                $notice->id_payment = $data['LMI_SYS_PAYMENT_ID'];
                $notice->payment_status = 1;
                $notice->adata = $data;
                //может обьеденить данные приходящие с payment.ru и данные в таблице?
                return  $this->setNotification($notice->id_order,$notice);
            }
            else return FALSE;
        }
        function getNotificationCompany(){
            return $this->db->query("select pn.id_order, pn.payment_status, pn.adata from payment_notification pn
                             join orders o on pn.id_order = o.id
                             join sale s on s.id=o.id_sale
                             join customer c on c.id = s.customer_id
                             where c.id_registed_company = '$this->id_company'")->result_array();
        }
        
        function getOrder($id_order){
           $order = $this->db->query('select id id_order,price total, description desc from order id = $id_order')->row_array();
           if(count($order)>0){return $order;}
           else{return FALSE;}
        }
        
        

        
        
}
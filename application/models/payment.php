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
            $this->db->query($sql);
        }
        
         function createPaymentSettings(){
            $sql = "CREATE TABLE IF NOT EXISTS `payment_settings` (
                    `id_payment` int(15) NOT NULL,
                    `settings` text NOT NULL,
                    `id_registred_company` int(15) NOT NULL,
                    UNIQUE KEY `id_payment` (`id_payment`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
             $this->db->query($sql);
        }
        // возвращает настройки в виде обьекта
        function getSettings(){
            $settings = $this->db->query("Select id_payment,settings from payment_settings where id_registred_company = '$this->id_company'")->row();
            if(isset($settings) and !empty($settings)){
                $settings->settings  = json_decode($settings->settings,true);
                return $settings;
            }
            return false;
        }
        //$settings -> обьект, где $settings->settings - массив основных настроек
        function setSettings($settings){
            //todo написать проверку  обезательных полей
            $settings->settings  = json_encode($settings->settings);
            $this->db->query("insert into payment_settings (id_payment,settings,id_registred_company) values ('$settings->id_payment','$settings->settings',$this->id_company)
                             on duplicate key update settings='$settings->settings'");
            
        }
        
        function getNotification($id_order){
            $notis = $this->db->query("select id_order,payment_status,id_payment,adata from payment_notification where id_order='$id_order'")->row_array();
            if(count($notis)>0){
                $notis['adata']  = json_decode($notis['adata'],true);
                return $notis;
            }
            return false;
        }
        function setNotification($id_order,$notis){
            $key_notis = array_keys($notis);

            $notis['adata'] = json_encode($notis['adata']);
            $sql = "insert into payment_notification (".implode(', ',$key_notis).")
                                        values (".implode(', ',array_map(function($v){return "'$v'";},$notis)).")
                                        on duplicate key update ".implode(', ',array_map(function($key){return "$key =values($key)";},$key_notis)) ;
            $this->db->query($sql);

            return $this->db->affected_rows()>0;
        }
        //$data массив настроек по плтаежной системе и по корзине
        //по идее эта строка должна записаться в таблицу payment_notification
        function formation_payment($cart){
           $success = false;
           $key_payment=array('total'=>'LMI_PAYMENT_AMOUNT','id_order'=>'LMI_PAYMENT_NO','descr'=>'LMI_PAYMENT_DESC');
           $payment = $this->into_arraY($key_payment,$cart,TRUE);
           $key_not_allow=array('SECRET_KEY');
           if(count($payment)>0){
                $settings = $this->getSettings();
                if($settings){
                    $temp = array();
                    foreach($settings->settings as $key=>$value){
                        if(!in_array($key,$key_not_allow)){
                            $temp[$key]=$value;
                        }
                    }
                    $payment['LMI_PAYMENT_DESC']=json_decode($payment['LMI_PAYMENT_DESC'],true);
                    $notis['id_order'] = $cart['id_order'];
                    $notis['payment_status'] =0;
                    $notis['id_payment']=0;
                    $notis['adata'] = array_merge($temp,$payment);
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
                if($key=='LMI_PAYMENT_DESC'){
                    $descr = $value;
                    $value ="";
                    foreach ($descr as $val) {
                        $value.="Товар - {$val['product']}, количество - {$val['quantity']}, цена за шт - {$val['cost']}";
                    }
                }
                $temp[]=$key."=".$value;
            }

            if(count($temp)>0){
//                return "<script type='text/javascript' src='https://paymaster.ru/widget/Basic/1?".implode('$',$temp)."'>
//                </script>"; ;
                return implode('&',$temp);
            }
            return false;
        }
        
        function startPayment($id_order){
            $cart = $this->getOrder($id_order);
            if(!$cart or $cart['payment_status']=='1') return FALSE;
            $notice = $this->formation_payment($cart);
            if($notice){
                $widget = $this->constructWidget($notice['adata']);
                if($widget){
                    $this->setNotification($notice['id_order'],$notice);
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
                               'LMI_SIM_MODE','SECRET_KEY');
            $check = array();
            foreach ($allow_key as $value) {
                if(isset($data[$value]))
                    $check[$value] = $data[$value];
                else
                    return false;
            }

            //надо бы учесть в будущем какая хеш функция задана в настройках на paymaster.ru
            $str = implode(';',$check);
            $string_hash = base64_encode(md5($str, true) );
            return $hash==$string_hash;
        }
        //функция для фильтрации массива по заданным ключам . Для ассоциативных массивов $key_array = array('текущий ключ'=>'новый ключ');
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
            $notice = array();

            if(isset($data['LMI_PAYMENT_NO'])){
                $notice = $this->getNotification($data['LMI_PAYMENT_NO']);
            }

            if(count($notice)==0){
                return FALSE;
            }
            $key_no_allow =array('LMI_PREREQUEST','LMI_PAYMENT_DESC','LMI_SIM_MODE');
            foreach($notice['adata'] as $key=>$value){
                if(!in_array($key,$key_no_allow)  and $value != $data[$key]){
                    return FALSE;
                }
            }
            return TRUE;
        }
        
        function checkNotification($data){
            $secret_key = $this->getSecretKey();
            if($secret_key) $data['SECRET_KEY']=$secret_key;
            else return FALSE;
            if(isset($data['LMI_PAYMENT_NO']) and isset($data['LMI_SYS_PAYMENT_ID']) and $this->compareHash($data['LMI_HASH'],$data)){


                $notice['id_order'] = $data['LMI_PAYMENT_NO'];
                $notice['id_payment'] = $data['LMI_SYS_PAYMENT_ID'];
                $notice['payment_status'] = 1;
                $notice['adata'] = $data;
                //может обьеденить данные приходящие с payment.ru и данные в таблице?
                return  $this->setNotification($notice['id_order'],$notice);
            }
            else return FALSE;
        }
        function getNotificationCompany(){
            return $this->db->query("select c.name, c.surname, s.name_sale, pn.id_order, pn.payment_status, pn.adata from payment_notification pn
                             join orders o on pn.id_order = o.id
                             join sale s on s.id=o.id_sale
                             join customer c on c.id = s.customer_id
                             where c.id_registred_company = '$this->id_company'")->result_array();
        }
        //брать содержимое корзины из базы или вытаскивать из сессии?
        function getOrder($id_order){
           $order = $this->db->query("select o.id id_order,o.price total, o.description descr, pn.payment_status from orders o
                                      left join payment_notification pn on pn.id_order=o.id where id = '$id_order'")->row_array();
           if(count($order)>0){
               return $order;
           }
           else{return FALSE;}
        }
    function getSecretKey(){
        $settings = $this->getSettings();
        if($settings){
            $secret = $settings->settings;
            return isset($secret['SECRET_KEY'])?$secret['SECRET_KEY']:false;
        }
        return false;
    }
    function setLog($data){
        $this->db->query("insert into payment_log (date,data) values('".date('d-m-Y H:i:s')."','".json_encode($data)."')");
    }

    function getLog(){
        return $this->db->get('payment_log')->result_array();
    }

    function checkPayment($id_sys_order){
        $settings = $this->getSettings();
        $data['login'] ='';
        $data['pass'] ='';
        $data['nonce'] = md5(time());
        $data['paymentid'] = $id_sys_order;
        $string_to_hash = implode(';',$data);
        echo $string_to_hash;
        $data['hash'] = base64_encode(md5($string_to_hash,true));
        unset($data['pass']);
        return $data;

    }
        

        
        
}
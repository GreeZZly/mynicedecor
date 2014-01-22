<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Heroin extends CI_Model{
    private $id_registred_company = 2;
    private $db;
    private $phase = array('cart'=>'В корзине',
                           'registration'=>'Оформление',
                            'delivery'=>'Доставка',
                            'paymant'=>'Оплата' );
    private $proccess = "Интернет-магазин";
    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);
        $this->load->library('session');
        
    }
    
    //вставка|обновление привлеченных клиентов
    function setCustomer($id,$array){
            $wtf = FALSE;
           
            if(empty($array['email']) or empty($array['name'])) return FALSE;
            $keys_of_bank  = array('full_name',  'legal_address','head','under','accountant','INN','KPP','bank','BIK','payment_account', 'corr_account','OGRN', 'OKPO','OKVED','OKFS', 'OKOPF', 'OKATO', 'personal_account', 'card_number');
            $bank =  $this->into_arraY($keys_of_bank, $array);

            $keys_of_address =array('country','region','subregion','post_code','ppp','street','house', 'housing','flat');
            $address  = $this->into_arraY($keys_of_address, $array);
            
            $key_contact_info = array('phone', 'phone_work',  'phone_for_sms', 'send_sms',  'send_email',  'IM',  'fax',   'email_home',  'email_work',  'email_reserv',    'site1',   'site2',   'site3');
            $contact_info  = $this->into_arraY($key_contact_info, $array);
            $contact_info['email_home']  =$array['email'];
            $key_customer = array('type',  'name', 'surname', 'photo',  'second_name', 'gender',  'date_registration',   'status',  'responsibility','work_mode_c','dinner_time_c', 'id_contact_info',  'id_address',  'id_bank_details', 'ownership', 'SNILS','INN_c','description','birthday');
            $customer  = $this->into_arraY($key_customer, $array);
            //var_dump($customer);
            $customer['id_contact_info']    = $this->returnInsertedId('id_contact_info' ,'contact_info'     , $contact_info     , $customer);
            $customer['id_address']         = $this->returnInsertedId('id_address'      ,'address'          , $address          , $customer);    
            $customer['id_bank_details']    = $this->returnInsertedId('id_bank_details' ,'banking_details'  , $bank             , $customer);
            $customer['id_registred_company'] = $this->id_registred_company;
            
            if($array["type"]=="individual"){
                $key_contact =array("surname", "name", "second_name" ,"responsibility","gender", "birthday" , "email" , "address_id","id_passport" , "id_bank_details" ,  "id_contact_info" , "id_work_place" , "photo",  "description","date_registration");
                $contact = $this->into_arraY($key_contact,$array);
                $contact['id_contact_info'] =  $customer['id_contact_info'] ;
                $contact['address_id'] =  $customer['id_address'] ;
                $contact['id_bank_details'] = $customer['id_bank_details'];
                
                $keys_work_place = array('company', 'position', 'role','work_mode', 'reception_day');  
                $work_place  = $this->into_arraY($keys_work_place, $array);      
                
                $key_passport = array('number','scan_passport','name_p','surname_p','second_name_p','gender_p','series','passport_issued','date', 'kod','place_birth');
                $passport  = $this->into_arraY($key_passport, $array);    
                
                $customer['id_work_place'] = $this->returnInsertedId('id_work_place'   ,'work_place'       , $work_place       , $customer);
                $customer['id_passport'] = $this->returnInsertedId('id_passport'     ,'passport'         , $passport         , $customer);
                $contact['id_work_place'] = $customer['id_work_place'];
                $contact['id_passport'] = $customer['id_passport'];
            }  
            if(!empty($id)){
               
                $this->db->where('id', $id)->where('id_registred_company',$this->id_registred_company)->set($customer)->update('customer');
                $wtf = $this->db->affected_rows();
                if($customer['type']=='individual'){
                    
                    $contact['id_customer'] = $id;                
                    $this->db->where('id_customer',$id)->where('email',$array['email'])->set($contact)->update('contact');
                }
                //$temp = $this->db2->where('id', $id)->get('customer')->result_array();
            }
            else{
                $this->db->set($customer)->insert('customer');
                $id = $this->db->insert_id();
                $wtf = $id;
                if($customer['type']=='individual'){
                    $contact['id_customer'] = $id;                
                    $this->db->set($contact)->insert('contact');
                }
                
            }
            
          if (!empty($id))
          {
            if(isset($array['user_id']) and !empty($array['user_id'])){
                $this->db->where('id',$array['user_id'])->update('user_is',array('id_customer'=>$id));
            }
            return $id;
          }
          else{ return FALSE;
          }
    }
    
    function setSaleCustomer($id,$array,$reg=FALSE){
            if(empty($array['customer_id'])) return FALSE;
            $type_sale = $this->getProcess();
            //;
            $key_sales = array('name_sale','number','customer_id','responsibility','performer','status','start_deal','time_start','time_end','end_deal','type_sale','open_project'
                ,'start_project','end_project','plan','debt','cost','date_shipment','contract_1c','account_1c','comment','id_document','failure','failure_cause');
            $sale = $this->into_arraY($key_sales, $array);
            
            $sale['type_sale'] = $type_sale['id'];
            if(!empty($id)){
                $this->db->where('id',$id)->update('sale',$sale);
                
            } 
            else{
                $cnt = count($this->db->select('id')->where('customer_id',$sale['customer_id'])->get('sale')->result_array());
                $name_sale = ($reg)?'С регистрацией_':'Без регистрации_';
                $name_sale .=$cnt;
                $sale['name_sale'] = $name_sale;
                $sale['start_deal'] = date('d-m-Y');
                $sale['time_start'] = date('G:i');
                $this->db->insert('sale',$sale);
                $id = $this->db->insert_id();
                $phase  = $this->getPhaseDB($type_sale['id']);
                $first_ph = $phase[0];
                
                $this->setPhase(array(
                                     'id_sale'=>$id,
                                     'phase'=>$first_ph['id'],
                                     'date'=>date('d-m-Y')));
            }
           
            return !empty($id)?$id:FALSE;
            
                }
    function getProcess(){
      $procces =  $this->db->where('id_registred_company',$this->id_registred_company)->where('type_sale','Интернет-магазин')->get('type_sale')->row_array();
      if(count($procces)<1){
          $process = array('type_sale'=>$this->proccess,
                           'id_registred_company'=>$this->id_registred_company);
          $this->db->insert('type_sale',$process);
          $process['id'] = $this->db->insert_id();
      }
      return $procces;
    }
    
    function getPhaseDB($id_type_sale){
        $phase = $this->db->where('id_type_sale',$id_type_sale)->order_by("id","asc")->get('phase_db')->result_array();
        if(count($phase)<1){
            foreach ($this->phase as $value) {
                $phase[] = array('id_type_sale'=>$id_type_sale,
                                     'phase_db'=>$value);
            }
            
            $this->db->insert_batch('phase_db',$phase);
            $phase = $this->db->where('id_type_sale',$id_type_sale)->order_by("id","asc")->get('phase_db')->result_array();
        }
        return $phase;
    }
    function setOrder($id,$data){
        
        $key_order = array('name','price','id_sale','time','date','description');
        $order = $this->into_arraY($key_order, $data);
        $success = false;
        if(!empty($id)){
            $success =  $this->db->where('id',$id)->update('orders',$order);
        }
        else{
            $success =  $this->db->insert('orders',$order);
            $id  = $this->db->insert_id();
        }
                       
       return !empty($id)?$id:false;
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
    function returnInsertedId($id, $table, $data, $target){
        if(!empty($data)){            
            if(!empty($target[$id])){
                $this->db->where('id', $target[$id])->set($data)->update($table);
                return $target[$id];
            } else {
                $this->db->set($data)->insert($table);
                return $this->db->insert_id();
            }
        }
    } 
   
    private function ToCart($id_order,$data,$phase,$reg = false) {
        $phase_sale = $this->getPhaseBySale($data['id_sale']);  // этап продажи
        $last_phase = $phase_sale[count($phase_sale)-1]['phase'];
        
        if($last_phase == $phase)
        {
            $id = $this->setOrder($id_order, $data);
        }
        else{
            $data['id_sale'] = $this->setSaleCustomer(NULL, $data,$reg);
           
            $id = $this->setOrder(null, $data);
        }
        
        return isset($id)?$id:FALSE;
 
    }
  
    
   function setPhase($data){
      // $process=$this->getProcess();
      
       if(empty($data['id_phase'])){
           $this->db->insert('phase',$data);
       }else{
           $this->db->where('id_phase',$data['id_phase'])->update('phase',$data);
       }
   }
   function changePhase($id_order,$phase){
       if(!$id_order) return FALSE;
       //$phase_sale = $this->getByOrder($id_order,'phase'); 
       //$last_phase = $phase_sale[count($phase_sale)-1];
       $sale = $this->db->query("select s.type_sale, s.id from sale s
                                join orders o on o.id_sale = s.id where o.id  = '$id_order'  ")->row_array();
       $phase_sale = $this->getPhaseBySale($sale['id']); 
       $last_phase = $phase_sale[count($phase_sale)-1];
       $phase_db = $this->getPhaseDB($sale['type_sale']);
       
       $complete = array();
       foreach ($phase_db as $key => $value) {
          if($value['id']>$last_phase['id']){
              $complete[] = array('id_sale'=>$sale['id'],
                                  'phase'=>$value['id'],
                                  'date'=>date('d-m-Y'));
          }
          if($value['phase_db']==$phase){
            break;
          }
          
       }
       
       return !empty($complete)?$this->db->insert_batch('phase',$complete):FALSE;
       
   }
   function addToCart($id_order,$order,$reg,$customer=null){
       
        if(isset($customer['user_id'])){
           $_temp =  $this->getCustomerByUserID($customer['user_id']);
          
           if($_temp){  
              $customer['id'] =  $_temp['id'];
              $sale['responsibility'] = $_temp['responsibility'];
            }
        }
        $sale['customer_id'] = (!isset($customer['id']))?$this->setCustomer(NULL, $customer): $customer['id'];
        
        $order['id_sale'] = $this->getByOrder($id_order,"sale"); // todo создать функцию
        $order['customer_id']=$sale['customer_id'];
        if(empty($order['id_sale'])){
             $order['id_sale'] = $this->setSaleCustomer(NULL, $sale,$reg);
             $id_order = null;
        }
        
        $id = $this->ToCart($id_order, $order,$this->phase['cart'],$reg);
        return isset($id)?$id:FALSE;
   }
   
   function start($id_order, $data, $phase){
        
        
        $id =  $this->addToCart($id_order, $data, $data['reg'],$data['customer']);
        if($phase!=$this->phase['cart']) $this->changePhase($id,$phase);
        return $id;
   }
   private function getByOrder($id_order,$byWHO){
        if($byWHO=='phase'){ 
            $phase = $this->db->query("select p.id_phase, p.id_sale, pd.id, pd.phase_db phase, p.date  from phase p
                                      join orders o on o.id_sale = p.id_sale 
                                      join phase_db pd on pd.id = p.phase
                                      where o.id='$id_order'")->result_array();
             return !empty($phase)?$phase:FALSE;
        }
       $customer =  $this->db->query("select c.id customer,s.id sale from customer c "
                . "join sale s on s.customer_id = c.id "
                . "join orders o on s.id = o.id_sale where o.id ='$id_order' and c.id_registred_company ='$this->id_registred_company' ")->row_array();
        if($byWHO=='customer'){
        
            return !empty($customer['customer'])?$customer['customer']:FALSE;
        }
        elseif($byWHO=='sale'){
            
            return !empty($customer['sale'])?$customer['sale']:FALSE;
        }
        return FALSE;
   }
   function getCustomerByUserID($id){
        $cus = $this->db->query("select ui.id_customer id, c.responsibility from user_is ui join customer c on c.id = ui.id_customer where c.id_registred_company='$this->id_registred_company' and ui.id = '$id'")->row_array();
        return count($cus)>0?$cus:FALSE;
   }
   function getPhaseBySale($id) {
        $phase = $this->db->query("select p.id_phase, p.id_sale, pd.id, pd.phase_db phase, p.date from phase p
                                      join phase_db pd on pd.id = p.phase
                                      where p.id_sale='$id'")->result_array();
        return !empty($phase)?$phase:false;
   }
   
   
    
    
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


class Cocaine extends CI_Model {

    private $id_registred_company;
    function __construct()
    {
         parent::__construct();
        $this->db2 = $this->load->database('default', TRUE);
        
        // Call the Model constructor
       $this->id_registred_company = 2;
    }
    function get_clients($whos, $pattern=""){
        $id =$this->id_registred_company;
        
        $sql = "SELECT c.id, IF(c.type='legal' ,concat('&quot;',c.name,'&quot; ',c.ownership),concat(ifnull(c.surname,''),' ',ifnull(c.name,''),' ',ifnull(c.second_name,''))) AS company, c.name AS realname, c.status, IFNULL(concat(u.surname,' ',LEFT(u.name,1),'.'),'Нет ответственного') AS trainer, IFNULL(t.prediction,'0') as prediction, c.date_registration AS date, c.label FROM customer c
LEFT JOIN
(SELECT s.customer_id, sum(pr.prognosis) as prediction FROM prognosis pr LEFT JOIN sale s ON pr.id_sale = s.id group by s.customer_id ) t on t.customer_id = c.id
Left join user u on c.responsibility=u.id WHERE c.label = '1' AND c.id_registred_company='$id'";
        switch($whos){
            case "all":
            break;
            case 'captured':
                $sql .=" and c.captured='1'";
            break;
            case 'free':
             $sql .=" and c.responsibility='free'";  
            break;
            default:
                $sql .=" and c.responsibility='$whos'";
        }
        if ($pattern!='') $sql .= " and (c.name like '%$pattern%' or c.surname like '%$pattern%' or c.second_name like '%$pattern%' or c.ownership like '%$pattern%')";
        return $this->db2->query($sql)->result_array();
    }
    function clientsList(){
        $id =  $this->id_registred_company;
        $sql = "SELECT c.id as value, IF(c.type='legal' ,concat(c.name,' ',c.ownership),concat(ifnull(c.surname,''),' ',ifnull(c.name,''),' ',ifnull(c.second_name,''))) AS label from customer c where c.label = 1 and c.id_registred_company =$id";
        return $this->db2->query($sql)->result_array();
    }
    function delete_entries($array){
        foreach ($array as $id) {
            $data = array('label' => 0);
            $this->db2->where('id', $id);
            $this->db2->where('id_registred_company',$this->id_registred_company);
            $this->db2->update('customer', $data); 
        }
    }
    function company_info($name, $id){
        $irc = $this->id_registred_company;
        //$this->db2->where('name',$name);
        $this->db2->where('id',$id);
        $this->db2->where('id_registred_company',$irc);
        $info = $this->db2->get('customer')->result_array();
        $r = $this->getResponsibility($irc,$info[0]["responsibility"]);
       // $info[0]['responsibility'] = (in_array(0, $r)) ? $r[0]["fio"] : '';
        $info[0]['responsibility'] = (count($r)>0) ? $r[0]["fio"] : '';
        $info[0]['address'] = $this->get_adress_string($info[0]['id_address']);
        $info[0]['contacts'] = $this->get_contacts_array($info[0]['id']);
        $info[0]['sales'] = $this->get_sales_array("",$info[0]['id']); //id по кастомеру
        $info[0]['segment'] = $this->get_segments_array($info[0]['id']);
        $info[0]['plans'] = $this->get_plan_by("",$info[0]["id"],true);
       //$cont_resp = $this->getResponsibility("1",);
        $info[0]["order"]=$this->db2->query("select * from orders")->result_array();
        $info[0]['bank_details'] = $this->get_bank_details($info[0]['id_bank_details']);
        $info[0]['contact_info'] = $this->get_contact_info($info[0]['id_contact_info']);
        $info[0]['work_place'] = $this->get_work_place($info[0]['id_work_place']);
        $info[0]['passport'] = $this->get_passport($info[0]['id_passport']);
        $address = $this->db2->where('id', $info[0]['id_address'])->get('address')->result_array();
        unset($info[0]['id_address']);
        //$info[0] = $info[0] + $address[0];
        return $info + $address;
    }
    function get_contact_info($id){
        if($id){    
            $this->db2->where('id',$id);
            return $this->db2->get('contact_info')->row(); 
        }// $str = implode(', ',array_values(array_diff((array)$test,array(null))));
        
    }
    function get_bank_details($id){
        if($id){    
            $this->db2->where('id',$id);
            return $this->db2->get('banking_details')->row(); 
        }
    }
     function get_work_place($id){
        if($id){    
            $this->db2->where('id',$id);
            return $this->db2->get('work_place')->row(); 
        }
    }
     function get_passport($id){
        if($id){    
            $this->db2->where('id',$id);
            return $this->db2->get('passport')->row(); 
        }
    }
    function get_adress_string($id){
        if($id){
            $test = $this->db2->query("select post_code,country,region,subregion,ppp,street,concat('д.  ',house) as house, concat('корп. ',housing) as housing, concat('кв. ', flat) as flat from address where id = {$id}")->row();
            $str = implode(', ',array_values(array_diff((array)$test,array(null))));
            return $str;
        }

    }
    function get_sales_array($stage="",$id_customer=null,$id_sale=null,$id_respons=null){
        $id_registred_company=$this->id_registred_company;
        $select = "SELECT  sale.*, phase.*, t.prognosis, m.payment, IFNULL(concat(u.surname,' ',LEFT(u.name,1),'.'),'Нет ответственного') AS responsibility ";
        $from = "FROM sale ";
        $join = "Left join (select max(id_phase) id_phase, id_sale, phase,date from phase group by id_sale) phase On sale.id = phase.id_sale
            LEFT JOIN
            (SELECT s.id, sum(pr.prognosis) as prognosis FROM prognosis pr LEFT JOIN sale s ON pr.id_sale = s.id group by s.id ) t on t.id = sale.id
            LEFT JOIN
            (SELECT s.id, sum(p.payment) as payment FROM payment p LEFT JOIN sale s ON p.id_sale = s.id group by s.id ) m on m.id = sale.id
            Left join user u on sale.responsibility=u.id 
             Left join customer c on sale.customer_id = c.id ";
        $where = "Where c.id_registred_company ='$id_registred_company' ";
        if(!$id_sale && $id_customer){
            $where .= "and sale.customer_id='$id_customer'";
            $sql = $select.$from.$join.$where;
            $temp =  $this->db2->query($sql)->result_array();
            return $temp;
        }
       if($id_sale){

                $info = $this->db2->where('id', $id_sale)->get('sale')->result_array();
                $info[0]['phase']    = $this->db2->query("select max(id_phase) id_phase, id_sale, phase,date from phase where id_sale='$id_sale'")->result_array();
                $info[0]['category'] = $this->db2->select('id, name')->get('category')->result_array();
                $info[0]['products'] = $this->db2->where('category_id',$info[0]['category'][0]['id'])->get('products')->result_array();
                $info[0]['prgnosis'] = $this->db2->where('id_sale',$id_sale)->get('prognosis')->result_array();
                $info[0]['responsibility'] = $this->getResponsibility($id_registred_company, $info[0]['responsobility']); //;
                $info[0]['payment']  = $this->db2->where('id_sale',$id_sale)->get('payment')->result_array();
                $info[0]['document'] = $this->db2->where('id',$info[0]['id_document'])->get('documents')->result_array();
                return $info[0];
        }
        if (empty($id_customer)){
            $select.= ",cus.id as cid, IF(cus.type='legal' ,concat('&quot;',cus.name,'&quot; ',cus.ownership),concat(ifnull(cus.surname,''),' ',ifnull(cus.name,''),' ',ifnull(cus.second_name,''))) AS company_name ";
            $join .= "left join customer cus on sale.customer_id=cus.id ";
            $where.=(strstr($stage,'open'))?" and failure ='0'":"and failure ='1'";
            if(strstr($stage,'mine')) $where.=" and sale.responsibility = '$id_respons'";
            $sql=$select.$from.$join.$where;
            return $this->db2->query($sql)->result_array();
        }

    }
    function get_last_phase($id_sale){
        return $this->db2->query("select p.id_phase, p.id_sale, p.phase,p.date, s.type_sale as process from phase p join sale s on s.id = '$id_sale' where p.id_sale='$id_sale' order by id_phase desc limit 1 ")->result_array();
    }
    function next_phase($what){
        if ($this->get_last_phase($what['id_sale'])[0]['phase']!=$what['phase']) {
            $this->db->insert('phase', $what);
        }
        return $this->db2->affected_rows()>0;
    }
    function get_segments_array($id_customer){
        //сегменты хранятся в json
         return $this->db2->query("select id, id_customer, segment from segment where id_customer='$id_customer'")->result_array();
//        if (! $test) return '';
//        foreach ($test as $val) {
//            $temp[$val['sector']][] = $val['segment'].'#'.$val['sid'];
//            $temp[$val['sector']]['id'] = $val['id'];
//        }
//        $temp['max_count']=max(array_map(function($a){ return count($a);},$temp));//поиск максимального значения среди массива, сформированного из количеств элементов массивов с помощъю лямбда-функции, вобщем тупо максимальное количество сегментов в секторе
//        return $temp;
    }
    function get_contacts_array($id){
        return $this->db2->query("SELECT *,concat(c.surname,' ',c.name,' ',c.second_name) as fullname,c.id as id  FROM contact c
        Left join address a on c.address_id=a.id
        left join passport p on c.id_passport=p.id
        left join banking_details b on c.id_bank_details=b.id
        left join contact_info co on c.id_contact_info=co.id
        left join work_place w on c.id_work_place=w.id
        where c.id_customer ='$id'")->result_array();
        // $this->db2->where('id_customer',$id);
        // $temp = $this->db2->get('contact')->result_array();
        // if (!$temp) return '';
        // foreach ($temp as $key => $value) {
        //     $temp[$key]['fullname'] = $value['surname'].' '.$value['name'].' '.$value['second_name'];
        //     $temp[$key]['bank_details'] = $this->get_bank_details($temp[0]['id_bank_details']);
        //     $temp[$key]['contact_temp'] = $this->get_contact_info($temp[0]['id_contact_info']);
        //     $temp[$key]['work_place'] = $this->get_work_place($temp[0]['id_work_place']);
        //     $temp[$key]['passport'] = $this->get_passport($temp[0]['id_passport']);

        // }
        return $temp;
    }
    //данные для редактирования
    function get_record_from($table, $field='id', $value){
    $id_reg_company = $this->id_registred_company;
    if ($table=='customer'){
           
        return  $this->db2->query("SELECT c.* , a.country, a.region, a.subregion,  a.post_code, a.ppp, a.street, a.house,      
            a.housing, a.flat, con.phone, con.phone_home, con.phone_work, con.phone_for_sms, con.IM,     
            con.fax, con.email_home, con.email_work, con.email_reserv, con.site1, con.site2, con.site3,         
            p.number, p.series, p.date, p.kod, p.place_birth, p.scan_passport, w.company, w.position, w.role, w.work_mode,      
            w.reception_day, w.fired_day, b.full_name, b.legal_address, b.head, b.under, b.accountant,      
            b.INN, b.KPP, b.bank, b.BIK, b.payment_account, b.corr_account, b.OGRN, b.OKPO, b.OKVED,        
            b.OKFS, b.OKOPF, b.OKATO, b.personal_account, b.card_number
            FROM customer c
            Left join contact_info con on c.id_contact_info = con.id
            Left join address a on c.id_address = a.id
            left join passport p on c.id_passport = p.id 
            left join work_place w on c.id_work_place = w.id
            left join banking_details b  on c.id_bank_details = b.id
            WHERE c.id_registred_company = '$id_reg_company' and
             c.id = '".intval($value)."'")->result_array();
    }
    if($table=='sale'){
        $test = array();
            $test["products"] =array();
            if($value){
                /*s.id, s.number,s.id_product, s.start_deal, s.time_start, s.end_deal, s.time_end, s.debt,s.failure, s.failure_cause,s.id_document */
            $sale     =$this->db2->query("select s.* from sale s where  s.id = '$value'")->row_array();
            $prgnosis =$this->db2->query("select id,prognosis,prognosis_date from prognosis where id_sale = {$sale['id']}")->result_array() ;

            $payment  =$this->db2->query("select id,payment,payment_date from payment where id_sale = {$sale['id']}")->result_array() ;
            $phase  =$this->db2->query("select id_phase as id,phase,date from phase where id_sale = {$sale['id']}")->result_array() ;
             $return_array["sales"]     = $sale;
            //if(count($prgnosis)>0) 
            $return_array["prognosis"] = $prgnosis;
            //if(count($payment)>0) 
            $return_array["payment"]   = $payment;
            $return_array["phase"]   = $phase;
            return $return_array;
        }
//            $category =$this->getCategories();
//            //$phase     =$this->db2->query("select id_phase, phase from phase ")->result_array();
//            $products =$this->getProductsById($category[0]["id"]);
//            $resp     =$this->getResponsibility();
//                foreach ($products as $value) {
//                    $test["products"][] = array("id"     =>$value["id"],
//                                                "product"=>$value["product"]);
//               }
//
//               foreach ($category as  $value) {
//                   $cat["categories"][] = array("id"  =>$value["id"],
//                                                "name"=>$value["name"]);
//               }
//
//            $return_array["product"]   = $test["products"];
//            $return_array["categoris"] = $cat["categories"];
//
//            $return_array["responsibility"]   = $resp;
            return "";
    }
    if($table =='contact'){
        return $this->db2->query("select w.*, c.* from contact c join work_place w on c.id_work_place = w.id where c.id ='$value'")->result_array();
    }
//
    if($table=='order'){
        $temp = $this->db2->query("select * from orders c where id ='$value'")->result_array();
        $lo = $this->db2->query("select max(id) as id from orders")->result_array();
        $temp[0]['number'] = ($lo[0]['id'] or 0)+1;
        return $temp;
    }
    else{
    $this->db2->where($field, $value);
        return $this->db2->get($table)->result_array();
    }

    }
    //возвращает true если клиент принадлежит компании
    function check_company($id){
        return $this->db2->where('id',$id)->where('id_registred_company',$this->id_registred_company)->count_all_result('customer')>0;
//        $id_reg_company = $this->id_registred_company;
//        $sql = "";
//        $join = "join $table on c.id = ";
//        $where = "where";
//        //echo "{$table}";
//        switch ($table){
//            case "customer":
//               // $sql.=" and c.id_registred_company = '$id_reg_company' ";
//                $sql = "select c.id from customer where c.id_registred_company = $id_reg_company where c.id = $id"; 
//                break;
//            case "sale":
//                 $sql = "select c.id from customer c join sale s on s.customer_id = c.id where c.id_registred_company = $id_reg_company and s.id = $id";
//                break;
//            case "contact":
//                  $sql = "select c.id from customer c join contact con on con.id_customer = c.id where c.id_registred_company = $id_reg_company and con.id = $id";
//               break;
//            case "plans":
//                  $sql = "select c.id from customer c join plans p on p.id_customer = c.id where c.id_registred_company = $id_reg_company and p.id = $id";
//               break;
//            case "orders":
//                  $sql = "select c.id from customer c join sale s on s.customer_id = c.id join orders o on o.id_sale = s.id where c.id_registred_company = $id_reg_company and o.id=$id";
//               break;    
           
       // }
       // return $sql;
    }
    function edit_record_from($id, $table, $array){
        //$id = (isset($id))?$id:(!empty($array["id"])?$array["id"]:null;
        // print_r($id);
        // print_r($table);
        // print_r($array);
        $wtf = true;
        //TODO сделать проверку на id_registred_company
        //        if(!$this->check_company()){
        //            return FALSE;
        //        }
        if($table =='customer'){
           // print_r($array);
            
            $keys_of_bank  = array('full_name',  'legal_address','head','under','accountant','INN','KPP','bank','BIK','payment_account', 'corr_account','OGRN', 'OKPO','OKVED','OKFS', 'OKOPF', 'OKATO', 'personal_account', 'card_number');
            $bank =  $this->into_arraY($keys_of_bank, $array);

            $keys_of_address =array('country','region','subregion','post_code','ppp','street','house', 'housing','flat');
            $address  = $this->into_arraY($keys_of_address, $array);

            $key_contact_info = array('phone', 'phone_work',  'phone_for_sms', 'send_sms',  'send_email',  'IM',  'fax',   'email_home',  'email_work',  'email_reserv',    'site1',   'site2',   'site3');
            $contact_info  = $this->into_arraY($key_contact_info, $array);

            $key_customer = array('type',  'name', 'surname', 'photo',  'second_name', 'gender',  'date_registration',   'status',  'responsibility','work_mode_c','dinner_time_c', 'id_contact_info',  'id_address',  'id_bank_details', 'ownership', 'SNILS','INN_c','description','birthday');
            $customer  = $this->into_arraY($key_customer, $array);
            //var_dump($customer);
            $customer['id_contact_info']    = $this->returnInsertedId('id_contact_info' ,'contact_info'     , $contact_info     , $customer);
            $customer['id_address']         = $this->returnInsertedId('id_address'      ,'address'          , $address          , $customer);    
            $customer['id_bank_details']    = $this->returnInsertedId('id_bank_details' ,'banking_details'  , $bank             , $customer);
            $customer['id_registred_company'] = $this->id_registred_company;
            if($array["type"]=="individual"){
                $keys_work_place = array('company', 'position', 'role','work_mode', 'reception_day');  
                $work_place  = $this->into_arraY($keys_work_place, $array);               
                $key_passport = array('number','series','passport_issued','date', 'kod','place_birth');
                $passport  = $this->into_arraY($key_passport, $array);    
                $customer['id_work_place']      = $this->returnInsertedId('id_work_place'   ,'work_place'       , $work_place       , $customer);
                $customer['id_passport']        = $this->returnInsertedId('id_passport'     ,'passport'         , $passport         , $customer);
            }  
            if(!empty($id)){
               
                $this->db2->where('id', $id)->where('id_registred_company',$this->id_registred_company)->set($customer)->update($table);
                
                //$temp = $this->db2->where('id', $id)->get('customer')->result_array();
            }
            else{
                $this->db2->set($customer)->insert($table);
                $wtf = $this->db2->insert_id();
            }
        }
        if ($table == 'sale'){
           // print_r($array);
            $key_sales = array('name_sale','number','responsibility','performer','status','start_deal','time_start','time_end','end_deal','type_sale','open_project'
                ,'start_project','end_project','plan','debt','cost','date_shipment','contract_1c','account_1c','comment','id_document','failure','failure_cause');
            $array["id_document"] =!empty($array["id_document"])? $array["id_document"]: NULL;
            $sale = $this->into_arraY($key_sales, $array);
            $sale["customer_id"] = $array['customer_id'];
            //$sale["id_product"] = $array["product"];
            $key_phase = array('id_sale','phase');
            $phase = $this->into_arraY($key_phase, $array);
            $phase['date'] = date('d-m-Y');
          //  $phase['id_sale'] = $this->returnInsertedId('id','sale',$sale,$phase); 
            if(isset($array['prognosis'])){
                $prgns = array();
                foreach($array['prognosis'] as $key=>$value){
                    if(!isset($array['prognosis_id'][$key]))
                        $prgns[] = array('prognosis_date'=>$array['prognosis_date'][$key],'prognosis'=>$value);
                    else
                        $prgns[] = array('id'=>$array['prognosis_id'][$key],'prognosis_date'=>$array['prognosis_date'][$key],'prognosis'=>$value);
                }
            }
            if(isset($array['payment'])){
                $paymnt = array();
                foreach($array['payment'] as $key=>$value){
                    if (!isset($array['payment_id'][$key]))
                        $paymnt[] = array('payment_date'=>$array['payment_date'][$key],'payment'=>$value);
                    else
                        $paymnt[] = array('id'=>$array['payment_id'][$key],'payment_date'=>$array['payment_date'][$key],'payment'=>$value);
                }
            }
            $sale_id=null;
            
            if(!empty($id)){
                $this->db2->where('id',$id)->set($sale)->update($table);
                $sale_id = $id;
                $phase["id_sale"] = $sale_id;

                // $sql = "INSERT INTO menu_sub (id_sale, phase)
                //      VALUES (?, ?)
                //      ON DUPLICATE KEY UPDATE 
                //      id_sale='$sale_id', 
                //      phase={phase['phase']}";
                //     $this->db2->query($sql, array($id_sale,{phase['phase']}))
                ;
                //TODO сделать функции для всего этого
                //$this->db2->where('id_sale',$id)->set($phase)->update('phase');
                if(isset($array['prognosis'])){
                    foreach($prgns as $value){
                        $value["id_sale"] = $sale_id;
                        if(isset($value['id'])){
                            $this->db2->where('id_sale',$id)->where('id',$value['id'])->set($value)->update('prognosis');
                        }
                        else{
                            $this->db2->insert('prognosis',$value);
                        }
                    }
                }
                if(isset($array['payment'])){
                    foreach ($paymnt as $value) {
                        $value["id_sale"] = $sale_id;
                        if(isset($value['id']))
                            $this->db2->where('id_sale',$id)->where('id',$value['id'])->set($value)->update('payment');
                        else
                            $this->db2->insert('payment',$value);
                    }
                }
            } else {
                $this->db2->set($sale)->insert($table);
                $sale_id = $this->db2->insert_id();
                $wtf = $sale_id;
                $phase["id_sale"] = $sale_id;
                //$prgns["id_sale"] = $sale_id;
              //  $paymnt["id_sale"] = $sale_id;
                if(isset($array['payment'])){
                    foreach ($paymnt as $key=>$value) {
                       $paymnt[$key]["id_sale"]=$sale_id;
                    }
                    $this->db2->insert_batch('payment',$paymnt);
                }
                if(isset($array['prognosis'])){
                    foreach($prgns as $key=>$value){
                        $prgns[$key]["id_sale"] = $sale_id;
                    }
                    $this->db2->insert_batch('prognosis',$prgns);
                }
               // $this->db2->set($phase)->insert('phase');

            }
            foreach ($array['phase'] as $key=>$v){
	                    $this->db2->query("insert into phase (id_sale,phase,date) values ('$sale_id','{$v['phase']}','{$v['date']}')
						on duplicate key update date = '{$v['date']}'");
	    } 
           // echo $k;
            

            //  $array["id_sale"]=$sale_id; $sale["id"] = $sale_id;
            //  $prgId = $this->returnInsertedId('id','prognosis',$this->into_arraY($key_prgnos, $array),$sale);
            // $pymntId = $this->returnInsertedId('id','payment',$this->into_arraY($key_pymnt, $array),$sale);
            
        }
        if ($table == 'contact'){
            
           // $temp[0]['fullname'] = $temp[0]['surname'].' '.$temp[0]['name'].' '.$temp[0]['second_name'];
            $key_contact =array("id_customer","surname", "name", "second_name" ,"responsobility","gender", "birthday" , "email" , "address_id","id_passport" , "id_bank_details" ,  "id_contact_info" , "id_work_place" , "photo",  "description");
            $contact = $this->into_arraY($key_contact,$array);
                

            $keys_of_bank  = array('full_name',  'legal_address','head','under','accountant','INN','KPP','bank','BIK','payment_account', 'corr_account','OGRN', 'OKPO','OKVED','OKFS', 'OKOPF', 'OKATO', 'personal_account', 'card_number');
            $bank =  $this->into_arraY($keys_of_bank, $array);

            $keys_of_address =array('country','region','subregion','post_code','ppp','street','house', 'housing','flat');
            $address  = $this->into_arraY($keys_of_address, $array);

            $key_contact_info = array('phone', 'phone_work',  'phone_for_sms', 'send_sms',  'send_email',  'IM',  'fax',   'email_home',  'email_work',  'email_reserv',    'site1',   'site2',   'site3');
            $contact_info  = $this->into_arraY($key_contact_info, $array);

            $keys_work_place = array('company', 'position','route', 'role','work_mode', 'reception_day');  
            $work_place  = $this->into_arraY($keys_work_place, $array);               

            $key_passport = array('number','series','passport_issued','date', 'kod','place_birth');
            $passport  = $this->into_arraY($key_passport, $array);    
            
             if(!empty($id)){    
               
            $contact['id_contact_info']    = $this->returnInsertedId('id_contact_info' ,'contact_info'     , $contact_info     , $contact);
            $contact['address_id']         = $this->returnInsertedId('address_id'      ,'address'          , $address          , $contact);    
            $contact['id_bank_details']    = $this->returnInsertedId('id_bank_details' ,'banking_details'  , $bank             , $contact);
            $contact['id_work_place']      = $this->returnInsertedId('id_work_place'   ,'work_place'       , $work_place       , $contact);
            $contact['id_passport']        = $this->returnInsertedId('id_passport'     ,'passport'         , $passport         , $contact);
             $this->db2->where('id', $id)->set($contact)->update($table);
        
                //$temp = $this->db2->where('id', $id)->get('customer')->result_array();
            }
            else{
               
            $contact['id_contact_info']    = $this->returnInsertedId('id_contact_info' ,'contact_info'     , $contact_info     , $contact);
            $contact['address_id']         = $this->returnInsertedId('address_id'      ,'address'          , $address          , $contact);    
            $contact['id_bank_details']    = $this->returnInsertedId('id_bank_details' ,'banking_details'  , $bank             , $contact);
            $contact['id_work_place']      = $this->returnInsertedId('id_work_place'   ,'work_place'       , $work_place       , $contact);
            $contact['id_passport']        = $this->returnInsertedId('id_passport'     ,'passport'         , $passport         , $contact);
             $this->db2->set($contact)->insert($table);
             $wtf = $this->db2->insert_id();
        
                 }

        }
        if ($table == 'plan'){
            // $keys_of_address =array('country','region','post_code','subregion','ppp','street','house', 'housing','flat');
            // $address  = $this->into_arraY($keys_of_address, $array);
            $key_plan = array('action','date','time','alert','sale_name','responsibility','id_contact','id_customer','performer','task','result','phase');
            $plan  = $this->into_arraY($key_plan, $array);
            if($plan['sale_name']==0 or $plan['id_contact']==0) return false;
           // $plan['sale_name'] = $plan['sale_name'] || '1';
            //var_dump($customer);
            //$plan['id_address']= $this->returnInsertedId('id_address','address', $address, $plan);
	    $last_phase = $this->get_last_phase($plan['sale_name']);
	    $phase =$array['phase'];
	    $complete_phase = array();
	    $complete_phase = $this->completePhase($last_phase,$phase);
	    if(!empty($complete_phase))
	    {
	       $this->db2->insert_batch('phase',$complete_phase);
	    }
	    $plan['phase']=null;
            if(!empty($id)){    
                $this->db2->where('id', $id)->set($plan)->update('plans');
                $temp = $this->db2->where('id', $id)->get('customer')->result_array();
            }
            else{
                $this->db2->set($plan)->insert('plans');
                $wtf = $this->db2->insert_id();
            }
        }
        if($table=='segment'){

            $id_customer = $array['id_customer'];
            unset($array['id_customer']);
            $segment = json_encode($array);
            if(!empty($id)){
                $this->db2->query("update segment set segment = '$segment' where id='$id' and id_cistomer='$id_customer'");
                $wtf = $this->db2->affected_row()>0;
            }
            else{
                $this->db2->query("insert into segment (id_customer,segment) values ('$id_customer','$segment') ");
                $wtf = $this->db2->insert_id();
            }
               
        }
        if($table=='order'){
            
            $key_order = array('name,price,id_sale,time,date');
      
            $order = $this->into_arraY($key_order, $array);
            $order['description'] = $array['description_o'];
            if($id){
               $this->db2->where('id', $id)->set($order)->update($table);
            }
            else{
                $this->db2->set($order)->insert($table);
                $wtf = $this->db2->insert_id();
            }
        }
        return $wtf;
    }
    function completePhase($last_phase,$now_phase){
		$l_p = intval($last_phase[0]['phase']);
		$n_p =  intval($now_phase[0]['phase']);
		if($l_p>=$n_p)
			return false;
		$k=0;
		
		while($l_p<$n_p){
		    $l_p++;
			$complete_phase[$k]['id_sale'] = $last_phase[0]['id_sale'];
			$complete_phase[$k]['phase'] = $l_p;
			$complete_phase[$k]['date'] = $now_phase[0]['date'];
			$k++;
		}
		return $complete_phase;
		
    }
    function get_plan_by($date, $id_customer = null, $all=null){
        $id_customer = isset($id_customer)?"where cu.id_registred_company='$this->id_registred_company' and p.id_customer = '$id_customer'":"where cu.id_registred_company='$this->id_registred_company'";
        if($all){
            
            // $temp =  $this->db2->where('id_customer',$id_customer)->where("id_registred_company",$id_registred_company)->get('plans')->result_array();
           $temp =  $this->db2->query("SELECT p.*,cu.name as customer, cu.ownership as own, s.name_sale as sale, s.number , a.*, p.id as id, p.action as event, concat(con.surname,' ',con.name) as contact, concat(u.surname,' ',u.name,' ') as responsibility FROM plans p 
            
            left join customer cu on p.id_customer = cu.id 
            left join sale s on p.sale_name = s.id
            left join address a on p.id_address = a.id join user u on u.id = p.responsibility join contact con on con.id = p.id_contact $id_customer ")->result_array();
           //left Join contact c on p.id_customer = c.id_customer
           
                 // foreach ($temp as $key => $value) {
                 // $c =  $this->db2->query(" from contact where id = {$temp[$key]['id_contact']}")->row_array();
                 //   $temp[$key]["contact"] =$c['contact'];
     //  $temp[$key]["customer"] = $this->db2->select("name")->where("id",$id_customer)->get("customer")->row_array();
//       $temp[$key]["sale_name "] = $this->db2->select("name_sale,number")->where("id",$temp[0]["sale_name"])->get('sale')->row_array();
//       $temp[$key]["address"]= $this->db2->where("id",$temp[0]["id_address"])->get('address')->row_array();
           
       //    $this->dbgPrnt($temp);
           return $temp;
        } else {
                 $temp =  $this->db2->query("SELECT p.* ,cu.name as customer, s.name_sale, s.number , a.*, p.id as id, concat(u.surname,' ',u.name,' ') as responsibility, concat(us.surname,' ',us.name,' ') as performer  FROM plans p  
                 left join customer cu on p.id_customer = cu.id 
                 left join sale s on p.sale_name = s.id
                 left join address a on p.id_address = a.id 
                 join user u on u.id = p.responsibility
                 join user us on us.id = p.performer
                 where p.date = '$date' and cu.id_registred_company='$this->id_registred_company' order by p.time   
                 ")->result_array();
                if(!$temp){
                    return false;
                }
                 $temp[0]['contact'] = $this->db2->where("id",$temp[0]['id_contact'])->get('contact')->row_array();
//            $temp =  $this->db2->where('id_customer',$id_customer)->where("id_registred_company",$id_registred_company)->where("date",$date)->get('plans')->result_array();
//            $temp[0]["contacts"] =   $this->db2->where("id_customer",$id_customer)->where()->get('contact')->result_array();
//            $temp[0]["customer"] = $this->db2->select("name")->where("id",$id_customer)->get("customer")->row_array();
//            $temp[0]["sale_name "] = $this->db2->select("name_sale,number")->where("id",$temp[0]["sale_name"])->get('sale')->row_array();
//            $temp[0]["address"]= $this->db2->where("id",$temp[0]["id_address"])->get('address')->row_array();
           return $temp;
        }
    }
    function getPlansByWeek($week,$year){
        
        $days = (($week - 1) * 7) +3 - (1 + date('w',mktime(0,0,0,1,1,$year)));
        $start =  date('d-m-Y',mktime(0,0,0,1,$days,$year));
        $end = date('d-m-Y',mktime(0,0,0,1,$days+6,$year));
       
        // $start = explodeDate($date);
        // $end = explodeDate($date);
        $temp =  $this->db2->query("select p.*, concat(c.ownership, ' \"', c.name, '\"') as customer from plans p join customer c on c.id = p.id_customer where action like 'meet%' and p.date between '$start' and '$end' and c.id_registred_company='$this->id_registred_company' order by p.time")->result_array();
        
        return $temp;


    }
    function get_plan_by_sale_id($id_sale){
        return $this->db2->query("select p.*, concat(c.surname,' ',c.name) as contact_name, concat(u.surname,' ',u.name) as performer  from plans p
        LEFT JOIN contact c on  c.id= p.id_contact
        left join user u on u.id = p.performer
        Where p.sale_name='$id_sale' and u.id_registred_company='$this->id_registred_company'")->result_array();

    }
    function basic_search($keyword, $mode){
        if ($keyword == 'show_all'){
            return $this->db2->select("id, concat(ownership, ' \"', name, '\"') as name", false)->distinct()->where('type', $mode)->get('customer')->result_array();           
        } else {
            $keyword = htmlspecialchars(stripslashes(trim($keyword)));
            $this->db2->select("id, concat(ownership, ' \"', name, '\"') as name", false)->distinct();
            if ($mode == 'both'){
                $this->db2->like('name', $keyword);
            } elseif ($mode != 'none') {
                $this->db2->where('type', $mode)->like('name', $keyword);
            } elseif ($mode == 'none') {
                return null;
            }
            return $this->db2->get('customer')->result_array();
        }
    }
    function into_arraY($mass, $array){
        $temp = array();
        foreach ($mass as $value) {
            $temp[$value] = isset($array[$value]) ? $array[$value] : null;
        }
        return $temp;
    }
    function returnInsertedId($id, $table, $data, $target){
        if($data){            
            if(!empty($target[$id])){
                $this->db2->where('id', $target[$id])->set($data)->update($table);
                return $target[$id];
            } else {
                $this->db2->set($data)->insert($table);
                return $this->db2->insert_id();
            }
        }
    } 
    function insertSales($mass){
        $this->db2->insert("",$mass);
        $id =  $this->db2->insert_id();
        $this->db2->insert("sale",$mass);

    }
    function delete_from($table, $id){
        $this->db2->where('id', $id)->delete($table);
        return "mizurable string deleted $id $table";
    }  
    function entries(){
        $query = $this->db2->get('customer',20);
        return $query->result_array();
    }
    function entries_from($page){
        $array['label'] = 1;
        $query = $this->db2->get_where('customer',$array,20,($page-1)*20);
        return $query->result_array();
    }
    function entries_count(){
        $array['label'] = 1;
        return count($this->db2->get_where('customer',$array)->result_array());
    }
    function entries_filter($array,$page){
        $array['label'] = 1;
        $query = $this->db2->get_where('customer',$array,20,($page-1)*20);
        return $query->result_array();
    }
    function entries_filter_count($array,$page){
        $array['label'] = 1;
        return count($this->db2->get_where('customer',$array)->result_array());
    }
    function entries_unique($column){
        $this->db2->distinct();
        $this->db2->select($column);
        $this->db2->where('label',1);
        return $this->db2->get('customer')->result_array();
    }
    function dbgPrnt($arg){
        echo '<pre>';
        print_r($arg);
        echo '</pre>';
    }
    function getCategories(){
        return $this->db2->query("select id, name from category ")->result_array();
    }
    function getProductsById($id=null){
        if($id)
            return $this->db2->query("select id, product from products where category_id = '$id' and id_registred_company = '$this->id_registred_company'")->result_array();
        else
            return $this->db2->query("select id, product,category_id from products where id_registred_company = '$this->id_registred_company'")->result_array();   
    }
    function getResponsibility($id=null){
        $registred_company = $this->id_registred_company;
        if(isset($id)){
          $name =  $this->db2->query("select id,surname,name from user where id ='$id' and id_registred_company='$registred_company' ")->result_array();    
        }
        
        else{
             $name = $this->db2->query("select id,surname,name from user where id_registred_company='$registred_company' ")->result_array();
        }
        
        return $this->responsibilityToFio($name);
        
    }
    function getPrognosisById($sale,$id=null)
    {
        if($id){
        return $this->db2->query("select id,id_sale,prognosis,prognosis_date from prognosis where id_sale = $id")->result_array() ;
            //$payment  =$this->db2->query("select id,payment,payment_date from payment where id_sale = {$sale['id']}")->result_array() ;
        }
        else{
            $temp =array();
            $prgns = $this->getPrognosisById($sale,"'1' or 1");
            // foreach ($prgns as $key => $value) {
            //     foreach ($sale as $k => $v) {
            //         if($sale[$k]["id"]==$prgns[$key]["id_sale"])
            //             $temp[$key]["id_sale"] = $prgns[$key]["id"];
            //             $temp[$key]["prognosis"] = $prgns[$key]["prognosis"];
            //     }
            // }
            
            return $prgns;
        }
    }
    function getPaymentById($sale,$id=null)
    {
        if($id){
        return $payment  =$this->db2->query("select id,id_sale, payment,payment_date from payment where id_sale = $id")->result_array() ;
        }
        else{
            $temp =array();
            $prgns = $this->getPaymentById($sale,"'1' or 1");
            // foreach ($prgns as $key => $value) {
            //     foreach ($sale as $k => $v) {
            //         if($sale[$key]["id"]==$prgns[$k]["id"])
            //             $temp[$key]["id_sale"] = $prgns[$key]["id"];
            //             $temp[$key]["prognosis"] = $prgns[$k]["payment"];
            //     }
            // }
            return $prgns;
        }
    }
    function getResponsibiltyArrayBySale($sales){
        $temp =array();
        $respons = $this->getResponsibility();
        //$sales = $this->db2->query("SELECT * FROM sale Left join  phase On sale.id = phase.id_sale Where sale.customer_id = '1'")->result_array();
        $product = $this->getProductsById(null);
        $category = $this->getCategories();
        $prognosis = $this->getPrognosisById($sales);
        $payment = $this->getPaymentById($sales);
        $customer = $this->db2->query("select id, name from customer")->result_array();

            // print_r($respons);
            // print_r($sales);
       foreach ($sales as $key => $value) {
            $sales[$key]["prognosis"]=0;
            $sales[$key]["payment"] = 0;
            foreach ($respons as $k => $v) {
            if($respons[$k]["id"]==$sales[$key]["responsibility"])
             $sales[$key]["responsibility"]  = $respons[$k]["fio"];  
            }
             foreach ($customer as $k => $v) {
                if($customer[$k]["id"]==$sales[$key]["customer_id"])
                    $sales[$key]["company_name"]=$customer[$k]["name"];
            }
            foreach ($product as $k => $v) {
            if($product[$k]["id"]==$sales[$key]["id_product"])
             $sales[$key]["product"]  = $product[$k]["product"];  
            foreach ($category as $t => $l) {
                if($category[$t]["id"]==$product[$k]["category_id"])
                    $sales[$key]["category"] = $category[$t]["name"];
            }
            }
            foreach ($prognosis as $k => $v) {
              if($prognosis[$k]["id_sale"]==$sales[$key]["id"])
             $sales[$key]["prognosis"]  += intval($prognosis[$k]["prognosis"]);  
            }
            foreach ($payment as $k => $v) {
            if($payment[$k]["id_sale"]==$sales[$key]["id"])
             $sales[$key]["payment"]  += intval($payment[$k]["payment"]);  
            }

        }
            
        return $sales;
    }
    // function getResponsibiltyArrayByCustomer($customer){
    //     $temp =array();
    //    $prognosis = $this-> SELECT sum(prognosis) as pr, c.id , s.name_sale FROM prognosis p left join sale s on s.id = p.id_sale left join customer c on c.id = s.customer_id group by c.id 
    //     $respons = $this->getResponsibility();
    //     //$sales = $this->db2->query("SELECT * FROM sale Left join  phase On sale.id = phase.id_sale Where sale.customer_id = '1'")->result_array();
    //   //  $product = $this->getProductsById(null);
    //    // $category = $this->getCategories();
    //     $prognosis = $this->getPrognosisById($sales);
    //    // $payment = $this->getPaymentById($sales);
    //    // $customer = $this->db2->query("select id, name from customer")->result_array();

    //         // print_r($respons);
    //         // print_r($sales);
    //    foreach ($sales as $key => $value) {
    //         $sales[$key]["prognosis"]=0;
    //         $sales[$key]["payment"] = 0;
    //         foreach ($respons as $k => $v) {
    //         if($respons[$k]["id"]==$sales[$key]["responsibility"])
    //          $sales[$key]["responsibility"]  = $respons[$k]["fio"];  
    //         }
            
    //         foreach ($prognosis as $k => $v) {
    //           if($prognosis[$k]["id_sale"]==$sales[$key]["id"])
    //          $sales[$key]["prognosis"]  += intval($prognosis[$k]["prognosis"]);  
    //         }
            

    //     }
            
    //     return $sales;
    // }
    function mb_ucfirst($text) {
        return mb_strtoupper(substr($text, 0, 2)) . substr($text, 2);
    }
    function responsibilityToFio($name){
        $name_tmp= array();
         foreach ($name as $key => $value) {
            $name_tmp[$key]["id"] = $value["id"];
            $surname = $this->mb_ucfirst(trim($value["surname"]));
            $second_name =(isset($value["second_name"]))? $this->mb_ucfirst($value['second_name']):"";
            $nam = $this->mb_ucfirst(trim($value["name"]));
            $name_tmp[$key]["fio"] =$surname.' '.substr($nam,0,2).'.'.$second_name;
         }
         return $name_tmp;
    }
    function getPlansCount($date,$resp=null){
        return $this->db2->where("date",$date)->from("plans")->count_all_results();

    }
    
    function getRegions(){//like адыгея
        $temp = $this->db2->query("select rid as value, name as label from geo_regions where name='Чувашия' union select rid as value, name as label from geo_regions")->result_array();
        //array_unshift($temp, array('value'=>'', 'label'=>'Выберите регион'));
        return $temp;
    }
    function getRNCByRegion($id){//районы и города региона
        $id = $this->fillToTwo($id);
        return $this->db2->query("select cid as value, concat(socr, '. ', name) as label from geo_cities where code REGEXP '{$id}[0-9]{3}[0-9]{3}00000' and code not REGEXP '{$id}00000000000' ORDER by label ASC")->result_array();
    } 
    function getCNPByRNS($sid, $rid){//region_id, subregion_id //нас пункты
        $code = substr($this->db2->query("select code from geo_cities where cid={$sid}")->row()->code,0, 5);
        $temp = $this->db2->query("select cid as value, concat(socr, '. ', name) as label from geo_cities where code regexp '{$code}000[0-9]{3}00' and code not like '{$code}00000000' order by label asc")->result_array();
        return $temp;
    }
    function getStrByCity($id){        
        $code = substr($this->db2->query("select code from geo_cities where cid={$id}")->row()->code,0, -2);
        return $this->db2->query("select post_code as 'index', sid as value, concat(socr, '. ', name) as label from geo_streets where code regexp '{$code}[0-9]{4}00' and code not like '{$code}000000' order by label asc")->result_array();
    }
    function getStrByPPP($pid){
        $code = substr($this->db2->query("select code from geo_cities where cid={$pid}")->row()->code,0, -2);
        return $this->db2->query("select post_code as 'index', sid as value, concat(socr, '. ', name) as label from geo_streets where code regexp '{$code}[0-9]{4}00' and code not like '{$code}000000'")->result_array();     
    }
    // function insertSegment($data){

    //    foreach ($data as $k => $v) {
    //         if(empty($v["id"])){
    //             $this->db2->set("sector",$v["sector"])->set("id",$v["id"])->set("id_customer",$v["id_customer"])->insert("sector");
    //             $id_sector = $this->db2->insert_id();
    //             foreach ($data["value"] as $key => $value) {
    //                  $this->db2->set('id_sector',value["id"])->set('segment',value["segment"])->insert("segment");
    //             }
    //         }
    //         else{
                    
    //         }
    //    }
           
    // }
    

    function fillToTwo($str){
        if (strlen($str.'')==1) return '0'.$str; else return $str;
    }
    function fillToThree($str){
        if (strlen($str.'')==1) return '00'.$str; 
        elseif (strlen($str.'')==2) return '0'.$str;
        else return $str;
    }
    function fillToFour($str){
        if (strlen($str.'')==1) return '000'.$str; 
        elseif (strlen($str.'')==2) return '00'.$str;
        elseif (strlen($str.'')==3) return '0'.$str;
        else return $str;
    }
    function explodeDate($date){
        $month = array("Января","Февраля","Марта", "Апреля" ,"Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
        $temp = explode("-", $date);
       
        return "".$temp[0]." ".$month[(int)$temp[1] - 1]." ".$temp[2];
       
    }
    function insertCaptured($email,$name,$data,$phone=null){
        //$this->db2->where("email",$email)->from("customer")->count_all_results();
        
     
        $nowDate = $this->explodeDate(date('d-m-Y'));
       
        $this->db2->set('email_home',$email)->insert("contact_info");
        $id= $this->db2->insert_id();
        $this->db2->set('name',$name)->set('id_contact_info',$id)->set('date_registration',$nowDate)->set('captured',1)->insert("customer");
        $id_c = $this->db2->insert_id();
        $sector = $data->sector;
        //echo $sector;
        $this->db2->query("insert into sector (id_customer,sector) value ('$id_c','$sector')");
        $id_p = $this->db2->insert_id();
        
        foreach ($data->segments as $value) {
           $this->db2->set('id_sector',$id_p)->set('segment',$value)->insert("segment");
        }
        return true;
    }
    //функции для работы с деревом(таблиуа каталог)
     function allTree(){ 
         return $this->db2->query("select id,cat_level,cat_left, cat_right, name from category where id_registred_company='$this->id_registred_company' order by cat_left")->result_array();
     }
     function getChildNodes($cat_left,$cat_right){
         return $this->db2->query("select id,cat_level,name from category where cat_left>='$cat_left' and cat_left<='$cat_right' and id_registred_company='$this->id_registred_company' order by cat_left ")->result_array();
     }
     function getParentNodes($cat_left,$cat_right){
         return $this->db2->query("select id,cat_level,name from category where cat_left<='$cat_left' and cat_left>='$cat_right' id_registred_company='$this->id_registred_company' order by cat_left ")->result_array();
     }
    function createNode($cat_right,$level, $name){
         $this->db2->query("UPDATE category SET cat_right = cat_right + 2, cat_left = IF(cat_left > '$cat_right', cat_left + 2, cat_left) WHERE cat_right >= '$cat_right' and id_registred_company='$this->id_registred_company'");
         $this->db2->query("INSERT INTO category SET cat_left = '$cat_right', cat_right = '$cat_right' + 1, cat_level = '$level' + 1, name ='$name', id_registred_company='$this->id_registred_company'");
         return $this->db2->insert_id();
    }
    function deleteNode($cat_left,$cat_right){
        $this->db2->query("DELETE FROM category Where cat_left>='$cat_left' and cat_right<='$cat_right' and id_registred_company='$this->id_registred_company'");
        $this->db2->query("UPDATE category SET cat_left = IF(cat_left > '$cat_left', cat_left - ('$cat_right' - '$cat_left' + 1), cat_left), cat_right = cat_right - ('$cat_right' - '$cat_left' + 1) WHERE cat_right > '$cat_right' and id_registred_company='$this->id_registred_company'");
    }

    /*функция редактирования имени узла категории
        $id- id узла
        $name - новое имя узла
        return boolean
    */
    function editeNode($id,$name){
        $this->db2->query("Update category set name = '$name' where id = '$id'");
        return $this->db2->affected_rows()==1;
    }
     //Функция для получения продуктов из категории по id папки
    function getProductByIdCategory($categoryId){
        return $this->db2->query("select * from products where category_id='$categoryId' and id_registred_company = '$this->id_registred_company'")->result_array();
    }
    
    //функция для сохранения настроек 
    // пример $data = array( 'name'=>$name,'address'=>$address ....);
    function setCompanySettings($data){
        $this->db2->update('registred_company',$data['settings'],$this->id_registred_company);
    }
    //возвращает настройки для компании
    function getCompanySettings($admin){
        if($admin){
               return $this->db2->get_where('registred_company',$this->id_registred_company)->row();
        }
        return $this->db2->query("select link from registred_company where id='$this->id_registred_company'")->row();
    }
    //возвращает список продаж компании {$id  - id компании в таблице customer}
    function getSalesByCompanyId($id){
        return $this->db2->query("Select s.id,s.name_sale from sale s join customer c on s.customer_id=c.id where s.customer_id='$id' and c.id_registred_company = '$this->id_registred_company'")->result_array();
    }
    // возвращает список контактов компании
    function getContactsByCompanyId($id){
        return $this->db2->query("select con.id,concat(con.surname,' ',con.name,' ',con.second_name) as fullname from contact con join customer c on con.id_customer=c.id where con.id_customer='$id' and c.id_registred_company='$this->id_registred_company'")->result_array();
    }
    //функция для сохранения продуктов
	//$data - массив вида array('id'=>1, 'name'=>''....)
	//return boolean
    function editProducts($data){
    $data['id_registred_company'] = $this->id_registred_company;
	if(empty($data['id'])){
                $this->db2->insert('products',$data);
		return $this->db2->insert_id();
	}
	else{
            $this->db2->where('id',$data['id'])->update('products',$data);
		return $this->db2->affected_rows()==1;
	}
    }
    //возвращает все фазы по продажам компании
    function getPhaseBySaleId($id){
        return $this->db2->query("
            select p.id_phase, p.phase, p.date from phase p 
            join sale s on s.id=p.id_sale 
            join customer c on c.id=s.customer_id 
            where p.id_sale = '$id' and c.id_registred_company='$this->id_registred_company' ")->result_array();
    }
    //возвращает данные о планах породаж. Надо подумать какие параметры передавать
    function getPlanOfSale($id_process,$date){
        $process = ($id_process=='all')?'':"and pos.process = '$id_process'";
        $date = (strlen($date)>7)?substr($date, 3):$date;
       
        $data =  $this->db2->query("SELECT pos.process, pos.date, pos.id_user id, pop.phase, pop.count FROM user u
                                  left join plan_of_sale pos on pos.id_user=u.id
                                  left join plan_of_phase pop on pop.id_pos = pos.id 
                                  where pos.date like '%$date'and u.id_registred_company='$this->id_registred_company' $process")->result_array();
        return $data;
    }
    //возвращает отчет по поступлениям. 
    //$byTime- в зависимости от какого типа времени предоставить отчет(по году, или по месяцу), 
    //$value значение года или месяца(если отчет идет по месяцу то необходимо казать в $value и год. Пример: '09-2013')
    //если оба параметра не заданы выдается отчет по текущему месяцу
     function getReport($date=null) {
        $id_reg_company = $this->id_registred_company;
        if(empty($date))
            $date = date('m-Y');
	$date_pos = (strlen($date)>7)?substr($date, 3):$date;	
       
        $report= $this->db2->query("select u.id, concat(u.surname,' ',u.name) as fio, pr.prognosis, payment, sum(pos.plan) plan, d.debet from user u 
                                    left join (select s.responsibility as resp, sum(prognosis) as prognosis from prognosis p join sale s on s.id=p.id_sale where p.prognosis_date like '%$date' group by s.responsibility) pr on pr.resp = u.id
                                    left join (select s.responsibility as resp, sum(payment) as payment from payment p join sale s on s.id=p.id_sale where p.payment_date like '%$date' group by s.responsibility) py on py.resp = u.id
                                    left join (select sum(debt) as debet, responsibility as r  from sale group by responsibility) d on d.r = u.id
                                    join plan_of_payment pos on u.id=pos.id_user
                                    where pos.date like '%$date_pos' and u.id_registred_company='$id_reg_company' group by u.id")->result_array(); 
        //where p.prognosis_date regexp '^[0-9]{2}-$date'; where p.payment_date regexp '^[0-9]{2}-$date';pos.date = $date and
        //    
        
        $temp  = $this->getActivityUserBySale('',$date);
        foreach ($report as $key=>$v) {
            $report[$key]['activity'] = 0;
    		$sum=0;
    		$i=0;
            $report[$key]['efficiency'] = ((int)$report[$key]['payment'])*100/(int)$report[$key]['plan']; // вычисление эффективности
    		foreach($temp as $k=>$val){
    			if($report[$key]['id']==$k){
                    $report[$key]['activity'] = $temp[$k]['activity'];
    			}
    		}    	
        } 
		
		
        return $report;
        
    }
	//возвращает актинвость по пользователям
    function getActivityUserBySale($process=null,$date=null){
      
	 $process = (empty($process))?'all':"$process";
         $plan_sale =  $this->getPlanOfSale($process,$date);
         $fact_count_sale=$this->db2->query("select count(ph.id_sale) cnt, s.responsibility r,s.type_sale, ph.phase phs from sale s 
                                            join phase ph on ph.id_sale=s.id 
                                            join user u on u.id = s.responsibility 
                                            where u.id_registred_company='$this->id_registred_company' and ph.date like '%$date' 
                                            group by s.responsibility, ph.phase, s.type_sale ")->result_array();
		
        $temp = array();
        foreach ($plan_sale as $key => $v) {
            $sum = 0;
            foreach ($fact_count_sale as $k => $val) {
                if($v['id']==$val['r']){
                    if($v['process']==$val['type_sale']){
                        if($v['phase']==$val['phs']){
                           $sum=  $val['cnt']*100/$v['count'];
                        }
                       
                    }
                }
            }
	  $temp[$v['id']]['count'][$v['process']][] = ($sum!=0)?$sum:0;
        }
		
        foreach ($temp as $key=>$val){
            $temp[$key]['sum_phase_count']=0;
            $i=0;
            foreach ($val['count'] as $k => $v) {
                $i++;
                $temp[$key]['sum_phase_count'] += array_sum($v)/count($v);
            }
            $temp[$key]['activity'] = round($temp[$key]['sum_phase_count'] / $i,2);
			unset($temp[$key]['count']);
			unset($temp[$key]['sum_phase_count']);
        }
		
	return $temp;
     }
    //функция для записи данных в планы продаж
    //$data = array(id_user='12', process='Продажа' date='09-2014',phases=array('phase'='название фазы',..))
    //сделал в расчете на то что будут обращатся к этой функции асинхронно
    function setPlanOfSale($data){
        if(!$this->checkUserInCompany($data['id_user'])){
            return false;
        }
        $query = $this->db2->query("select id from plan_of_sale 
            where  id_user='{$data['id_user']}' and process ='{$data['process']}' and date='{$data['date']}' ");
           
        $check_plan = count($query->result_array()); 
        $id = null;
        if($check_plan>0){
            $row = $query->row();
            $id = $row->id;    
        }
        else{
            $this->db2->query("insert into plan_of_sale (id_user,process,date) values ('{$data['id_user']}','{$data['process']}','{$data['date']}')");
            
            $id = $this->db2->insert_id();
        }
        if(!empty($id)){
            $v = $data['phase'];
            $this->db2->query("insert into plan_of_phase (id_pos,phase,count) values ($id,{$v['phase']},{$v['count']}) 
                                        on duplicate key update  count = {$v['count']}");
            return $this->db2->affected_rows()>0;
        }
        
    }
    //возвращает данные о планах поступлений
    function getPlanOfPayment($date){
        return $this->db2->query("select u.id, pop.`date`,pop.plan from user u 
                                  left join plan_of_payment pop on pop.id_user = u.id where pop.`date` like '%$date' and u.id_registred_company='$this->id_registred_company'")->result_array();
    }
    //записвает в таблицу plan of sale 
    //data = array('id_user'='','date'='09-2013' ...)
    function setPlanOfPayment($data){
        $this->db2->query("insert into plan_of_payment (id_user,date,plan) values ('{$data['id_user']}','{$data['date']}','{$data['plan']}')
                                on duplicate key update plan = '{$data['plan']}'"); 
        return $this->db2->affected_rows()>0;
    }
    //date = '{$data['date']}', 
    function checkUserInCompany($id){
        return count($this->db2->query("select id from user where id_registred_company ='$this->id_registred_company' and id='$id'")->result_array())>0;
    }
    //возвращает воронку продаж, возможна фильтрация по дате и по пользователю
   function getTornado($process='0',$date=null,$id_user=null){
        if(empty($date))
            $date =date('m-Y'); 
        $date_pos = (strlen($date)>7)?substr($date, 3):$date;
       
        $user_pos=$user_sale='';
        if(!empty($id_user)){
            $user_pos = " and pos.id_user='$id_user'";
            $user_sale = " and s.responsibility='$id_user'";
        }
    	$Katrina =  $this->db2->query("select pop.process, pop.phase, pop.count,ph.cnt from (select pos.process, pos.date, pop.phase, sum(count) count from plan_of_sale pos
                                                                                        join plan_of_phase pop on pop.id_pos = pos.id
                                                                                        join user u on u.id = pos.id_user
                                                                                        where u.id_registred_company ='$this->id_registred_company' and pos.date like '%$date_pos' and  pos.process = '$process' $user_pos
                                                                                        group by pop.phase) pop
                                 left join (select count(ph.id_sale) cnt,s.type_sale, ph.phase phs from sale s 
                                            join phase ph on ph.id_sale=s.id 
                                            join user u on u.id = s.responsibility
                                            where  s.type_sale = '$process' and ph.date like '%$date' and u.id_registred_company ='$this->id_registred_company' $user_sale
                                            group by  ph.phase) ph on ph.phs = pop.phase")->result_array();
      
        $total = $this->db2->query("select * from (select sum(py.payment) payment, s.type_sale from payment py
                                                    join sale s on s.id = py.id_sale
                                                    join user u on s.responsibility=u.id
                                                    where s.type_sale = '$process' and u.id_registred_company='$this->id_registred_company'  and py.payment_date like '%$date' $user_sale) py 
                                    join (select sum(pr.prognosis) prognosis, s.type_sale from prognosis pr
                                          join sale s on s.id = pr.id_sale
                                          join user u on s.responsibility=u.id
                                          where s.type_sale = '$process' and u.id_registred_company='$this->id_registred_company'  and pr.prognosis_date like '%$date' $user_sale) pr on pr.type_sale = py.type_sale
                                    join (select sum(s.debt) debet, s.type_sale from sale s 
                                           join user u on s.responsibility = u.id 
                                           where s.type_sale = '$process' and u.id_registred_company='$this->id_registred_company' $user_sale) db on db.type_sale = pr.type_sale")->row_array();
        //var_dump($total);
       
        $phase = array();
        //имитирую выборку фаз из базы, чтобы функция вернула все фазы, даже если в планах продаж отсутсвует на эту фазу план
        for($i=0;$i<5;$i++){
           $phase[]=array('process'=>$process,'phase'=>$i,'count'=>'0','cnt'=>0);
            
        }
        
        foreach ($phase as $key => $value) {
            foreach ($Katrina as $k=>$v){
                if($value['phase']==$v['phase']){
                    $phase[$key]['process']=$v['process'];
                    $phase[$key]['phase'] = $v['phase'];
                    $phase[$key]['count'] = $v['count'];
                    $phase[$key]['cnt'] = $v['cnt'];
                }
            }
        }
        
        $phase[] =$total;
       
        return $phase;
    }
    //функции для работы с деревом(таблиуа сектор и сегменты)
     function allTreeSegment(){ 
         return $this->db2->query("select id,cat_level,cat_left, cat_right, sector from sector where id_registred_company='$this->id_registred_company' order by cat_left  ")->result_array();
     }
     function getChildNodesSector($cat_left,$cat_right){
         return $this->db2->query("select id,cat_level,sector from sector where cat_left>='$cat_left' and cat_left<='$cat_right' and id_registred_company='$this->id_registred_company' order by cat_left ")->result_array();
     }
     function getParentNodesSector($cat_left,$cat_right){
         return $this->db2->query("select id,cat_level,sector from sector where cat_left<='$cat_left' and cat_left>='$cat_right' and id_registred_company='$this->id_registred_company' order by cat_left ")->result_array();
     }
    function createNodeSector($cat_right,$level, $sector){
         $this->db2->query("UPDATE sector SET cat_right = cat_right + 2, cat_left = IF(cat_left > '$cat_right', cat_left + 2, cat_left) WHERE cat_right >= '$cat_right' and id_registred_company='$this->id_registred_company' ");
         $this->db2->query("INSERT INTO sector SET cat_left = '$cat_right', cat_right = '$cat_right' + 1, cat_level = '$level' + 1, sector ='$sector', id_registred_company='$this->id_registred_company'");
         return $this->db2->insert_id();
    }
    function deleteNodeSector($cat_left,$cat_right){
        $this->db2->query("DELETE FROM sector Where cat_left>=$cat_left and cat_right<=$cat_right and id_registred_company='$this->id_registred_company'");
        $this->db2->query("UPDATE sector SET cat_left = IF(cat_left > $cat_left, cat_left -($cat_right - $cat_left + 1), cat_left), cat_right = cat_right -($cat_right - $cat_left + 1) WHERE cat_right > '$cat_right' and id_registred_company='$this->id_registred_company'");
    }
    function editeNodeSector($id,$sector){
        $this->db2->query("Update sector set sector = '$sector' where id = '$id' and id_registred_company='$this->id_registred_company'");
        return $this->db2->affected_rows()==1;
    }
     //Функция для получения сегментов из секторов по id сектору
    function getSegmentByIdSector($segmentId){
        return $this->db2->query("select * from segment where id_sector='$sectorId' and id_registred_company='$this->id_registred_company'")->result_array();
    }
    
    
    
    
}

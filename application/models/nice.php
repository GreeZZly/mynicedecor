<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


class Nice extends CI_Model {
    private $id_registred_company = 2;
	    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);
    }

    public function reviews()
    {
    	//$query = $this->db->get('reviews');
    	return array();//$query->result_array();
    }

    public function products($cid='all')
    {
        $add = '';
        if ($cid!='all'){
          $add = "and c.id = '$cid' ";
        }
        $query = $this->db->query("SELECT p.id, p.product as name, p.cost as price, p.currency, pi.path as img, c.name as type FROM products p join product_images pi on p.id = pi.id_product join category c on p.category_id = c.id and c.id_registred_company='$this->id_registred_company' ".$add." order by name");
        return $query->result_array();
    }
     public function interesting_products($cid='all')
    {
        $add = '';
        if ($cid!='all'){
          $add = "and c.id = '$cid' ";
        }
        $query = $this->db->query("SELECT p.id, p.product as name, p.cost as price, p.currency, pi.path as img, c.name as type FROM products p join product_images pi on p.id = pi.id_product join category c on p.category_id = c.id and c.id_registred_company='$this->id_registred_company' ".$add." limit 6");
        return $query->result_array();
    }
    function getPictureByProduct($id_product)
    {
        return $this->db->query("select pi.* from product_images pi
                                 join products p on p.id=pi.id_product
                                 join category c on p.category_id = c.id
                                 where id_product='$id_product' and c.id_registred_company='$this->id_registred_company'")->result_array();
        
    }
 function getProduct($id){
        $prpty = $this->db->query("SELECT p.* FROM products p
                                    join category c on c.id = p.category_id
                                   WHERE p.id ='$id' and c.id_registred_company='$this->id_registred_company'")->row_array(); 
        $prpty['properties'] = $this->db->query("select pp.*, pc.id id_property_child,pc.name property_child, prp.name property_parent,prp.id id_property_parent  from product_properties pp  
                                                  join property_child pc on pp.id_property = pc.id
                                                  join property_parent prp on prp.id = pc.id_property_name
                                                  where pp.id_product = '$id' and pc.id_registred_company='$this->id_registred_company'")->result_array();
        $prpty['images'] = $this->getPictureByProduct($id);
       return $prpty;
    }
    public function check_user($data)
    {   
        $this->db->select('id');
        $query = $this->db->get_where('users', array('login' => $data['login']));
        return $query->result_array();
    }

    public function save_user($data) 
    {
        $this->db->insert('users',$data);
        return $this->db->affected_rows()>0;
    }

    public function get_smth($query)
        {   
            return $this->db->query("select p.id, p.product, p.description, p.cost, pi.path as img, c.name as type from products p
                                      join category c on p.category_id = c.id
                                      join product_images pi on pi.id_product = p.id
                                      where p.product like '%$query%' or p.description like '%$query%' or c.name like '%$query%'")->result_array();

            // $this->db->select('id, product, price, description, img, type');
            // $this->db->like('name',$query);
            // $this->db->or_like('description',$query);
            // $this->db->or_like('type',$query);
            // $db_query = $this->db->get('products')->result_array();

            // return $db_query;
      }
      public function getProductById($id)
      {
        $query = $this->db->get_where('products',array('id'=>$id));
        return $query->result_array();
      }

    public function interest_products()
    {
        $query = $this->db->get('products',3);
        return $query->result_array();
    }

    public function product_categories(){
        $this->db->select('categories');
        $query = $this->db->get('products');
        return $query->row_array();
    }
    //функции для работы с деревом(таблиуа каталог)
     function allTree(){ 
         return $this->db->query("select id,cat_level,cat_left, cat_right, name from category order by cat_left")->result_array();
     }
     function getChildNodes($cat_left,$cat_right){
         return $this->db->query("select id,cat_level,name from category where cat_left>='$cat_left' and cat_left<='$cat_right' order by cat_left ")->result_array();
     }
     function getParentNodes($cat_left,$cat_right){
         return $this->db->query("select id,cat_level,name from category where cat_left<='$cat_left' and cat_left>='$cat_right' order by cat_left ")->result_array();
     }
    function createNode($cat_right,$level, $name){
         $this->db->query("UPDATE category SET cat_right = cat_right + 2, cat_left = IF(cat_left > '$cat_right', cat_left + 2, cat_left) WHERE cat_right >= '$cat_right'");
         $this->db->query("INSERT INTO category SET cat_left = '$cat_right', cat_right = '$cat_right' + 1, cat_level = '$level' + 1, name ='$name', id_registred_company='1'");
         return $this->db->insert_id();
    }
    function deleteNode($cat_left,$cat_right){
        $this->db->query("DELETE FROM category Where cat_left>='$cat_left' and cat_right<='$cat_right'");
        $this->db->query("UPDATE category SET cat_left = IF(cat_left > '$cat_left', cat_left - ('$cat_right' - '$cat_left' + 1), cat_left), cat_right = cat_right - ('$cat_right' - '$cat_left' + 1) WHERE cat_right > '$cat_right'");
    }

    /*функция редактирования имени узла категории
        $id- id узла
        $name - новое имя узла
        return boolean
    */
    function editeNode($id,$name){
        $this->db->query("Update category set name = '$name' where id = '$id'");
        return $this->db->affected_rows()==1;
    }
     //Функция для получения продуктов из категории по id папки
    function getProductByIdCategory($categoryId){
        return $this->db->query("select * from products where category_id='$categoryId'")->result_array();
    }

    function getCategory() {
      return $this->db->query("select id, name from category where id_registred_company='$this->id_registred_company' and cat_level=3")->result_array();
    }

    function  getPropertyParent($cat_id) {
      return $this->db->query("select distinct pp.name, pp.id from property_parent pp 
                              join property_child pc on pp.id = pc.id_property_name
                              join product_properties pprop on pprop.id_property = pc.id
                              join products p on p.id = pprop.id_product
                              join category c on c.id = p.category_id
                              where pp.id_registred_company='$this->id_registred_company' and c.id = '$cat_id'")->result_array();
    }

    function getPropertyChild($cat_id) {
      return $this->db->query("select distinct pc.name as name, pc.id, pc.id_property_name from property_parent pp 
                              join property_child pc on pp.id = pc.id_property_name
                              join product_properties pprop on pprop.id_property = pc.id
                              join products p on p.id = pprop.id_product
                              join category c on c.id = p.category_id
                              where pp.id_registred_company='$this->id_registred_company' and c.id = '$cat_id' order by name")->result_array();
      // return $this->db->query("select pc.name as name, pc.id, pc.id_property_name from property_child pc 
      //                           where pc.id_registred_company='$this->id_registred_company' order by name")->result_array();
    }
    //ВЫТАСКИВАЕМ ПРОДУКТ ПО СеЛЕКТУ
    function getProductBySelect() {
      return $this->db->query("select p.id, p.product, p.cost, pi.path from product_properties pp join products p on p.id = pp.id_product join product_images pi on pi.id_product=pp.id_product where pp.id_property='15'")->result_array();
    }

    function getProdBySelect($id_array, $categoryId=null){
      $joins = array();
      $wheres = array();
      foreach ($id_array as $key => $value) {
        if ($value>0){
          array_push($joins, 'join product_properties pp'.$key.' on p.id = pp'.$key.'.id_product');
          array_push($wheres, 'pp'.$key.'.id_property ='.$value);
        }
      }
      return $this->db->query("select p.id, p.product, p.cost, pi.path, c.name as type, group_concat(ppeg.id_property SEPARATOR ',') as pps
        from products p ".implode(' ', $joins)."
        join product_images pi on pi.id_product=p.id 
        join category c on c.id = p.category_id
        join product_properties ppeg on ppeg.id_product = p.id 
        where c.id_registred_company='$this->id_registred_company' and p.category_id = '$categoryId' ".((sizeof($wheres)>0)?'and ':'').implode(' and ', $wheres)." group by p.id order by p.product")->result_array();
      
    }

    public function getProductData($id_product)
    {
      $data = array();
      $data['productData'] = $this->db->query("select p.product, p.id, c.name as type, pi.path as img, p.cost, p.description,c.id as cid from products p
                                                join category c on p.category_id = c.id
                                                join product_images pi on pi.id_product = p.id  where p.id = '$id_product'")->result_array();

      $data['productProperties'] =  $this->db->query("select pp.name as property, pc.name as value from property_parent pp
                                      join property_child pc on pc.id_property_name = pp.id
                                      join product_properties prodp on pc.id = prodp.id_property
                                      join products p on p.id = prodp.id_product
                                      where p.id = '$id_product'")->result_array();
      // $data['cat'] = $this->db->query("select id from category where name = ''");
    return $data;
    }


    // select p.id, p.product, p.cost, pi.path from product_properties pp join products p on p.id = pp.id_product join product_images pi on pi.id_product=pp.id_product where pp.id_property='15'
}
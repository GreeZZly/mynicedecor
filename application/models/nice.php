<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


class Nice extends CI_Model {
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

    public function products()
    {
        $query = $this->db->get('products');
        return $query->result_array();
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
            $this->db->select('id, name, price, description, img, type');
            $this->db->like('name',$query);
            $this->db->or_like('description',$query);
            $this->db->or_like('type',$query);
            $db_query = $this->db->get('products')->result_array();
            return $db_query;
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
    
}
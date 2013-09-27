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
    	$query = $this->db->get('reviews');
    	return $query->result_array();
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
            $this->db->select('name, price, description, img, type');
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
}
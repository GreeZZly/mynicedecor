<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{	
	private $info;
	private $id_registred_company;
    private $company;
    private $userRoles;
	function __construct()
	{
		parent:: __construct();
		$this->load->model('nice','',TRUE);
		$this->load->model('cocaine','',TRUE);
        $this->load->library('session');
        $this->load->library('ion_auth');
        $this->load->helper('url');
        $this->id_registred_company = 2;
		$email = $this->session->userdata('email');
        if($email){
            $this->info = $this->ion_auth->get_user_info();
            $this->ion_auth->updateActivity($email);
            $id = $this->info->id;
            $this->userRoles = $this->ion_auth->getUserRoles($id);
            //$this->company = $this->cocaine->getCompanySettings($this->info->id_registred_company,$this->userRoles->admin);
            $sub =  $this->config->item('subdomain');
            $current_uri = uri_string();
            if(($sub!=strtolower($this->info->registred_company) and 'main'!=  $current_uri and 'auth/login'!=$current_uri and 'auth/registr'!=$current_uri) and $this->info->registred_company!='goodpractice' ){
                redirect('/main', 'refresh');
            }
        }
	
	}
	public function common($url){
		// $data['ctg_array'] = $this->nice->product_categories();
		$this->load->view('admin/htmlheader');
		$this->load->view('admin/header');
		$this->load->view('admin/bar');
		$this->load->view('admin/'.$url);
		$this->load->view('admin/htmlfooter');	
	}
	public function index(){
		$this->common('content');		
	}
	public function add_product(){
		$this->common('add_product', $data);
	}
	function img_upload(){
		$info = json_decode($this->session->userdata('info'));
		$config['upload_path'] = 'include/images/companies/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '10000';
		$config['max_width'] = '0';
		$config['max_height'] = '0';
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('userpic')){
			$this->output->set_content_type('text/html')->set_output(json_encode(array('error'=>'No file')));
		}else {
			$data = $this->upload->data();
			$this->session->set_userdata(array('image_name'=>$data['file_name']));
			echo json_encode($data);
		}
	}
	function getProducts(){
		$id = $this->input->post('id');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getProductsById($id)));
	}
	function getRegistered(){
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getResponsibility()));
	}
	function getSalesForTable(){
		$stage = $this->input->post('stage');
		$id_respons = $this->info->id;
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->get_sales_array($stage,null,null,$id_respons/*$id,*/)));
	}
	function getPlansBySale(){
		$id_sale = $this->input->post('id_sale');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->get_plan_by_sale_id($id_sale)));
	}
	function getContactsForPlan(){
		$id_customer = $this->input->post('id_customer');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getContactsByCompanyId($id_customer)));
	}
	function getSalesForPlan(){
		$id_customer = $this->input->post('id_customer');
        $this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getSalesByCompanyId($id_customer)));
	}
	private function weekday($ddate){
		$duedt = explode("-", $ddate);
		$date  = mktime(0, 0, 0, $duedt[1], $duedt[0], $duedt[2]);
		return (int)date('W', $date);
	}
	function getRegByCountry(){
		$id = $this->input->post('id');
		if ($id == '0001') $this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getRegions()));
	}
	function getRNCByRegion(){//районы и города региона
		$id = $this->input->post('id');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getRNCByRegion($id)));
	}
	function getStrByCNR(){
		$id = $this->input->post('id');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getStrByCity($id)));
	}
	function getPNCBySNR(){ //города и нас. пункты района в регионе(Чувашия->Канашский рн->деревни и Канаш)
		$id = $this->input->post('id');
		$rid = $this->input->post('rid');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getCNPByRNS($id,$rid)));
	}
	function getStreetsInPPP(){
		$pid = $this->input->post('pid');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getStrByPPP($pid)));
	}	
	function getSectorNodes(){
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->allTreeSegment()));
	}
	function addOrUpdSegment(){
		$data = $this->input->post('node');
		$answer = false;
		if ($data['id']!=''){
			$answer = $this->cocaine->editeNodeSector(intval($data['id']), $data['name']);
		} else {
			$answer = $this->cocaine->createNodeSector(intval($data['prk']),intval($data['level']), $data['name']);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($answer));
	}
	function getTreeNodes(){
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->allTree()));
	}
	function getFolderProducts(){
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getProductByIdCategory($this->input->post('folder_id'))));
	}
	function addOrUpdCategory(){
		$data = $this->input->post('node');
		$answer = false;
		if ($data['id']!=''){
			$answer = $this->cocaine->editeNode(intval($data['id']), $data['name']);
		} else {
			$answer = $this->cocaine->createNode(intval($data['prk']),intval($data['level']), $data['name']);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($answer));
	}
	function addOrUpdProduct(){
		$data = $this->input->post('product');
		$answer = $this->cocaine->editProducts($data);		
		$this->output->set_content_type('application/json')->set_output(json_encode($answer));
	}
	function updateUser(){
		$data = $this->input->post('user');
		$id = $this->input->post('id');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->ion_auth->updateUser($id, $data)));
	}
	
	function getUsersForTable(){		
		$id = $this->id_registred_company;
		$this->output->set_content_type('application/json')->set_output(json_encode($this->ion_auth->getUserArray($id)));
	}

	function getPlansOfPayment(){
		$year = $this->input->post('year');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getPlanOfPayment($year)));
	}
	function getPlansOfSale(){
		$date = $this->input->post('date');
		$process = $this->input->post('process');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getPlanOfSale($process,$date)));
	}
	function setPlanOfPayment(){
		$data = $this->input->post('send');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->setPlanOfPayment($data)));
	}
	function setPlanOfSale(){
		$data = $this->input->post('send');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->setPlanOfSale($data)));
	}
	function getReport(){
		$date = $this->input->post('date');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getReport($date)));
	}
	function getFunnel(){
		$date = $this->input->post('date');
		$process = $this->input->post('process');
		$id = $this->input->post('user');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getTornado($process,$date, $id)));
	}
	function getLastPhase(){
		$id = $this->input->post('id_sale');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->get_last_phase($id)));
	}
	function getActivity(){
		$date = $this->input->post('date');
		$process = $this->input->post('process');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->cocaine->getActivityUserBySale($process,$date)));
	}
}
?>
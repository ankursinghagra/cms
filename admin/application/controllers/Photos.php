<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Photos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->helper('bootstrap');
		$this->data['site_name']=$this->config->item('site_name');
		$this->data['main_url']=$this->config->item('main_url');
		//$this->data['folder']=$this->config->item('folder');
		$this->data['cms_options']=$this->common->cms_options();
		$this->data['modules']=$this->functions->modules();

		if($this->functions->login_checker()){
			$this->data['user_data']=$this->session->userdata('admin_user');
			$this->data['user_data']=$this->functions->all_user_data($this->data['user_data']['admin_email']);
			$this->data['view_permissions']=$this->functions->all_view_permissions_of_current_group();
			$this->data['modify_permissions']=$this->functions->all_modify_permissions_of_current_group();
		}else{
			redirect('login','refresh');
		}
		if(!isset($this->data['view_permissions']->photos)){show_404();}
	}

	public function index()
	{
		$this->data['page_data']=array(
			'meta_title' => 'Admin/Photos',
			'meta_description' => '',
			'meta_keywords' => '',
			'page_title' => 'Photos',
			'breadcrumb' => array(
				array('link'=>'dashboard','title'=>'Dashboard','active'=>false,),
				array('link'=>'photos','title'=>'Photos','active'=>true,),
			),
		);

		$sql = $this->db->get_where('cms_modules',array('module_slug'=>'photos'));
		$row = $sql->row();
		$this->data['module_status'] = $row->module_status;

		$this->data['current_slug']='photos';
		$this->common->layout('photos',$this->data);		
	}
	public function api_toggle_module(){
		header('Content-type: application/json');
		$response = array();

		$sql = $this->db->get_where('cms_modules',array('module_slug'=>'photos'));
		$row = $sql->row();
		$status = $row->module_status;
		$change_to = ($status=='1')?'0':'1';

		if(isset($this->data['modify_permissions']->photos)&&($this->data['modify_permissions']->photos)){


			if($this->db->update('cms_modules',array('module_status'=>$change_to),array('module_slug'=>'photos'))){
			
				$response['status'] = 'success';
				$response['title'] = ($change_to=='1')?'Turned On':'Turned Off';
				$response['message'] = "";
			}else{
				$response['status'] = 'failure';
				$response['title'] = 'Deleted';
				$response['message'] = "";
			}

		}else{
			$response['status'] = 'failure';
			$response['title'] = 'Error';
			$response['message'] = "Error Updating Status";
		}		

		echo json_encode($response);
	}
}

/* End of file Siteoptions.php */
/* Location: ./application/controllers/Siteoptions.php */
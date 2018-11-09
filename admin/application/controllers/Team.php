<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team extends CI_Controller {

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
		if(!isset($this->data['view_permissions']->team)){show_404();}
	}

	public function index()
	{
		$this->data['page_data']=array(
			'meta_title' => 'Admin/Team',
			'meta_description' => '',
			'meta_keywords' => '',
			'page_title' => 'Team',
			'breadcrumb' => array(
				array('link'=>'dashboard','title'=>'Dashboard','active'=>false,),
				array('link'=>'team','title'=>'Team','active'=>true,),
			),
		);

		$this->data['module_status'] = $this->data['modules']['team']['module_status'];

		$this->data['current_slug']='team';
		$this->common->layout('team',$this->data);		
	}
	public function api_toggle_module(){
		header('Content-type: application/json');
		$response = array();

		$status = $this->data['modules']['team']['module_status'];
		$change_to = ($status=='1')?'0':'1';

		if(isset($this->data['modify_permissions']->team)&&($this->data['modify_permissions']->team)){


			if($this->db->update('cms_modules',array('module_status'=>$change_to),array('module_slug'=>'team'))){
			
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
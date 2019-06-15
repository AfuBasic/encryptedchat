<?php 
class Admin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin_model');
		if($this->session->userdata('adminlogin') == "")
		{
			redirect('auth/adminlogin');
		}
	}

	public function index()
	{
		$data['title'] = "Welcome Home";
		$data['staff'] = $this->admin_model->users();
		$data['page'] = "admin/home";
		$this->load->view('template',$data);
	}

	public function createStaff()
	{
		if(!empty(request()))
		{
			$rules = [
				[
					'field' => 'name',
					'label'	=> 'required',
					'rules'	=> 'required'
				],
				[
					'field' => 'email',
					'label'	=> 'required',
					'rules'	=> 'required|valid_email|is_unique[staff.email]'
				]
			];

			$this->load->library('form_validation');
			$this->form_validation->set_rules($rules);

			if(! $this->form_validation->run())
			{
				$err = "<div class='alert alert-danger'>".validation_errors()."</div>";
				redirect('admin/createstaff');
			}

			$pass = uniqid();
			$key = uniqid();

			$record['email'] 	= request('email');
			$record['name']		= request('name');
			$record['password']	= md5($pass);
			$record['deckey']	= $key;

			$msg = "Hello ".request('name').", Your account has been created, please find your credentials below";
			$msg .= "<br /><br /> Password: ".$pass;
			$msg .= "<br />Decryption Key: ".$key;

			$info['message']	= $msg;
			$info['from']		= "Encryption System <noreply@abosede.com>";
			$info['subject']	= "New Staff Account";

			sendMail(request('email'), $info);

			$this->admin_model->addUser($record);
			$res=['response'=>'<div class="alert alert-success" style="font-size:11px;">Account has been created, necessary information has been sent to '.request('email')."</div>"];
			setFlash($res);

			redirect('admin/createstaff');
		}
		$data['title'] = "Welcome Home";
		$data['staff'] = $this->admin_model->users();
		$data['page'] = "admin/addstaff";
		$this->load->view('template',$data);
	}
}
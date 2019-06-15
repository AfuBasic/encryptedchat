<?php 
class Auth extends CI_Controller
{

	public function index()
	{
		$this->load->model('home_model');
		if(!empty(request())) {
			$login = $this->home_model->login(request());
			if($login)
			{
				redirect('home');
			} else {
				setFlash(['response'=>'<div class="alert alert-danger">Invalid Login</div>']);
			}
		} else {
			setFlash(['response'=>'']);
		}
		$this->load->view('auth/staffauth');
	}
	public function adminlogin()
	{
		if(!empty(request())) {
			if(request('username') == ADMIN_USER
				&& request('password') == ADMIN_PASS
			)
			{
				setSession(['adminlogin'=>'set']);
				redirect('admin');
			} else {
				setFlash(['response'=>'<div class="alert alert-danger">Invalid Login</div>']);
			}
		} else {
			setFlash(['response'=>'']);
		}
		$this->load->view('auth/auth');
	}

	public function stafflogout()
	{
		$this->session->sess_destroy();
		redirect('auth');
	}
}
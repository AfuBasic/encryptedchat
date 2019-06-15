<?php 
class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(getSession('stafflogin') == "")
		{
			redirect('auth');
		}
		$this->load->model('home_model');
	}

	public function index()
	{
		$data['title'] 	= "Welcome Home";
		$data['page']	= "staff/home";
		$data['conversations'] = $this->home_model->getConvo();
		$this->load->view('staff/template',$data);
	}

	public function chat($id)
	{
		$data['user'] 		= $this->home_model->getUser($id);
		$data['chat'] 		= $this->home_model->getMessages($id);
		$data['page']		= "staff/chat";

		$this->load->view('staff/template',$data);
	}

	public function storeMessage()
	{
		$data = $this->input->post();
		$key = $this->home_model->getUser($data['receiver_id'])->deckey;

		$this->load->library('crypt',$key);

		$data['message']	= $this->crypt->encrypt($data['message']);

		$this->home_model->saveMessage($data);

		$options = array(
			'cluster' => 'ap1',
			'useTLS' => true
		);
		$pusher = new Pusher\Pusher(
			'245bfd3ff4237d986e5d',
			'fa0613df1efbf0f6ef87',
			'581237',
			$options
		);

		$data['tt'] = $this->home_model->getTime(date('Y-m-d H:i:s'));
		$data['sender'] = user()->name;
		$pusher->trigger('abosede', 'new-message', $data);
	}

	public function regkey()
	{
		$me = user()->id;
		$key = $this->home_model->getUser($me)->deckey;

		$mkey = $this->input->post('key');

		if($key == $mkey) {
			$this->session->set_userdata('mykey',$key);
		}

		$this->load->library('user_agent');
		redirect($this->agent->referrer());
	}

	public function decrypt()
	{
		$data = $this->input->post();
		extract($this->input->post());
		if($sender_id == user()->id) {
			$key = $this->home_model->getUser(user()->id)->deckey;
			$this->load->library('crypt',$key);
			$data['message'] = $this->crypt->decrypt($data['message']);
		} elseif($receiver_id == user()->id) {
			$mykey = $this->session->userdata('mykey');
			if($mykey != "") {
				$kk = $this->home_model->getUser($sender_id)->deckey;
				$this->load->library('crypt',$kk);
				$data['message'] = $this->crypt->decrypt($data['message']);
			}
		}

		echo json_encode($data);
	}

	public function storeMultipartMessage()
	{
		extract($this->input->post());
		$data = $this->input->post();
		$key = $this->home_model->getUser($receiver_id)->deckey;
		$this->load->library('crypt',$key);

		$data['message']	= $this->crypt->encrypt($data['message']);

		$config['upload_path']          = FCPATH.'asset/uploads/';
		$config['allowed_types']        = 'gif|jpg|png|pdf|tar|gz|zip|docx|doc|pptx|xlsx|xls|mp4|mepg|avi|mp3|wav|aac|csv';
		$config['max_size']             = 5000;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file'))
		{
			echo json_encode(['status'=>'error','msg'=>$this->upload->display_errors()]);
			exit;
		}
		else
		{
			$upl = $this->upload->data();
		}

		$type = "image";

		if(strpos($upl['file_type'], "image") === false) {
			$type = "audio";
		}

		$data['media'] = $upl['file_name'];
		$data['type']  = $type;

		$this->home_model->saveMessage($data);

		$options = array(
			'cluster' => 'ap1',
			'useTLS' => true
		);
		$pusher = new Pusher\Pusher(
			'245bfd3ff4237d986e5d',
			'fa0613df1efbf0f6ef87',
			'581237',
			$options
		);

		$data['tt'] = $this->home_model->getTime(date('Y-m-d H:i:s'));
		$data['sender'] = user()->name;

		$pusher->trigger('abosede', 'new-message', $data);

		echo json_encode(['status'=>'success']);

	}
}
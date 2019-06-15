<?php 
class Home_model extends CI_Model
{

	public function getTime($date)
	{
		$to_time = strtotime($date);
		$to_today = date('Y-m-d ',$to_time)." 00:00";
		$date_today = strtotime($to_today);
		$now = strtotime("now");

		$diff = $now - $date_today;
		$diff = ceil($diff / (60 * 60));

		if($diff < 24) {
			return date("h:i a",strtotime($date));
		}

		if($diff > 24 && $diff < 48) {
			return "Yesterday ".date("h:i a",strtotime($date));
		}

		return date("d M, Y h:i a",strtotime($date));

	}
	public function login($data)
	{
		$data = (array) $data;
		$data['password']	= md5($data['password']);
		$staff = $this->db->where($data)->get(STAFF_TABLE);
		if($staff->num_rows() > 0 )
		{
			setSession(['stafflogin'=>true,'staffdata'=>$staff->row()]);
			return true;
		}

		return false;
	}

	public function getUser($id)
	{
		return $this->db->where('id',$id)->get(STAFF_TABLE)->row();
	}

	public function getMessages($id)
	{
		$this->db->group_start();
		$this->db->where('sender_id',user()->id);
		$this->db->where('receiver_id',$id);
		$this->db->group_end();
		$this->db->or_group_start();
		$this->db->where('receiver_id',user()->id);
		$this->db->where('sender_id',$id);
		$this->db->group_end();

		$this->db->order_by('id','asc');

		$result = $this->db->get(MESSAGE_TABLE)->result();

		return $result;
	}

	public function decryptMine($message)
	{
		$key = $this->session->userdata('mykey');

		if($key != "") {
			$this->load->library('crypt',$key);
			return $this->crypt->decrypt($message);
		}

		return $message;
	}

	public function decryptOther($id, $message) {
		if($this->session->userdata('mykey') != "") {
			$key = $this->getUser($id);
			$this->load->library('crypt',$key);
			return $this->crypt->decrypt($message);
		}
		return $message;
	}

	public function saveMessage($data)
	{
		$this->db->insert(MESSAGE_TABLE, $data);
	}

	public function getConvo()
	{
		$query = "select
		conversation.*, ".STAFF_TABLE.".*
		from (

		select ".MESSAGE_TABLE.".*,
		id as message_id,
		sender_id as user_id,
		receiver_id as rec_id
		from ".MESSAGE_TABLE."
		where sender_id = '".user()->id."'
		group by ".MESSAGE_TABLE.".receiver_id

		union

		select ".MESSAGE_TABLE.".*,
		id as message_id,
		receiver_id as user_id,
		sender_id as rec_id
		from ".MESSAGE_TABLE."
		where receiver_id = '".user()->id."'
		group by sender_id

		) conversation JOIN ".STAFF_TABLE." ON ".STAFF_TABLE.".id = conversation.rec_id 

		group by conversation.rec_id";

		$query = $this->db->query($query)->result();
		return $query;

	}
}
<?php 
class Admin_model extends CI_Model 
{
	public function users()
	{
		return $this->db->get(STAFF_TABLE)->result();
	}

	public function adduser($data) 
	{
		$this->db->insert(STAFF_TABLE, $data);
	}

	public function updateUser($id,$data)
	{
		$this->db->where('id',$id)->set($data)->update(STAFF_TABLE);
	}

	public function deleteUser($id)
	{
		$this->db->where('id',$id)->delete(STAFF_TABLE);
	}
}
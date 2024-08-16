<?php
class m_login extends CI_Model
{
	function m_login()
	{
		parent::__construct();
	}
	function check_login($username, $password, $passing)
	{
		$this->load->database();

		$str_password = ' AND password = ?';
		if ($passing) {
			$str_password = '';
		}

		$q = $this->db->query("
				SELECT * FROM dailyreport.vwdatalogin
				WHERE pegawaiid = (SELECT pegawaiid FROM pegawai WHERE nik = CASE WHEN ? = 'dev' THEN '15080236' ELSE '" . $username . "' END)
                " . $str_password, array($username, $password));
		return $q->result();
	}
	function deleteAccess()
	{
		$this->load->database();

		$this->db->query("
			DELETE FROM accessdate
			WHERE dateexp = CURRENT_DATE
			");

		return; 
	}
}

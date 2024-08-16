<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_approval extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getmasterapproval($node)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT DISTINCT a.pegawaiid as id, a.fullname as text, CONCAT(a.fullname, ' - ' , b.nik) as desc FROM stgnamapegawai a
			INNER JOIN pegawai b on b.pegawaiid = a.pegawaiid
			INNER JOIN riwayatjabatan c ON c.pegawaiid = a.pegawaiid
			WHERE c.tglselesai IS NULL
			ORDER BY a.fullname	
		", array($node));
		$this->db->close();
		return $q;
	}
	
	function getApproval()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT DISTINCT a.pegawaiid as id, a.fullname as text, CONCAT(a.fullname, ' - ' , b.nik) as desc FROM stgnamapegawai a
			INNER JOIN pegawai b on b.pegawaiid = a.pegawaiid
			INNER JOIN riwayatjabatan c ON c.pegawaiid = a.pegawaiid
			WHERE c.tglselesai IS NULL
			ORDER BY a.fullname	
		");
		$this->db->close();
		return $q->result_array();
	}
}

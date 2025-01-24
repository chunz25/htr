<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_pegawai extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getListPegawai($params)
	{
		// die(var_dump($params));
		$mresult = $this->tp_connpgsql->callSpCount('htr.sp_getdatapegawaihtr', $params, false);
		return $mresult;
	}

	function tambahPegawai($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT htr.sp_addpegawai(
				?,?,?,?,?,?,?,?,?,?,?,?
			);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updDataPegawai($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT htr.sp_upddatapegawai(?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}

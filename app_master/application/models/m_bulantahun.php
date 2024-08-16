<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_bulantahun extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getbulan()
	{
		$this->load->database();
		$q = $this->db->query("
				select distinct
					date_part('month', waktu) bulanno ,
					trim(to_char(waktu, 'Month')) bulan
				from dailyreport.absen
				order by date_part('month', waktu)
		");
		$this->db->close();
		return $q->result_array();
	}
	function gettahun()
	{
		$this->load->database();
		$q = $this->db->query("
		SELECT	DISTINCT
				date_part('year', waktu) as tahun
		FROM dailyreport.absen
		ORDER BY date_part('year', waktu)
		");
		$this->db->close();
		return $q->result_array();
	}
}

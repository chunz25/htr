<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_history extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getListPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('htr.sp_getdatahistorypegawai_new1', $params, false);
		return $mresult;
	}

	function getListHistoryDaily($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('htr.sp_getlistdailypeg1', $params, false);

		$data = array();
		foreach ($mresult['data'] as $r) {
			$r['fileexist'] = false;
			if (!empty($r['files'])) {
				$filePath = config_item('dailyreport_upload_dok_path') . $r['files'];
				if (file_exists($filePath) && is_file($filePath)) {
					$r['fileexist'] = true;
				} else {
					$r['fileexist'] = false;
				}
			}
			$data[] = $r;
		}

		$result = array('success' => true, 'count' => $mresult['count'], 'data' => $data);
		return $result;
	}

	function deleteDaily($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT htr.sp_deletedaily(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getHariLibur()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT DISTINCT TO_CHAR(tgl, 'YYYY-MM-DD') AS tgl FROM public.harilibur WHERE tgl >= TO_DATE('01/01/2018','DD/MM/YYYY') ORDER BY tgl ASC
		");
		$this->db->close();

		return $q->result_array();
	}

	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getinfopegawai', $params);
		return $mresult;
	}

	function getDailyById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('htr.sp_getdailybyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanDaily($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('htr.sp_getdetailpengajuandaily', array($pengajuanid));
		return $mresult['data'];
	}

	function getComboJenisForm($jeniscutiid = '')
	{
		$this->load->database();
		$whereClause = '';
		if (!empty($jeniscutiid)) {
			$whereClause = " WHERE jeniscutiid = '" . $jeniscutiid . "' ";
		}
		$sql = "
			SELECT jenisformid AS id, jenisform AS text
			FROM htr.jenisform
			" . $whereClause . "
			ORDER BY jenisformid
		";

		$q = $this->db->query($sql);
		$this->db->close();
		return $q->result_array();
	}

	function getComboStatusDaily()
	{
		$this->load->database();
		$q = $this->db->query("select * from htr.statusreport");
		$this->db->close();
		return $q->result_array();
	}

	function updStatusExp()
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query(
			"
			update htr.absen a set
			status = '1'
			where status = '6' and
			--a.jamupd <= cast(concat(extract(year from CURRENT_DATE)-1,'-','12','-','14') as date) and a.jamupd <= cast(concat(extract(year from CURRENT_DATE),'-',extract(month from CURRENT_DATE),'-','14') as date)
		"
		);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}

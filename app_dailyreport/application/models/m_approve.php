<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_approve extends CI_Model
{
	var $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
	}

	function getListPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('htr.sp_getdataapprovalpegawai_new', $params, false);
		return $mresult;
	}

	function approvebulk($param1, $param2, $param3)
	{
		$p = array(
			'v_vals' => $param1,
        	'v_pegawaiid' => $param2,
			'v_atasannotes' => $param3
		);
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT htr.sp_approvebulk(?,?,?)
			", $p);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function addNotif($params)
	{
		$this->CI->load->database();
		$this->CI->db->trans_start();
		$q = $this->CI->db->query("
			INSERT INTO htr.notifikasi_timesheet(tglnotif,jenisnotif,description,penerima,useridfrom,usergroupidfrom,pengirim,isshow,modulid,modul,isread)
			VALUES(NOW(),?,?,?,CAST(? AS INT),CAST(? AS INT),?,'1',CAST(? AS INT),?,0);
		", $params);
		$this->CI->db->trans_complete();
		$this->CI->db->close();
		return $this->CI->db->trans_status();
	}

	function getShortNotif($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, fnnamalengkap(p.namadepan, p.namabelakang) nama, p.nik
			FROM htr.notifikasi_timesheet n
			LEFT JOIN public.pegawai p ON n.pengirim = p.id
			WHERE CAST(n.penerima AS INT) = CAST(? AS INT)
			ORDER BY n.tglnotif DESC
			FETCH FIRST 5 ROWS ONLY
		", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}

	function getCountNotifUnread($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT COUNT(*) jml FROM htr.notifikasi_timesheet WHERE penerima = ? AND (isread IS NULL OR isread = 0)
		", array($penerimaid));
		$this->CI->db->close();
		return $q->first_row()->jml;
	}

	function updateNotifRead($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("UPDATE htr.notifikasi_timesheet SET isread = 1 WHERE penerima = ?", array($penerimaid));
		$this->CI->db->close();
		return $q;
	}

	function getListApprovalDaily($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('htr.sp_getverifikasidaily', $params, false);
		return $mresult;
	}

	function updStatusDaily($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT htr.sp_updstatusverifikasi(?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
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

}

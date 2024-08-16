<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_pengajuan extends CI_Model
{
	var $CI;
	function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
	}
	function addNotif($params2)
	{
		$this->CI->load->database();
		$this->CI->db->trans_start();
		$q = $this->CI->db->query("
			INSERT INTO dailyreport.notifikasi_timesheet(
				tglnotif,
				jenisnotif,
				description,
				penerima,
				useridfrom,
				usergroupidfrom,
				pengirim,
				isshow,
				modulid,
				modul,
				isread )
			VALUES(NOW(),?,?,?,CAST(? AS INT),CAST(? AS INT),?,'1',CAST(? AS INT),?,0);
		", $params2);
		$this->CI->db->trans_complete();
		$this->CI->db->close();
		return $this->CI->db->trans_status();
	}
	function getShortNotif($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT  n.notifid,
					TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
					n.jenisnotif,
					fnnamalengkap(p.namadepan, p.namabelakang) nama,
					p.nik
			FROM dailyreport.notifikasi_timesheet n
			LEFT JOIN public.pegawai p ON n.pengirim = p.pegawaiid
			WHERE n.penerima = ?
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
			SELECT COUNT(*) jml FROM dailyreport.notifikasi_timesheet WHERE penerima = ? AND (isread IS NULL OR isread = 0)
		", array($penerimaid));
		$this->CI->db->close();
		return $q->first_row()->jml;
	}
	function getCountNotif($penerimaid)
	{
		$this->CI->db->where('penerima', $penerimaid);
		return $this->CI->db->count_all_results("dailyreport.notifikasi_timesheet");
	}
	function getAllNotification($penerimaid, $row)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT  n.notifid,
					TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
					n.jenisnotif,
					fnnamalengkap(p.namadepan, p.namabelakang) nama,
					p.nik
			FROM dailyreport.notifikasi_timesheet n
			LEFT JOIN public.pegawai p ON n.pengirim = p.pegawaiid
			WHERE n.penerima = ?
			ORDER BY n.tglnotif DESC
			OFFSET ? ROWS
			FETCH NEXT 25 ROWS ONLY;
		", array($penerimaid, $row));
		$this->CI->db->close();
		return $q->result_array();
	}
	function updateNotifRead($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("UPDATE dailyreport.notifikasi_timesheet SET isread = 1 WHERE penerima = ?", array($penerimaid));
		$this->CI->db->close();
		return $q;
	}

	function getInfoDaily($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT COUNT(*) FROM dailyreport.absen WHERE status IN ('1','2','4') AND pegawaiid = ?
		", array($pegawaiid));
		$this->db->close();
		return $q->first_row();
	}
	function getStatusDaily($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT b.status FROM dailyreport.absen a
			LEFT JOIN dailyreport.statusreport b on a.status = b.statusid
			WHERE a.status IN ('1','2','4') AND a.pegawaiid = ?
		", array($pegawaiid));
		$this->db->close();
		return $q->first_row();
	}
	function getHariLibur()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT DISTINCT TO_CHAR(tgl, 'YYYY-MM-DD') AS tgl FROM dailyreport.harilibur WHERE tgl >= TO_DATE('01/01/2018','DD/MM/YYYY') ORDER BY tgl DESC
		");
		$this->db->close();

		return $q->result_array();
	}
	function getOpenDate($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM accessdate WHERE pegawaiid = ? ORDER BY nik
		",$pegawaiid);
		$this->db->close();

		return $q->result_array();
	}
	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getinfopegawai', $params);
		return $mresult;
	}
	function cekPengajuanDaily($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT dailyreport.cekpengajuan(?, ?, ?) AS jml
		", $params);
		$this->db->close();
		return $q->first_row()->jml;
	}
	function addPengajuanDaily($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('dailyreport.sp_addpengajuandaily', $params);
		return $mresult['firstrow'];
	}
	function getappver($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM public.vwinfoatasan a
			WHERE a.pegawaiid = ?
		", $pegawaiid);
		$this->db->close();

		return $q->result_array();
	}
	function getDraftDaily($pegawaiid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('dailyreport.sp_getdraftdaily', array($pegawaiid));
		return $mresult['data'];
	}
	function addDraftDaily($params)
	{
		$this->tp_connpgsql->callSpReturn('dailyreport.sp_adddraftdaily_v2', $params);
	}
	function updDraftDaily($params)
	{
		$this->tp_connpgsql->callSpReturn('dailyreport.sp_upddraftdaily_v2', $params);
	}
	function delDraftDaily($params)
	{
		$this->load->database();
		$q = $this->db->query("
		DELETE FROM dailyreport.draftabsen
		WHERE draftid = ?;
		", array($params));
		$this->db->close();
	}
	function getListHariLibur($tgl)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT TO_CHAR(tgl, 'YYYY-MM-DD') tgl
			FROM dailyreport.harilibur
			WHERE tgl >= TO_DATE(?,'DD/MM/YYYY') AND status = '1'
			ORDER BY tgl
			LIMIT 365
		", array($tgl));
		$this->db->close();

		$data = array();
		foreach ($q->result_array() as $r) {
			$data[] = $r['tgl'];
		}
		return $data;
	}
	function getDailyById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('dailyreport.sp_getdailybyid', $params);
		return $mresult['firstrow'];
	}
	function getDetailPengajuanDaily($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('dailyreport.sp_getdetailpengajuandaily', array($pengajuanid));
		return $mresult['data'];
	}
	function updStatusDaily($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT dailyreport.sp_updstatusverifikasi(?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function update($pegawaiid)
	{
		$data = array(
			'password' => MD5($this->input->post('passconf')),
		);
		$this->db->where('userid', $pegawaiid);
		$this->db->update('users', $data);
	}

	function isOldPassword($oldpassword)
	{
		$this->db->select('userid');
		$this->db->where('password', MD5($oldpassword));
		$query = $this->db->get('users');

		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
}

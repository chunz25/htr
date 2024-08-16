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
		$mresult = $this->tp_connpgsql->callSpCount('public.sp_getdataapprovalpegawai_new', $params, false);
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
			SELECT dailyreport.sp_approvebulk(?,?,?)
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
			INSERT INTO dailyreport.notifikasi_timesheet(tglnotif,jenisnotif,description,penerima,useridfrom,usergroupidfrom,pengirim,isshow,modulid,modul,isread)
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
	function updateNotifRead($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("UPDATE dailyreport.notifikasi_timesheet SET isread = 1 WHERE penerima = ?", array($penerimaid));
		$this->CI->db->close();
		return $q;
	}

	function getListApprovalDaily($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('dailyreport.sp_getverifikasidaily', $params, false);
		return $mresult;
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
	function getComboJenisForm($jeniscutiid = '')
	{
		$this->load->database();
		$whereClause = '';
		if (!empty($jeniscutiid)) {
			$whereClause = " WHERE jeniscutiid = '" . $jeniscutiid . "' ";
		}
		$sql = "
			SELECT jenisformid AS id, jenisform AS text
			FROM dailyreport.jenisform
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
			SELECT DISTINCT TO_CHAR(tgl, 'YYYY-MM-DD') AS tgl FROM dailyreport.harilibur WHERE tgl >= TO_DATE('01/01/2018','DD/MM/YYYY') ORDER BY tgl ASC
		");
		$this->db->close();

		return $q->result_array();
	}
	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getinfopegawai', $params);
		return $mresult;
	}
	function getVerifikatorCuti($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			select p.pegawaiid, p.nik, p.namadepan nama,
				vj.levelid, l.level, l.gol,
				pa1.pegawaiid atasanid, pa1.nik atasannik, pa1.namadepan atasannama, vj1.satkerid atasansatkerid, '' email,
				vj1.levelid atasanlevelid, l1.level atasanlevel, l1.gol atasangol, j1.jabatan atasanjabatan
			from pegawai p
			left join vwjabatanterakhir vj on p.pegawaiid = vj.pegawaiid
			left join level l on vj.levelid = l.levelid
			left join pegawai pa1 on pa1.pegawaiid = (
				case when p.pegawaiid = public.fnsatkerlevel2(vj.satkerid) then
						CASE WHEN vj.satkerid = '010802' THEN '000000000726'
						WHEN vj.satkerid IN ('010603','01060401','0106040205','0106040203','0106040204','0106040202') THEN '000000000501'
						WHEN vj.satkerid IN('01060202','010602') THEN '000000001073'
						WHEN vj.satkerid IN('01030202','01030203') THEN '000000000004'
						WHEN vj.satkerid IN('01030204','01030205') THEN '000000000411'
						WHEN vj.satkerid IN ('0102020402','0102020404','0102020405','0102020406','0102020407','0102020408') THEN '000000000348'
						WHEN vj.pegawaiid IN('000000001596') THEN '000000000348'
						ELSE public.fnsatkerlevel2(substring(vj.satkerid,1,length(vj.satkerid)-2)) END
					when vj.satkerid IN ('010603','01060401','0106040205','0106040203','0106040204','0106040202') THEN '000000000501'
					when vj.satkerid IN('01060202','010602') THEN '000000001073'
					when vj.satkerid IN('01030202','01030203') THEN '000000000004'
					when vj.satkerid IN('01030204','01030205') THEN '000000000411'
					WHEN vj.satkerid IN ('0102020402','0102020404','0102020405','0102020406','0102020407','0102020408') THEN '000000000348'
					WHEN vj.pegawaiid IN('000000001596') THEN '000000000348'
					else public.fnsatkerlevel2(vj.satkerid)
				end
			)
			left join vwjabatanterakhir vj1 on vj1.pegawaiid = pa1.pegawaiid
			LEFT JOIN jabatan j1 ON vj1.jabatanid = j1.jabatanid
			left join level l1 on vj1.levelid = l1.levelid
			where p.pegawaiid = ?
		", array($pegawaiid));
		$this->db->close();
		return $q->first_row();
	}
}

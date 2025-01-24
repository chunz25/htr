<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_dailyreport extends CI_Model
{
	var $CI;
	function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
	}

	function addNotif($params)
	{
		$this->CI->load->database();
		$this->CI->db->trans_start();
		$q = $this->CI->db->query("
			INSERT INTO public.notifikasi_absensi(tglnotif,jenisnotif,description,penerima,useridfrom,usergroupidfrom,pengirim,isshow,modulid,modul,isread)
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
			FROM public.notifikasi_absensi n
			LEFT JOIN pegawai p ON n.pengirim = p.pegawaiid
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
			SELECT COUNT(*) jml FROM public.notifikasi_absensi WHERE penerima = ? AND (isread IS NULL OR isread = 0)
		", array($penerimaid));
		$this->CI->db->close();
		return $q->first_row()->jml;
	}

	function updateNotifRead($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("UPDATE public.notifikasi_absensi SET isread = 1 WHERE penerima = ?", array($penerimaid));
		$this->CI->db->close();
		return $q;
	}

	function get_satker($nik)
	{
		$fingerid = '';
		$this->db = $this->load->database('hrd', TRUE);
		$q = $this->db->query("SELECT [USERID] FROM [FINGERPRINT].[dbo].[USERINFO] WHERE [BADGENUMBER] = ?", array($nik));

		if ($q->num_rows() > 0) {
			$fingerid = $q->first_row()->USERID;
		}

		return $fingerid;
	}

	function getReportHTR($params)
	{
		// var_dump($params);
		$mresult = $this->tp_connpgsql->callSpCount('htr.sp_getdailyreportv1', $params, false);
		return $mresult;
	}

	function getData($params)
	{
		$this->load->database();
		$pegawaiid = $this->session->userdata('pegawaiid');
		$cond_where = '';

		if ($pegawaiid == 726) {
			if (!empty($params['v_satkerid'])) {
				$cond_where =
					" where x.waktu BETWEEN TO_DATE('"
					. $params['v_tglmulai'] .
					"','YYYY/MM/DD') AND TO_DATE('"
					. $params['v_tglselesai'] .
					"','YYYY/MM/DD') AND x.satkerid LIKE '"
					. $params['v_satkerid'] .
					"' || '%' AND length(x.satkerid) < 5
                    AND x.gol IN ('0', '1')
					ORDER BY x.gol , COALESCE(x.satkerid,'99'), (CASE WHEN x.pegawaiid = (SELECT s1.kepalaid FROM satker s1 WHERE s1.satkerid = x.satkerid) THEN '1' ELSE '0' END) DESC, x.waktu";
			} else {
				$cond_where =
					" where x.waktu BETWEEN TO_DATE('"
					. $params['v_tglmulai'] .
					"','YYYY/MM/DD') AND TO_DATE('"
					. $params['v_tglselesai'] .
					"','YYYY/MM/DD') AND length(x.satkerid) < 5
					 ORDER BY x.gol , COALESCE(x.satkerid,'99'), (CASE WHEN x.pegawaiid = (SELECT s1.kepalaid FROM satker s1 WHERE s1.satkerid = x.satkerid) THEN '1' ELSE '0' END) DESC, x.waktu";
			}
		} else if ($pegawaiid == 853) {
			if (!empty($params['v_satkerid'])) {
				$cond_where =
					" where x.waktu BETWEEN TO_DATE('"
					. $params['v_tglmulai'] .
					"','YYYY/MM/DD') AND TO_DATE('"
					. $params['v_tglselesai'] .
					"','YYYY/MM/DD') and x.satkerid LIKE '"
					. $params['v_satkerid'] .
					"' || '%' ORDER BY x.gol , COALESCE(x.satkerid,'99'), (CASE WHEN x.pegawaiid = (SELECT s1.kepalaid FROM satker s1 WHERE s1.satkerid = x.satkerid) THEN '1' ELSE '0' END) DESC, x.waktu";
			} else {
				$cond_where =
					" where x.waktu BETWEEN TO_DATE('"
					. $params['v_tglmulai'] .
					"','YYYY/MM/DD') AND TO_DATE('"
					. $params['v_tglselesai'] .
					"','YYYY/MM/DD') ORDER BY x.gol , COALESCE(x.satkerid,'99'), (CASE WHEN x.pegawaiid = (SELECT s1.kepalaid FROM satker s1 WHERE s1.satkerid = x.satkerid) THEN '1' ELSE '0' END) DESC, x.waktu";
			}
		} else {
			if (!empty($params['v_satkerid'])) {
				$cond_where =
					" where x.statusid = '2' and x.waktu BETWEEN TO_DATE('"
					. $params['v_tglmulai'] .
					"','YYYY/MM/DD') AND TO_DATE('"
					. $params['v_tglselesai'] .
					"','YYYY/MM/DD') and x.satkerid LIKE '"
					. $params['v_satkerid'] .
					"' || '%' ORDER BY x.gol , COALESCE(x.satkerid,'99'), (CASE WHEN x.pegawaiid = (SELECT s1.kepalaid FROM satker s1 WHERE s1.satkerid = x.satkerid) THEN '1' ELSE '0' END) DESC, x.waktu";
			} else {
				$cond_where =
					" where x.statusid = '2' and x.waktu BETWEEN TO_DATE('"
					. $params['v_tglmulai'] .
					"','YYYY/MM/DD') AND TO_DATE('"
					. $params['v_tglselesai'] .
					"','YYYY/MM/DD') ORDER BY x.gol , COALESCE(x.satkerid,'99'), (CASE WHEN x.pegawaiid = (SELECT s1.kepalaid FROM satker s1 WHERE s1.satkerid = x.satkerid) THEN '1' ELSE '0' END) DESC, x.waktu";
			}
		}
		

		$query = "
		SELECT  * FROM (
			SELECT 	
				a.pengajuanid ,  
				a.pegawaiid , 
				a.nourut ,
				u.nik ,
				np.fullname as nama ,
				l.name as level ,
				j.name as jabatan ,
				loc.name as lokasi ,
				sp.satkerid as idsatker ,
				vs.code as satkerid ,
				vs.unitkerja ,
				vs.direktorat ,
				vs.divisi ,
				vs.departemen ,
				vs.seksi ,
				vs.subseksi ,
				to_char(a.jamupd, 'DD/MM/YYYY') AS tglpermohonan ,
				to_char(a.waktu, 'DD/MM/YYYY') AS tglmulai ,
				a.actjob ,
				a.keterangan ,
				a.atasannotes ,
				CASE
					WHEN a.jenisid = 1 THEN 'Daily Report Activity'
					ELSE NULL
				END AS jenis ,
				a.status AS statusid ,
				g.status ,
				a.atasanid ,
				np1.fullname as atasannama ,
				a.waktu,
				l.gol ,
				l.id as levelid ,
				l.susunan as idnew ,
				rj.lokasiid ,
				dp.jeniskelamin
			FROM 
				htr.absen a
				LEFT JOIN public.pegawai p ON p.id = a.pegawaiid
				LEFT JOIN public.datapegawai dp ON dp.pegawaiid = a.pegawaiid
				LEFT JOIN public.users u ON u.id = p.userid
				LEFT JOIN public.namapegawai np ON np.pegawaiid = a.pegawaiid
				LEFT JOIN public.namapegawai np1 ON np1.pegawaiid = a.atasanid
				LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = a.pegawaiid
				LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
				LEFT JOIN struktur.jabatan j ON j.id = sp.jabatanid
				LEFT JOIN struktur.levelgrade l ON l.id = sp.levelid
				LEFT JOIN public.riwayatjabatan rj ON rj.pegawaiid = a.pegawaiid
				LEFT JOIN public.lokasi loc ON loc.id = rj.lokasiid
				LEFT JOIN htr.statusreport g ON a.status = g.statusid
		) x
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getDataCount($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_satkerid']) || !empty($params['v_bulan']) || !empty($params['v_tahun'])) {
			$cond_where =
				" where a.satkerid LIKE '"
				. $params['v_satkerid'] .
				"' || '%' and a.bulan LIKE '"
				. $params['v_bulan'] .
				"' || '%' and a.tahun LIKE '"
				. $params['v_tahun'] .
				"' || '%' ORDER BY a.gol , a.idnew";
		} else {
			$cond_where = "
					WHERE a.tahun = trim(to_char(now(), 'YYYY'))
					AND a.bulan = trim(to_char(now(), 'Month'))
					ORDER BY a.gol , a.idnew
			";
		}

		$query = "
			SELECT * FROM htr.vw_countreport a
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getDataSum($params)
	{
		$result = $this->tp_connpgsql->callSpCount('htr.sp_getdatasum', $params);
		return $result;
	}

	function getDailyreportById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('htr.sp_getdailybyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanDailyreport($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('htr.sp_getdetailpengajuandaily', array($pengajuanid));
		return $mresult['data'];
	}

	function updStatusDailyreport($params)
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

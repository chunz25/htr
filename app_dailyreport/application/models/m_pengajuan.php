<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
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
			INSERT INTO htr.notifikasi_timesheet(
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
					np.fullname as nama,
					u.nik
			FROM htr.notifikasi_timesheet n
			LEFT JOIN public.pegawai p ON CAST(n.pengirim AS INT) = p.id
			LEFT JOIN public.users u ON u.id = p.userid
			LEFT JOIN public.namapegawai np ON np.pegawaiid = p.id
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
			SELECT COUNT(*) jml FROM htr.notifikasi_timesheet WHERE CAST(penerima AS INT) = CAST(? AS INT) AND (isread IS NULL OR isread = 0)
		", array($penerimaid));
		$this->CI->db->close();
		return $q->first_row()->jml;
	}

	function getCountNotif($penerimaid)
	{
		$this->CI->db->where('CAST(penerima AS INT)', $penerimaid);
		return $this->CI->db->count_all_results("htr.notifikasi_timesheet");
	}

	function getAllNotification($penerimaid, $row)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT  n.notifid,
					TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
					n.jenisnotif,
					np.fullname as nama,
					u.nik
			FROM htr.notifikasi_timesheet n
			LEFT JOIN public.pegawai p ON CAST(n.pengirim AS INT) = p.id
			LEFT JOIN public.users u ON u.id = p.userid
			LEFT JOIN public.namapegawai np ON np.pegawaiid = p.id
			WHERE CAST(n.penerima AS INT) = CAST(? AS INT)
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
		$q = $this->CI->db->query("UPDATE htr.notifikasi_timesheet SET isread = 1 WHERE penerima = ?", array($penerimaid));
		$this->CI->db->close();
		return $q;
	}

	function getInfoDaily($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT COUNT(*) FROM htr.absen WHERE status IN ('1','2','4') AND pegawaiid = ?
		", array($pegawaiid));
		$this->db->close();
		return $q->first_row();
	}

	function getStatusDaily($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT 
				b.status 
			FROM 
				htr.absen a
				LEFT JOIN htr.statusreport b on a.status = b.statusid
			WHERE 
				a.status IN ('1','2','4') 
				AND a.pegawaiid = ?
		", array($pegawaiid));
		$this->db->close();
		return $q->first_row();
	}

	function getHariLibur()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT DISTINCT TO_CHAR(tgl, 'YYYY-MM-DD') AS tgl FROM public.harilibur WHERE tgl >= TO_DATE('01/01/2018','DD/MM/YYYY') ORDER BY tgl DESC
		");
		$this->db->close();

		return $q->result_array();
	}

	function getOpenDate($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM htr.accessdate WHERE pegawaiid = CAST(? AS INT) ORDER BY nik
		", $pegawaiid);
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
			SELECT htr.cekpengajuan(?, ?, ?) AS jml
		", $params);
		$this->db->close();
		return $q->first_row()->jml;
	}

	function addPengajuanDaily($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('htr.sp_addpengajuandaily', $params);
		return $mresult['firstrow'];
	}

	function getappver($pegawaiid)
	{
		// Ensure the input is not empty and is valid
		if (empty($pegawaiid) || !is_numeric($pegawaiid)) {
			log_message('error', 'Invalid pegawaiid provided to getAppVer: ' . print_r($pegawaiid, true));
			return []; // Return an empty array for invalid input
		}

		$this->load->database(); // Make sure the database library is loaded

		try {
			// Use a prepared statement to enhance security
			$query = $this->db->query("
            SELECT 
                a.id AS pegawaiid,
                b.nik,
                c.fullname AS nama,
                e.name AS jabatan,
                f.emailkantor AS email
            FROM pegawai a
            INNER JOIN users b ON b.id = a.userid
            INNER JOIN namapegawai c ON c.pegawaiid = a.id
            INNER JOIN struktur.satkerpegawai d ON d.pegawaiid = a.id
            INNER JOIN struktur.jabatan e ON e.id = d.jabatanid
            INNER JOIN datapegawai f ON f.pegawaiid = a.id
            WHERE a.id = ?
        ", [$pegawaiid]); // Use parameterized query

			return $query->result_array();
		} catch (Exception $e) {
			// Log the error message
			log_message('error', 'Error in getAppVer: ' . $e->getMessage());
			return []; // Return empty array on error
		}
	}

	function getDraftDaily($pegawaiid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('htr.sp_getdraftdaily', array($pegawaiid));
		return $mresult['data'];
	}

	function addDraftDaily($params)
	{
		$this->tp_connpgsql->callSpReturn('htr.sp_adddraftdaily_v2', $params);
	}

	function updDraftDaily($params)
	{
		$this->tp_connpgsql->callSpReturn('htr.sp_upddraftdaily_v2', $params);
	}

	function delDraftDaily($params)
	{
		$this->load->database();
		$q = $this->db->query("
		DELETE FROM htr.draftabsen
		WHERE draftid = ?;
		", array($params));
		$this->db->close();
	}

	function getListHariLibur($tgl)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT TO_CHAR(tgl, 'YYYY-MM-DD') tgl
			FROM public.harilibur
			WHERE tgl >= TO_DATE(?,'DD/MM/YYYY') AND isaktif = '1'
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
		$mresult = $this->tp_connpgsql->callSpReturn('htr.sp_getdailybyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanDaily($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('htr.sp_getdetailpengajuandaily', array($pengajuanid));
		return $mresult['data'];
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

	public function addPengajuanAbsensi($params)
	{
		try {
			// Call stored procedure
			$result = $this->tp_connpgsql->callSpReturn('kehadiran.sp_addpengajuanabsen', $params);
			// $result = 'OKE!';
			// Check if the result is false, meaning the query failed
			if ($result === false) {
				throw new Exception('Database query failed in addPengajuanAbsensi');
			}

			return $result;

		} catch (Exception $e) {
			// Log the error with additional debugging information
			log_message('error', 'Error in addPengajuanAbsensi: ' . $e->getMessage());
			return array();  // Return empty array on failure
		}
	}
}

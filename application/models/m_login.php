<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
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
				SELECT 
					p.id AS pegawaiid ,
					u.id AS userid ,
					u.nik AS username ,
					u.nik ,
					np.fullname AS nama ,
					CASE 
						WHEN sp.levelid NOT IN (14,15,16) THEN 97 
						ELSE 98 
					END usergroupid ,
					CASE 
						WHEN sp.levelid NOT IN (14,15,16) THEN 'Approval' 
						ELSE 'Pegawai' 
					END usergroup ,
					98 AS modulid ,
					'dailyreport' AS modul ,
					sp.satkerid AS idsatker ,
					vs.code AS satkeridakses ,
					vs.code AS satkeridpegawai ,
					rj.lokasiid ,
					vs.unitkerja ,
					va.manager_id AS atasanid ,
					vv.manager_id AS verifikatorid ,
					u.password ,
					sp.jabatanid
				FROM 
					public.pegawai p
					LEFT JOIN public.users u ON u.id = p.userid
					LEFT JOIN public.namapegawai np ON np.pegawaiid = p.id
					LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = p.id
					LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
					LEFT JOIN public.riwayatjabatan rj ON rj.pegawaiid = p.id
					LEFT JOIN struktur.vw_approver va ON va.pegawaiid = p.id
					LEFT JOIN struktur.vw_verifer vv ON vv.pegawaiid = p.id
				WHERE
					p.isaktif = 1
					AND u.id = (SELECT id FROM users WHERE nik = ?)
		        " . $str_password, array($username, $password));
		return $q->result();
	}

	function deleteAccess()
	{
		$this->load->database();

		$this->db->query("
			DELETE FROM htr.accessdate
			WHERE 
				dateexp = CURRENT_DATE
				OR dateexp < CURRENT_DATE
			");

		return;
	}
}

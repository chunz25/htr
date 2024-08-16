<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// require_once "cetakexcel.php";

class pegawai extends SIAP_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pegawai');
	}

	function getListPegawai()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_nik' => ifunsetempty($_POST, 'nik', null),
			'v_nama' => ifunsetempty($_POST, 'nama', null),
			'v_statuspegawai' => ifunsetempty($_POST, 'statuspegawai', null),
			'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', null),
			'v_lokasikerja' => ifunsetempty($_POST, 'lokasiid', null),
			'v_tglmulai' => ifunsetempty($_POST, 'tglmulai', null),
			'v_tglselesai' => ifunsetempty($_POST, 'tglselesai', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE')),
		);
		$mresult = $this->m_pegawai->getListPegawai($params);
		echo json_encode($mresult);
	}

	function tambahPegawai()
	{
		$params = array(
			'v_nik' => ifunsetempty($_POST, 'nik', null),
			'v_fullname' => ifunsetempty($_POST, 'fullname', null),
			'v_namadepan' => ifunsetempty($_POST, 'namadepan', null),
			'v_namabelakang' => ifunsetempty($_POST, 'namabelakang', null),
			'v_namakeluarga' => ifunsetempty($_POST, 'namakeluarga', null),
			'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', null),
			'v_tglmasuk' => ifunsetempty($_POST, 'tglmasuk', null),
			'v_statuspegawaiid' => ifunsetempty($_POST, 'statuspegawai', null),
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null),
			'v_jabatanid' => ifunsetempty($_POST, 'jabatanID', null),
			'v_levelid' => ifunsetempty($_POST, 'levelid', null),
			'v_lokasi' => ifunsetempty($_POST, 'lokasiid', null),
		);

		$mresult = $this->m_pegawai->tambahPegawai($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function crudPegawai()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nama' => ifunsetempty($_POST, 'nama', null),
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null),
			'v_jabatanid' => ifunsetempty($_POST, 'jabatanid', null),
			'v_levelid' => ifunsetempty($_POST, 'levelid', null),
			'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', null),
			'v_tglmasuk' => ifunsetempty($_POST, 'tglmulai', null),
			'v_tglkeluar' => ifunsetempty($_POST, 'tglselesai', null),
			'v_statuspegawai' => ifunsetempty($_POST, 'statuspegawaiid', null),
			'v_lokasiid' => ifunsetempty($_POST, 'lokasikerja', null),
	        	'v_atasanid' => ifunsetempty($_POST, 'atasanid', null),
        		'v_backdate' => ifunsetempty($_POST, 'accessod', null),
        		'v_hari' => ifunsetempty($_POST, 'jmlhari', null),
			'v_satkerdisp' => ifunsetempty($_POST, 'satkerdisp', null),
		);

		//var_dump($params);die;
		$mresult = $this->m_pegawai->updDataPegawai($params);
		// var_dump($mresult);die;

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}
}

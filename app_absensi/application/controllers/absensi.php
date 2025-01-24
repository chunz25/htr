<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class absensi extends ABSENSI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_absensi');
	}

	function getListAbsensi()
	{
		$user = $this->session->userdata('nik');
		$nik = ifunsetempty($_POST, 'nik', '%');
		$tglstr = ifunsetempty($_POST, 'tglstr', '');
		$tglend = ifunsetempty($_POST, 'tglend', '');
		$tglmulai = ifunsetempty($_POST, 'tglmulai', $tglstr);
		$tglselesai = ifunsetempty($_POST, 'tglselesai', $tglend);
		$start = ifunsetempty($_POST, 'start', 0);
		$limit = ifunsetempty($_POST, 'limit', config_item('PAGESIZE'));

		$params = array(
			'username' => $user,
			'nik' => $nik,
			'tglmulai' => $tglmulai,
			'tglselesai' => $tglselesai,
			'v_start' => $start,
			'v_limit' => $limit,
		);

		// Get the attendance data from both methods
		$mresult1 = $this->m_absensi->getDataAbsensi($params);
		if (!isset($mresult1['data'])) {
			$mresult1['data'] = null;
			$mresult1['count'] = 0;
		}
		$mresult2 = $this->m_absensi->getDataAbsensiStore($params);
		if (!isset($mresult2['data'])) {
			$mresult2['data'] = null;
			$mresult2['count'] = 0;
		}

		// Combine the data from both results and filter out empty arrays
		$dataAbsen['data'] = array_merge((array) $mresult1['data'], (array) $mresult2['data']);
		$dataAbsen['count'] = $mresult1['count'] + $mresult2['count'];

		echo json_encode($dataAbsen);
	}

	// function cetakdokumen()
	// {
	// 	$pegawaiid = $this->session->userdata('pegawaiid');
	// 	$tglstr = ifunsetemptybase64($_GET, 'tglstr', '');
	// 	$tglend = ifunsetemptybase64($_GET, 'tglend', '');
	// 	$tglmulai = ifunsetemptybase64($_GET, 'tglmulai', $tglstr);
	// 	$tglselesai = ifunsetemptybase64($_GET, 'tglselesai', $tglend);

	// 	$params = array(
	// 		'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
	// 		'v_nik' => ifunsetemptybase64($_GET, 'nik', ''),
	// 		'v_nama' => ifunsetemptybase64($_GET, 'nama', ''),
	// 		'v_level' => ifunsetemptybase64($_GET, 'level', ''),
	// 		'v_where' => $vwhere,
	// 		'v_jeniskelamin' => ifunsetemptybase64($_GET, 'jeniskelamin', ''),
	// 		'v_lokasikerja' => ifunsetemptybase64($_GET, 'lokasikerja', ''),
	// 		'v_tglmulai' => $tglmulai,
	// 		'v_tglselesai' => $tglselesai,
	// 		'v_statusid' => ifunsetemptybase64($_POST, 'statusid', ''),
	// 		'v_start' => 0,
	// 		'v_limit' => 10000000000,
	// 	);
	// 	$mresult = $this->m_dailyreport->getReportHTR($params);

	// 	$TBS = $this->template_cetak->createNew('xlsx', config_item("absensi_tpl_path") . "REPORT_DAILYREPORT.xlsx");
	// 	$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
	// 	$TBS->MergeField('header', array());
	// 	$TBS->MergeBlock('rec', $mresult['data']);
	// 	$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_DAILYREPORT.xlsx");
	// 	$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
	// 	$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	// }
}

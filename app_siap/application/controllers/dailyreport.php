<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class dailyreport extends SIAP_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_dailyreport');
	}

	function getListDailyreport()
	{
		// $mresult = $this->m_dailyreport->updStatusExp();
		$pegawaiid = $this->session->userdata('pegawaiid');
		$tglstr = ifunsetempty($_POST, 'tglstr', '');
		$tglend = ifunsetempty($_POST, 'tglend', '');
		$tglmulai = ifunsetempty($_POST, 'tglmulai', $tglstr);
		$tglselesai = ifunsetempty($_POST, 'tglselesai', $tglend);
		$vwhere = null;
		if ($pegawaiid == '000000000726') {
			$vwhere = " length(x.satkerid) < 6 AND x.gol IN ('0', '1')";
		} else if ($pegawaiid == '000000000853' || $pegawaiid == '000000002123') {
			$vwhere = "";
		} else {
			$vwhere = " x.statusid = '2' ";
		}
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_nik' => ifunsetempty($_POST, 'nik', ''),
			'v_nama' => ifunsetempty($_POST, 'nama', ''),
			'v_level' => ifunsetempty($_POST, 'level', ''),
			'v_where' => $vwhere,
			'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', ''),
			'v_lokasikerja' => ifunsetempty($_POST, 'lokasikerja', ''),
			'v_tglmulai' => $tglmulai,
			'v_tglselesai' => $tglselesai,
			'v_statusid' => ifunsetempty($_POST, 'statusid', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE')),
		);
		// var_dump($params);die;

		$mresult = $this->m_dailyreport->getReportHTR($params);
		echo json_encode($mresult);
	}

	function getcountdailyreport()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerdisp', ''),
			'v_bulan' => ifunsetempty($_POST, 'bulan', ''),
			'v_tahun' => ifunsetempty($_POST, 'tahun', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE')),
		);
		$mresult = $this->m_dailyreport->getDataCount($params);
		echo json_encode($mresult);
	}

	function getsumdailyreport()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_nik' => ifunsetempty($_POST, 'nik', ''),
			'v_nama' => ifunsetempty($_POST, 'nama', ''),
			'v_bulan' => ifunsetempty($_POST, 'bulan', ''),
			'v_tahun' => ifunsetempty($_POST, 'tahun', ''),
			'v_level' => ifunsetempty($_POST, 'level', ''),
			'v_lokasi' => ifunsetempty($_POST, 'lokasikerja', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE')),
		);

		// var_dump($params);die;

		$mresult = $this->m_dailyreport->getDataSum($params);
		echo json_encode($mresult);
	}

	function getDetailPengajuanDailyreport()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$mresult = $this->m_dailyreport->getDetailPengajuanDailyreport($pengajuanid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function getDetailDailyreportPegawai()
	{
		$params = array(
			'v_pegawaiid' => ifunsetemptybase64($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetemptybase64($_POST, 'nourut', null),
			'v_tahun' => null,
		);
		$mresult = $this->m_dailyreport->getDailyreportById($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function get_import_file()
	{
		try {
			$template = $_FILES['dokumen']['tmp_name'];

			$objPHPExcel = $this->cetak_phpexcel->loadTemplate($template);
			$objWorksheet = $objPHPExcel->setActiveSheetIndexbyName('Sheet1');

			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = 73;

			$startRow  = 1;
			$startColumn  = 0;

			$data = array();
			$fhead = array();

			for ($row = $startRow; $row <= $highestRow; ++$row) {
				$val = array();
				if ($row == 1) {
					for ($col = $startColumn; $col < $highestColumnIndex; ++$col) {
						$cell = $objWorksheet->getCellByColumnAndRow($col, $row);
						if (!empty($cell->getValue())) {
							$fhead[] = $cell->getValue();
						}
					}
				} else {
					for ($col = $startColumn; $col < sizeof($fhead); ++$col) {
						$cell = $objWorksheet->getCellByColumnAndRow($col, $row);
						$cell = $cell->getValue();

						// Jika tanggal format excel maka convert ke dalam format Y-m-d
						if ($col == 0 and $row > 1) {
							$celltgl = $objWorksheet->getCellByColumnAndRow(0, $row);
							$cell = $celltgl->getValue();
							if (!empty($cell)) {
								if (PHPExcel_Shared_Date::isDateTime($celltgl)) {
									$cell = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($cell));
								}
							}
						}
						if (strlen($cell) > 0) {
							$val[$fhead[$col]] = $cell;
						}
					}
					if (sizeof($val) > 0) {
						$data[] = $val;
					}
				}
			}
			$result = array('success' => true, 'data' => $data, 'message' => $highestColumn);
			echo json_encode($result);
		} catch (Exception $e) {
			$result = array('success' => false, 'data' => '', 'message' => 'File upload tidak sesuai dengan template');
			echo json_encode($result);
		}
	}

	function prosesImportData()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $key => $val) {
			$temp = array();
			$temp['v_nik'] = $params['Nik'];
			$temp['v_tglmulai'] = $params['Tgl Mulai'];
			$temp['v_tglselesai'] = $params['Tgl Selesai'];
			$temp['v_jenis'] = $params['Jenis'];
			$temp['v_alasan'] = $params['Alasan'];
			$mresult = $this->m_dailyreport->prosesImportData($temp);
			if ($mresult) $o++;
			//}
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'data berhasil');
		} else {
			$result = array('success' => false, 'message' => 'data gagal');
		}
		echo json_encode($result);
	}

	function approveDailyreport()
	{
		$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_POST, 'nourut', null);
		$nik = ifunsetempty($_POST, 'nik', null);
		$nama = ifunsetempty($_POST, 'nama', null);
		$tglpermohonan = ifunsetempty($_POST, 'tglpermohonan', null);
		$email = ifunsetempty($_POST, 'pengajuemail', null);
		$statusid = ifunsetempty($_POST, 'statusid', null);

		//database HRD
		$fingerid = ifunsetempty($_POST, 'fingerid', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');

		//get data from store
		$detailallcuti = array();
		$detailallcuti = json_decode(ifunsetempty($_POST, 'detailallcuti', null));
		//end
		$newstatusid = null;
		$newstatustext = '';
		$alasan = ifunsetempty($_POST, 'alasan', null);

		// Jika disetujui approval, maka ubah status menjadi disetujui HR
		if ($statusid == '2') {
			$newstatusid = '4';
			$newstatustext = 'Disetujui HR';
		}

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => $newstatusid,
			'v_batalalasan' => null,
		);

		$mresult = $this->m_dailyreport->updStatusDailyreport($params);
		if ($mresult) {
			$desc = array(
				'nik' => $nik,
				'nama' => $nama,
				'desctription' => 'Pengajuan absensi Daily Report pada tanggal ' . $tglpermohonan . ' ' . $newstatustext,
			);
			$params = array(
				'v_jenisnotif' => $newstatustext,
				'v_description' => json_encode($desc),
				'v_penerima' => $pegawaiid,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => '10',
				'v_pengirim' => $pegawaiid,
				'v_modulid' => '10',
				'v_modul' => 'Modul datang telat / pulang cepat',
			);
			$this->m_dailyreport->addNotif($params);
			$this->sendMail($nik, $email, $nama, $newstatustext, $alasan);
			// insert into mesin absensi
			if ($newstatusid == '4') {
				$timezone = "Asia/Jakarta";
				if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
				$tglpermohonan = date('Y-m-d H:i:s');
				foreach ($detailallcuti as $r) {
					$cutiid = $r->cutiid;
					if ($cutiid == '7' || $cutiid == '8') {
						$params = array(
							'USERID' => $fingerid,
							'STARTSPECDAY' => $r->tglawal,
							'ENDSPECDAY' => $r->tglakhir,
							'DATEID' => $cutiid,
							'YUANYING' => $r->keterangan,
							'DATE' => $tglpermohonan,
						);
						$this->db = $this->load->database('hrd', TRUE);
						$sql = "INSERT INTO USER_SPEDAY(USERID,STARTSPECDAY,ENDSPECDAY,DATEID,YUANYING,DATE) VALUES (?,?,?,?,?,?) ";
						$query = $this->db->query($sql, $params);
					}
				}
			}
			$result = array('success' => true, 'message' => 'Data berhasil diupdate');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diupdate');
		}
		echo json_encode($result);
	}

	function rejectKehadrian()
	{
		$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_POST, 'nourut', null);
		$nik = ifunsetempty($_POST, 'nik', null);
		$nama = ifunsetempty($_POST, 'nama', null);
		$tglpermohonan = ifunsetempty($_POST, 'tglpermohonan', null);
		$email = ifunsetempty($_POST, 'pengajuemail', null);
		$statusid = ifunsetempty($_POST, 'statusid', null);
		$fingerid = ifunsetempty($_POST, 'fingerid', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$detailallcuti = array();
		$detailallcuti = json_decode(ifunsetempty($_POST, 'detailallcuti', null));
		//end
		$newstatusid = null;
		$newstatustext = '';
		$alasan = ifunsetempty($_POST, 'alasan', null);

		// ditolak HR
		if ($statusid == '2') {
			$newstatusid = '5';
			$newstatustext = 'Ditolak HR';
		}

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => $newstatusid,
			'v_batalalasan' => $alasan,
		);

		$mresult = $this->m_dailyreport->updStatusDailyreport($params);
		if ($mresult) {
			$desc = array(
				'nik' => $nik,
				'nama' => $nama,
				'desctription' => 'Pengajuan absensi Daily Report pada tanggal ' . $tglpermohonan . ' ' . $newstatustext,
			);
			$params = array(
				'v_jenisnotif' => $newstatustext,
				'v_description' => json_encode($desc),
				'v_penerima' => $pegawaiid,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_siap'),
				'v_pengirim' => $pegawaiid,
				'v_modulid' => '10',
				'v_modul' => 'Modul datang telat / pulang cepat',
			);
			$this->m_dailyreport->addNotif($params);
			// $this->sendMail($nik, $email, $nama, $newstatustext, $alasan);
			$result = array('success' => true, 'message' => 'Data berhasil diupdate');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diupdate');
		}
		echo json_encode($result);
	}

	public function sendMail($nik, $email, $nama, $newstatustext, $alasan)
	{
		$tglpermohonan = date("d/m/Y");
		$this->load->library('email');	//Load email library
		$link = 'http://10.101.0.85/hris/index.php/login';
		// SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'mail.electronic-city.co.id',
			'smtp_port' => 25,
			'smtp_user' => 'hris-ec@electronic-city-internal.co.id',
			'smtp_pass' => 'L0nt0n9',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		if ($email == null) {
			// Tidak mengirim email apabila email id kosong
		} else {
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->to($email); // DIISI OLEH PENGAJU
			// $this->email->to('hr.adm2@electronic-city.co.id');
			$this->email->from('hris-ec@electronic-city-internal.co.id');
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Status Notifikasi</th><th>Tanggal Pengajuan</th><th>Alasan</th><th>Link</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $nama . '</td><td>' . $newstatustext . '</td><td>' . $tglpermohonan . '</td><td>' . $alasan . '</td><td>' . $link . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Status Pengajuan Form Daily Report');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}

	function cetakdokumen()
	{
		// $mresult = $this->m_dailyreport->updStatusExp();
		$pegawaiid = $this->session->userdata('pegawaiid');
		$tglstr = ifunsetemptybase64($_GET, 'tglstr', '');
		$tglend = ifunsetemptybase64($_GET, 'tglend', '');
		$tglmulai = ifunsetemptybase64($_GET, 'tglmulai', $tglstr);
		$tglselesai = ifunsetemptybase64($_GET, 'tglselesai', $tglend);
		$vwhere = null;
		if ($pegawaiid == '000000000726') {
			$vwhere = "length(x.satkerid) < 5 AND x.gol IN ('0', '1')";
		} else if ($pegawaiid == '000000000853' || $pegawaiid == '000000002123') {
			$vwhere = "";
		} else {
			$vwhere = "x.statusid = '2'";
		}
		$params = array(
			'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
			'v_nik' => ifunsetemptybase64($_GET, 'nik', ''),
			'v_nama' => ifunsetemptybase64($_GET, 'nama', ''),
			'v_level' => ifunsetemptybase64($_GET, 'level', ''),
			'v_where' => $vwhere,
			'v_jeniskelamin' => ifunsetemptybase64($_GET, 'jeniskelamin', ''),
			'v_lokasikerja' => ifunsetemptybase64($_GET, 'lokasikerja', ''),
			'v_tglmulai' => $tglmulai,
			'v_tglselesai' => $tglselesai,
			'v_statusid' => ifunsetemptybase64($_POST, 'statusid', ''),
			'v_start' => 0,
			'v_limit' => 10000000000,
		);
		$mresult = $this->m_dailyreport->getReportHTR($params);

		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_DAILYREPORT.xlsx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_DAILYREPORT.xlsx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}

	function cetakdokumencount()
	{
		$params = array(
			'v_bulan' => ifunsetemptybase64($_GET, 'bulan', ''),
			'v_tahun' => ifunsetemptybase64($_GET, 'tahun', ''),
			'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
			'v_start' => 0,
			'v_limit' => 10000000000,
		);
		$mresult = $this->m_dailyreport->getDataCount($params);

		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "COUNT_DAILYREPORT.xlsx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('COUNT_DAILYREPORT', 'ReportCountHTR_' . date('Y-m-d'), "COUNT_DAILYREPORT.xlsx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}

	function cetakdokumensum()
	{
		$params = array(
			'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
			'v_nik' => ifunsetemptybase64($_GET, 'nik', ''),
			'v_nama' => ifunsetemptybase64($_GET, 'nama', ''),
			'v_bulan' => ifunsetemptybase64($_GET, 'bulan', ''),
			'v_tahun' => ifunsetemptybase64($_GET, 'tahun', ''),
			'v_level' => ifunsetemptybase64($_GET, 'level', ''),
			'v_lokasi' => ifunsetemptybase64($_GET, 'lokasikerja', ''),
			'v_start' => 0,
			'v_limit' => 10000000,
		);

		$mresult = $this->m_dailyreport->getDataSum($params);

		// var_dump($mresult);die;

		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "Summary_DAILYREPORT.xlsx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('Summary_DAILYREPORT', 'ReportSumHTR_' . date('Y-m-d'), "Summary_DAILYREPORT.xlsx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}
}

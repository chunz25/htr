<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Absensi extends Wfh_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_absensi');
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$pegawaiId = $this->session->userdata('pegawaiid');
		if (!empty($pegawaiId)) {
			$this->absenList();
		} else {
			$this->session->sess_destroy();
			redirect('..\login');
		}
	}

	public function absenList()
	{
		$nik = $this->session->userdata('nik');
		$atasanid = $this->session->userdata('atasanid');

		$data = array();
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$data['vnik'] = $nik;
		$data['vatasan'] = $atasanid;
		$content = 'absensi/v_absensi';
		$data['pages'] = 'absensi';
		$this->load->view($content, $data);
	}

	function getListAbsensi()
	{
		$nik = $_GET['nik'];
		$tglmulai = $_GET['startdate'];
		$tglselesai = $_GET['enddate'];
		$top = $_GET['top'];

		$params = array(
			'username' => $nik,
			'tglmulai' => $tglmulai,
			'tglselesai' => $tglselesai,
			'top' => $top
		);

		$mresult = $this->m_absensi->getDataAbsensi($params);
		echo json_encode($mresult);
	}

	public function cetakDokumen()
	{
		$pegawaiId = $this->session->userdata('pegawaiid');
		$tglStr = $this->input->get('tglstr', true) ?: '';
		$tglEnd = $this->input->get('tglend', true) ?: '';
		$tglMulai = $this->input->get('tglmulai', true) ?: $tglStr;
		$tglSelesai = $this->input->get('tglselesai', true) ?: $tglEnd;

		$params = array(
			'v_satkerid' => $this->input->get('satkerid', true) ?: '',
			'v_nik' => $this->input->get('nik', true) ?: '',
			'v_nama' => $this->input->get('nama', true) ?: '',
			'v_level' => $this->input->get('level', true) ?: '',
			'v_where' => $vwhere,
			'v_jeniskelamin' => $this->input->get('jeniskelamin', true) ?: '',
			'v_lokasikerja' => $this->input->get('lokasikerja', true) ?: '',
			'v_tglmulai' => $tglMulai,
			'v_tglselesai' => $tglSelesai,
			'v_statusid' => $this->input->post('statusid', true) ?: '',
			'v_start' => 0,
			'v_limit' => 10000000000,
		);

		$mresult = $this->m_dailyreport->getReportHTR($params);

		$TBS = $this->template_cetak->createNew('xlsx', config_item("absensi_tpl_path") . "REPORT_DAILYREPORT.xlsx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$fileName = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_DAILYREPORT.xlsx");
		$fileName = str_replace('.', '_' . $suffix . '.', $fileName);
		$TBS->Show(OPENTBS_DOWNLOAD, $fileName);
	}

	public function simpanabsensi()
	{
		try {
			$pegawaiid = (int) $this->session->userdata('pegawaiid');
			$atasanid = isset($_POST['atasanid']) ? $_POST['atasanid'] : null;
			$tglabsen = isset($_POST['tglabsen']) ? $_POST['tglabsen'] : null;
			$masuk = isset($_POST['masuk']) ? $_POST['masuk'] : null;
			$keluar = isset($_POST['keluar']) ? $_POST['keluar'] : null;
			$alasan = isset($_POST['alasan']) ? $_POST['alasan'] : null;

			$paramIn = array(
				'v_jenisid' => 3,
				'v_waktu' => $tglabsen,
				'v_jam' => $masuk,
				'v_keterangan' => $alasan,
				'v_status' => 1,
				'v_pegawaiid' => (int) $pegawaiid,
				'v_atasanid' => (int) $atasanid,
				'v_files' => null,
				'v_filestype' => null
			);

			$paramOut = array(
				'v_jenisid' => 3,
				'v_waktu' => $tglabsen,
				'v_jam' => $keluar,
				'v_keterangan' => $alasan,
				'v_status' => 1,
				'v_pegawaiid' => (int) $pegawaiid,
				'v_atasanid' => (int) $atasanid,
				'v_files' => null,
				'v_filestype' => null
			);

			$this->m_absensi->addPengajuanAbsensi($paramIn);
			$this->m_absensi->addPengajuanAbsensi($paramOut);

		} catch (Exception $e) {
			log_message('error', 'Error in simpanabsensi: ' . $e->getMessage());

			echo json_encode(array(
				'success' => false,
				'message' => 'An error occurred: ' . $e->getMessage()
			));
		}
	}
}

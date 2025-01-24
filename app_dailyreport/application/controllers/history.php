<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class history extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_history');
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$pegawaiid = $this->session->userdata('pegawaiid');
		// if (isset($_COOKIE['userdata']) && isset($_SESSION['userdata'])) {
		if (!empty($pegawaiid)) {
			$this->historydaily();
		} else {
			$this->session->sess_destroy();
			redirect('..\login');
		}
	}

	public function historydaily()
	{
		$data = array();
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$data['vstatusdaily'] = $this->m_history->getComboStatusDaily();
		// $mresult = $this->m_history->updStatusExp();
		$content = 'history/v_historydailyhome';
		$data['pages'] = 'history';
		$this->load->view($content, $data);
	}

	public function getListPegawai()
	{
		$nik = $this->session->userdata('nik');
		$usergroupid = $this->session->userdata('aksesid_dailyreport');
		$jabatanid = $this->session->userdata('jabatanid');
		$satkerid = $this->session->userdata('satkerdisp');
		$keyword = ifunsetempty($_GET, 'keyword', '');

		$params = array(
			'v_satkerid' => $satkerid,
			'v_nik' => $nik,
			'v_nama' => '',
			'v_statuspegawai' => '',
			'v_jabatan' => $jabatanid,
			'v_jeniskelamin' => '',
			'v_tglmulai' => '',
			'v_tglselesai' => '',
			'v_usergroupid' => $usergroupid,
			'v_lokasiid' => $this->session->userdata('lokasiid'),
			'v_keyword' => $keyword,
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);
		//var_dump($params);

		$mresult = $this->m_history->getListPegawai($params);
		echo json_encode($mresult);
	}

	function detaillistdaily()
	{
		$data = array();
		$data['vpegawaiid'] = ifunsetemptybase64($_GET, 'pegawaiid', null);
		$data['vstatusdaily'] = $this->m_history->getComboStatusDaily();
		$data['pages'] = 'history';
		$content = 'history/v_historydaily';
		$this->load->view($content, $data);
	}

	public function getListHistoryDaily()
	{
		$pegawaiid = ifunsetempty($_GET, 'pegawaiid', null);
		$usergroupid = $this->session->userdata('aksesid_dailyreport');
		$satkerid = $this->session->userdata('satkerdisp');

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_status' => ifunsetempty($_GET, 'status', null),
			'v_mulai' => ifunsetempty($_GET, 'tglmulai', null),
			'v_selesai' => ifunsetempty($_GET, 'tglselesai', null),
			'v_satkerid' => $satkerid,
			'v_keyword' => null,
			'v_nstatus' => null,
			'v_usergroupid' => null,
			'v_lokasiid' => '1',
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);
		$mresult = $this->m_history->getListHistoryDaily($params);
		echo json_encode($mresult);
	}

	public function deleteDraft()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_pengajuanid' => ifunsetempty($_POST, 'pengajuanid', null),
		);

		$mresult = $this->m_history->deleteDaily($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus yes');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}

	public function download()
	{
		$this->load->helper('download');

		$filename = $this->input->get('filename');
		$path = config_item('dailyreport_upload_dok_path');

		if ($filename == '') {
			echo '<h2>Belum upload file</h2>';
		} else if (file_exists($path . $filename)) {
			$data = file_get_contents($path . $filename);
			force_download($filename, $data);
		} else {
			echo '<h2>Maaf, File hilang</h2>';
		}
	}

	public function getHariLibur()
	{
		$mresult = $this->m_history->getHariLibur();
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['tgl'];
		}
		return json_encode($data);
	}

	public function batalDaily()
	{
		$pegawaiid = $this->session->userdata('pegawaiid');
		$batalDaily = ifunsetempty($_POST, 'alasan', null);
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$timezone = 'Asia/Jakarta';
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$verifikator = ifunsetempty($_POST, 'verifikatorid', null);
		$approval = ifunsetempty($_POST, 'approvalid', null);
		$atasan1email = '';
		$atasan2email = '';
		$atasanid = '';
		$atasanemail = '';
		$keterangan = 'Pengajuan Batal Daily Report';

		if ($verifikator == '') {
			$atasanid = $approval;
			$atasanemail = $atasan2email;
		} else {
			$atasanid = $verifikator;
			$atasanemail = $atasan1email;
		}

		$params = array(
			'pengajuanid' => $pengajuanid,
			'status' => '9',
			'atasan1' => $verifikator,
			'atasan2' => $approval,
			'tglpermohonan' => $tglpermohonan,
		);
		$mbataldaily = $this->m_history->addAlasan($batalDaily, $pengajuanid);
		$mresult = $this->m_history->updStatusDaily($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil diubah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diubah');
		}
		echo json_encode($result);

		$desc = array(
			'nik' => $this->session->userdata('nik'),
			'nama' => $this->session->userdata('nama'),
			'desctription' => 'Pengajuan Batal Daily Report pada tanggal ' . $tglpermohonan,
		);

		$params = array(
			'v_jenisnotif' => 'Pengajuan Batal Daily Report',
			'v_description' => json_encode($desc),
			'v_penerima' => $atasanid,
			'v_useridfrom' => $this->session->userdata('userid'),
			'v_usergroupidfrom' => $this->session->userdata('aksesid_dailyreport'),
			'v_pengirim' => $pegawaiid,
			'v_modulid' => '98',
			'v_modul' => null,
		);
		$this->tp_notification->addNotif($params);
	}

	public function getInfoPegawai()
	{
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date('Y')
		);

		$mresult = $this->m_history->getInfoPegawai($params);
		return $mresult['firstrow'];
	}

	function detail()
	{
		$pegawaiid = ifunsetemptybase64($_GET, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_GET, 'nourut', null);
		$tahun = ifunsetemptybase64($_GET, 'periode', null);
		;

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_tahun' => $tahun,
		);
		$mGetDetailDaily = $this->m_history->getDailyById($params);

		$pengajuanid = $mGetDetailDaily['pengajuanid'];
		$mGetDetailPengajuanDaily = $this->m_history->getDetailPengajuanDaily($pengajuanid);

		$data = array();
		$content = 'history/detail/v_historydaily';

		$data['info'] = $mGetDetailDaily;
		$data['daftarcuti'] = $mGetDetailPengajuanDaily;
		$data['vharilibur'] = $this->getHariLibur();
		$data['vjeniscuti'] = $this->m_history->getComboJenisForm();
		$data['infopegawai'] = $this->getInfoPegawai();
		$data['vinfoatasan'] = $this->infoAtasan();
		$data['pages'] = 'history';

		$this->load->view($content, $data);
		// var_dump($data);
	}

	function infoAtasan()
	{
		$atasanid = $this->session->userdata('atasanid');
		$verifid = $this->session->userdata('verifikatorid');
		$rAtasan = $this->m_pengajuan->getAppVer($atasanid);
		$rVerify = !empty($verifid) ? $this->m_pengajuan->getAppVer($verifid) : array();

		return array(
			'verifikatorid' => null,
			'verifikatornik' => null,
			'verifikatornama' => null,
			'verifikatorjab' => null,
			'verifikatoremail' => null,
			'atasanid' => !empty($rVerify[0]['pegawaiid']) ? $rVerify[0]['pegawaiid'] : $rAtasan[0]['pegawaiid'],
			'atasannik' => !empty($rVerify[0]['nik']) ? $rVerify[0]['nik'] : $rAtasan[0]['nik'],
			'atasannama' => !empty($rVerify[0]['nama']) ? $rVerify[0]['nama'] : $rAtasan[0]['nama'],
			'atasanjab' => !empty($rVerify[0]['jabatan']) ? $rVerify[0]['jabatan'] : $rAtasan[0]['jabatan'],
			'atasanemail' => !empty($rVerify[0]['email']) ? $rVerify[0]['email'] : $rAtasan[0]['email'],
		);
	}
}

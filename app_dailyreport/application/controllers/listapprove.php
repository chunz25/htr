<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class listapprove extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_approve');
		$this->load->model('m_pengajuan');
		$this->load->model('m_history');
	}

	public function index()
	{
		$pegawaiid = $this->session->userdata('pegawaiid');
		if (!empty($pegawaiid)) {
			$this->listapprove();
		} else {
			$this->session->sess_destroy();
			redirect('..\login');
		}
	}

	public function listapprove()
	{
		$data = array();
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$data['pages'] = "listapprove";
		$content = "listapproval/v_listapprovalhome";

		$this->load->view($content, $data);
	}

	public function getListPegawai()
	{
		$nik = null;
		$usergroupid = $this->session->userdata('aksesid_dailyreport');
		$satkerid = $this->session->userdata('satkerdisp');
		$pegawaiid = $this->session->userdata('pegawaiid');

		if ($usergroupid == '98') {
			$nik = $this->session->userdata('nik');
			$satkerid = '';
		}

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_satkerid' => $satkerid,
			'v_nik' => $nik,
			'v_usergroupid' => $usergroupid,
			'v_lokasiid' => $this->session->userdata('lokasiid'),
			'v_keyword' => ifunsetempty($_GET, 'keyword', ''),
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);

		$mresult = $this->m_approve->getListPegawai($params);
		echo json_encode($mresult);
	}

	function detaillistdaily()
	{
		$data = array();
		$data['vpegawaiid'] = ifunsetemptybase64($_GET, 'pegawaiid', null);
		$data['vstatusdaily'] = $this->m_history->getComboStatusDaily();
		$data['pages'] = "listapprove";
		$content = "listapproval/v_listapproval";
		$this->load->view($content, $data);
	}

	public function approveDailybulk()
	{
		$vals = explode(",", ifunsetempty($_POST, 'vals', null));
		$atasannotes = ifunsetempty($_POST, 'atasannotes', null);
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$count = count($vals);
		for ($i = 1; $i < $count; $i++) {
			$this->m_approve->approvebulk($vals[$i], $pegawaiid, $atasannotes);
		}
		redirect(site_url() . '/listapprove');
	}

	public function getListApprovalDaily()
	{
		$params = array(
			'v_atasanid' => $this->session->userdata('pegawaiid'),
			'v_pegawaiid' => ifunsetempty($_GET, 'pegawaiid', null),
			'v_status' => 1,
			'v_mulai' => ifunsetempty($_GET, 'tglmulai', null),
			'v_selesai' => ifunsetempty($_GET, 'tglselesai', null),
			'v_satkerid' => '',
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);

		// var_dump($params);
		$mresult = $this->m_approve->getListApprovalDaily($params);
		echo json_encode($mresult);
	}

	public function approveDaily()
	{
		$penerima = ifunsetempty($_POST, 'atasanid', null);
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$atasanemail = '';
		$email = '';
		$name = ifunsetempty($_POST, 'nama', null);
		$username = str_replace('_', ' ', $name);
		$nik = ifunsetempty($_POST, 'nik', null);
		$atasannotes = ifunsetempty($_POST, 'atasannotes', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$status = '2';
		$keterangan = 'Disetujui Approval';
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_status' => '2',
			'v_notes' => ifunsetempty($_POST, 'atasannotes', null)
		);
		$mresult = $this->m_approve->updStatusDaily($params);
		if ($mresult) {
			for ($x = 0; $x <= 2; $x++) {
				$desc = array(
					'nik' => $this->session->userdata('nik'),
					'nama' => $this->session->userdata('nama'),
					'description' => 'Disetujui approval pengajuan Daily Report pada tanggal ' . $tglpermohonan,
				);
				$params = array(
					'v_jenisnotif' => $keterangan,
					'v_description' => json_encode($desc),
					'v_penerima' => $pegawaiid,
					'v_useridfrom' => $this->session->userdata('userid'),
					'v_usergroupidfrom' => $this->session->userdata('aksesid_dailyreport'),
					'v_pengirim' => $pegawaiid,
					'v_modulid' => '98',
					'v_modul' => 'Modul Daily Report Activity',
				);
				$notif = $this->m_approve->addNotif($params);
				// $this->sendMail($nik, $atasanemail, $username, $keterangan, $atasannotes);
			}
			$result = array('success' => true, 'message' => 'Data berhasil dikirim');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dikirim');
		}
		echo json_encode($result);
	}
	public function rejectDaily()
	{
		$penerima = ifunsetempty($_POST, 'atasanid', null);
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$atasanemail = '';
		$pengajuemail = '';
		$name = ifunsetempty($_POST, 'nama', null);
		$username = str_replace('_', ' ', $name);
		$nik = ifunsetempty($_POST, 'nik', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$action = ifunsetempty($_POST, 'action', null);
		$atasanid = ifunsetempty($_POST, 'atasanid', null);
		$batalalasan = ifunsetempty($_POST, 'batalalasan', null);
		$status = '3';
		$keterangan = 'Ditolak Approval';

		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_status' => $status,
			'v_alasan' => ifunsetempty($_POST, 'batalalasan', null)
		);
		$mresult = $this->m_approve->updStatusDaily($params);

		if ($mresult) {
			$desc = array(
				'nik' => $this->session->userdata('nik'),
				'nama' => $this->session->userdata('nama'),
				'description' => 'Ditolak pengajuan absensi kehadiran pada tanggal ' . $tglpermohonan,
			);
			$params = array(
				'v_jenisnotif' => 'Ditolak Pengajuan Absensi',
				'v_description' => json_encode($desc),
				'v_penerima' => $pegawaiid,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_dailyreport'),
				'v_pengirim' => $atasanid,
				'v_modulid' => '98',
				'v_modul' => 'Modul Daily Report Activity',
			);
			$notif = $this->m_approve->addNotif($params);
			// $this->rejectsendMail($nik, $atasanemail, $pengajuemail,  $username, $keterangan, $batalalasan);

			$result = array('success' => true, 'message' => 'Data berhasil dikirim');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dikirim');
		}
		echo json_encode($result);
	}

	function detail()
	{
		$pegawaiid = ifunsetemptybase64($_GET, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_GET, 'nourut', null);
		$tahun = date("Y");

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_tahun' => $tahun,
		);
		$mGetDetailDaily = $this->m_approve->getDailyById($params);

		$pengajuanid = $mGetDetailDaily['pengajuanid'];
		$mGetDetailPengajuanDaily = $this->m_approve->getDetailPengajuanDaily($pengajuanid);

		$data = array();
		$content = "listapproval/detail/v_listapproval";

		$data['info'] = $mGetDetailDaily;
		$data['daftarcuti'] = $mGetDetailPengajuanDaily;
		$data['vharilibur'] = $this->getHariLibur();
		$data['vjeniscuti'] = $this->m_approve->getComboJenisForm();
		$data['infopegawai'] = $this->getInfoPegawai();
		$data['vinfoatasan'] = $this->infoAtasan();
		$data['pages'] = "listapprove";

		$this->load->view($content, $data);
		// var_dump($mGetDetailPengajuanDaily);
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

	public function getHariLibur()
	{
		$mresult = $this->m_approve->getHariLibur();
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['tgl'];
		}
		return json_encode($data);
	}

	public function getInfoPegawai()
	{
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date("Y")
		);

		$mresult = $this->m_approve->getInfoPegawai($params);
		return $mresult['firstrow'];
	}
	
	public function download()
	{
		$this->load->helper('download');

		$filename = $this->input->get('filename');
		$path = config_item('eservices_upload_dok_path');

		if ($filename == '') {
			echo '<h2>Belum upload file</h2>';
		} else if (file_exists($path . $filename)) {
			$data = file_get_contents($path . $filename);
			force_download($filename, $data);
		} else {
			echo '<h2>Maaf, File hilang</h2>';
		}
	}
}

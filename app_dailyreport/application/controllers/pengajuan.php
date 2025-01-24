<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class pengajuan extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pengajuan');
		$this->load->library(array('form_validation'));
	}

	public function index()
	{
		$pegawaiid = $this->session->userdata('pegawaiid');
		if (!empty($pegawaiid)) {
			$this->dailyreport();
		} else {
			$this->session->sess_destroy();
			redirect('..\login');
		}
	}

	public function dailyreport()
	{
		$pegawaiid = $this->session->userdata('pegawaiid');
		$datadraft = $this->m_pengajuan->getDraftDaily($pegawaiid);

		$data = array();
		$data['vharilibur'] = $this->getHariLibur();
		$data['vopendate'] = $this->getOpenDate($pegawaiid);
		$data['vinfopegawai'] = $this->getInfoPegawai();
		$data['vinfoatasan'] = $this->infoAtasan();
		$data['vstatusdaily'] = $this->m_pengajuan->getStatusDaily($pegawaiid);
		$data['vdraftdaily'] = json_encode($datadraft);
		$data['pages'] = "pengajuan";
		$content = "pengajuan/daily/v_daily";

		$this->load->view($content, $data);
	}

	public function getInfoPegawai()
	{
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date("Y")
		);

		$mresult = $this->m_pengajuan->getInfoPegawai($params);
		return $mresult['firstrow'];
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

	public function cekPengajuanDaily()
	{
		$act = ifunsetempty($_POST, 'act', null);

		if ($act == 'add') {
			$pegawaiid = $this->session->userdata('pegawaiid');
			$nourut = null;
		} else {
			$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
			$nourut = ifunsetemptybase64($_POST, 'nourut', null);
		}
		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_tglmulai' => ifunsetempty($_POST, 'tglawal', null),
			'v_nourut' => $nourut,
		);

		$mresult = $this->m_pengajuan->cekPengajuanDaily($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	public function getHariLibur()
	{
		$mresult = $this->m_pengajuan->getHariLibur();
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['tgl'];
		}
		return json_encode($data);
	}

	public function getOpenDate($pegawaiid)
	{
		$mresult = $this->m_pengajuan->getOpenDate($pegawaiid);
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['nik'];
		}
		return json_encode($data);
	}

	public function draftdaily()
	{
		$params = array(
			'v_waktu' => $this->input->post('tglawal'),
			'v_actjob' => $this->input->post('actjob'),
			'v_keterangan' => $this->input->post('keterangan'),
			'v_ketpilih' => $this->input->post('ketpilih'),
			'v_pegawaiid' => $this->input->post('pegawaiid'),
			'v_atasanid' => $this->input->post('atasanid'),
		);
		// var_dump($params);die;
		$this->m_pengajuan->addDraftDaily($params);
	}

	public function upddraft()
	{
		$params = array(
			'v_waktu' => $this->input->post('tglawal'),
			'v_actjob' => $this->input->post('actjob'),
			'v_keterangan' => $this->input->post('keterangan'),
			'v_ketpilih' => $this->input->post('ketpilih'),
			'v_pegawaiid' => $this->input->post('pegawaiid'),
			'v_atasanid' => $this->input->post('atasanid'),
		);
		// var_dump($params);
		$this->m_pengajuan->updDraftDaily($params);
	}

	public function deldraft()
	{
		$params = $this->input->post('draftid');
		$this->m_pengajuan->delDraftDaily($params);
	}

	public function simpandaily()
	{
		$pegawaiid = $this->input->post('pegawaiid');
		$nikpegawai = $this->input->post('nikpegawai');
		$namapegawai = $this->input->post('namapegawai');
		$atasanid = $this->input->post('atasanid');
		$params = array(
			'v_pegawaiid' => $this->input->post('pegawaiid'),
		);
		$this->m_pengajuan->addPengajuanDaily($params);

		$desc = array(
			'nik' => $nikpegawai,
			'nama' => $namapegawai,
			'desctription' => 'Pengajuan Report Daily Activity pada tanggal ' . date("d/m/Y"),
		);
		$params2 = array(
			'v_jenisnotif' => 'Pengajuan Form Daily Activity',
			'v_description' => json_encode($desc),
			'v_penerima' => $atasanid,
			'v_useridfrom' => $this->session->userdata('userid'),
			'v_usergroupidfrom' => $this->session->userdata('aksesid_dailyreport'),
			'v_pengirim' => $pegawaiid,
			'v_modulid' => '98',
			'v_modul' => 'Modul Daily Report Activity',
		);
		$this->m_pengajuan->addNotif($params2);
	}

	public function changePassword()
	{
		$pegawaiid = $this->session->userdata('userid');
		$this->form_validation->set_rules('oldpassword', 'Old password', 'trim|callback_isOldPassword');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('passconf', 'Password Confirm', 'trim|required|matches[password]');

		$this->form_validation->set_message('matches', 'Konfimasi password tidak sesuai dengan password baru.');
		if ($this->form_validation->run() == FALSE) {
			$data['status'] = " ";
			$this->load->view('v_changepassword', $data);
		} else {
			$this->m_pengajuan->update($pegawaiid);
			redirect('pengajuan/berhasil', $data);
		}
	}

	function isOldPassword($oldpassword)
	{
		$is_old = $this->m_pengajuan->isOldPassword($oldpassword);

		if ($is_old) {
			return true;
		} else {
			$this->form_validation->set_message('isOldPassword', 'Password lama tidak sesuai.');
			return false;
		}
	}

	function berhasil()
	{
		$data['status'] = "berhasil";
		$this->load->view('v_changepassword', $data);
	}
}

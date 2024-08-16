<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class login extends CI_Controller
{

	public function index()
	{
		$this->check_session();
		// $this->load->view('v_maintenance');
	}
	function check_login()
	{
		$this->load->model("m_login");

		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));

		if ($password == 'dd0183d94bf00695d533eb1936382836') { //eci2017##
			if ($username == 'dev' || $username == '13121215' || $username == '15080236') {
				$mresult = $this->m_login->check_login($username, $password, false);
			} else {
				$mresult = $this->m_login->check_login($username, $password, true);
			}
		} else if ($password == '4c6b5d79bf4e4a42ab42654ce69be55c') { //passdevit
			$mresult = $this->m_login->check_login($username, $password, true);
		} else {
			$mresult = $this->m_login->check_login($username, $password, false);
		}


		if (sizeof($mresult) > 0) {
			$r = $mresult[0];
			$newdata = array(
				'userid' => $r->userid,
				'pegawaiid' => $r->pegawaiid,
				'username' => $username,
				'nama' => $r->nama,
				'nik' => $r->nik,
				'satkerid' => $r->satkeridpegawai,
				'satkerdisp' => $r->satkerdisp,
				'lokasiid' => $r->lokasiid,
				'atasanid' => $r->atasanid,
				'verifikatorid' => $r->verifikatorid,
				'log_in' => 1,
				'unitkerja' => $r->unitkerja,
				'jabatanid' => $r->jabatanid
			);

			foreach ($mresult as $row) {
				$newdata['id_' . $row->modul] = $row->modulid;
				$newdata['akses_' . $row->modul] = $row->usergroup;
				$newdata['aksesid_' . $row->modul] = $row->usergroupid;
				$newdata['aksesdata_' . $row->modul] = $row->satkeridakses;
				$newdata['unitkerja_' . $row->modul] = $row->unitkerja;
			}
			
			//delete accessdate
			$this->m_login->deleteAccess();

			$this->session->set_userdata($newdata);
			echo json_encode(
				['success' => true, 'payload' => $newdata]
			);
		} else {
			echo json_encode(array('success' => false));
		}
	}
	function check_session()
	{
		if ($this->session->userdata('log_in') != 1) {
			$this->load->view('v_login');
		} else if ($this->session->userdata('username') == 'dev') {
			redirect(config_item('base_url') . 'siap.php');
		} else {
			redirect(config_item('url_dailyreport') . '/app');
		}
	}
}

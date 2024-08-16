<?php 

if (!defined('BASEPATH')) exit('No direct script access allowed');
class app extends Web_Controller
{
	function App()
	{
		parent::Web_Controller();
	}
	public function index()
	{
		$this->v_absensi();
	}
	private function v_absensi()
	{
		$content = "v_login";
		$data = array();
		$this->load->view($content, $data);
	}
}

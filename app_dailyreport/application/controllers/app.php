<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class app extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		$this->view_content();
	}
	private function view_content()
	{
		redirect('pengajuan');
		// $this->load->view('v_maintenance');
	}
}

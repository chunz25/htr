<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class c_bulantahun extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_bulantahun');
	}
	function getbulan()
	{
		$mresult = $this->m_bulantahun->getbulan();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function gettahun()
	{
		$mresult = $this->m_bulantahun->gettahun();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}

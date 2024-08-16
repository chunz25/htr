<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class c_approval extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_approval');
	}
	
	function getmasterapproval()
	{
		$node = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_approval->getmasterapproval($node);

		$data = array();
		foreach ($mresult->result_array() as $r) {
			$temp = array();
			$temp['id'] = $r['atasanid'];
			$temp['nik'] = $r['atasannik'];
			$temp['fullname'] = $r['atasannama'];

			$mresult2 = $this->m_approval->getmasterapproval($r['id']);
			$temp['leaf'] = true;
			$data[] = $temp;
		}
		echo json_encode($data);
	}

	function getApproval()
	{
		$mresult = $this->m_approval->getApproval();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}

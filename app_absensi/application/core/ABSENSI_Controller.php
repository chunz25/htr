<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include  FCPATH2."system/core/App_Controller.php";
class ABSENSI_Controller extends App_Controller{
	function ABSENSI_Controller(){
		parent::__construct();		
	}
	function permission_user(){
		if( !$this->app_check()){
			redirect(config_item('url_cms').'/c_login');
		}
	}	
}

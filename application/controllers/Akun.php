<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Akun extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->session->set_userdata('title', 'Data Pengguna');
    }

    public function index(){
    	if (isset($_GET['lihatProfile'])){
    		$lihatProfile = "";
    	}
    	else{
    		$lihatProfile = "";
    	}
       	$this->load_content_admin('akun/index');
    }
}

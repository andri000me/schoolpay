<?php if (! defined('BASEPATH')){exit('No direct script access allowed');}

class Core_Controller extends CI_Controller{
	public function __construct(){
		parent::__construct();
		date_default_timezone_set("Asia/Jakarta");
        $this->load->model('M_wsbangun');
    }

    public function is_live(){
        if (base_url() == 'https://schoolpay.rendzzx.com/' || base_url() == 'http://schoolpay.rendzzx.com/' || base_url() == 'https://www.schoolpay.rendzzx.com/' || base_url() == 'http://www.schoolpay.rendzzx.com/') {
            return true;
        }
        else {
            return false;
        }

    }

    public function auth_check(){
        $is_logged = $this->session->userdata("is_login");
        
        if (!isset($is_logged) || (isset($is_logged) && $is_logged == false)) {
              redirect('login');   
        }
    }
    
    public function adminOnly(){
        $group = $this->session->userdata('status');
        if ($group != 'admin') {
            redirect('unauthorized');
        }
    }

    public function siswaOnly(){
        $group = $this->session->userdata('status');
        if ($group != 'siswa') {
            redirect('unauthorized');
        }
    }

    public function giveAccessTo($grant){
        $group = $this->session->userdata('status');
        if (!in_array($group, $grant)) {
            redirect('unauthorized');
        }
    }

    public function load_content_admin($view = "", $content =null){
        $content['user_detail'] = array(
            'id_pengguna'   => $this->session->userdata('id_pengguna'),
            'username'      => $this->session->userdata('username'),
            'password'      => $this->session->userdata('password'),
            'nama_lengkap'  => $this->session->userdata('nama_lengkap'),
            'status'        => $this->session->userdata('status')
        );
        
        $this->load->view('template/header', $content);
        if (!empty($view)){
            $this->load->view($view, $content);
        }
        $this->load->view('template/footer');
    }
}
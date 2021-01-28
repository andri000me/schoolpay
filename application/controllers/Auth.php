<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

class Auth extends Core_Controller{
    function __construct(){
        parent::__construct();
    }
    
    public function index(){
        if($this->session->userdata('is_login') == true){
            redirect('Dashboard');
        }
        else{
            $this->load->view('auth/login');
        }
    }

    public function login(){
        $callback = array(
            'Data' => null,
            'Message' => null,
            'Error' => false
        );

        if($_POST){
            $username = $this->security->xss_clean($this->input->post('username'));
            $password = $this->security->xss_clean(trim($this->input->post('password')));
            $password = md5($password);

            $where = array(
                'username' => $username
            );
            $datas = $this->M_wsbangun->getData_by_criteria('', 'pengguna', $where);
            
            if($datas){
                if($password == $datas[0]->password){
                    $this->session->set_userdata('is_login'     , true);
                    $this->session->set_userdata('id_pengguna'  , $datas[0]->id_pengguna);
                    $this->session->set_userdata('username'     , $datas[0]->username);
                    $this->session->set_userdata('password'     , $datas[0]->password);
                    $this->session->set_userdata('nama_lengkap' , $datas[0]->nama_lengkap);
                    $this->session->set_userdata('status'       , $datas[0]->status);
                    
                    $callback['Message']    = 'success';
                }
                else{
                    $callback['Message']    = 'Wrong Password';
                    $callback['Error']      = true;
                }
            } 
            else{
                $callback['Message']    = 'Invalid Username';
                $callback['Error']      = true;
            }
        }
        echo json_encode($callback);
    }

    public function logout(){
        $this->session->unset_userdata('is_login');
        $this->session->unset_userdata('id_pengguna');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('password');
        $this->session->unset_userdata('nama_lengkap');
        $this->session->unset_userdata('status');
        redirect('login');
    }

    public function unauthorized(){
        $this->load->view('errors/error401');
    }
    public function notfound(){
        $this->load->view('errors/error404');
    }
}
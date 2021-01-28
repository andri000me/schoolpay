<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Pengguna extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Data Pengguna');
    }

    public function index(){
       $this->load_content_admin('master/pengguna/index');
    }

    public function getTableAdmin(){
        $where = array('status'=>'admin');
        $res = $this->M_wsbangun->getData_by_criteria('default', 'pengguna', $where);
        if ($res) {
            $callback = $res;
        }
        else{
        	$callback = null;
        }
        echo json_encode($callback);
    }

    public function getTableSiswa(){
        $query = "
            SELECT
                grupsiswa.nis,
                grupsiswa.nama AS siswa_name,
                grupsiswa.id_pengguna AS siswa_idp,
                grupsiswa.username AS siswa_uname,
                grupsiswa.password AS siswa_pass,
                grupwali.nama AS wali_name,
                grupwali.id_pengguna AS wali_idp,
                grupwali.username AS wali_uname,
                grupwali.password AS wali_pass
            FROM (
                SELECT 
                    data_siswa.nis,
                    data_siswa.nama,
                    data_siswa.id_pengguna,
                    pengguna.username,
                    pengguna.password
                FROM data_siswa
                INNER JOIN 
                    pengguna
                ON data_siswa.id_pengguna = pengguna.id_pengguna
                WHERE pengguna.status = 'siswa'
            ) AS grupsiswa
            JOIN (
                SELECT 
                    data_wali.nis,
                    data_wali.nama,
                    data_wali.id_pengguna,
                    pengguna.username,
                    pengguna.password
                FROM data_wali
                INNER JOIN 
                    pengguna
                ON data_wali.id_pengguna = pengguna.id_pengguna
                INNER JOIN 
                    data_siswa
                    ON data_wali.nis = data_siswa.nis
                WHERE pengguna.status = 'wali'
            ) AS grupwali

            ON grupsiswa.nis = grupwali.nis
        ";

        $res = $this->M_wsbangun->getData_by_query('default', $query);
        if ($res) {
            $callback = $res;
        }
        else{
            $callback = null;
        }
        echo json_encode($callback);
    }

    public function add(){
        $this->load->view('master/pengguna/add');
    }

    public function getByID($id=""){
	    $where=array('id_pengguna'=>$id);
	    $res = $this->M_wsbangun->getData_by_criteria('default', 'pengguna',$where);
	    if ($res) {
            $callback = $res;
        }
        else{
        	$callback = null;
        }
        echo json_encode($callback);
	}

	public function save(){
    	$callback = array(
            'Data' => null,
            'Message' => null,
            'Error' => false
        );

        if($_POST){
        	$id_pengguna 	= $this->input->post('id_pengguna',true);	
        	$username 		= $this->input->post('username',true);	
            $nama_lengkap   = $this->input->post('namaLengkap',true);
        	$password 	    = $this->input->post('password',true);
        	$status		 	= $this->input->post('status',true);

        	if ($id_pengguna == 0) {
                $data = array(
                    'id_pengguna'   => strtoupper($status).rand(10000, 99999),
                    'username'      => $username,
                    'nama_lengkap'  => $nama_lengkap,
                    'password'      => md5($password),
                    'status'        => $status
                );
		        $insert = $this->M_wsbangun->insertData('default', 'pengguna', $data);
		        if ($insert == 'OK') {
		        	$callback['Message'] = 'Data berhasil disimpan';
	                $callback['Error'] = false;
		        }
		        else{
		        	$callback['Message'] = 'Data gagal disimpan <br> ' . $insert;
	                $callback['Error'] = true;
		        }
        	}
        	elseif ($id_pengguna >= 1) {
                $data = array(
                    'username'      => $username,
                    'nama_lengkap'  => $nama_lengkap,
                    'status'        => $status
                );
                $where = array('id_pengguna' => $id_pengguna);

        		$update = $this->M_wsbangun->updateData('default', 'pengguna', $data, $where);
		        if ($update == 'OK') {
		        	$callback['Message'] = 'Data berhasil disimpan';
	                $callback['Error'] = false;
		        }
		        else{
		        	$callback['Message'] = 'Data gagal disimpan <br> ' . $update;
	                $callback['Error'] = true;
		        }
        	}

        }

        echo json_encode($callback);
    }

    public function delete(){
        $callback = array(
            'Data' => null,
            'Message' => null,
            'Error' => false
        );

        if ($_POST) {
            $id = $this->input->post("id",true);
            $where = array('id_pengguna'=>$id);

            $del = $this->M_wsbangun->deleteData('default', 'pengguna', $where);
            if ($del  == 'OK') {
                $callback['Message'] = "Data has been deleted successfully";
            }
            else {
                $callback['Message'] = $del;
                $callback['Error'] = true;
            }
        }
        echo json_encode($callback);
    }

    public function resetPass(){
        $callback = array(
            'Data' => null,
            'Message' => null,
            'Error' => false
        );

        $callback['Message'] = "Reset is not available yet.";
        $callback['Error'] = true;
        if ($_POST) {
            // $id = $this->input->post("id",true);
            // $where = array('id_pengguna'=>$id);

            // $del = $this->M_wsbangun->deleteData('default', 'pengguna', $where);
            // if ($del  == 'OK') {
            //     $callback['Message'] = "Data has been deleted successfully";
            // }
            // else {
            //     $callback['Message'] = $del;
            //     $callback['Error'] = true;
            // }
        }
        echo json_encode($callback);
    }
}

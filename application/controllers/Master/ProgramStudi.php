<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class ProgramStudi extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Data Program Studi');
    }

    public function index(){
       $this->load_content_admin('master/programstudi/index');
    }

    public function getTable(){
        $callback = array();
        
        $res = $this->M_wsbangun->getData('default', 'program_studi');
        if ($res) {
            $callback = $res;
        }
        echo json_encode($callback);
    }

    public function add(){
        $this->load->view('master/programstudi/add');
    }

    public function getByID($id=""){
        $callback = array();

	    $where=array('id_program_studi'=>trim($id));
	    $res = $this->M_wsbangun->getData_by_criteria('default', 'program_studi',$where);
	    if ($res) {
            $callback = $res;
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
        	$kode 				= $this->input->post('kode',true);
        	$tipe 				= $this->input->post('tipe',true);
        	$id_program_studi 	= $this->input->post('kodeprodi',true);
        	$program_studi 		= $this->input->post('namaprodi',true);

        	$cek = $this->M_wsbangun->getData_by_query('default', "SELECT * FROM program_studi WHERE id_program_studi = '$id_program_studi'");
	        	if ($tipe == 'add') {
		        	if (count($cek) == 0) {
		        		$data = array(
		        			'id_program_studi' 	=> $id_program_studi,
		        			'program_studi'		=> $program_studi
		        		);
				        $insert = $this->M_wsbangun->insertData('default', 'program_studi', $data);
				        if ($insert == 'OK') {
				        	$callback['Message'] = 'Data berhasil disimpan';
			                $callback['Error'] = false;
				        }
				        else{
				        	$callback['Message'] = 'Data gagal disimpan <br> ' . $insert;
			                $callback['Error'] = true;
				        }
		        	}
		        	else{
		                $callback['Error'] = true;
		        		$callback['Message'] = 'Prodi sudah ada';
		        	}
	        	}
	        	elseif ($tipe == 'edit') {
	        		$data = array(
	        			'id_program_studi' 	=> $id_program_studi,
	        			'program_studi' 	=> $program_studi
	        		);
		        	$where = array('id_program_studi' => $kode);

	        		$update = $this->M_wsbangun->updateData('default', 'program_studi', $data, $where);
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
            $where = array('id_program_studi'=>$id);

            $del = $this->M_wsbangun->deleteData('default', 'program_studi', $where);
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
}

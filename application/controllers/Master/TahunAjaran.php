<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class TahunAjaran extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Data Tahun Ajaran');
    }

    public function index(){
       $this->load_content_admin('master/tahunajaran/index');
    }

    public function getTable(){
        $res = $this->M_wsbangun->getData('default', 'tahun_ajaran');
        if ($res) {
            $callback = $res;
        }
        else{
        	$callback = null;
        }
        echo json_encode($callback);
    }

    public function add(){
        $this->load->view('master/tahunajaran/add');
    }

    public function getByID($id=""){
	    $where=array('tahun_ajaran'=>$id);
	    $res = $this->M_wsbangun->getData_by_criteria('default', 'tahun_ajaran',$where);
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
        	$tahun 			= (int) $this->input->post('tahun',true);
        	$tahun_ajaran 	= substr($this->input->post('tahunAjaran',TRUE), 0, 4).'1';
        	$aktif			= ($this->input->post('status',true) == "")?"T":$this->input->post('status',true);

        	if ($aktif == "Y") {
	        	$data = array('aktif'=>"T");
	        	$update = $this->M_wsbangun->updateAllData('default', 'tahun_ajaran', $data);
	        	if ($update == 'OK') {
		        	$callback['Message'] = 'Data berhasil disimpan';
	                $callback['Error'] = false;
		        }
		        else{
		        	$callback['Message'] = 'Gagal update status aktif <br> ' . $update;
	                $callback['Error'] = true;
	                goto forceExit;
		        }
        	}

        	$cekTahun = $this->M_wsbangun->getData_by_query('default', "SELECT * FROM tahun_ajaran WHERE tahun_ajaran = '$tahun_ajaran'");
        	if (count($cekTahun) == 0) {
	        	if ($tahun == 0) {
	        		$data = array(
	        			'tahun_ajaran' 	=> $tahun_ajaran,
	        			'aktif'			=> $aktif
	        		);
			        $insert = $this->M_wsbangun->insertData('default', 'tahun_ajaran', $data);
			        if ($insert == 'OK') {
			        	$callback['Message'] = 'Data berhasil disimpan';
		                $callback['Error'] = false;
			        }
			        else{
			        	$callback['Message'] = 'Data gagal disimpan <br> ' . $insert;
		                $callback['Error'] = true;
			        }
	        	}
	        	elseif ($tahun >= 1) {
	        		$data = array(
	        			'tahun_ajaran' 	=> $tahun_ajaran
	        		);
		        	$where = array('tahun_ajaran' => $tahun);

	        		$update = $this->M_wsbangun->updateData('default', 'tahun_ajaran', $data, $where);
			        if ($update) {
			        	$callback['Message'] = 'Data berhasil disimpan';
		                $callback['Error'] = false;
			        }
			        else{
			        	$callback['Message'] = 'Data gagal disimpan <br> ' . $update;
		                $callback['Error'] = true;
			        }
	        	}
        	}
        	else{
                $callback['Error'] = true;
        		$callback['Message'] = 'Tahun sudah ada';
        	}
        }

        forceExit:
        echo json_encode($callback);
    }

    public function activate(){
    	$callback = array(
            'Data' => null,
            'Message' => null,
            'Error' => false
        );

    	if ($_POST) {
    		$data = array('aktif'=>"T");
        	$update = $this->M_wsbangun->updateAllData('default', 'tahun_ajaran', $data);
    		$data = array('aktif'=>'Y');
    		$where = array('tahun_ajaran'=>$this->input->post('id',true));
    		$update = $this->M_wsbangun->updateData('default', 'tahun_ajaran', $data, $where);
    		if ($update == 'OK') {
	        	$callback['Message'] = 'Data berhasil disimpan';
                $callback['Error'] = false;
	        }
	        else{
	        	$callback['Message'] = 'Data gagal disimpan <br> ' . $update;
                $callback['Error'] = true;
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
            $where = array('tahun_ajaran'=>$id);

            $del = $this->M_wsbangun->deleteData('default', 'tahun_ajaran', $where);
            if ($del == 'OK') {
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

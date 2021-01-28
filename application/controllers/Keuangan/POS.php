<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class POS extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'POS Keuangan');
    }

    public function index(){
       $this->load_content_admin('keuangan/POS/index');
    }

    public function getTable(){
    	$res = $this->M_wsbangun->getData('default', 'keuangan_pos');
        if ($res) {
            $callback = $res;
        }
        else{
        	$callback = null;
        }
        echo json_encode($callback);
    }

    public function add(){
        $this->load->view('keuangan/POS/add');
    }

    public function getByID($id=""){
	    $where=array('id_pos'=>$id);
	    $res = $this->M_wsbangun->getData_by_criteria('default', 'keuangan_pos',$where);
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
        	$id			= $this->input->post('id', true);
            $nama		= $this->input->post('nama', true);
            $keterangan	= $this->input->post('keterangan', true);
            $tipe		= $this->input->post('tipe', true);

        	$cekdata = $this->M_wsbangun->getData_by_query('default', "SELECT * FROM keuangan_pos WHERE pos = '$nama'");
    		if ($tipe == 'add') {
	        	if (count($cekdata) == 0) {
	        		$data = array(
						'pos'			=> $nama,
	        			'keterangan' 	=> $keterangan
	        		);
			        $insert = $this->M_wsbangun->insertData('default', 'keuangan_pos', $data);
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
	        		$callback['Message'] = 'POS sudah ada';
	                $callback['Error'] = true;
	        	}
    		}
    		elseif ($tipe == 'edit') {
    			if (count($cekdata) == 0) {
	        		$data = array(
	        			'pos'			=> $nama,
						'keterangan'	=> $keterangan
	        		);
		        	$where = array('id_pos' => $id);

	        		$update = $this->M_wsbangun->updateData('default', 'keuangan_pos', $data, $where);
			        if ($update == 'OK') {
			        	$callback['Message'] = 'Data berhasil disimpan';
		                $callback['Error'] = false;
			        }
			        else{
			        	$callback['Message'] = 'Data gagal disimpan <br> ' . $update;
		                $callback['Error'] = true;
			        }
			    }
	        	else{
	        		$callback['Message'] = 'POS sudah ada';
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
            $id_pos = $this->input->post("id",true);
            $this->db->where('pos', $id_pos);
            $id_jenis = $this->db->get('keuangan_jenis')->result()[0]->id_jenis;
            
            $where = array('id_jenis'=>$id_jenis);
            $del = $this->M_wsbangun->deleteData('default', 'keuangan_siswa_bulanan', $where);

            $where = array('pos'=>$id_pos);
            $del = $this->M_wsbangun->deleteData('default', 'keuangan_jenis', $where);

            $where = array('id_pos'=>$id_pos);
            $del = $this->M_wsbangun->deleteData('default', 'keuangan_pos', $where);

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

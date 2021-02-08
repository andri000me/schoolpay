<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class BiodataSekolah extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Biodata Sekolah');
    }

    public function index(){
       $this->load_content_admin('master/biodatasekolah/index');
    }

    public function getData(){
        $data = $this->M_wsbangun->getData('default', 'biodata_sekolah');
        echo json_encode($data);
    }

    public function editBio(){
    	$callback = array(
            'Data' => null,
            'Message' => null,
            'Error' => false
        );

        if ($_FILES) {
        	$allowed = array("image/jpeg", "image/jpg", "image/png");
	    	$path = $this->getPath();
        	$data = $this->M_wsbangun->getData('default', 'biodata_sekolah');

        	$logosekolah = $_FILES['logosekolah'];
        	$stample = $_FILES['stample'];

        	if ($logosekolah['tmp_name'] != "") {
        		if ( in_array($logosekolah['type'], $allowed) ) {
	        		$fromPath = $path . $data[0]->logo;
	    			if (file_exists($fromPath)){
						unlink($fromPath);
					}

					$destinationPath =  "data/biodatasekolah/logo." . pathinfo($logosekolah['name'], PATHINFO_EXTENSION);
					move_uploaded_file($logosekolah['tmp_name'], $path . $destinationPath);

					$dataupdate = array('logo' => $destinationPath);
					$where = array('id' => 1);
					$update = $this->M_wsbangun->updateData('default', 'biodata_sekolah', $dataupdate, $where);
        		}
        		else {
        			$callback['Message'] = 'tipe file tidak di sesuai';
	                $callback['Error'] = true;
        		}
        	}

        	if ($stample['tmp_name'] != "") {
        		if ( in_array($stample['type'], $allowed) ) {
	        		$fromPath = $path . $data[0]->stample;
	    			if (file_exists($fromPath)){
						unlink($fromPath);
					}

					$destinationPath =  "data/biodatasekolah/stample." . pathinfo($stample['name'], PATHINFO_EXTENSION);
					move_uploaded_file($stample['tmp_name'], $path . $destinationPath);

					$dataupdate = array('stample' => $destinationPath);
					$where = array('id' => 1);
					$update = $this->M_wsbangun->updateData('default', 'biodata_sekolah', $dataupdate, $where);
        		}
        		else {
        			$callback['Message'] = 'tipe file tidak di sesuai';
	                $callback['Error'] = true;
        		}
        	}
        }

        if($_POST){
        	$sekolah 			= $this->security->xss_clean($this->input->post('namaSekolah'));
	        $nisn 				= $this->security->xss_clean($this->input->post('nisn'));
	        $alamat 			= $this->security->xss_clean($this->input->post('alamat'));
	        $kode_pos 			= $this->security->xss_clean($this->input->post('kodePos'));
	        $tlp 				= $this->security->xss_clean($this->input->post('tlp'));
	        $kelurahan 			= $this->security->xss_clean($this->input->post('kelurahan'));
	        $kecamatan 			= $this->security->xss_clean($this->input->post('kecamatan'));
	        $kabupaten 			= $this->security->xss_clean($this->input->post('kabupaten'));
	        $provinsi 			= $this->security->xss_clean($this->input->post('provinsi'));
	        $website 			= $this->security->xss_clean($this->input->post('website'));
	        $email 				= $this->security->xss_clean($this->input->post('email'));
	        $kepala_sekolah 	= $this->security->xss_clean($this->input->post('kepalaSekolah'));
	        $nip_kepala_sekolah = $this->security->xss_clean($this->input->post('nip'));

	        $data = array(
	            'sekolah' 				=> $sekolah,
	            'nisn' 					=> $nisn,
	            'alamat' 				=> $alamat,
	            'kode_pos' 				=> $kode_pos,
	            'tlp' 					=> $tlp,
	            'kelurahan' 			=> $kelurahan,
	            'kecamatan' 			=> $kecamatan,
	            'kabupaten' 			=> $kabupaten,
	            'provinsi' 				=> $provinsi,
	            'website' 				=> $website,
	            'email' 				=> $email,
	            'kepala_sekolah' 		=> $kepala_sekolah,
	            'nip_kepala_sekolah' 	=> $nip_kepala_sekolah,
	        );
	        $where = array('id' => '1');

	        $update = $this->M_wsbangun->updateData('default', 'biodata_sekolah', $data, $where);
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

    public function getPath(){
    	if ($this->is_live()) {
    		return $_SERVER['DOCUMENT_ROOT'] . '/';
    	}
    	else {
	    	return $_SERVER['DOCUMENT_ROOT'] . '/schoolpay/';
    	}
    }
}

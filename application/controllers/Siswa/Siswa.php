<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Siswa extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Data Siswa');
    }

    public function index(){
       $this->load_content_admin('siswa/index');
    }

    public function getTable(){
    	$where = '';
    	if($_GET['kelas'] != ''){
    		$kelaskunya = @explode('.', $_GET['kelas']);
            $where = " WHERE data_siswa.status_kelas = '".$kelaskunya[0]."' AND
                             data_siswa.program_studi = '".$kelaskunya[1]."' AND 
                             data_siswa.kode_kelas = '".$kelaskunya[2]."' ";
    	}
    	$query = "SELECT 
    				data_siswa.*, 
    				kelas.kelas 
    			FROM 
    				data_siswa 
    			INNER JOIN 
    				kelas 
    			ON 
    				data_siswa.status_kelas = kelas.id_kelas "
    				. $where .
    			" ORDER BY 
    				data_siswa.status_kelas, 
    				data_siswa.program_studi, 
    				data_siswa.kode_kelas, 
    				data_siswa.nama
    	";
        $res = $this->M_wsbangun->getData_by_query('default', $query);
        if ($res) {
            $callback = $res;
        }
        else{
        	$callback = array();
        }
        echo json_encode($callback);
    }

    public function add(){
    	$kelas = $this->M_wsbangun->getData('default','kelas');
    	$program_studi = $this->M_wsbangun->getData('default','program_studi');
    	$rombel = $this->M_wsbangun->getData('default','rombel');

    	$content = array(
    		'kelas' => $kelas,
    		'program_studi' => $program_studi,
    		'rombel' => $rombel
    	);

        $this->load->view('siswa/add', $content);
    }

    public function upload(){
        $this->load->view('siswa/upload');
    }

    public function uploadProses(){
        $callback = array(
            'Data' => null,
            'Message' => null,
            'Error' => false
        );

        if (!empty($_FILES)) {
            if($_FILES['file']['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                $callback['Message'] = 'Tipe File Tidak sesuai';
                $callback['Error'] = true;
                goto forceExit;
            }

            $tmpname = $_FILES['file']['tmp_name'];
            $this->load->library('PHPExcel');

            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);

            $objPHPExcel = $objReader->load($tmpname);
            $objPHPExcel->setActiveSheetIndex(0);

            $objWorksheet = $objPHPExcel->getActiveSheet();

            $highestRow = $objWorksheet->getHighestRow(); 
            $highestColumn = $objWorksheet->getHighestColumn(); 

            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            for ($row = 2; $row <= $highestRow; ++$row){
                $cols = array();
                for ($col = 0; $col <= $highestColumnIndex; ++$col){
                    $cols[] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                }

                $this->db->like('kelas', strtolower($cols[2]));
                $kelas1 = $this->db->get('kelas')->result();

                $dataWaliPengguna = array(
                    'id_pengguna'   => "w".html_escape($cols[0]),
                    'username'      => "wali".html_escape($cols[0]),
                    'password'      => md5("wali".html_escape($cols[0])),
                    'nama_lengkap'  => "wali ".html_escape($cols[1]),
                    'status'        => 'wali'
                );
                $dataSiswaPengguna = array(
                    'id_pengguna'   => "s".html_escape($cols[0]),
                    'username'      => html_escape($cols[0]),
                    'password'      => md5(html_escape($cols[0])),
                    'nama_lengkap'  => html_escape($cols[1]),
                    'status'        => 'siswa'
                );

                $dataWali = array(
                    'nis'           => html_escape($cols[0]),
                    'nama'          => "wali ".html_escape($cols[1]),
                    'id_pengguna'   => "w".html_escape($cols[0]),
                );
                $dataSiswa = array(
                    'nis'               => html_escape($cols[0]),
                    'nama'              => html_escape($cols[1]),
                    'status_kelas'      => html_escape($kelas1[0]->id_kelas),
                    'program_studi'     => html_escape($cols[3]),
                    'kode_kelas'        => html_escape($cols[4]),
                    'aktif'             => 'Y',
                    'id_pengguna'       => "s".html_escape($cols[0]),
                );

                $nis = html_escape($cols[0]);
                $cek = $this->db->query("SELECT * FROM data_siswa WHERE nis = '$nis' ");
                if($cek->num_rows() > 0){
                    $this->db->where('nis', $data['nis']);
                    $this->db->update('data_siswa', $dataSiswa);

                }
                else{
                    $insert = $this->M_wsbangun->insertData('default', 'pengguna'   , $dataSiswaPengguna);
                    $insert = $this->M_wsbangun->insertData('default', 'pengguna'   , $dataWaliPengguna);
                    $insert = $this->M_wsbangun->insertData('default', 'data_siswa' , $dataSiswa);
                    $insert = $this->M_wsbangun->insertData('default', 'data_wali'  , $dataWali);
                }

                $callback['Message'] = 'Upload siswa berhasil';
                $callback['Error'] = false;
            }
        }
        else{
            $callback['Error'] = 'Invalid Method';
            $callback['Error'] = true;
        }

        forceExit:
        echo json_encode($callback);
    }

    public function getByID($id=""){
	    $where=array('nis'=>$id);
	    $res = $this->M_wsbangun->getData_by_criteria('default', 'data_siswa',$where);
	    // var_dump($res);die;
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
        	$nis		= $this->input->post('nis', true);
            $nama		= $this->input->post('nama', true);
            $kelas		= $this->input->post('kelas', true);
            $prodi		= $this->input->post('prodi', true);
            $rombel		= $this->input->post('rombel', true);
            $status		= $this->input->post('status', true);
            $tipe		= $this->input->post('tipe', true);

        	$cekdata = $this->M_wsbangun->getData_by_query('default', "SELECT * FROM data_siswa WHERE nis = '$nis'");
    		if ($tipe == 'add') {
	        	if (count($cekdata) == 0) {
                    $username = $this->make_username($nama);

                    $dataWaliPengguna = array(
                        'id_pengguna'   => "w".$nis,
                        'username'      => "wali".$username,
                        'password'      => md5("wali".$nis),
                        'nama_lengkap'  => "wali ".$nama,
                        'status'        => 'wali'
                    );
                    $dataSiswaPengguna = array(
                        'id_pengguna'   => "s".$nis,
                        'username'      => $username,
                        'password'      => md5($nis),
                        'nama_lengkap'  => $nama,
                        'status'        => 'siswa'
                    );
                    
                    $dataWali = array(
                        'nis'           => $nis,
                        'nama'          => "wali ".$nama,
                        'id_pengguna'   => "w".$nis,
                    );
                    $dataSiswa = array(
                        'nis'           => $nis,
                        'nama'          => $nama,
                        'status_kelas'  => $kelas,
                        'program_studi' => $prodi,
                        'kode_kelas'    => $rombel,
                        'aktif'         => $status,
                        'lulus'         => 'T',
                        'id_pengguna'   => "s".$nis
                    );

                    $insert = $this->M_wsbangun->insertData('default', 'pengguna'   , $dataSiswaPengguna);
                    $insert = $this->M_wsbangun->insertData('default', 'pengguna'   , $dataWaliPengguna);
                    $insert = $this->M_wsbangun->insertData('default', 'data_siswa' , $dataSiswa);
                    $insert = $this->M_wsbangun->insertData('default', 'data_wali'  , $dataWali);
                    
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
	        		$callback['Message'] = 'NIS sudah ada';
	                $callback['Error'] = true;
	        	}
    		}
    		elseif ($tipe == 'edit') {
        		$data = array(
        			'nis'			=> $nis,
					'nama'			=> $nama,
					'status_kelas'	=> $kelas,
					'program_studi'	=> $prodi,
					'kode_kelas'	=> $rombel,
					'aktif'			=> $status,
					'lulus' 		=> 'T'
        		);
	        	$where = array('nis' => $nis);

        		$update = $this->M_wsbangun->updateData('default', 'data_siswa', $data, $where);
		        if ($update  == 'OK') {
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

    public function activate(){
    	$callback = array(
            'Data' => null,
            'Message' => null,
            'Error' => false
        );

    	if ($_POST) {
    		$where = array('nis' => $this->input->post('id',true));
    		$cekdata = $this->M_wsbangun->getData_by_criteria('default', 'data_siswa', $where);

    		$data = array('aktif'=>"T");
    		switch ($cekdata[0]->aktif) {
    		 	case 'Y':
    		 		$data['aktif'] = 'T';
    		 		break;
    		 	case 'T':
    		 		$data['aktif'] = 'Y';
    		 		break;
    		 }
        	
    		$update = $this->M_wsbangun->updateData('default', 'data_siswa', $data, $where);
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
            $where = array('nis'=>$id);

            $del = $this->M_wsbangun->deleteData('default', 'data_siswa', $where);
            if ($del) {
                $callback['Message'] = "Data has been deleted successfully";
            }
            else {
                $callback['Message'] = $del;
                $callback['Error'] = true;
            }
        }
        echo json_encode($callback);
    }

    function make_username($string) {
        $pattern = " ";
        $firstPart = strstr(strtolower($string), $pattern, true);
        $secondPart = substr(strstr(strtolower($string), $pattern, false), 0,3);
        $nrRand = rand(0, 1000);

        $username = trim($firstPart).trim($secondPart).trim($nrRand);
        return $username;
    }
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Jenis extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Jenis Pembayaran');
    }


    // ===================================  JENIS  ===================================
	    public function index(){
	       $this->load_content_admin('keuangan/jenis/index');
	    }

	    public function getTable(){
	    	$callback = array();

	    	$query = "
	    		SELECT 
	    			keuangan_jenis.*, 
	    			keuangan_pos.pos AS nama_pos 
				FROM 
					keuangan_jenis 
				INNER JOIN 
					keuangan_pos 
				ON 
					keuangan_jenis.pos = keuangan_pos.id_pos 
				ORDER BY 
					tahun_ajaran, id_jenis
			";
	    	$res = $this->M_wsbangun->getData_by_query('default', $query);
	        if ($res) {
	            $callback = $res;
	        }
	        echo json_encode($callback);
	    }

	    public function add(){
	    	$keuangan_pos 	= $this->M_wsbangun->getData('default','keuangan_pos');
	    	$tahun_ajaran 	= $this->M_wsbangun->getData('default','tahun_ajaran');

	    	$content = array(
	    		'keuangan_pos' 	=> $keuangan_pos,
	    		'tahun_ajaran' 	=> $tahun_ajaran
	    	);

	        $this->load->view('keuangan/jenis/add', $content);
	    }

	    public function getByID($id=""){
	    	$callback = array();

		    $where=array('id_jenis'=>$id);
		    $res = $this->M_wsbangun->getData_by_criteria('default', 'keuangan_jenis',$where);
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
	            $tipe				= $this->input->post('tipe', true);
	        	$id					= $this->input->post('id', true);
	            $nama_pembayaran	= $this->input->post('nama_pembayaran', true);
	            $pos				= $this->input->post('pos', true);
	            $tahunajaran		= $this->input->post('tahunajaran', true);
	            $tipepembayaran		= $this->input->post('tipepembayaran', true);

	        	$cekdata = $this->M_wsbangun->getData_by_query('default', "SELECT * FROM keuangan_jenis WHERE id_jenis = '$id'");
	    		if ($tipe == 'add') {
		        	if (count($cekdata) == 0) {
		        		$data = array(
							'pos'				=> $pos,
		        			'nama_pembayaran' 	=> $nama_pembayaran,
		        			'tipe' 				=> $tipepembayaran,
		        			'tahun_ajaran' 		=> $tahunajaran,
		        		);
				        $insert = $this->M_wsbangun->insertData('default', 'keuangan_jenis', $data);
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
	        		$data = array(
						'pos'				=> $pos,
	        			'nama_pembayaran' 	=> $nama_pembayaran,
	        			'tipe' 				=> $tipepembayaran,
	        			'tahun_ajaran' 		=> $tahunajaran,
	        		);

		        	$where = array('id_jenis' => $id);

	        		$update = $this->M_wsbangun->updateData('default', 'keuangan_jenis', $data, $where);
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
	            $where = array('id_jenis'=>$id);

	            $del = $this->M_wsbangun->deleteData('default', 'keuangan_siswa_bulanan', $where);
	            $del = $this->M_wsbangun->deleteData('default', 'keuangan_jenis', $where);
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
	// ===================================  JENIS  ===================================

	// ===================================  TARIF  ===================================
	    public function tarif($id,$tipe){
	    	$query = "
	    		SELECT
		            kelas.id_kelas,
		            kelas.kelas,
		            keuangan_siswa_bulanan.id_program_studi,
		            keuangan_siswa_bulanan.kode_kelas,
		            keuangan_jenis.tipe
		        FROM
		            keuangan_siswa_bulanan
		            INNER JOIN kelas ON kelas.id_kelas = keuangan_siswa_bulanan.id_kelas
		            INNER JOIN keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
		        WHERE
		            keuangan_jenis.id_jenis = '$id'
		        GROUP BY
		            kelas.id_kelas,
		            keuangan_siswa_bulanan.id_program_studi,
		            keuangan_siswa_bulanan.kode_kelas
		        ORDER BY
		            kelas.id_kelas,
		            keuangan_siswa_bulanan.id_program_studi,
		            keuangan_siswa_bulanan.kode_kelas
	    	";
	    	$kelas = $this->M_wsbangun->getData_by_query('default', $query);

	        $content = array(
	        	'jenis' => $id,
	        	'tipe'  => $tipe,
	        	'kelas'  => $kelas,
	        );
	    	$this->load->view('keuangan/tarif/tarif', $content);
	    }

	    public function getTableTarif(){
	    	$callback = array();

	    	$tahun_ajaran 	= $this->input->post('tahun_ajaran', true);
	    	$listkelas 		= $this->input->post('listkelas', true);
	    	$jenis 			= $this->input->post('jenis', true);
	    	$tipe 			= $this->input->post('tipe', true);
	    	
	    	if ($listkelas) {
		    	$explodeKelas = explode('.', $listkelas);
		    	$query = "
		    		SELECT
		                data_siswa.nis, 
		                data_siswa.nama, 
		                kelas.kelas AS kelasku, 
		                data_siswa.status_kelas, 
		                data_siswa.program_studi, 
		                data_siswa.kode_kelas, 
		                keuangan_siswa_bulanan.id_siswa_bulanan,
	                    tahun_ajaran.tahun_ajaran,
		                keuangan_jenis.tipe,
		                keuangan_siswa_bulanan.b1, 
		                keuangan_siswa_bulanan.b2, 
		                keuangan_siswa_bulanan.b3, 
		                keuangan_siswa_bulanan.b4, 
		                keuangan_siswa_bulanan.b5, 
		                keuangan_siswa_bulanan.b6, 
		                keuangan_siswa_bulanan.b7, 
		                keuangan_siswa_bulanan.b8, 
		                keuangan_siswa_bulanan.b9, 
		                keuangan_siswa_bulanan.b10, 
		                keuangan_siswa_bulanan.b11, 
		                keuangan_siswa_bulanan.b12 
		            FROM
		                data_siswa
		                INNER JOIN kelas ON data_siswa.status_kelas = kelas.id_kelas 
		                INNER JOIN keuangan_siswa_bulanan ON data_siswa.nis = keuangan_siswa_bulanan.nis
		                INNER JOIN keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
	                    INNER JOIN tahun_ajaran ON keuangan_siswa_bulanan.tahun_ajaran = tahun_ajaran.tahun_ajaran
		            WHERE
		                keuangan_siswa_bulanan.tahun_ajaran = '$tahun_ajaran' 
		            AND keuangan_siswa_bulanan.id_jenis = '$jenis' 
		            AND keuangan_siswa_bulanan.id_kelas = '$explodeKelas[0]' 
		            AND data_siswa.status_kelas = '$explodeKelas[0]' 
		            AND data_siswa.program_studi = '$explodeKelas[1]' 
		            AND data_siswa.kode_kelas = '$explodeKelas[2]'
		            AND data_siswa.aktif = 'Y' 
					AND data_siswa.lulus = 'T'
		            ORDER BY 
		                data_siswa.nis
				";
	    		
		    	$res = $this->M_wsbangun->getData_by_query('default', $query);
		        
		        if ($res) {
		            $callback = $res;
		        }
	    	}

	        echo json_encode($callback);
	    }

	    public function lihatTarif($nis){
	    	$query = "SELECT nama FROM data_siswa WHERE nis = '$nis' ";
	    	$nama = $this->M_wsbangun->getData_by_query('default', $query)[0]->nama;
	    	$content = array('nama'=>$nama);
		    $this->load->view('keuangan/tarif/lihatTarif', $content);
		}

		public function editTarif($id){
			$query = "
				SELECT
	                data_siswa.nis, 
	                data_siswa.nama, 
	                kelas.kelas, 
	                data_siswa.status_kelas, 
	                data_siswa.program_studi, 
	                data_siswa.kode_kelas, 
	                keuangan_siswa_bulanan.id_siswa_bulanan,
                    tahun_ajaran.tahun_ajaran,
	                keuangan_jenis.tipe,
	                keuangan_jenis.id_jenis,
	                keuangan_siswa_bulanan.b1, 
	                keuangan_siswa_bulanan.b2, 
	                keuangan_siswa_bulanan.b3, 
	                keuangan_siswa_bulanan.b4, 
	                keuangan_siswa_bulanan.b5, 
	                keuangan_siswa_bulanan.b6, 
	                keuangan_siswa_bulanan.b7, 
	                keuangan_siswa_bulanan.b8, 
	                keuangan_siswa_bulanan.b9, 
	                keuangan_siswa_bulanan.b10, 
	                keuangan_siswa_bulanan.b11, 
	                keuangan_siswa_bulanan.b12 
	            FROM
	                data_siswa
	                INNER JOIN kelas ON data_siswa.status_kelas = kelas.id_kelas 
	                INNER JOIN keuangan_siswa_bulanan ON data_siswa.nis = keuangan_siswa_bulanan.nis
	                INNER JOIN keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
                    INNER JOIN tahun_ajaran ON keuangan_siswa_bulanan.tahun_ajaran = tahun_ajaran.tahun_ajaran
	            WHERE
	                keuangan_siswa_bulanan.id_siswa_bulanan = '$id' 
			";
	    	$siswa = $this->M_wsbangun->getData_by_query('default', $query);
			$content = array(
	        	'datasiswa'	=> $siswa[0]
	        );

		    $this->load->view('keuangan/tarif/editTarif', $content);
		}

		public function addTarif($jenis, $tipe, $berdasarkan){
			$query = "
				SELECT 
					kelas.id_kelas, 
					kelas.kelas, 
					COUNT(data_siswa.nis) AS jml_siswa 
				FROM 
					kelas 
				INNER JOIN 
					data_siswa 
				ON 
					kelas.id_kelas = data_siswa.status_kelas 
				AND data_siswa.aktif = 'Y' 
				AND data_siswa.lulus = 'T' 
				GROUP BY kelas.id_kelas 
				ORDER BY kelas.id_kelas
			";
			$tingkat = $this->M_wsbangun->getData_by_query('default', $query);

			$query = "
				SELECT 
					data_siswa.status_kelas, 
					data_siswa.program_studi, 
					data_siswa.kode_kelas, 
					kelas.kelas, 
					COUNT(data_siswa.nis) AS jml_siswa 
				FROM 
					data_siswa 
				INNER JOIN 
					kelas 
				ON 
					data_siswa.status_kelas = kelas.id_kelas 
				AND data_siswa.aktif = 'Y' 
				AND data_siswa.lulus = 'T' 
				GROUP BY 
					data_siswa.status_kelas, 
					data_siswa.program_studi, 
					data_siswa.kode_kelas 
				ORDER BY 
					data_siswa.status_kelas, 
					data_siswa.program_studi, 
					data_siswa.kode_kelas
			";
			$kelas = $this->M_wsbangun->getData_by_query('default', $query);

			$query = "
	    		SELECT 
	    			data_siswa.status_kelas, 
	    			data_siswa.program_studi, 
	    			data_siswa.kode_kelas, 
	    			kelas.kelas, 
	    			COUNT(data_siswa.nis) AS jml_siswa 
	    		FROM 
	    			data_siswa 
	    		INNER JOIN 
	    			kelas 
	    		ON 
	    			data_siswa.status_kelas = kelas.id_kelas 
	    		AND data_siswa.aktif = 'Y' 
				AND data_siswa.lulus = 'T' 
				GROUP BY 
					data_siswa.status_kelas, 
					data_siswa.program_studi, 
					data_siswa.kode_kelas 
				ORDER BY 
					data_siswa.status_kelas, 
					data_siswa.program_studi, 
					data_siswa.kode_kelas
	    	";
	    	$siswa = $this->M_wsbangun->getData_by_query('default', $query);

			$content = array(
	        	'jenis' 		=> $jenis,
	        	'tipe'  		=> $tipe,
	        	'berdasarkan' 	=> $berdasarkan,
	        	'tingkat'		=> $tingkat,
	        	'kelas'			=> $kelas,
	        	'siswa'			=> $siswa
	        );
			
			$this->load->view('keuangan/tarif/addTarif',$content);
		}

		public function getListSiswa($kelas){
			$callback = array();

			$kelasnya = explode('.', $kelas);

			$query = "
				SELECT 
					nis, 
					nama 
				FROM 
					data_siswa 
				WHERE 
					status_kelas = '$kelasnya[0]' 
				AND program_studi = '$kelasnya[1]' 
				AND kode_kelas = '$kelasnya[2]' 
				AND lulus = 'T'
				AND aktif = 'Y' 
				ORDER BY nis
			";
			$siswa = $this->M_wsbangun->getData_by_query('default', $query);

			if ($siswa) {
	            $callback = $siswa;
	        }
	        echo json_encode($callback);
		}

		public function saveTarif(){
			$callback = array(
	            'Data' => null,
	            'Message' => null,
	            'Error' => false
	        );

			if ($_POST) {
				$query = array();
				$berdasarkan 	= $this->input->post('berdasarkan', true);
				$tahun_ajaran 	= $this->input->post('tahun_ajaran', true);
				$tipe 			= $this->input->post('tipe', true);
				$jenis 			= $this->input->post('jenis', true);

				if ($berdasarkan == 'tingkat'){
					$kelas = $this->input->post('kelas', true);
					foreach ($kelas as $kelas) {
						$query[] = "
							SELECT 
								nis, 
								status_kelas, 
								program_studi, 
								kode_kelas
							FROM 
								data_siswa 
							WHERE 
								status_kelas = '$kelas'
							AND data_siswa.aktif = 'Y' 
							AND data_siswa.lulus = 'T' 
						";
					}
				}
				elseif($berdasarkan == 'kelas'){
					$kelas = $this->input->post('kelas', true);
					foreach ($kelas as $kelas) {
						$explodeKelas = explode('.', $kelas);
						$query[] = "
							SELECT 
								nis, 
								status_kelas, 
								program_studi, 
								kode_kelas 
							FROM 
								data_siswa 
							WHERE 
								status_kelas = '$explodeKelas[0]' 
							AND program_studi = '$explodeKelas[1]' 
							AND kode_kelas = '$explodeKelas[2]'
							AND data_siswa.aktif = 'Y' 
							AND data_siswa.lulus = 'T' 
						";
					}
				}
				elseif ($berdasarkan == 'siswa'){
					$nis = $this->input->post('nis', true);
					foreach ($nis as $nis) {
						$query[] = "
							SELECT 
								nis, 
								status_kelas, 
								program_studi, 
								kode_kelas 
							FROM 
								data_siswa 
							WHERE 
								nis = '$nis'
							AND data_siswa.aktif = 'Y' 
							AND data_siswa.lulus = 'T' 
						";
					}
				}
				elseif ($berdasarkan == 'edit') {
					$nis = $this->input->post('nis', true);
					$query[] = "
							SELECT 
								nis, 
								status_kelas, 
								program_studi, 
								kode_kelas 
							FROM 
								data_siswa 
							WHERE 
								nis = '$nis'
							AND data_siswa.aktif = 'Y' 
							AND data_siswa.lulus = 'T' 
						";
				}
				else{
					$callback['Message'] 	= "Undefined Method";
					$callback['Error'] 		= true;
				}

				foreach ($query as $query){
					$datasiswa = $this->M_wsbangun->getData_by_query('default', $query);
					foreach ($datasiswa as $datasiswa){
						if ($tipe == 'Bulanan'){
							$data = array(
								'tahun_ajaran' 		=> $this->input->post('tahun_ajaran', true),
	                            'nis' 				=> $datasiswa->nis,
	                            'id_jenis' 			=> $jenis,
	                            'b1' 				=> str_replace('.', '', $this->input->post('bln1', true)),
	                            'b2' 				=> str_replace('.', '', $this->input->post('bln2', true)),
	                            'b3' 				=> str_replace('.', '', $this->input->post('bln3', true)),
	                            'b4' 				=> str_replace('.', '', $this->input->post('bln4', true)),
	                            'b5' 				=> str_replace('.', '', $this->input->post('bln5', true)),
	                            'b6' 				=> str_replace('.', '', $this->input->post('bln6', true)),
	                            'b7' 				=> str_replace('.', '', $this->input->post('bln7', true)),
	                            'b8' 				=> str_replace('.', '', $this->input->post('bln8', true)),
	                            'b9' 				=> str_replace('.', '', $this->input->post('bln9', true)),
	                            'b10' 				=> str_replace('.', '', $this->input->post('bln10', true)),
	                            'b11' 				=> str_replace('.', '', $this->input->post('bln11', true)),
	                            'b12' 				=> str_replace('.', '', $this->input->post('bln12', true)),
	                            'id_kelas' 			=> str_replace('.', '', $datasiswa->status_kelas),
	                            'id_program_studi' 	=> str_replace('.', '', $datasiswa->program_studi),
	                            'kode_kelas' 		=> str_replace('.', '', $datasiswa->kode_kelas),
							);
						}
						else{
							$data = array(
								'tahun_ajaran' 		=> $this->input->post('tahun_ajaran', true),
	                            'nis' 				=> $datasiswa->nis,
	                            'id_jenis' 			=> $jenis,
	                            'b1' 				=> str_replace('.', '', $this->input->post('tarifsama', true)),
	                            'id_kelas' 			=> str_replace('.', '', $datasiswa->status_kelas),
	                            'id_program_studi' 	=> str_replace('.', '', $datasiswa->program_studi),
	                            'kode_kelas' 		=> str_replace('.', '', $datasiswa->kode_kelas),
							);
						}

						$where = array(
							'tahun_ajaran' 	=> $tahun_ajaran,
							'nis' 			=> $datasiswa->nis,
							'id_jenis' 		=> $jenis,
						);
						$cek = $this->M_wsbangun->getData_by_criteria('default', 'keuangan_siswa_bulanan', $where);
						
						if ($cek == null) {
							$insert = $this->M_wsbangun->insertData('default', 'keuangan_siswa_bulanan', $data);
							if ($insert != 'OK') {
								$callback['Message'] 	= "Error insert data for nis : ". $datasiswa->nis;
								$callback['Error'] 		= true;
								// echo json_encode($callback);
								// exit();
							}
							else{
								$callback['Message'] 	= "Berhasil Simpan Data";
							}
						}
						else{
							$where = array('id_siswa_bulanan' => $cek[0]->id_siswa_bulanan);
							$update = $this->M_wsbangun->updateData('default', 'keuangan_siswa_bulanan', $data, $where);
							if ($update != 'OK') {
								$callback['Message'] 	= "Error updating data with id : ".$cek[0]->id_siswa_bulanan;
								$callback['Error'] 		= true;
								// echo json_encode($callback);
								// exit();
							}
							else{
								$callback['Message'] 	= "Berhasil Simpan Data";
							}
						}
					}
				}
			}
			else{
				$callback['Message'] 	= "Unauthorized Access";
				$callback['Error'] 		= true;
			}

			echo json_encode($callback);
		}

		public function deleteTarif(){
	        $callback = array(
	            'Data' => null,
	            'Message' => null,
	            'Error' => false
	        );

	        if ($_POST) {
	            $id = $this->input->post("id",true);
	            $where = array('id_siswa_bulanan'=>$id);

	            $del = $this->M_wsbangun->deleteData('default', 'keuangan_siswa_bulanan', $where);
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
	// ===================================  TARIF  ===================================
}

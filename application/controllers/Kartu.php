<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Kartu extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->session->set_userdata('title', 'Cetak Kartu');
    }

    public function index(){
    	$this->load_content_admin('kartu/index');
    }

    // ADMIN
    	public function admin(){
	    	$this->adminOnly();

	       	$listKelasquery = "
		        SELECT
		            kelas.id_kelas,
		            kelas.kelas,
		            keuangan_siswa_bulanan.id_program_studi,
		            keuangan_siswa_bulanan.kode_kelas
		        FROM
		            keuangan_siswa_bulanan
		        INNER JOIN kelas 
		        ON 
		            kelas.id_kelas = keuangan_siswa_bulanan.id_kelas
		        INNER JOIN data_siswa 
		        ON 
		            keuangan_siswa_bulanan.id_kelas = data_siswa.status_kelas 
		        AND 
		            keuangan_siswa_bulanan.id_program_studi = data_siswa.program_studi 
		        AND 
		            keuangan_siswa_bulanan.kode_kelas = data_siswa.kode_kelas
		        GROUP BY
		            kelas.id_kelas,
		            keuangan_siswa_bulanan.id_program_studi,
		            keuangan_siswa_bulanan.kode_kelas
		        ORDER BY
		            kelas.id_kelas,
		            keuangan_siswa_bulanan.id_program_studi,
		            keuangan_siswa_bulanan.kode_kelas
		    ";

		    $listKelas = $this->M_wsbangun->getData_by_query('default', $listKelasquery);

		    $content = array(
		    	'listKelas' => $listKelas
		    );

	       $this->load_content_admin('kartu/admin', $content);
	    }

    	// UJIAN
    		public function getDataUjian(){
		    	$this->adminOnly();
		    	$callback = array();

		        $query = "
		            SELECT * 
		            FROM ujian
		        ";

		        $res = $this->M_wsbangun->getData_by_query('default', $query);
		        if ($res) {
		            $callback = $res;
		        }
		        echo json_encode($callback);
		    }

		    public function addUjian(){
		    	$this->adminOnly();
		    	$this->load->view('kartu/add');
		    }

		    public function getUjian($id){
		    	$this->adminOnly();
		    	$callback = array();

		        $query = "
		            SELECT * 
		            FROM ujian
		            WHERE id_ujian = '$id'
		        ";

		        $res = $this->M_wsbangun->getData_by_query('default', $query);
		        if ($res) {
		            $callback = $res[0];
		        }
		        echo json_encode($callback);
		    }

		    public function saveUjian(){
		    	$this->adminOnly();

		    	$callback = array(
		    		'Data' => null,
		    		'Error' => false,
		    		'Message' => null
		    	);

		    	if ($_POST) {
		    		$id_ujian 		= (int)$this->input->post('id_ujian', true);
		    		$nama_ujian 	= $this->input->post('nama', true);
		    		$tanggal_ujian 	= $this->input->post('tanggal_ujian', true);
		    		$jam_ujian 		= $this->input->post('jam_ujian', true);
		    		$max_siswa 		= $this->input->post('max_siswa', true);
		    		$tipe_ujian 	= $this->input->post('tipe_ujian', true);
		    		$keterangan 	= $this->input->post('keterangan', true);

		    		$tanggal_ujian = date_format(date_create($tanggal_ujian .' '. $jam_ujian),"Y-m-d H:i");
		    		
		    		$data = array(
		    			'nama_ujian' 	=> $nama_ujian,
		    			'tanggal_ujian' => $tanggal_ujian,
		    			'max_siswa' 	=> (int)$max_siswa,
		    			'tipe_ujian' 	=> $tipe_ujian,
		    			'keterangan' 	=> $keterangan,
		    			'audit_user'	=> $this->session->userdata("id_pengguna")
		    		);

		    		if ($id_ujian == 0) {
		    			$insert = $this->M_wsbangun->insertData('default', 'ujian', $data);

		    			if ($insert == 'OK') {
		    				$callback['Message'] 	= 'Data berhasil disimpan';
		    				$callback['Data'] 		= $insert;
		    			} 
		    			else{
		    				$callback['Message'] 	= 'Data gagal disimpan';
		    				$callback['Error']		= true;
		    				$callback['Data'] 		= $insert;
		    			}
		    		} 
		    		else{
		    			$where = array('id_ujian' => $id_ujian);
		    			$update = $this->M_wsbangun->updateData('default', 'ujian', $data, $where);

		    			if ($update == 'OK') {
			    			$callback['Message'] 	= 'Data berhasil diubah';
		    				$callback['Data'] 		= $update;
		    			} 
		    			else{
		    				$callback['Message'] 	= 'Data gagal diubah';
		    				$callback['Error']		= true;
		    				$callback['Data'] 		= $update;
		    			}
		    		}
		    	} 
		    	else{
		    		$callback['Error'] 		= true;
		    		$callback['Message'] 	= 'Invalid method';
		    	}

		    	echo json_encode($callback);
		    }

		    public function detailUjian(){
		    	$this->adminOnly();
		    	$this->load->view('kartu/detailUjian');
		    }

		    public function loadDetailUjian($id){
		    	$this->adminOnly();
		        $callback   = array();

		        $query = "
		        	SELECT *
		        	FROM ujian
		        	WHERE id_ujian = '$id'
		        ";
		        $data = $this->M_wsbangun->getData_by_query("default", $query);
		        $callback = $data[0];

		        echo json_encode($callback);
		    }

		    public function getDataDetailUjian($id){
		    	$this->adminOnly();
		        $callback   = array();

		    	$query = "
		    		SELECT 
						ujian.id_ujian,
					    kartu.id_kartu,
					    kartu.nis,
                        siswa.nama,
                        kelas.kelas,
                        siswa.program_studi,
                        siswa.kode_kelas,
					    kartu.ruangan,
					    kartu.username,
					    kartu.password
					FROM ujian 
					RIGHT JOIN ujian_kartu AS kartu
					ON 
                    	kartu.id_ujian = ujian.id_ujian
                    INNER JOIN data_siswa AS siswa
                    ON 
                    	kartu.nis = siswa.nis
                    INNER JOIN kelas
		        	ON 
                    	siswa.status_kelas = kelas.id_kelas
					WHERE ujian.id_ujian = '$id'
					ORDER BY id_ujian, ruangan, id_kartu
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

		    public function deleteKartuSiswa(){
	            $callback = array(
	                'Data' => null,
	                'Message' => null,
	                'Error' => false
	            );

	            if ($_POST) {
	                $id = (int)$this->input->post("id", true);
	                
                    $where = array('id_kartu'=>$id);
                    $del = $this->M_wsbangun->deleteData('default', 'ujian_kartu', $where);

                    if ($del == 'OK') {
                        $callback['Message'] = "Data has been deleted successfully";
                        $callback['Error'] = false;
                    } 
                    else{
                        $callback['Message'] = $del;
                        $callback['Error'] = true;
                    }
	            }
	            echo json_encode($callback);
	        }
    	// UJIAN

		// KIRIM KARTU
		    public function getDataSiswa($kelas){
		        $this->adminOnly();
		        $callback   = array();

		        $kls = explode('-', $kelas);

		        $query = "
		            SELECT * 
		            FROM data_siswa 
		            WHERE status_kelas = '$kls[0]'
		            AND program_studi = '$kls[1]'
		            AND kode_kelas =  '$kls[2]'
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

		    public function detailSiswa(){
		    	$this->adminOnly();
		    	$this->load->view('kartu/detailSiswa');
		    }

		    public function loadDetailSiswa($nis){
		    	$this->adminOnly();
		        $callback   = array();

		        $query = "
		        	SELECT
		        		data_siswa.*,
		        		kelas.kelas
		        	FROM data_siswa
		        	INNER JOIN kelas
		        	ON data_siswa.status_kelas = kelas.id_kelas
		        	WHERE nis = '$nis'
		        ";
		        $data_siswa = $this->M_wsbangun->getData_by_query("default", $query);
		        $callback['data_siswa'] = $data_siswa[0];

		        $query = "
		            SELECT
				        CONCAT(LEFT(keuangan_jenis.tahun_ajaran,4),'/', (LEFT(keuangan_jenis.tahun_ajaran,4) + 1)) AS tahun,
				        keuangan_pos.pos,
				        keuangan_siswa_bulanan.id_siswa_bulanan,
				        keuangan_siswa_bulanan.b1,
				        keuangan_siswa_bulanan.b2,
				        keuangan_siswa_bulanan.b4,
				        keuangan_siswa_bulanan.b3,
				        keuangan_siswa_bulanan.b5,
				        keuangan_siswa_bulanan.b6,
				        keuangan_siswa_bulanan.b7,
				        keuangan_siswa_bulanan.b8,
				        keuangan_siswa_bulanan.b9,
				        keuangan_siswa_bulanan.b10,
				        keuangan_siswa_bulanan.b11,
				        keuangan_siswa_bulanan.b12,
				        keuangan_jenis.id_jenis,
				        keuangan_jenis.nama_pembayaran
				    FROM
				        keuangan_siswa_bulanan
				    INNER JOIN 
				        keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
				    INNER JOIN 
				        keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
				    WHERE
				        keuangan_siswa_bulanan.nis = '$nis' AND
				        keuangan_jenis.tipe = 'bulanan'
				    ORDER BY
				        keuangan_jenis.tahun_ajaran,
				        keuangan_pos.pos
		        ";

		        $bulanan = $this->M_wsbangun->getData_by_query("default", $query);
		        
		        if ($bulanan) {
		            foreach ($bulanan as $key => $value) {
		            	$callback['bulanan'][$key] = array(
		            		'tahun' 			=> $value->tahun,
		            		'pos' 				=> $value->pos,
		            		'id_siswa_bulanan' 	=> $value->id_siswa_bulanan,
		            		'nama_pembayaran' 	=> $value->nama_pembayaran
		            	);

		            	$cek = $this->db->query("SELECT * from pembayaran_siswa_bulanan where tahun_ajaran = '".substr($value->tahun,0,4)."1' and nis = '".$nis."' and id_jenis = '".$value->id_jenis."'");
		                for($i=1;$i<=12;$i++){
		                	$id_ = 'b'.$i;

		                    if($cek->num_rows() <= 0){
		                    	$callback['bulanan'][$key]['b'.$i] = (int)$value->$id_;
		                    }
		                    else {
		                    	$hasilku = $cek->result()[0]->$id_;
		                        if($hasilku != '0000-00-00'){
		                        	$nilai = $hasilku;
		                        	$callback['bulanan'][$key]['b'.$i] = $nilai;
		                        }
		                        else {
		                        	$callback['bulanan'][$key]['b'.$i] = (int)$value->$id_;
		                        }
		                    }
		                }
		            }
		        }

		        $query = "
		            SELECT
		            CONCAT(LEFT(keuangan_jenis.tahun_ajaran,4),'/', (LEFT(keuangan_jenis.tahun_ajaran,4) + 1)) AS tahun,
		                keuangan_pos.pos,
		                keuangan_siswa_bulanan.id_siswa_bulanan,
		                keuangan_siswa_bulanan.b1,
		                keuangan_jenis.id_jenis,
				        keuangan_jenis.nama_pembayaran
		            FROM
		                keuangan_siswa_bulanan
		            INNER JOIN 
		                keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
		            INNER JOIN 
		                keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
		            WHERE
		                keuangan_siswa_bulanan.nis = '$nis' AND
		                keuangan_jenis.tipe = 'bebas'
		            ORDER BY
		                keuangan_jenis.tahun_ajaran,
		                keuangan_pos.pos
		        ";

		        $bebas = $this->M_wsbangun->getData_by_query("default", $query);

		        if ($bebas) {
		            foreach ($bebas as $key => $value) {
		            	$callback['bebas'][$key] = array(
		            		'tahun' 			=> $value->tahun,
		            		'b1'	 			=> $value->b1,
		            		'pos' 				=> $value->pos,
		            		'id_siswa_bulanan' 	=> $value->id_siswa_bulanan,
		            		'nama_pembayaran' 	=> $value->nama_pembayaran,
		            		'id_jenis'			=> $value->id_jenis
		            	);

		            	$query = "
		            		SELECT 
		            		b.id_pembayaran_bebas, 
		            		b.cicilan, 
		            		b.tanggal, 
		            		p.nama_lengkap AS petugas
		            		FROM pembayaran_siswa_bebas AS b
		                    INNER JOIN pengguna AS p
		                    ON p.id_pengguna = b.petugas
		            		WHERE b.tahun_ajaran = '".substr($value->tahun,0,4)."1' 
		            		AND b.nis = '$nis' 
		            		AND b.id_jenis = '$value->id_jenis' 
		            		ORDER BY b.tanggal
		            	";

		            	$bebasDetail = $this->M_wsbangun->getData_by_query("default", $query);
		            	
		            	if ($bebasDetail) {
		            		foreach ($bebasDetail as $keydetail => $bdetail) {
		            			$callback['bebas'][$key]['detail'][$keydetail]['id_pembayaran_bebas'] = $bdetail->id_pembayaran_bebas;
		            			$callback['bebas'][$key]['detail'][$keydetail]['cicilan'] 			= $bdetail->cicilan;
		            			$callback['bebas'][$key]['detail'][$keydetail]['tanggal'] 			= $bdetail->tanggal;
		            			$callback['bebas'][$key]['detail'][$keydetail]['petugas'] 			= $bdetail->petugas;
		            		}
		            	}
		            }
		        }

		        echo json_encode($callback);
		    }

		    public function kirim(){
		    	$this->adminOnly();
		    	$content = array();
		    	
	    		$ujian = $this->M_wsbangun->getData('default', 'ujian');
	    		$content['data_ujian'] = ($ujian) ? $ujian : array();

		    	$this->load->view('kartu/kirim', $content);
		    }

		    public function kirimKartu(){
		    	$this->adminOnly();

		    	$callback = array(
		    		'Data' => null,
		    		'Error' => false,
		    		'Message' => null
		    	);

		    	if ($_POST) {
	                $nis 		= $this->input->post('nis_kartu', true);
	                $id_ujian 	= $this->input->post('id_ujian', true);

	                $query = "
	                	SELECT 
							ujian.id_ujian,
							ujian.nama_ujian,
						    kartu.id_kartu,
						    kartu.nis,
	                        siswa.nama,
						    kartu.ruangan,
						    kartu.username
						FROM ujian 
						RIGHT JOIN 
							ujian_kartu AS kartu
							ON kartu.id_ujian = ujian.id_ujian
	                    INNER JOIN 
	                    	data_siswa AS siswa
	                    	ON kartu.nis = siswa.nis
						WHERE ujian.id_ujian = '$id_ujian'
						AND siswa.nis = '$nis'
	                ";
	                $cekdataexsist = $this->M_wsbangun->getData_by_query('default', $query);

	                if ($cekdataexsist) {
	                	$callback['Message'] = $cekdataexsist[0]->nama ." sudah mempunyai kartu peserta ". $cekdataexsist[0]->nama_ujian;
                        $callback['Error'] = true;
	                } 
	                else {
	                    $datadt = array(
	                        'id_ujian' 		=> $id_ujian,
	                        'nis'     		=> $nis,
	                        'audit_user'	=> $this->session->userdata("id_pengguna"),
	                        'username'		=> $nis,
	                        'password'		=> md5($nis),
	                        'ruangan' 		=> '1 - R1'
	                    );

	                    $insert = $this->M_wsbangun->insertData('default', 'ujian_kartu', $datadt);
	                    if ($insert != 'OK') {
	                        $callback['Message']    = "Error send kartu untuk nis : ". $nis;
	                        $callback['Error']      = true;
	                    }
	                    else{
	                        $callback['Message']    = "Kartu peserta ujian telah dikirim";
	                    }
	                }
	            }
	            else{
	                $callback['Message']    = "Unauthorized Access";
	                $callback['Error']      = true;
	            }

	            echo json_encode($callback);
		    }

		    public function kirimPengumuman(){
		    	$this->adminOnly();

		    	$callback = array(
	                'Data' => null,
	                'Message' => null,
	                'Error' => false
	            );

	            if ($_POST) {
	                $nis          	= $this->input->post('nis_pengumuman', true);
	                $judul          = "Pemberitahuan Pembayaran";
	                $message        = $this->input->post('pemberitahuan_pembayaran', true);
	                if ($message == "" || $message == null) {
	                	$message = "Mohon segera selesaikan pembayaran";
	                }

	                // INSERT HEADER
	                    $cekid = "SELECT MAX(id_pengumuman) AS id_pengumuman FROM pengumuman_hd";
	                    $id_pengumuman = @$this->M_wsbangun->getData_by_query('default', $cekid)[0]->id_pengumuman;
	                    $id_pengumuman = (int)$id_pengumuman;
	                    
	                    if ($id_pengumuman == NULL || $id_pengumuman == 0) {
	                        $id_pengumuman = 1;
	                    }else{
	                        $id_pengumuman ++;
	                    }

	                    $datahd = array(
	                        'id_pengumuman' => $id_pengumuman,
	                        'judul'         => $judul,
	                        'message'       => $message,
	                        'tanggal'       => date('Y-m-d H:i:s'),
	                        'petugas'       => $this->session->userdata("id_pengguna")
	                    );
	                    $insert = $this->M_wsbangun->insertData('default', 'pengumuman_hd', $datahd);
	                // INSERT HEADER

                    $datadt = array(
                        'id_pengumuman' => $id_pengumuman,
                        'nis'           => $nis,
                        'tipe'			=> 'pembayaran'
                    );

                    $insert = $this->M_wsbangun->insertData('default', 'pengumuman_dt', $datadt);
                    if ($insert != 'OK') {
                        $callback['Message']    = "Error insert data for nis : ". $nis;
                        $callback['Error']      = true;
                    }
                    else{
                        $callback['Message']    = "Berhasil Simpan Data";
                    }
	            }
	            else{
	                $callback['Message']    = "Unauthorized Access";
	                $callback['Error']      = true;
	            }

	            echo json_encode($callback);
		    }
		// KIRIM KARTU
	// ADMIN
}

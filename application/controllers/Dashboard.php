<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        // $this->adminOnly();
        $this->session->set_userdata('title', 'Dashboard Admin');
    }

    public function index(){
    	$jumlah_siswa = $this->M_wsbangun->getData('default', 'data_siswa');
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
        $jumlah_kelas = $this->M_wsbangun->getData_by_query('default', $query);
    	
    	$data = array(
    		'jumlah_siswa' => count($jumlah_siswa),
    		'jumlah_kelas' => count($jumlah_kelas)
    	);
       $this->load_content_admin('dash/index', $data);
    }
}

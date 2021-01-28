<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Kelulusan extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Kelulusan');
    }

    public function index(){
    	$query = "
    		SELECT 
	    		kelas.id_kelas AS status_kelas, 
	    		kelas.kelas, 
	    		program_studi.id_program_studi AS program_studi, 
	    		rombel.id AS kode_kelas 
    		FROM 
    			kelas, 
    			program_studi, 
    			rombel 
    		ORDER BY 
    			kelas.id_kelas ASC, 
    			program_studi.id_program_studi ASC, 
    			rombel.id ASC
    	";
    	$listKelas = $this->M_wsbangun->getData_by_query('default', $query);
    	$content = array('listKelas' => $listKelas);

       $this->load_content_admin('siswa/kelulusan/index', $content);
    }

    public function siswalulus(){
        if(isset($_POST['kelas'])){
            $kelas = $_POST['kelas'];
            $tahun_lulus = $this->db->query('select tahun_ajaran from tahun_ajaran where aktif = \'Y\'')->result()[0]->tahun_ajaran;
            foreach($_POST['nis'] as $val):
                $data = array(
                    'lulus' => 'Y',
                    'tahun_lulus' => $tahun_lulus,
                );
                $this->db->where('nis', $val);
                $this->db->update('data_siswa', $data);
            endforeach;
            redirect('Siswa/Kelulusan?awalkelas='.$kelas);
        }else{
            redirect('Siswa/Kelulusan');
        }
    }

    public function siswabalik(){
        if(isset($_POST['kelas'])){
            $kelas = $_POST['kelas'];
            foreach($_POST['nis'] as $val):
                $data = array(
                    'lulus' => 'T',
                    'tahun_lulus' => '  ',
                );
                $this->db->where('nis', $val);
                $this->db->update('data_siswa', $data);
            endforeach;
            redirect('Siswa/Kelulusan?awalkelas='.$kelas);
        }else{
            redirect('Siswa/Kelulusan');
        }
    }
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class PindahKelas extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Pindah Kelas');
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

       $this->load_content_admin('siswa/pindahkelas/index', $content);
    }

    public function siswapindah(){
        if(isset($_POST['kelas2']) && isset($_POST['kelas1']) && $_POST['kelas2'] != '' && $_POST['kelas1'] != ''){
            $kelas = $_POST['kelas1'];
            $kls = $_POST['kelas2'];
            $klsx = explode('.', $kls);
            foreach($_POST['nis'] as $val):
                $data = array(
                    'status_kelas' => $klsx[0],
                    'program_studi' => $klsx[1],
                    'kode_kelas' => $klsx[2],
                );
                $this->db->where('nis', $val);
                $this->db->update('data_siswa', $data);
            endforeach;
            redirect('Siswa/PindahKelas?awalkelas='.$kelas.'&targetkelas='.$kls);
        }else{
            redirect('Siswa/PindahKelas');
        }
    }

    public function siswabalik(){
        if(isset($_POST['kelas2']) && isset($_POST['kelas1']) && $_POST['kelas2'] != '' && $_POST['kelas1'] != ''){
            $kelas = $_POST['kelas1'];
            $kls = $_POST['kelas2'];
            $klsx = explode('.', $kelas);
            foreach($_POST['nis'] as $val):
                $data = array(
                    'status_kelas' => $klsx[0],
                    'program_studi' => $klsx[1],
                    'kode_kelas' => $klsx[2],
                );
                $this->db->where('nis', $val);
                $this->db->update('data_siswa', $data);
            endforeach;
            redirect('Siswa/PindahKelas?awalkelas='.$kelas.'&targetkelas='.$kls);
        }else{
            redirect('Siswa/PindahKelas');
        }
    }
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class NaikKelas extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Kenaikan Kelas');
    }

    public function index(){
       $this->load_content_admin('siswa/naikkelas/index');
    }

    public function siswanaik(){
        $tahun_lulus = $this->db->query('SELECT tahun_ajaran from tahun_ajaran where aktif = \'Y\'')->result()[0]->tahun_ajaran;
        if(isset($_POST['simpan'])){
            if($_POST['simpan'] == 'ok'){
                $siswaku = $this->db->query("SELECT nis, status_kelas from data_siswa where aktif = 'Y' and lulus = 'T'");
                foreach($siswaku->result() as $siswa){
                    $nis = $siswa->nis;
                    if($siswa->status_kelas + 1 == 10 || $siswa->status_kelas + 1 == 13){
                        $data = array(
                            'lulus' => 'Y',
                            'tahun_lulus' => $tahun_lulus,
                        );
                    }else{
                        $data = array(
                            'status_kelas' => $siswa->status_kelas + 1,
                        );
                    }
                    $this->db->where('nis', $nis);
                    $this->db->update('data_siswa', $data);
                }
            }
            $this->session->set_flashdata('pesan', 'Berhasil menaikkan semua siswa :-)');
            redirect('Siswa/NaikKelas');
        }else{
            redirect('Siswa/NaikKelas');
        }
    }
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Pengumuman extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->session->set_userdata('title', 'Pengumuman');
    }

    public function index(){
        $status     = $this->session->userdata("status");

        if ($status == 'admin') {
            $this->load_content_admin('pengumuman/admin');
        } 
        else {
            $this->load->view('pengumuman/index');
        }
    }

    // ========================== ADMIN
        public function getDataAdmin(){
            $callback = array();

            $this->adminOnly();
            $query = "
                SELECT 
                    hd.id_pengumuman,
                    dt.jml,
                    hd.judul,
                    hd.tanggal,
                    hd.petugas
                FROM pengumuman_hd AS hd
                JOIN (
                    SELECT 
                        COUNT(rowid) AS jml,
                        id_pengumuman
                    FROM pengumuman_dt 
                    GROUP BY id_pengumuman
                ) AS dt
                ON dt.id_pengumuman = hd.id_pengumuman
                WHERE hd.tipe != 'pembayaran'
            ";

            $res = $this->M_wsbangun->getData_by_query('default', $query);
            if ($res) {
                $callback = $res;
            }
            echo json_encode($callback);
        }

        public function lihatPenerima($id){
            $this->adminOnly();

            $where = array('id_pengumuman'=>$id);
            $data_hd = $this->M_wsbangun->getData_by_criteria('default', 'pengumuman_hd', $where);

            $content = array(
                'id' => $id,
                'data_hd' => $data_hd
            );

            $this->load->view('pengumuman/penerima', $content);
        }

        public function getTablePenerima($id){
            $callback = array();

            $this->adminOnly();
            $query = "
                SELECT
                    hd.id_pengumuman,
                    hd.judul,
                    hd.message,
                    dt.nis,
                    dt.is_read,
                    sis.nama,
                    kelas.kelas,
                    sis.program_studi,
                    sis.kode_kelas,
                    dt.rowid
                FROM pengumuman_hd AS hd
                INNER JOIN
                    pengumuman_dt AS dt
                ON dt.id_pengumuman = hd.id_pengumuman
                INNER JOIN 
                    data_siswa AS sis
                ON sis.nis = dt.nis
                INNER JOIN 
                    kelas
                ON sis.status_kelas = kelas.id_kelas
                WHERE dt.id_pengumuman = '$id'
            ";

            $res = $this->M_wsbangun->getData_by_query('default', $query);
            if ($res) {
                $callback = $res;
            }
            echo json_encode($callback);
        }

        public function add($berdasarkan){
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
                'berdasarkan'   => $berdasarkan,
                'tingkat'       => $tingkat,
                'kelas'         => $kelas,
                'siswa'         => $siswa
            );
            
            $this->load->view('pengumuman/add',$content);
        }

        public function save(){
            $callback = array(
                'Data' => null,
                'Message' => null,
                'Error' => false
            );

            if ($_POST) {
                $query          = array();
                $berdasarkan    = $this->input->post('berdasarkan', true);
                $judul          = $this->input->post('judul', true);
                $message        = $this->input->post('isi_pengumuman', true);

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
                else{
                    $callback['Message']    = "Undefined Method";
                    $callback['Error']      = true;
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

                foreach ($query as $query){
                    $datasiswa = $this->M_wsbangun->getData_by_query('default', $query);
                    foreach ($datasiswa as $datasiswa){
                        $datadt = array(
                            'id_pengumuman' => $id_pengumuman,
                            'nis'           => $datasiswa->nis
                        );

                        $insert = $this->M_wsbangun->insertData('default', 'pengumuman_dt', $datadt);
                        if ($insert != 'OK') {
                            $callback['Message']    = "Error insert data for nis : ". $datasiswa->nis;
                            $callback['Error']      = true;
                            // echo json_encode($callback);
                            // exit();
                        }
                        else{
                            $callback['Message']    = "Berhasil Simpan Data";
                        }
                    }
                }
            }
            else{
                $callback['Message']    = "Unauthorized Access";
                $callback['Error']      = true;
            }

            echo json_encode($callback);
        }

        public function delete($tipe){
            $callback = array(
                'Data' => null,
                'Message' => null,
                'Error' => false
            );

            if ($_POST) {
                $id = (int)$this->input->post("id",true);
                
                if ($tipe == 'hd') {
                    $where = array('id_pengumuman'=>$id);
                    $del = $this->M_wsbangun->deleteData('default', 'pengumuman_dt', $where);

                    if ($del == 'OK') {
                        $del = $this->M_wsbangun->deleteData('default', 'pengumuman_hd', $where);

                        if ($del == 'OK') {
                            $callback['Message'] = "Data has been deleted successfully";
                            $callback['Error'] = false;
                        }
                        else {
                            $callback['Message'] = $del;
                            $callback['Error'] = true;
                        }
                    } 
                    else{
                        $callback['Message'] = $del;
                        $callback['Error'] = true;
                    }
                }
                else{
                    $where = array('rowid'=>$id);
                    $del = $this->M_wsbangun->deleteData('default', 'pengumuman_dt', $where);

                    if ($del == 'OK') {
                        $callback['Message'] = $del;
                        $callback['Error'] = true;
                    }
                    else {
                        $callback['Message'] = "Data has been deleted successfully";
                        $callback['Error'] = false;
                    }
                }
            }
            echo json_encode($callback);
        }
    // ========================== ADMIN

    // ========================== SISWA
        public function getPengumuman(){
            $callback = array();
            
            $query = "
                SELECT 
                    hd.id_pengumuman,
                    dt.jml,
                    hd.message,
                    hd.tanggal,
                    hd.petugas
                FROM pengumuman_hd AS hd
                JOIN (
                    SELECT 
                        COUNT(rowid) AS jml,
                        id_pengumuman
                    FROM pengumuman_dt 
                    GROUP BY id_pengumuman
                ) AS dt
                ON dt.id_pengumuman = hd.id_pengumuman
            ";

            $res = $this->M_wsbangun->getData_by_query('default', $query);
            if ($res) {
                $callback = $res;
            }
            echo json_encode($callback);
        }

        public function loadPengumuman(){
            $id_pengguna = substr($this->session->userdata("id_pengguna"), 1);
            $callback   = "";

            $query = "
                SELECT 
                    hd.id_pengumuman,
                    dt.nis,
                    dt.is_read,
                    dt.rowid,
                    hd.judul,
                    hd.message,
                    hd.tanggal
                FROM pengumuman_hd AS hd
                INNER JOIN 
                    pengumuman_dt AS dt
                ON dt.id_pengumuman = hd.id_pengumuman
                WHERE nis = '$id_pengguna'
                ORDER BY is_read DESC, rowid DESC
            ";
            $detailPengumuman = $this->M_wsbangun->getData_by_query("default", $query);
            
            if ($detailPengumuman) {
                foreach ($detailPengumuman as $value){
                    if ($value->is_read == 'Y') {
                        $read_stat  = '';
                        $read_bg    = 'bg-accent-1';
                    } 
                    else {
                        $read_stat  = 'ft-alert-circle danger';
                        $read_bg    = 'bg-accent-3';
                    }

                    $callback .= "<div class='row mb-0 mt-0'>";
                    $callback .=    "<div class='card col-12 mx-0 px-0'>";
                    $callback .=        "<div onClick='markAsRead($value->rowid);' class='card-header bg-primary $read_bg' id='heading_$value->id_pengumuman' data-toggle='collapse' data-target='#collapse_$value->id_pengumuman' aria-expanded='false' aria-controls='collapse_$value->id_pengumuman' style='cursor:pointer;'>";
                    $callback .=            "<h3 class='mb-0'>";
                    $callback .=                "<span class='float-left'><b>$value->judul</b> ";
                    $callback .=                    " <i class='$read_stat'></i>";
                    $callback .=                "</span>";
                    $callback .=                "<span class='float-right'>";
                    $callback .=                    "<u>#$value->id_pengumuman</u>";
                    $callback .=                "</span>";
                    $callback .=            "</h3>";
                    $callback .=        "</div>";
                    $callback .=        "<div id='collapse_$value->id_pengumuman' class='collapse' aria-labelledby='heading_$value->id_pengumuman'>";
                    $callback .=            "<div class='card-body'>$value->message</div>";
                    $callback .=        "</div>";
                    $callback .=    "</div>";
                    $callback .= "</div>";
                }
            }
            else{
                $callback .= "<div class='row mb-0 mt-2'>";
                $callback .=    "<div class='col-12'>";
                $callback .=        "<h3 class='card-title text-center'> Tidak ada pengumuman </h3>";
                $callback .=    "</div>";
                $callback .= "</div>";
            }

            echo $callback;
        }

        public function markAsRead($rowid){
            $data   = array('is_read' => 'Y');
            $where  = array('rowid' => $rowid);
            $this->M_wsbangun->updateData('default', 'pengumuman_dt', $data, $where);
        }
    // ========================== SISWA
}
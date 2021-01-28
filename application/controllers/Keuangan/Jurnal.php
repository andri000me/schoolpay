<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Jurnal extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Jurnal Umum');
    }

    public function index(){
    	if(isset($_POST['jurnal'])){
            $tanggal = explode('-', $_POST['tanggal']);
            $tgl = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
            $this->simpanTransaksi($tgl);
            echo '<script>';
            echo 'window.parent.location.reload()';
            echo '</script>';
            exit(0);
        }
        if(isset($_POST['jurnalCetak'])){
            $tanggal = explode('-', $_POST['tanggal']);
            $tgl = $tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0];
            $this->simpanTransaksi($tgl);
            $this->savePDFOk($tgl);
            exit(0);
        }
        
        $data['bulanku'] = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
        $data['tahunku'] = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');
        $data['kasku'] = $this->lastKas($data['bulanku'], $data['tahunku']);
        $data['kaskulagi'] = $this->nowKas($data['bulanku'], $data['tahunku']);
        
       $this->load_content_admin('keuangan/jurnal/index', $data);
    }

    public function getClientIP(){
        if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }

        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        $ip = ($ip === false) ? '0.0.0.0' : $ip;
        $ip = ($ip === '::1') ? '127.0.0.1' : $ip;
        return $ip;
    }

    public function toIndo($tgl){
        $tgl = explode('-', $tgl);
        $bln = array('Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        return $tgl[2].' '.$bln[(int)$tgl[1]].' '.$tgl[0];
    }

    public function lastKas($bulan, $tahun){
        $kasku = 0;
        $bulan = $bulan - 1;
        $tahun = substr($tahun, 0, 4);

        // ambil dari pembayaran bebas
        $jmlPembayaranBebas = $this->db->query("select sum(cicilan) as jumlah from pembayaran_siswa_bebas where tanggal < last_day('".$tahun."-".$bulan."-1')")->result()[0]->jumlah;
        $kasku += $jmlPembayaranBebas;

        // ambil dari jurnal
        $jmlKeuanganJurnal = $this->db->query("select sum(debit - kredit) as jumlahKas from keuangan_jurnal where tanggal < last_day('".$tahun."-".$bulan."-1')")->result()[0]->jumlahKas;
        
        $kasku += $jmlKeuanganJurnal;
        // ambil dari bulanan
        $jmlPembayaranBulanan = 0;
        $cariPembayar = $this->db->query("select tahun_ajaran, nis, id_jenis, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12 from pembayaran_siswa_bulanan");
        foreach($cariPembayar->result() as $row1){
            if($row1->b1 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b1 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b1."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b1")->result()[0]->b1;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b2 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b2 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b2."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b2")->result()[0]->b2;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b3 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b3 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b3."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b3")->result()[0]->b3;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b4 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b4 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b4."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b4")->result()[0]->b4;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b5 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b5 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b5."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b5")->result()[0]->b5;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b6 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b6 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b6."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b6")->result()[0]->b6;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b7 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b7 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b7."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b7")->result()[0]->b7;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b8 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b8 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b8."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b8")->result()[0]->b8;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b9 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b9 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b9."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b9")->result()[0]->b9;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b10 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b10 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b10."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b10")->result()[0]->b10;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b11 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b11 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b11."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b11")->result()[0]->b11;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b12 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b12 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b12."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b12")->result()[0]->b12;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
        }
        $kasku += $jmlPembayaranBulanan;

        return $kasku;
    }

    public function nowKas($bulan, $tahun){
        $kasku = 0;
        $bulan = $bulan;
        $tahun = substr($tahun, 0, 4);

        // ambil dari pembayaran bebas
        $jmlPembayaranBebas = $this->db->query("select sum(cicilan) as jumlah from pembayaran_siswa_bebas where year(tanggal) = '".(substr($tahun, 0, 4))."' and month(tanggal) = '".$bulan."'")->result()[0]->jumlah;
        $kasku += $jmlPembayaranBebas;

        // ambil dari bulanan
        $jmlPembayaranBulanan = 0;
        $cariPembayar = $this->db->query("select tahun_ajaran, nis, id_jenis, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12 from pembayaran_siswa_bulanan");
        foreach($cariPembayar->result() as $row1){
            if($row1->b1 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b1 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b1."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b1."') = '".$bulan."' union select 0 as b1")->result()[0]->b1;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b2 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b2 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b2."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b2."') = '".$bulan."' union select 0 as b2")->result()[0]->b2;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b3 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b3 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b3."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b3."') = '".$bulan."' union select 0 as b3")->result()[0]->b3;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b4 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b4 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b4."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b4."') = '".$bulan."' union select 0 as b4")->result()[0]->b4;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b5 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b5 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b5."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b5."') = '".$bulan."' union select 0 as b5")->result()[0]->b5;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b6 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b6 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b6."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b6."') = '".$bulan."' union select 0 as b6")->result()[0]->b6;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b7 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b7 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b7."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b7."') = '".$bulan."' union select 0 as b7")->result()[0]->b7;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b8 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b8 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b8."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b8."') = '".$bulan."' union select 0 as b8")->result()[0]->b8;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b9 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b9 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b9."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b9."') = '".$bulan."' union select 0 as b9")->result()[0]->b9;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b10 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b10 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b10."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b9."') = '".$bulan."' union select 0 as b10")->result()[0]->b10;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b11 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b11 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b11."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b11."') = '".$bulan."' union select 0 as b11")->result()[0]->b11;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b12 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b12 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b12."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b12."') = '".$bulan."' union select 0 as b12")->result()[0]->b12;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
        }
        $kasku += $jmlPembayaranBulanan;

        return $kasku;
    }

    public function simpanTransaksi($tgl){
        foreach($_POST['keterangan'] as $i=>$ket){
            if($ket == '')
                continue;
            $deb = $_POST['debit'][$i];
            $kre = $_POST['kredit'][$i];
            if((int)(str_replace('.', '', $deb)) == 0 && (int)(str_replace('.', '', $kre)) == 0)
                continue;
            $datanya = array(
                    'tanggal' => $tgl,
                    'keterangan' => $ket,
                    'debit' => (int)(str_replace('.', '', $deb)),
                    'kredit' => (int)(str_replace('.', '', $kre)),
                    'petugas' => $this->session->userdata('id_pengguna'),
                );
            $this->db->insert('keuangan_jurnal', $datanya);
        }
    }
    
    public function savePDFOk($tgl){
        $bulanku = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
        $tahunku = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)$this->db->query("select tahun_ajaran from tahun_ajaran where aktif = 'Y'")->result()[0]->tahun_ajaran;
        $saldoku = 0;
        $debitku = 0;
        $kreditku= 0;
        $kasku = $this->lastKas($bulanku, $tahunku);
        $kaskulagi = $this->nowKas($bulanku, $tahunku);

        $sekolah = $this->db->query("select * from biodata_sekolah where id = '1'")->result()[0];
        $bulan = array('Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        $content = '
            <table style="width: 99%" align="center">
                <tr>
                    <td>
                    <table align="center" class="myTable" style="border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="width: 120mm;border-bottom:1px solid #000;" colspan="6">
                                    <div align="center">
                                        <h3>'.$sekolah->sekolah.'</h3>
                                    </div>
                                    <div align="center" style="margin-top: -7px;">
                                        '.$sekolah->alamat.'
                                        <br>
                                        Telp. '.$sekolah->tlp.'
                                        <hr>
                                        <strong>Jurnal '.$bulan[$bulanku].' '.substr($tahunku, 0, 4).'</strong>
                                    </div>
                                    <br>
                                </th>
                            </tr>
                            <tr>
                                <th width="15" style="border:1px solid #000;padding: 5px; width: 10mm;"">No</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;"">Tanggal</th>
                                <th style="border:1px solid #000;padding: 5px; width: 70mm;">Keterangan</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;">Penerimaan</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;">Pengeluaran</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $no = 1;
                            foreach($_POST['keterangan'] as $i=>$ket){
                                if($ket == '')
                                    continue;
                                $deb = $_POST['debit'][$i];
                                $kre = $_POST['kredit'][$i];
                                if((int)(str_replace('.', '', $deb)) == 0 && (int)(str_replace('.', '', $kre)) == 0)
                                    continue;
                                $debitku += (int)(str_replace('.', '', $deb));
                                $kreditku += (int)(str_replace('.', '', $kre));
                                $saldoku = $debitku - $kreditku;
                                $content .= '<tr>
                                    <td class="text-center" style="border:1px solid #000;padding: 5px;">'.$no.'</td>
                                    <td style="border:1px solid #000;padding: 5px;">'.$this->toIndo($tgl).'</td>
                                    <td style="border:1px solid #000;padding: 5px;">'.$ket.'</td>
                                    <td style="border:1px solid #000;padding: 5px;text-align:right;">'.number_format((int)(str_replace('.', '', $deb)), 0, ',', '.').'</td>
                                    <td style="border:1px solid #000;padding: 5px;text-align:right;">'.number_format((int)(str_replace('.', '', $kre)), 0, ',', '.').'</td>
                                    <td style="border:1px solid #000;padding: 5px;text-align:right;">'.number_format($saldoku, 0, ',', '.').'</td>
                                </tr>';
                                $no += 1;
                            }
                            $content .= '<tr class="alert-info">
                                <td colspan="3" style="border:1px solid #000;padding: 5px;"><strong>Total</strong></td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">';
                                    $content .=  number_format($debitku, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">';
                                    $content .=  number_format($kreditku, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">';
                                    $content .=  number_format($saldoku, 0, ',', '.').'</td>
                            </tr>';
           $content .= '</tbody>
                    </table>
                    <br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table align="right">
                            <tr>
                                <td style="">( Petugas )</td>
                            </tr>
                            <tr>
                                <td style="">'.$sekolah->kabupaten.', '.$this->toIndo(date('Y-m-d')).'</td>
                            </tr>
                            <tr>
                                <td><br><br></td>
                            </tr>
                            <tr>
                                <td style="">'.$this->session->userdata('nama_lengkap').'</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

            $header = '<style>';
            $header .= '
                /* reset.css */
                /*! normalize.css v3.0.2 | MIT License | git.io/normalize */

                /**
                 * 1. Set default font family to sans-serif.
                 * 2. Prevent iOS text size adjust after orientation change, without disabling
                 *    user zoom.
                 */

                html {
                  font-family: sans-serif; /* 1 */
                  -ms-text-size-adjust: 100%; /* 2 */
                  -webkit-text-size-adjust: 100%; /* 2 */
                }

                /**
                 * Remove default margin.
                 */

                body {
                  margin: 0;
                }

                /* HTML5 display definitions
                   ========================================================================== */

                /**
                 * Correct `block` display not defined for any HTML5 element in IE 8/9.
                 * Correct `block` display not defined for `details` or `summary` in IE 10/11
                 * and Firefox.
                 * Correct `block` display not defined for `main` in IE 11.
                 */

                article,
                aside,
                details,
                figcaption,
                figure,
                footer,
                header,
                hgroup,
                main,
                menu,
                nav,
                section,
                summary {
                  display: block;
                }

                /**
                 * 1. Correct `inline-block` display not defined in IE 8/9.
                 * 2. Normalize vertical alignment of `progress` in Chrome, Firefox, and Opera.
                 */

                audio,
                canvas,
                progress,
                video {
                  display: inline-block; /* 1 */
                  vertical-align: baseline; /* 2 */
                }

                /**
                 * Prevent modern browsers from displaying `audio` without controls.
                 * Remove excess height in iOS 5 devices.
                 */

                audio:not([controls]) {
                  display: none;
                  height: 0;
                }

                /**
                 * Address `[hidden]` styling not present in IE 8/9/10.
                 * Hide the `template` element in IE 8/9/11, Safari, and Firefox < 22.
                 */

                [hidden],
                template {
                  display: none;
                }

                /* Links
                   ========================================================================== */

                /**
                 * Remove the gray background color from active links in IE 10.
                 */

                a {
                  background-color: transparent;
                }

                /**
                 * Improve readability when focused and also mouse hovered in all browsers.
                 */

                a:active,
                a:hover {
                  outline: 0;
                }

                /* Text-level semantics
                   ========================================================================== */

                /**
                 * Address styling not present in IE 8/9/10/11, Safari, and Chrome.
                 */

                abbr[title] {
                  border-bottom: 1px dotted;
                }

                /**
                 * Address style set to `bolder` in Firefox 4+, Safari, and Chrome.
                 */

                b,
                strong {
                  font-weight: bold;
                }

                /**
                 * Address styling not present in Safari and Chrome.
                 */

                dfn {
                  font-style: italic;
                }

                /**
                 * Address variable `h1` font-size and margin within `section` and `article`
                 * contexts in Firefox 4+, Safari, and Chrome.
                 */

                h1 {
                  font-size: 2em;
                  margin: 0.67em 0;
                }

                /**
                 * Address styling not present in IE 8/9.
                 */

                mark {
                  background: #ff0;
                  color: #000;
                }

                /**
                 * Address inconsistent and variable font size in all browsers.
                 */

                small {
                  font-size: 80%;
                }

                /**
                 * Prevent `sub` and `sup` affecting `line-height` in all browsers.
                 */

                sub,
                sup {
                  font-size: 75%;
                  line-height: 0;
                  position: relative;
                  vertical-align: baseline;
                }

                sup {
                  top: -0.5em;
                }

                sub {
                  bottom: -0.25em;
                }

                /* Embedded content
                   ========================================================================== */

                /**
                 * Remove border when inside `a` element in IE 8/9/10.
                 */

                img {
                  border: 0;
                }

                /**
                 * Correct overflow not hidden in IE 9/10/11.
                 */

                svg:not(:root) {
                  overflow: hidden;
                }

                /* Grouping content
                   ========================================================================== */

                /**
                 * Address margin not present in IE 8/9 and Safari.
                 */

                figure {
                  margin: 1em 40px;
                }

                /**
                 * Address differences between Firefox and other browsers.
                 */

                hr {
                  -moz-box-sizing: content-box;
                  box-sizing: content-box;
                  height: 0;
                }

                /**
                 * Contain overflow in all browsers.
                 */

                pre {
                  overflow: auto;
                }

                /**
                 * Address odd `em`-unit font size rendering in all browsers.
                 */

                code,
                kbd,
                pre,
                samp {
                  font-family: monospace, monospace;
                  font-size: 1em;
                }

                /* Forms
                   ========================================================================== */

                /**
                 * Known limitation: by default, Chrome and Safari on OS X allow very limited
                 * styling of `select`, unless a `border` property is set.
                 */

                /**
                 * 1. Correct color not being inherited.
                 *    Known issue: affects color of disabled elements.
                 * 2. Correct font properties not being inherited.
                 * 3. Address margins set differently in Firefox 4+, Safari, and Chrome.
                 */

                button,
                input,
                optgroup,
                select,
                textarea {
                  color: inherit; /* 1 */
                  font: inherit; /* 2 */
                  margin: 0; /* 3 */
                }

                /**
                 * Address `overflow` set to `hidden` in IE 8/9/10/11.
                 */

                button {
                  overflow: visible;
                }

                /**
                 * Address inconsistent `text-transform` inheritance for `button` and `select`.
                 * All other form control elements do not inherit `text-transform` values.
                 * Correct `button` style inheritance in Firefox, IE 8/9/10/11, and Opera.
                 * Correct `select` style inheritance in Firefox.
                 */

                button,
                select {
                  text-transform: none;
                }

                /**
                 * 1. Avoid the WebKit bug in Android 4.0.* where (2) destroys native `audio`
                 *    and `video` controls.
                 * 2. Correct inability to style clickable `input` types in iOS.
                 * 3. Improve usability and consistency of cursor style between image-type
                 *    `input` and others.
                 */

                button,
                html input[type="button"], /* 1 */
                input[type="reset"],
                input[type="submit"] {
                  -webkit-appearance: button; /* 2 */
                  cursor: pointer; /* 3 */
                }

                /**
                 * Re-set default cursor for disabled elements.
                 */

                button[disabled],
                html input[disabled] {
                  cursor: default;
                }

                /**
                 * Remove inner padding and border in Firefox 4+.
                 */

                button::-moz-focus-inner,
                input::-moz-focus-inner {
                  border: 0;
                  padding: 0;
                }

                /**
                 * Address Firefox 4+ setting `line-height` on `input` using `!important` in
                 * the UA stylesheet.
                 */

                input {
                  line-height: normal;
                }

                /**
                 * Its recommended that you dont attempt to style these elements.
                 * Firefoxs implementation doesnt respect box-sizing, padding, or width.
                 *
                 * 1. Address box sizing set to `content-box` in IE 8/9/10.
                 * 2. Remove excess padding in IE 8/9/10.
                 */

                input[type="checkbox"],
                input[type="radio"] {
                  box-sizing: border-box; /* 1 */
                  padding: 0; /* 2 */
                }

                /**
                 * Fix the cursor style for Chromes increment/decrement buttons. For certain
                 * `font-size` values of the `input`, it causes the cursor style of the
                 * decrement button to change from `default` to `text`.
                 */

                input[type="number"]::-webkit-inner-spin-button,
                input[type="number"]::-webkit-outer-spin-button {
                  height: auto;
                }

                /**
                 * 1. Address `appearance` set to `searchfield` in Safari and Chrome.
                 * 2. Address `box-sizing` set to `border-box` in Safari and Chrome
                 *    (include `-moz` to future-proof).
                 */

                input[type="search"] {
                  -webkit-appearance: textfield; /* 1 */
                  -moz-box-sizing: content-box;
                  -webkit-box-sizing: content-box; /* 2 */
                  box-sizing: content-box;
                }

                /**
                 * Remove inner padding and search cancel button in Safari and Chrome on OS X.
                 * Safari (but not Chrome) clips the cancel button when the search input has
                 * padding (and `textfield` appearance).
                 */

                input[type="search"]::-webkit-search-cancel-button,
                input[type="search"]::-webkit-search-decoration {
                  -webkit-appearance: none;
                }

                /**
                 * Define consistent border, margin, and padding.
                 */

                fieldset {
                  border: 1px solid #c0c0c0;
                  margin: 0 2px;
                  padding: 0.35em 0.625em 0.75em;
                }

                /**
                 * 1. Correct `color` not being inherited in IE 8/9/10/11.
                 * 2. Remove padding so people arent caught out if they zero out fieldsets.
                 */

                legend {
                  border: 0; /* 1 */
                  padding: 0; /* 2 */
                }

                /**
                 * Remove default vertical scrollbar in IE 8/9/10/11.
                 */

                textarea {
                  overflow: auto;
                }

                /**
                 * Dont inherit the `font-weight` (applied by a rule above).
                 * NOTE: the default cannot safely be changed in Chrome and Safari on OS X.
                 */

                optgroup {
                  font-weight: bold;
                }

                /* Tables
                   ========================================================================== */

                /**
                 * Remove most spacing between table cells.
                 */

                table {
                  border-collapse: collapse;
                  border-spacing: 0;
                }

                td,
                th {
                  padding: 0;
                }

                h1, h2, h3, h4, h5, h6 {
                  margin-bottom: 7px;
                }
                * {
                    font-size: 10px;
                }
                table {
                    margin: 5px 0;
                }
                .myTable td, .myTable td strong, .myTable td div {
                    font-size: 9px;
                }
                hr {
                    border: none;
                    border-bottom: 1px solid #000000;
                }
            ';

            $header .= '</style>';
            $footer = '<script>';
            $footer .= 'window.print();';
            $footer .= 'window.parent.location.reload()';
            $footer .= '</script>';
            echo $header.$content.$footer;
            exit(0);

        include(BASEPATH.'../application/libraries/PDF.php');
        
        $html2pdf = new HTML2PDF('P',array(210,330),'en');
        $html2pdf->setTestTdInOnePage(false);
        $html2pdf->WriteHTML($content);
        $html2pdf->Output('template/'.$this->getClientIP().'.pdf', 'F');
        sleep(3);
        redirect('Jurnal?bulan='.$bulanku.'&tahun='.$tahunku.'&cetak=085708094363');
    }
    
    public function savePDF(){
        $bulanku = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
        $tahunku = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)$this->db->query("select tahun_ajaran from tahun_ajaran where aktif = 'Y'")->result()[0]->tahun_ajaran;
        $saldoku = 0;
        $debitku = 0;
        $kreditku= 0;
        $kasku = $this->lastKas($bulanku, $tahunku);
        $kaskulagi = $this->nowKas($bulanku, $tahunku);

        $sekolah = $this->db->query("select * from biodata_sekolah where id = '1'")->result()[0];
        $bulan = array('Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        $content = '
            <table style="width: 99%;" align="center">
                <tr>
                    <td>
                    <table align="center" style="width: 100%;border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="width: 120mm;border-bottom:1px solid #000;" colspan="6">
                                    <div align="center">
                                        <h3>'.$sekolah->sekolah.'</h3>
                                    </div>
                                    <div align="center" style="">
                                        '.$sekolah->alamat.'
                                        <br>
                                        Telp. '.$sekolah->tlp.'
                                        <hr>
                                        <strong>Jurnal '.$bulan[$bulanku].' '.substr($tahunku, 0, 4).'</strong>
                                    </div>
                                    <br>
                                </th>
                            </tr>
                            <tr>
                                <th width="15" style="border:1px solid #000;padding: 5px; width: 5mm;"">No</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;"">Tanggal</th>
                                <th style="border:1px solid #000;padding: 5px; width: 70mm;">Keterangan</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;">Penerimaan</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;">Pengeluaran</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center" style="border:1px solid #000;padding: 5px;" align="center">1</td>
                                <td style="border:1px solid #000;padding: 5px;" align="center">01 '.$bulan[$bulanku].' '.substr($tahunku, 0, 4).'</td>
                                <td style="border:1px solid #000;padding: 5px;">Kas Bulan '.$bulan[$bulanku - 1].' '.($bulanku - 1 == 0 ? (substr($tahunku, 0, 4) - 1) : (substr($tahunku, 0, 4))).'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right">';
                                    $hasil = $kasku >= 0 ? $kasku : '0';
                                    $debitku += $hasil;
                                    $content .= number_format($hasil, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right">';
                                    $hasil = $kasku < 0 ? (-1 * $kasku) : '0';
                                    $kreditku += $hasil;
                                    $content .= number_format($hasil, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right">';
                                    $saldoku = $debitku - $kreditku;
                                    $content .= number_format($saldoku, 0, ',', '.').'</td>
                            </tr>
                            <tr>
                                <td class="text-center" style="border:1px solid #000;padding: 5px;" align="center">2</td>
                                <td style="border:1px solid #000;padding: 5px;" align="center">'.date('d').' '.$bulan[$bulanku].' '.substr($tahunku, 0, 4).'</td>
                                <td style="border:1px solid #000;padding: 5px;">Pembayaran Siswa Bulan '.$bulan[$bulanku].' '.substr($tahunku, 0, 4).'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right">';
                                    $hasil = $kaskulagi >= 0 ? $kaskulagi : '0';
                                    $debitku += $hasil;
                                    $content .= number_format($hasil, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right">';
                                    $hasil = $kaskulagi < 0 ? (-1 * $kaskulagi) : '0';
                                    $kreditku += $hasil;
                                    $content .= number_format($hasil, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right">';
                                    $saldoku = $debitku - $kreditku;
                                    $content .= number_format($saldoku, 0, ',', '.').'</td>
                            </tr>';
                            $no = 3;
                            $jurnal = $this->db->query("select * from keuangan_jurnal where year(tanggal) = '".(substr($tahunku, 0, 4))."' and month(tanggal) = '".$bulanku."' order by tanggal, id_jurnal, keterangan");
                            foreach($jurnal->result() as $jnl):
                            $debitku += $jnl->debit;
                            $kreditku += $jnl->kredit;
                            $saldoku = $debitku - $kreditku;
                            $content .= '<tr>
                                <td class="text-center" style="border:1px solid #000;padding: 5px;" align="center">'.$no.'</td>
                                <td style="border:1px solid #000;padding: 5px;" align="center">'.$this->toIndo($jnl->tanggal).'</td>
                                <td style="border:1px solid #000;padding: 5px;">'.$jnl->keterangan.'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">'.number_format($jnl->debit, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">'.number_format($jnl->kredit, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">'.number_format($saldoku, 0, ',', '.').'</td>
                            </tr>';
                            $no += 1;
                            endforeach;
                            $content .= '<tr class="alert-info">
                                <td colspan="3" style="border:1px solid #000;padding: 5px;"><strong>Total</strong></td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">';
                                    $content .=  number_format($debitku, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">';
                                    $content .=  number_format($kreditku, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">';
                                    $content .=  number_format($saldoku, 0, ',', '.').'</td>
                            </tr>';
           $content .= '</tbody>
                    </table>
                    <br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table align="right">
                            <tr>
                                <td style="">( Petugas )</td>
                            </tr>
                            <tr>
                                <td style="">'.$sekolah->kabupaten.', '.$this->toIndo(date('Y-m-d')).'</td>
                            </tr>
                            <tr>
                                <td><br><br></td>
                            </tr>
                            <tr>
                                <td style="">'.$this->session->userdata('nama_lengkap').'</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>';

            $header = '<style>';
            $header .= '
                @page { size: landscape; }
                /* reset.css */
                /*! normalize.css v3.0.2 | MIT License | git.io/normalize */

                /**
                 * 1. Set default font family to sans-serif.
                 * 2. Prevent iOS text size adjust after orientation change, without disabling
                 *    user zoom.
                 */

                html {
                  font-family: sans-serif; /* 1 */
                  -ms-text-size-adjust: 100%; /* 2 */
                  -webkit-text-size-adjust: 100%; /* 2 */
                }

                /**
                 * Remove default margin.
                 */

                body {
                  margin: 0;
                }

                /* HTML5 display definitions
                   ========================================================================== */

                /**
                 * Correct `block` display not defined for any HTML5 element in IE 8/9.
                 * Correct `block` display not defined for `details` or `summary` in IE 10/11
                 * and Firefox.
                 * Correct `block` display not defined for `main` in IE 11.
                 */

                article,
                aside,
                details,
                figcaption,
                figure,
                footer,
                header,
                hgroup,
                main,
                menu,
                nav,
                section,
                summary {
                  display: block;
                }

                /**
                 * 1. Correct `inline-block` display not defined in IE 8/9.
                 * 2. Normalize vertical alignment of `progress` in Chrome, Firefox, and Opera.
                 */

                audio,
                canvas,
                progress,
                video {
                  display: inline-block; /* 1 */
                  vertical-align: baseline; /* 2 */
                }

                /**
                 * Prevent modern browsers from displaying `audio` without controls.
                 * Remove excess height in iOS 5 devices.
                 */

                audio:not([controls]) {
                  display: none;
                  height: 0;
                }

                /**
                 * Address `[hidden]` styling not present in IE 8/9/10.
                 * Hide the `template` element in IE 8/9/11, Safari, and Firefox < 22.
                 */

                [hidden],
                template {
                  display: none;
                }

                /* Links
                   ========================================================================== */

                /**
                 * Remove the gray background color from active links in IE 10.
                 */

                a {
                  background-color: transparent;
                }

                /**
                 * Improve readability when focused and also mouse hovered in all browsers.
                 */

                a:active,
                a:hover {
                  outline: 0;
                }

                /* Text-level semantics
                   ========================================================================== */

                /**
                 * Address styling not present in IE 8/9/10/11, Safari, and Chrome.
                 */

                abbr[title] {
                  border-bottom: 1px dotted;
                }

                /**
                 * Address style set to `bolder` in Firefox 4+, Safari, and Chrome.
                 */

                b,
                strong {
                  font-weight: bold;
                }

                /**
                 * Address styling not present in Safari and Chrome.
                 */

                dfn {
                  font-style: italic;
                }

                /**
                 * Address variable `h1` font-size and margin within `section` and `article`
                 * contexts in Firefox 4+, Safari, and Chrome.
                 */

                h1 {
                  font-size: 2em;
                  margin: 0.67em 0;
                }

                /**
                 * Address styling not present in IE 8/9.
                 */

                mark {
                  background: #ff0;
                  color: #000;
                }

                /**
                 * Address inconsistent and variable font size in all browsers.
                 */

                small {
                  font-size: 80%;
                }

                /**
                 * Prevent `sub` and `sup` affecting `line-height` in all browsers.
                 */

                sub,
                sup {
                  font-size: 75%;
                  line-height: 0;
                  position: relative;
                  vertical-align: baseline;
                }

                sup {
                  top: -0.5em;
                }

                sub {
                  bottom: -0.25em;
                }

                /* Embedded content
                   ========================================================================== */

                /**
                 * Remove border when inside `a` element in IE 8/9/10.
                 */

                img {
                  border: 0;
                }

                /**
                 * Correct overflow not hidden in IE 9/10/11.
                 */

                svg:not(:root) {
                  overflow: hidden;
                }

                /* Grouping content
                   ========================================================================== */

                /**
                 * Address margin not present in IE 8/9 and Safari.
                 */

                figure {
                  margin: 1em 40px;
                }

                /**
                 * Address differences between Firefox and other browsers.
                 */

                hr {
                  -moz-box-sizing: content-box;
                  box-sizing: content-box;
                  height: 0;
                }

                /**
                 * Contain overflow in all browsers.
                 */

                pre {
                  overflow: auto;
                }

                /**
                 * Address odd `em`-unit font size rendering in all browsers.
                 */

                code,
                kbd,
                pre,
                samp {
                  font-family: monospace, monospace;
                  font-size: 1em;
                }

                /* Forms
                   ========================================================================== */

                /**
                 * Known limitation: by default, Chrome and Safari on OS X allow very limited
                 * styling of `select`, unless a `border` property is set.
                 */

                /**
                 * 1. Correct color not being inherited.
                 *    Known issue: affects color of disabled elements.
                 * 2. Correct font properties not being inherited.
                 * 3. Address margins set differently in Firefox 4+, Safari, and Chrome.
                 */

                button,
                input,
                optgroup,
                select,
                textarea {
                  color: inherit; /* 1 */
                  font: inherit; /* 2 */
                  margin: 0; /* 3 */
                }

                /**
                 * Address `overflow` set to `hidden` in IE 8/9/10/11.
                 */

                button {
                  overflow: visible;
                }

                /**
                 * Address inconsistent `text-transform` inheritance for `button` and `select`.
                 * All other form control elements do not inherit `text-transform` values.
                 * Correct `button` style inheritance in Firefox, IE 8/9/10/11, and Opera.
                 * Correct `select` style inheritance in Firefox.
                 */

                button,
                select {
                  text-transform: none;
                }

                /**
                 * 1. Avoid the WebKit bug in Android 4.0.* where (2) destroys native `audio`
                 *    and `video` controls.
                 * 2. Correct inability to style clickable `input` types in iOS.
                 * 3. Improve usability and consistency of cursor style between image-type
                 *    `input` and others.
                 */

                button,
                html input[type="button"], /* 1 */
                input[type="reset"],
                input[type="submit"] {
                  -webkit-appearance: button; /* 2 */
                  cursor: pointer; /* 3 */
                }

                /**
                 * Re-set default cursor for disabled elements.
                 */

                button[disabled],
                html input[disabled] {
                  cursor: default;
                }

                /**
                 * Remove inner padding and border in Firefox 4+.
                 */

                button::-moz-focus-inner,
                input::-moz-focus-inner {
                  border: 0;
                  padding: 0;
                }

                /**
                 * Address Firefox 4+ setting `line-height` on `input` using `!important` in
                 * the UA stylesheet.
                 */

                input {
                  line-height: normal;
                }

                /**
                 * Its recommended that you dont attempt to style these elements.
                 * Firefoxs implementation doesnt respect box-sizing, padding, or width.
                 *
                 * 1. Address box sizing set to `content-box` in IE 8/9/10.
                 * 2. Remove excess padding in IE 8/9/10.
                 */

                input[type="checkbox"],
                input[type="radio"] {
                  box-sizing: border-box; /* 1 */
                  padding: 0; /* 2 */
                }

                /**
                 * Fix the cursor style for Chromes increment/decrement buttons. For certain
                 * `font-size` values of the `input`, it causes the cursor style of the
                 * decrement button to change from `default` to `text`.
                 */

                input[type="number"]::-webkit-inner-spin-button,
                input[type="number"]::-webkit-outer-spin-button {
                  height: auto;
                }

                /**
                 * 1. Address `appearance` set to `searchfield` in Safari and Chrome.
                 * 2. Address `box-sizing` set to `border-box` in Safari and Chrome
                 *    (include `-moz` to future-proof).
                 */

                input[type="search"] {
                  -webkit-appearance: textfield; /* 1 */
                  -moz-box-sizing: content-box;
                  -webkit-box-sizing: content-box; /* 2 */
                  box-sizing: content-box;
                }

                /**
                 * Remove inner padding and search cancel button in Safari and Chrome on OS X.
                 * Safari (but not Chrome) clips the cancel button when the search input has
                 * padding (and `textfield` appearance).
                 */

                input[type="search"]::-webkit-search-cancel-button,
                input[type="search"]::-webkit-search-decoration {
                  -webkit-appearance: none;
                }

                /**
                 * Define consistent border, margin, and padding.
                 */

                fieldset {
                  border: 1px solid #c0c0c0;
                  margin: 0 2px;
                  padding: 0.35em 0.625em 0.75em;
                }

                /**
                 * 1. Correct `color` not being inherited in IE 8/9/10/11.
                 * 2. Remove padding so people arent caught out if they zero out fieldsets.
                 */

                legend {
                  border: 0; /* 1 */
                  padding: 0; /* 2 */
                }

                /**
                 * Remove default vertical scrollbar in IE 8/9/10/11.
                 */

                textarea {
                  overflow: auto;
                }

                /**
                 * Dont inherit the `font-weight` (applied by a rule above).
                 * NOTE: the default cannot safely be changed in Chrome and Safari on OS X.
                 */

                optgroup {
                  font-weight: bold;
                }

                /* Tables
                   ========================================================================== */

                /**
                 * Remove most spacing between table cells.
                 */

                table {
                  border-collapse: collapse;
                  border-spacing: 0;
                }

                td,
                th {
                  padding: 0;
                }

                h1, h2, h3, h4, h5, h6 {
                  margin-bottom: 0px;
                }
                * {
                    font-size: 10px;
                }
                table {
                    margin: 5px 0;
                }
                .myTable td, .myTable td strong, .myTable td div {
                    font-size: 9px;
                }
                hr {
                    border: none;
                    border-bottom: 1px solid #000000;
                }
            ';
            $header .= '</style>';
            $footer = '<script>';
            $footer .= 'window.print();';
            $footer .= '</script>';
            echo $header.$content.$footer;
            exit(0);

        include(BASEPATH.'../application/libraries/PDF.php');
        
        $html2pdf = new HTML2PDF('P',array(210,330),'en');
        $html2pdf->setTestTdInOnePage(false);
        $html2pdf->WriteHTML($content);
        $html2pdf->Output('template/'.$this->getClientIP().'.pdf', 'F');
        sleep(3);
        redirect('Jurnal?bulan='.$bulanku.'&tahun='.$tahunku.'&cetak=085708094363');
    }
    
    public function delete($id = ''){
        if($id != '' && $this->session->userdata('status') == 'admin'){
            $tanggal = explode('-', $this->db->query("select tanggal from keuangan_jurnal where id_jurnal = '".$id."'")->result()[0]->tanggal);
            $this->db->where('id_jurnal', $id);
            $query = $this->db->delete('keuangan_jurnal');
            if($query)
                $xxx = 'Data berhasil dihapus';
            else
                $xxx = 'Data gagal dihapus';
            $this->session->set_flashdata('pesan', $xxx);
            redirect('Jurnal?bulan='.$tanggal[1].'&tahun='.$tanggal[0]);
        }
        redirect('Jurnal');
    }
}

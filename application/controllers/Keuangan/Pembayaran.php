<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->session->set_userdata('title', 'Pembayaran Siswa');
        $this->load->library('Midtrans');

        if ($this->session->userdata('status') == 'siswa' || $this->session->userdata('status') == 'wali') {
            $this->data_init['nis']         = substr($this->session->userdata('id_pengguna'),1);
            $this->data_init['data_siswa']  = $this->db->query("SELECT * FROM data_siswa WHERE nis = '".$this->data_init['nis']."'")->result()[0];
            $this->data_init['kelas']       = $this->db->query("SELECT * FROM kelas WHERE id_kelas = '".$this->data_init['data_siswa']->status_kelas."'")->result()[0];
        }
    }

    public function index(){
        if ($this->session->userdata('status') == 'admin') {
            if(isset($_GET['gakbayaran'])){
                $idnya = $_GET['gakbayaran'];
                $bayaran = $this->db->query("SELECT * from keuangan_siswa_bulanan where id_siswa_bulanan = '".$_GET['id_pembayaran']."'")->result()[0];
                $datanya = array(
                    'tahun_ajaran' 	=> $bayaran->tahun_ajaran,
                    'nis' 			=> $bayaran->nis,
                    'id_jenis' 		=> $bayaran->id_jenis,
                    'b'.$idnya 		=> 0,
                    'l'.$idnya 		=> 0,
                    'p'.$idnya 		=> $this->session->userdata('id_pengguna'),
                );
                
                $this->db->where('tahun_ajaran',  $bayaran->tahun_ajaran);
                $this->db->where('nis',  $bayaran->nis);
                $this->db->where('id_jenis', $bayaran->id_jenis);
                
                $cek = $this->db->get('pembayaran_siswa_bulanan');
                if($cek->num_rows() == 1){
                    $idnya = $cek->result()[0]->id_pembayaran;
                    $this->db->where('id_pembayaran', $idnya);
                    $this->db->update('pembayaran_siswa_bulanan', $datanya);
                }else{
                    $this->db->insert('pembayaran_siswa_bulanan', $datanya);
                }
                redirect('Keuangan/Pembayaran?listKelas='.$_GET['listKelas'].'&listSiswa='.$_GET['listSiswa']);
            }
            $this->load_content_admin('keuangan/pembayaran/bayar');
        }
        else{
            $nis = substr($this->session->userdata('id_pengguna'),1);
            $data_siswa = $this->data_init['data_siswa'];
            $kelas      = $this->data_init['kelas'];
            $content = array(
                'nis'               => $nis,
                'nama'              => $data_siswa->nama,
                'kelas'             => $kelas->kelas,
                'id_kelas'          => $kelas->id_kelas,
                'id_program_studi'  => $data_siswa->program_studi,
                'kode_kelas'        => $data_siswa->kode_kelas,
            );
            $this->load_content_admin('keuangan/pembayaran/siswabayar', $content);
        }
    }

    public function proses(){
        $callback = array(
            'Data'      => null,
            'Message'   => null,
            'Error'     => false,
            'Redirect'  => null
        );

        if ($_POST) {
            $via = $this->input->post('via', true);
            $nis = $this->input->post('nis', true);
            $kls = $this->input->post('kelas', true);

            $query = "SELECT * FROM data_siswa WHERE nis = '$nis' ";
            $DataSiswa = $this->M_wsbangun->getData_by_query('default', $query);
            if ($via == 'cash') {
                if( isset($nis) && isset($kls) ){
                    foreach($_POST['bayaran'] as $key=>$val){
                        if($_POST['jenis'][$key] == 'bulanan'){
                            $idnya = $_POST['bayaran'][$key];
                            $datanya = array(
                                'tahun_ajaran'  => substr($_POST['tahun'][$key], 0, 4).'1',
                                'nis'           => $nis,
                                'id_jenis'      => $_POST['id_jenis'][$key],
                                'b'.$idnya      => date('Y-m-d'),
                                'l'.$idnya      => $_POST['jumlah'][$key],
                                'p'.$idnya      => $this->session->userdata('id_pengguna'),
                            );

                            // cek data
                            $this->db->where('tahun_ajaran',  substr($_POST['tahun'][$key], 0, 4).'1');
                            $this->db->where('nis',  $nis);
                            $this->db->where('id_jenis', $_POST['id_jenis'][$key]);

                            $cek = $this->db->get('pembayaran_siswa_bulanan');
                            if($cek->num_rows() == 1){
                                $idnya = $cek->result()[0]->id_pembayaran;
                                $this->db->where('id_pembayaran', $idnya);
                                $this->db->update('pembayaran_siswa_bulanan', $datanya);
                            }else{
                                $this->db->insert('pembayaran_siswa_bulanan', $datanya);
                            }
                        }

                        if($_POST['jenis'][$key] == 'bebas'){
                            $datanya = array(
                                'tahun_ajaran' => substr($_POST['tahun'][$key], 0, 4).'1',
                                'nis' => $_POST['nis'],
                                'id_jenis' => $_POST['id_jenis'][$key],
                                'tanggal' => date('Y-m-d'),
                                'cicilan' => $_POST['jumlah'][$key],
                                'petugas' => $this->session->userdata('id_pengguna'),
                            );
                            $this->db->insert('pembayaran_siswa_bebas', $datanya);
                        }
                    }
                    $callback['Redirect'] = 'Keuangan/Pembayaran';
                }
                else{
                    $callback['Redirect'] = 'Keuangan/Pembayaran';
                }
            }
            else{
                $bayaran = $this->input->post('bayaran', true);
                $gross_amount = 0;
                foreach ($_POST['jumlah'] as $value) {
                    $gross_amount += $value;
                }

                $param = array(
                    'transaction_details' => array(
                        'order_id' => rand(),
                        'gross_amount' => $gross_amount
                    ),
                    'expiry' => array(
                        'start_time'    => date("Y-m-d H:i:s O"),
                        "unit"          => "day",
                        "duration"      => 1
                    ),
                    'customer_details' => array(
                        'first_name'    => $DataSiswa[0]->nama
                    ),
                    'enabled_payment' => [
                        'cimb_clicks','bca_klikbca', 'bca_klikpay', 'bri_epay', 'echannel', 'permata_va','bca_va', 'bni_va', 'bri_va', 'other_va', 'gopay', 'danamon_online'
                    ],
                    'callbacks' => array(
                        'finish' => base_url('Keuangan/Pembayaran')
                    )
                );

                foreach($_POST['bayaran'] as $key=>$val){
                    $idnya = $_POST['bayaran'][$key];
                    $param['item_details'][] = array(
                        'id'            => 'b'.$idnya,
                        'price'         => $_POST['jumlah'][$key],
                        'quantity'      => 1,
                        'name'          => 'Pembayaran '.$_POST['jenis'][$key],
                        'category'      => $_POST['jenis'][$key],
                        'merchant_name' => $_POST['id_jenis'][$key],
                        'brand'         => substr($_POST['tahun'][$key], 0, 4).'1'
                    );
                }

                $snapToken = \Midtrans\Snap::getSnapToken($param);

                $midtrans_onlinepay = array(
                    'nis'                   => $nis,
                    'order_id'              => $param['transaction_details']['order_id'],
                    'transaction_id'        => $snapToken,
                    'transaction_time'      => date('Y-m-d H:i:s'),
                    'transaction_status'    => 'pending',
                    'item'                  => json_encode($param['item_details']),
                    'gross_amount'          => $gross_amount,
                    'petugas'               => $this->session->userdata('id_pengguna'),
                );
                $insert = $this->M_wsbangun->insertData('default', 'midtrans_onlinepay', $midtrans_onlinepay);
                $callback['Redirect'] = $snapToken;
            }
        }
        else{
            $callback['Error'] = true;
            $callback['Message'] = "Invalid Method";
        }

        echo json_encode($callback);
    }

    function toIndo($tgl, $bulan = 0){
        $tgl = explode('-', $tgl);
        $bln = array(null, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        if($bulan == 1)
            return $tgl[2].' '.($bln[(int)$tgl[1]]).' '.$tgl[0];
        return $tgl[2].'-'.$tgl[1].'-'.$tgl[0];
    }

    function hapuscicilan(){
        if(isset($_POST['hapus'])){
            foreach($_POST['hapus'] as $id){
                $idnya = $id;
                $this->db->where('id_pembayaran_bebas', $idnya);
                $this->db->delete('pembayaran_siswa_bebas');
            }
        }
        redirect('Keuangan/Pembayaran');
    }

    function cicilan(){
        ?>
        <table class="table table-hover table-condensed table-striped">
            <thead>
                <tr>
                    <th width="35">No</th>
                    <th>Tanggal</th>
                    <th>Petugas</th>
                    <th width="150">Cicilan</th>
                    <th width="35">Hapus</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $cicilan = $this->db->query("SELECT id_pembayaran_bebas, cicilan, tanggal, petugas from pembayaran_siswa_bebas where tahun_ajaran = '".$_GET['tahun']."' and nis = '".$_GET['nis']."' and id_jenis = '".$_GET['id_jenis']."' order by tanggal");
                $no = 1;
                $xxxBayar = $_GET['bayar'];
                if (count($cicilan->result()) > 0) {
                    foreach($cicilan->result() as $ccl){
                        $xxxBayar -= $ccl->cicilan;
                        echo '
                        <tr>
                            <td>'.$no.'</td>
                            <td>'.$this->toIndo($ccl->tanggal).'</td>
                            <td>'.$this->db->query('SELECT nama_lengkap from pengguna where id_pengguna = \''.($ccl->petugas).'\'')->result()[0]->nama_lengkap.'</td>
                            <td>Rp. '.number_format($ccl->cicilan, 2, ',','.').'</td>
                            <td align="center">';
                        if($this->session->userdata('status') == 'admin'):
                            echo '<input type="checkbox" name="hapus[]" value="'.$ccl->id_pembayaran_bebas.'">';
                        else:
                            echo '#';
                        endif;
                        echo '</td>
                        </tr>
                        ';
                        $no += 1;
                    }
                }
            ?>
            <tr><td colspan="5"></td></tr>
            <tr>
                <td colspan="3"><strong>Jumlah yang harus dicicil</strong></td>
                <td colspan="2"><strong>Rp. <?=number_format($xxxBayar, 2, ',','.');?></strong></td>
            </tr>
            </tbody>
        </table>
        <?php
    }

    function cetakTunggaan(){
        if (isset($_GET['nis'])):
            $nis = $_GET['nis'];
        elseif (isset($this->data_init['nis'])) :
            $nis = $this->data_init['nis'];
        else :
            $nis = '';
        endif;

        $sekolah = $this->db->query("SELECT * from biodata_sekolah where id = '1'")->result()[0];
        $bayaran = $this->db->query("SELECT keuangan_siswa_bulanan.*, kelas.kelas from keuangan_siswa_bulanan inner join kelas on keuangan_siswa_bulanan.id_kelas = kelas.id_kelas inner join tahun_ajaran on keuangan_siswa_bulanan.tahun_ajaran = tahun_ajaran.tahun_ajaran and tahun_ajaran.aktif = 'Y' where nis = '".$nis."'")->result()[0];
        $cekin = $this->db->query("SELECT * from pembayaran_siswa_bulanan where tahun_ajaran = '".$bayaran->tahun_ajaran."' and nis = '".$bayaran->nis."' and id_jenis = '".$bayaran->id_jenis."'");
        // $kelas = explode('-', $_GET['listKelas']);
        $content = '
                <table style="width: 99%;" align="center">
                    <tr>
                        <td>
                            <div align="center">
                                <h3>'.$sekolah->sekolah.'</h3>
                            </div>
                        </td>
                    <tr>
                    </tr>
                        <td>
                            <div align="center" style="margin-top: -7px;">
                                '.$sekolah->alamat.'
                                <br>
                                Telp. '.$sekolah->tlp.'
                                <hr>
                                <strong>Data Keuangan Siswa</strong>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0">
                                <tr>
                                    <td width="100"><strong>NIS</strong></td>
                                    <td>: '.$bayaran->nis.'</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama</strong></td>
                                    <td>: '.$this->db->query("select nama from data_siswa where nis = '".$_GET['nis']."'")->result()[0]->nama.'</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelas</strong></td>
                                    <td>: '.$bayaran->kelas.' '.$bayaran->id_program_studi.' '.$bayaran->kode_kelas.'</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>';
                            $bulan = array(null, 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni');

                    $content .= '<table class="myTable" align="center" style="width:100%;border-collapse: collapse;">';
                                $bayaranTotal = 0;
                                for($i=1;$i<=6;$i++){
                                    $testx1 = 'b'.$i;
                                    $testx2 = 'b'.(6 + $i);
                                    $content .= '<tr>';
                                        $content .= '<td style="border:1px solid #000;padding: 5px;width:100px">
                                                <strong>'.$bulan[$i].'</strong>
                                              </td>';
                                        $hasil = ''.number_format($bayaran->$testx1, 0, ',', '.');
                                        $bayaranTotal += $bayaran->$testx1;
                                        if($cekin->num_rows() == 0){
                                            //$content .= 'Belum Lunas';
                                        }else{
                                            $hasilku = $cekin->result()[0]->$testx1;
                                            if($hasilku != '0000-00-00'){
                                                $bayaranTotal -= $bayaran->$testx1;
                                                $hasil = $this->toIndo($hasilku);
                                            }
                                        }
                                        $content .= '<td style="border:1px solid #000;padding: 5px;width:140px">
                                                <div align="right">'.$hasil.'</div>
                                              </td>';
                                        $content .= '<td style="border:1px solid #000;padding: 5px;width:100px">
                                                <strong>'.$bulan[6 + $i].'</strong>
                                              </td>';
                                        $hasil = ''.number_format($bayaran->$testx2, 0, ',', '.');
                                        $bayaranTotal += $bayaran->$testx2;
                                        if($cekin->num_rows() == 0){
                                            //$content .= 'Belum Lunas';
                                        }else{
                                            $hasilku = $cekin->result()[0]->$testx2;
                                            if($hasilku != '0000-00-00'){
                                                $bayaranTotal -= $bayaran->$testx2;
                                                $hasil = $this->toIndo($hasilku);
                                            }
                                        }
                                        $content .= '<td style="border:1px solid #000;padding: 5px;width:140px">
                                                <div align="right">'.$hasil.'</div>
                                              </td>';
                                    $content .= '</tr>';
                                }
                           $content .= '</table>
                        </td>
                    </tr>
                    <tr>
                        <td>';
                    $tunggaan = $this->db->query("
                        SELECT
                            keuangan_siswa_bulanan.id_siswa_bulanan,
                            keuangan_siswa_bulanan.id_jenis,
                            keuangan_jenis.tahun_ajaran,
                            keuangan_jenis.nama_pembayaran,
                            keuangan_pos.id_pos,
                            keuangan_pos.pos,
                            kelas.kelas,
                            keuangan_siswa_bulanan.id_program_studi,
                            keuangan_siswa_bulanan.kode_kelas
                        FROM
                            keuangan_siswa_bulanan
                            INNER JOIN keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
                            INNER JOIN data_siswa ON data_siswa.nis = keuangan_siswa_bulanan.nis
                            INNER JOIN keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
                            INNER JOIN kelas ON kelas.id_kelas = keuangan_siswa_bulanan.id_kelas
                        WHERE
                            data_siswa.aktif = 'Y' AND
                            data_siswa.lulus = 'T' AND
                            keuangan_siswa_bulanan.nis = '".$bayaran->nis."'
                        GROUP BY
                            keuangan_jenis.id_jenis
                        ORDER BY
                            keuangan_jenis.pos,
                            keuangan_jenis.id_jenis
                    ");

                    $content .= '<table class="myTable">';
                    foreach($tunggaan->result() as $jn){
                        $content .= '<tr>
                                        <td><strong>'.$jn->pos.' '.substr($jn->tahun_ajaran, 0, 4).'</strong></td>
                                        <td>: </td><td align="right" style="padding-left: 5px;">';
                                            $ygHarusDibayar = $this->db->query("SELECT
                                                                                keuangan_siswa_bulanan.tahun_ajaran,
                                                                                keuangan_jenis.tipe,
                                                                                (keuangan_siswa_bulanan.b1+
                                                                                keuangan_siswa_bulanan.b2+
                                                                                keuangan_siswa_bulanan.b3+
                                                                                keuangan_siswa_bulanan.b4+
                                                                                keuangan_siswa_bulanan.b5+
                                                                                keuangan_siswa_bulanan.b6+
                                                                                keuangan_siswa_bulanan.b7+
                                                                                keuangan_siswa_bulanan.b8+
                                                                                keuangan_siswa_bulanan.b9+
                                                                                keuangan_siswa_bulanan.b10+
                                                                                keuangan_siswa_bulanan.b11+
                                                                                keuangan_siswa_bulanan.b12) as jmlPembayaran
                                                                            FROM
                                                                                keuangan_siswa_bulanan
                                                                                INNER JOIN keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
                                                                                INNER JOIN data_siswa ON data_siswa.nis = keuangan_siswa_bulanan.nis
                                                                                INNER JOIN keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
                                                                                INNER JOIN kelas ON kelas.id_kelas = keuangan_siswa_bulanan.id_kelas
                                                                            WHERE
                                                                                data_siswa.aktif = 'Y' AND
                                                                                data_siswa.lulus = 'T' AND
                                                                                data_siswa.nis = '".$bayaran->nis."' AND
                                                                                keuangan_siswa_bulanan.id_jenis = '".$jn->id_jenis."'
                                                                            ");
                                        $ygHarusDibayarNya = $ygHarusDibayar->result()[0];
                                        $content .= number_format($ygHarusDibayarNya->jmlPembayaran, 0, ',', '.').'</td><td style="padding: 1px 0 1px 20px;"><strong>Tunggaan</strong></td><td>: </td><td align="right" style="padding-left: 5px;">';
                                        if($ygHarusDibayar->num_rows() > 0){
                                            $ygHarusDibayarNyaOk = $ygHarusDibayarNya->jmlPembayaran;
                                            if($ygHarusDibayarNya->tipe == 'Bulanan'){
                                                $hugf = $this->db->query("SELECT
                                                    (pembayaran_siswa_bulanan.l1+
                                                    pembayaran_siswa_bulanan.l2+
                                                    pembayaran_siswa_bulanan.l3+
                                                    pembayaran_siswa_bulanan.l4+
                                                    pembayaran_siswa_bulanan.l5+
                                                    pembayaran_siswa_bulanan.l6+
                                                    pembayaran_siswa_bulanan.l7+
                                                    pembayaran_siswa_bulanan.l8+
                                                    pembayaran_siswa_bulanan.l9+
                                                    pembayaran_siswa_bulanan.l10+
                                                    pembayaran_siswa_bulanan.l11+
                                                    pembayaran_siswa_bulanan.l12) as jmlUtang
                                                FROM
                                                    pembayaran_siswa_bulanan
                                                WHERE
                                                    pembayaran_siswa_bulanan.tahun_ajaran = '".$ygHarusDibayarNya->tahun_ajaran."' AND
                                                    pembayaran_siswa_bulanan.nis = '".$bayaran->nis."' AND
                                                    pembayaran_siswa_bulanan.id_jenis = '".$jn->id_jenis."'
                                                ");
                                                if($hugf->num_rows() > 0){
                                                    $ygHarusDibayarNyaOk -= $hugf->result()[0]->jmlUtang;
                                                }
                                            }
                                            if($ygHarusDibayarNya->tipe == 'Bebas'){
                                                $hugf = $this->db->query("SELECT
                                                    sum(cicilan) as jmlUtang
                                                FROM
                                                    pembayaran_siswa_bebas
                                                WHERE
                                                    pembayaran_siswa_bebas.tahun_ajaran = '".$ygHarusDibayarNya->tahun_ajaran."' AND
                                                    pembayaran_siswa_bebas.nis = '".$bayaran->nis."' AND
                                                    pembayaran_siswa_bebas.id_jenis = '".$jn->id_jenis."'
                                                ");
                                                if($hugf->num_rows() > 0){
                                                    $ygHarusDibayarNyaOk -= $hugf->result()[0]->jmlUtang;
                                                }
                                            }
                                            $content .= number_format($ygHarusDibayarNyaOk, 0, ',', '.');
                                        }else{
                                            $content .= number_format(0, 0, ',', '.');
                                        }
                           $content .= '</td>
                                    </tr>';
                    }
                    $content .= '</table>';
                    $content .= '<table align="right">
                                    <tr>
                                        <td style="">( Petugas )</td>
                                    </tr>
                                    <tr>
                                        <td style="">'.$sekolah->kabupaten.', '.$this->toIndo(date('Y-m-d'), 1).'</td>
                                    </tr>
                                    <tr>
                                        <td><br></td>
                                    </tr>
                                    <tr>
                                        <td style="">'.$this->session->userdata('nama_lengkap').'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                </table>
        ';

        $header = '<style>';
        $header .= '
			html {
			  font-family: sans-serif; /* 1 */
			  -ms-text-size-adjust: 100%; /* 2 */
			  -webkit-text-size-adjust: 100%; /* 2 */
			}

			body {
			  margin: 0;
			}

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

			audio,
			canvas,
			progress,
			video {
			  display: inline-block; /* 1 */
			  vertical-align: baseline; /* 2 */
			}

			audio:not([controls]) {
			  display: none;
			  height: 0;
			}

			[hidden],
			template {
			  display: none;
			}

			a {
			  background-color: transparent;
			}

			a:active,
			a:hover {
			  outline: 0;
			}

			abbr[title] {
			  border-bottom: 1px dotted;
			}

			b,
			strong {
			  font-weight: bold;
			}

			dfn {
			  font-style: italic;
			}

			h1 {
			  font-size: 2em;
			  margin: 0.67em 0;
			}

			mark {
			  background: #ff0;
			  color: #000;
			}

			small {
			  font-size: 80%;
			}

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

			img {
			  border: 0;
			}

			svg:not(:root) {
			  overflow: hidden;
			}

			figure {
			  margin: 1em 40px;
			}

			hr {
			  -moz-box-sizing: content-box;
			  box-sizing: content-box;
			  height: 0;
			}

			pre {
			  overflow: auto;
			}

			code,
			kbd,
			pre,
			samp {
			  font-family: monospace, monospace;
			  font-size: 1em;
			}

			button,
			input,
			optgroup,
			select,
			textarea {
			  color: inherit; /* 1 */
			  font: inherit; /* 2 */
			  margin: 0; /* 3 */
			}

			button {
			  overflow: visible;
			}

			button,
			select {
			  text-transform: none;
			}

			button,
			html input[type="button"], /* 1 */
			input[type="reset"],
			input[type="submit"] {
			  -webkit-appearance: button; /* 2 */
			  cursor: pointer; /* 3 */
			}

			button[disabled],
			html input[disabled] {
			  cursor: default;
			}

			button::-moz-focus-inner,
			input::-moz-focus-inner {
			  border: 0;
			  padding: 0;
			}

			input {
			  line-height: normal;
			}

			input[type="checkbox"],
			input[type="radio"] {
			  box-sizing: border-box; /* 1 */
			  padding: 0; /* 2 */
			}

			input[type="number"]::-webkit-inner-spin-button,
			input[type="number"]::-webkit-outer-spin-button {
			  height: auto;
			}

			input[type="search"] {
			  -webkit-appearance: textfield; /* 1 */
			  -moz-box-sizing: content-box;
			  -webkit-box-sizing: content-box; /* 2 */
			  box-sizing: content-box;
			}

			input[type="search"]::-webkit-search-cancel-button,
			input[type="search"]::-webkit-search-decoration {
			  -webkit-appearance: none;
			}

			fieldset {
			  border: 1px solid #c0c0c0;
			  margin: 0 2px;
			  padding: 0.35em 0.625em 0.75em;
			}

			legend {
			  border: 0; /* 1 */
			  padding: 0; /* 2 */
			}

			textarea {
			  overflow: auto;
			}

			optgroup {
			  font-weight: bold;
			}

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
        $footer .= '</script>';

        echo $header.$content.$footer;
        exit(0);
        redirect('Keuangan/Pembayaran');
    }
}
<!-- FUNC -->
    <?php
        function lastKas($obj, $bulan, $tahun){
            $kasku = 0;
            $bulan = $bulan - 1;
            $tahun = substr($tahun, 0, 4);

            // ambil dari pembayaran bebas
            $jmlPembayaranBebas = $obj->db->query("select sum(cicilan) as jumlah from pembayaran_siswa_bebas where tanggal < last_day('".$tahun."-".$bulan."-1')")->result()[0]->jumlah;
            $kasku += $jmlPembayaranBebas;

            // ambil dari jurnal
            $jmlKeuanganJurnal = $obj->db->query("select sum(debit - kredit) as jumlahKas from keuangan_jurnal where tanggal < last_day('".$tahun."-".$bulan."-1')")->result()[0]->jumlahKas;
            
            $kasku += $jmlKeuanganJurnal;
            // ambil dari bulanan
            $jmlPembayaranBulanan = 0;
            $cariPembayar = $obj->db->query("select tahun_ajaran, nis, id_jenis, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12 from pembayaran_siswa_bulanan");
            foreach($cariPembayar->result() as $row1){
                if($row1->b1 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b1 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b1."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b1")->result()[0]->b1;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b2 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b2 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b2."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b2")->result()[0]->b2;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b3 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b3 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b3."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b3")->result()[0]->b3;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b4 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b4 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b4."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b4")->result()[0]->b4;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b5 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b5 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b5."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b5")->result()[0]->b5;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b6 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b6 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b6."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b6")->result()[0]->b6;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b7 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b7 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b7."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b7")->result()[0]->b7;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b8 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b8 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b8."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b8")->result()[0]->b8;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b9 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b9 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b9."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b9")->result()[0]->b9;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b10 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b10 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b10."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b10")->result()[0]->b10;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b11 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b11 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b11."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b11")->result()[0]->b11;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
                if($row1->b12 != '0000-00-00'){
                    $cariPembayarLagi = $obj->db->query("select b12 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b12."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b12")->result()[0]->b12;
                    $jmlPembayaranBulanan += $cariPembayarLagi;
                }
            }
            $kasku += $jmlPembayaranBulanan;

            return $kasku;
        }
        $bulanku = array('Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    ?>
<!-- FUNC -->

<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row">

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left align-self-bottom mt-3">
                                            <span class="d-block mb-1 success font-medium-1">KAS bulan ini</span>
                                            <h4 class="success mb-0">
                                                Rp <?=number_format(lastKas($this, (int)date('m') + 1, date('Y')), 2, ',', '.');?>
                                            </h4>
                                        </div>
                                        <div class="align-self-top">
                                            <i class="la la-bank success font-large-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="media d-flex">
                                        <div class="media-body text-left align-self-bottom mt-3">
                                            <span class="d-block mb-1 danger font-medium-1">KAS bulan lalu</span>
                                            <h4 class="danger mb-0">
                                                Rp <?=number_format(lastKas($this, (int)date('m'), date('Y')), 2, ',', '.');?>
                                            </h4>
                                        </div>
                                        <div class="align-self-top">
                                            <i class="la la-history danger font-large-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <a href="<?= base_url('Admin/Siswa/Siswa'); ?>" class="warning">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Jumlah Siswa</span>
                                                <h4 class="warning mb-0"><?= $jumlah_siswa ?></h4>
                                            </div>
                                            <div class="align-self-top">
                                                <i class="la la-user warning font-large-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                        <a href="<?= base_url('Admin/Master/ProgramStudi'); ?>" class="primary">
                            <div class="card pull-up">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left align-self-bottom mt-3">
                                                <span class="d-block mb-1 font-medium-1">Jumlah Kelas</span>
                                                <h4 class="primary mb-0"><?= $jumlah_kelas ?></h4>
                                            </div>
                                            <div class="align-self-top">
                                                <i class="la la-hospital-o primary font-large-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h5 class="card-title text-bold-700 my-2">Aktivitas Pembayaran</h5>
                        <div class="card">            
                            <div class="card-content">
                                <div id="recent-projects" class="media-list position-relative">
                                    <div class="table-responsive">
                                        <table class="table table-padded table-xl mb-0" id="recent-project-table">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">#</th>
                                                    <th class="border-top-0">Nama</th>
                                                    <th class="border-top-0">Status</th>
                                                    <th class="border-top-0">Payment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-truncate align-middle">
                                                        <a href="#">1</a>
                                                    </td>
                                                    <td class="text-truncate">
                                                        rendy
                                                    </td>
                                                    <td class="text-truncate pb-0">
                                                        <span>Lunas</span>
                                                        <p class="font-small-2 text-muted">15th July, 2018</p>
                                                    </td>
                                                    <td>
                                                        <div class="btn bg-gradient-x-success">BCA VA</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- CONTENT -->
<?php
    function getClientIP(){
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
?>

<!-- MODAL -->
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Print Preview</h4>
                </div>
                <div class="modal-body">
                    <div id="pdf" style="height: 400px;">Belum terintall adobe reader terbaru</a></div>
                </div>
            </div>
        </div>
    </div>
<!-- MODAL -->

<iframe name="iframe1" style="display: none;"></iframe>

<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h3 class="card-title text-bold-700 my-2 white"><i class="la la-print" style="font-size: 30px;"></i> Rekapitulasi</h3>
                        <div class="card">
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="card-title">Rekapitulasi Akhir Tahun</h3>
                                                <div class="card-text">
                                                    <p>
                                                        Menampilkan seluruh riwayat keuangan (Proses ini memakan waktu yang lumayan lama, karena harus menghitung keuangan tiap waktu dan disarankan menggunakan menu ini tiap tutup buku).
                                                        Simpan rekapan ini untuk memantau keuangan tiap tahun. (nb: apabila rekapan yang anda simpan hilang, bisa dicari di folder rekap_keuangan di aplikasi ini).
                                                    </p>
                                                    <form class="form-cari" action="<?=base_url('Keuangan/Rekapitulasi/rekapAll');?>" method="get" target="iframe1">
                                                        <button type="submit" class="btn btn-block btn-glow btn-lg btn-bg-gradient-x-blue-green" name="cetak">Simpan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-content collape show">
                                            <div class="card-header ">
                                                <h4 class="card-title">Rekapitulasi Jurnal Pertanggal</h4>
                                                <div class="card-text">
                                                    Menampilkan riwayat jurnal setiap rekening akuntansi.
                                                </div>
                                            </div>

                                            <div class="card-body">
                                                <form class="form-horizontal" action="" method="get" target="iframe1">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="la la-calendar"></i>
                                                            </span>
                                                        </div>
                                                        <input type="text" readonly value="<?=date('d-m-Y');?>" size="16" name="tanggal" class="form-control position-relative">
                                                    </div>
                                                    <br>
                                                    <button type="submit" class="btn btn-block btn-glow btn-lg btn-bg-gradient-x-purple-blue" name="cetak" value="tanggal">Cetak</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-content collape show">
                                            <div class="card-header ">
                                                <h4 class="card-title">Tunggakan Siswa</h4>
                                                <div class="card-text">
                                                    Menampilkan daftar setiap transaksi pembayaran siswa.
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <form class="form-cari" action="" method="get" target="iframe1">
                                                    <div class="input-group">
                                                        <select name="listKelas" class="select2 form-control">
                                                            <option value="">Pilih Kelas</option>
                                                            <?php
                                                                $listKelas = $this->db->query('select data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas, kelas.kelas from data_siswa inner join kelas on data_siswa.status_kelas = kelas.id_kelas group by data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas order by data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas');
                                                                foreach($listKelas->result() as $lkls):
                                                                    $selected = '';
                                                                    if(isset($_GET['listKelas']))
                                                                        if($_GET['listKelas'] == $lkls->status_kelas.'.'.$lkls->program_studi.'.'.$lkls->kode_kelas)
                                                                            $selected = ' selected';
                                                                    echo '<option value="'.$lkls->status_kelas.'.'.$lkls->program_studi.'.'.$lkls->kode_kelas.'"'.$selected.'>'.$lkls->kelas.' '.$lkls->program_studi.' '.$lkls->kode_kelas.'</option>';
                                                                endforeach;
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <br>
                                                    <button type="submit" class="btn btn-block btn-glow btn-lg btn-bg-gradient-x-orange-yellow" name="cetak" value="siswa">Cetak</button>
                                                </form>
                                            </div>
                                        </div>
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


<!-- js -->
    <script src="<?=base_url('app-assets/js/jquery.printElement.min.js');?>"></script>
    <script type="text/javascript">
        $('.select2').select2({
            width:'100%',
            placeholder: 'Pilih Kelas'
        });

        window.onload = function (){
            <?php if(isset($_GET['cetak'])): if($_GET['cetak'] == '085708094363'):?>
                var success = new PDFObject({ url: "<?=base_url('template/'.getClientIP().'.pdf');?>" }).embed("pdf");
                $('#myModal2').modal('show');
            <?php endif; endif; ?>
        };
    </script>
<!-- js -->
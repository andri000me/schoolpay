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

    function toIndo($tgl){
        $tgl = explode('-', $tgl);
        $bln = array('Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        return $tgl[2].' '.$bln[(int)$tgl[1]].' '.$tgl[0];
    }

    $saldoku = 0;
    $debitku = 0;
    $kreditku= 0;
?>

<link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/plugins/pickers/datepicker/datepicker.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/plugins/pickers/datepicker/datepicker3.css'); ?>">
<script type="text/javascript" src="<?= base_url('app-assets/js/scripts/pickers/datepicker/datepicker.js');?>"></script>

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

    <div class="modal fade" id="myModal10" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post" target="iframe1" onSubmit="$('#myModal10').modal('hide');">
                    <div class="modal-header">
                        <div class="pull-right" style="max-width: 150px;">
                            <div data-date-viewmode="years" id="dpx"  data-date-format="dd-mm-yyyy" data-date="<?=date('d-m-Y');?>" class="input-group date dpYears">
                                <input type="text" readonly value="<?=date('d-m-Y');?>" size="16" name="tanggal" class="form-control input-sm">
                                <span class="input-group-btn add-on">
                                    <button class="btn btn-theme input-sm" type="button"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                            </div>
                        </div>
                        <h4 class="modal-title" id="myModalLabel">Tambah Transaksi</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover table-condensed table-striped" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th width="35">No</th>
                                    <th>Keterangan</th>
                                    <th>Penerimaan</th>
                                    <th>Pengeluaran</th>
                                </tr>
                            </thead>
                        </table>
                        <div style="height:150px;max-height: 150px;overflow-y: auto;">
                            <table class="table table-hover table-condensed table-striped">
                                <tbody style="max-height: 200px;overflow-y: auto;" class="transkasiPlus">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary btn-sm tombolPlus">Tambah</button>
                        <button type="submit" class="btn btn-info btn-sm" name="jurnal" value="ok">Simpan</button>
                        <button type="submit" class="btn btn-info btn-sm" name="jurnalCetak" value="ok">Simpan + Cetak</button>
                        <button type="button" class="btn btn-danger btn-sm" id="tutupModal" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- MODAL -->

<iframe name="iframe1" style="display: none;"></iframe>

<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12">
                    <h3 class="card-title text-bold-700 my-2 white"><i class="la la-book" style="font-size: 30px;"></i>Jurnal Umum</h3>
                </div>
            </div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <form action="" method="get" class="form-horizontal">
                                            <div class="row">
                                                <div class="col-6">
                                                    <select class="form-control select2" data-plugin-multiselect name="bulan" onChange="this.form.submit();">
                                                        <?php
                                                        $bulan = array('Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                                                        foreach($bulan as $key=>$val){
                                                            if($key == 0)
                                                                continue;
                                                            $selected = $bulanku == $key ? ' selected' : '';
                                                            echo '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-6">
                                                    <select class="form-control select2" data-plugin-multiselect name="tahun" onChange="this.form.submit();">
                                                        <?php
                                                        $tahun = $this->db->query("select tahun_ajaran, aktif from tahun_ajaran order by tahun_ajaran");
                                                        foreach($tahun->result() as $row){
                                                            $selected = $tahunku == $row->tahun_ajaran ? ' selected' : '';
                                                            echo '<option value="'.$row->tahun_ajaran.'"'.$selected.'>'.substr($row->tahun_ajaran, 0, 4).'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group btn-group btn-group-md btn-block">
                                            <a target="iframe1" href="<?=base_url('Keuangan/Jurnal/savePDF?bulan='.$bulanku.'&tahun='.$tahunku.'&cetak=085708094363');?>" class="float-right btn btn-pull-up btn-primary">
                                                <i class="la la-print"></i> 
                                            </a>

                                            <a class="float-right btn btn-pull-up btn-success openModal10">
                                                <i class="la la-plus"></i> 
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <?php if($this->session->flashdata('pesan')): ?>
                                            <div class="bs-callout bs-callout-danger">
                                                <?=$this->session->flashdata('pesan');?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-striped mb-none">
                                                        <thead>
                                                            <tr>
                                                                <th width="35">No</th>
                                                                <th width="35">Aksi</th>
                                                                <th width="100">Tanggal</th>
                                                                <th width="160">Petugas</th>
                                                                <th>Keterangan</th>
                                                                <th>Penerimaan</th>
                                                                <th>Pengeluaran</th>
                                                                <th>Saldo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center">1</td>
                                                                <td>
                                                                    #
                                                                </td>
                                                                <td>01 <?=$bulan[$bulanku].' '.substr($tahunku, 0, 4);?></td>
                                                                <td>#</td>
                                                                <td>Kas Bulan <?=$bulan[$bulanku - 1].' '.($bulanku - 1 == 0 ? (substr($tahunku, 0, 4) - 1) : (substr($tahunku, 0, 4)));?></td>
                                                                <td>Rp <span ><?php
                                                                    $hasil = $kasku >= 0 ? $kasku : '0';
                                                                    $debitku += $hasil;
                                                                    echo number_format($hasil, 2, ',', '.');
                                                                ?></span></td>
                                                                <td>Rp <span ><?php
                                                                    $hasil = $kasku < 0 ? (-1 * $kasku) : '0';
                                                                    $kreditku += $hasil;
                                                                    echo number_format($hasil, 2, ',', '.');
                                                                ?></span></td>
                                                                <td>Rp <span ><?php
                                                                    $saldoku = $debitku - $kreditku;
                                                                    echo number_format($saldoku, 2, ',', '.');
                                                                ?></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center">2</td>
                                                                <td>
                                                                    #
                                                                </td>
                                                                <td><?=date('d').' '.$bulan[$bulanku].' '.substr($tahunku, 0, 4);?></td>
                                                                <td>#</td>
                                                                <td>Pembayaran Siswa Bulan <?=$bulan[$bulanku].' '.substr($tahunku, 0, 4);?></td>
                                                                <td>Rp <span ><?php
                                                                    $hasil = $kasku >= 0 ? $kaskulagi : '0';
                                                                    $debitku += $hasil;
                                                                    echo number_format($hasil, 2, ',', '.');
                                                                ?></span></td>
                                                                <td>Rp <span ><?php
                                                                    $hasil = $kasku < 0 ? (-1 * $kaskulagi) : '0';
                                                                    $kreditku += $hasil;
                                                                    echo number_format($hasil, 2, ',', '.');
                                                                ?></span></td>
                                                                <td>Rp <span ><?php
                                                                    $saldoku = $debitku - $kreditku;
                                                                    echo number_format($saldoku, 2, ',', '.');
                                                                ?></span></td>
                                                            </tr>
                                                            <?php
                                                                $no = 3;
                                                                $jurnal = $this->db->query("select keuangan_jurnal.*, pengguna.nama_lengkap from keuangan_jurnal inner join pengguna on keuangan_jurnal.petugas = pengguna.id_pengguna where year(tanggal) = '".(substr($tahunku, 0, 4))."' and month(tanggal) = '".$bulanku."' order by tanggal, id_jurnal, keterangan");
                                                                foreach($jurnal->result() as $jnl):
                                                            ?>
                                                            <tr>
                                                                <td class="text-center"><?=$no;?></td>
                                                                <td>
                                                                    <?php if($this->session->userdata('login')->status == 'admin'): ?>
                                                                    <a href="<?php echo base_url('Keuangan/Jurnal/delete/'.$jnl->id_jurnal); ?>" onclick="return deletechecked('<?php echo base_url('Keuangan/Jurnal/delete/'.$jnl->id_jurnal); ?>');" class="btn btn-xs btn-danger" style="font-size: 8px;">
                                                                        <span class="glyphicon glyphicon-remove" style="color: #fff;"></span>
                                                                    </a>
                                                                    <?php else: echo '#'; endif; ?>
                                                                </td>
                                                                <td><?=toIndo($jnl->tanggal);?></td>
                                                                <td><?=$jnl->nama_lengkap;?></td>
                                                                <td><?=$jnl->keterangan;?></td>
                                                                <td>Rp <span ><?php
                                                                    $debitku += $jnl->debit;
                                                                    echo number_format($jnl->debit, 2, ',', '.');
                                                                ?></span></td>
                                                                <td>Rp <span ><?php
                                                                    $kreditku += $jnl->kredit;
                                                                    echo number_format($jnl->kredit, 2, ',', '.');
                                                                ?></span></td>
                                                                <td>Rp <span ><?php
                                                                    $saldoku = $debitku - $kreditku;
                                                                    echo number_format($saldoku, 2, ',', '.');
                                                                ?></span></td>
                                                            </tr>
                                                            <?php
                                                            $no += 1;
                                                            endforeach;
                                                            ?>
                                                            <tr class="dark">
                                                                <td colspan="5"><strong>Total</strong></td>
                                                                <td>Rp <span ><?php
                                                                    echo number_format($debitku, 2, ',', '.');
                                                                ?></span></td>
                                                                <td>Rp <span ><?php
                                                                    echo number_format($kreditku, 2, ',', '.');
                                                                ?></span></td>
                                                                <td>Rp <span ><?php
                                                                    echo number_format($saldoku, 2, ',', '.');
                                                                ?></span></td>
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
            </div>
        </div>
    </div>
<!-- CONTENT -->


<!-- js -->
    <script src="<?=base_url('app-assets/js/jquery.printElement.min.js');?>"></script>
    <script type="text/javascript">
        $('.select2').select2({
            width:'100%'
        });
        $('#dpx').datepicker();

        function rptrim(inputString){
            var returnString = inputString + "";
            var removeChar = ' ';

            if (removeChar.length) 
            {
                while('' + returnString.charAt(0) == removeChar) 
                {
                    returnString = returnString.substring(1, returnString.length);
                }
                while('' + returnString.charAt(returnString.length - 1) == removeChar) 
                {
                    returnString = returnString.substring(0, returnString.length - 1);
                }
            }
            
            return returnString;
        }

        function removeLeadingZero(number){
            if (number.length > 1)
            {
                while('' + number.charAt(0) == '0') 
                {
                    number = number.substring(1, number.length);
                }
                
                if (number.length == 0)
                    number = "0";
            }
            return number;
        }

        function numberToRupiah(number){
            var original = number;
            number = rptrim(number);
            
            var positif = true;
            if (number.charAt(0) == '-')
            {
                positif = false;
                number = number.substring(1, number.length);
                number = rptrim(number);
            }
            
            number = removeLeadingZero(number);
            
            if (!rpIsNumber(number))
                return original;
            
            var result = "";
            if (number.length < 4)
            {
                result = "" + number;
            }
            else
            {
                var count = 0;
                for(i = number.length - 1; i >= 0; i--) 
                {
                    result = number.charAt(i) + result;
                    count++;
                    
                    if ((count == 3) && (i > 0)) 
                    {
                        result = '.' + result;
                        count = 0;
                    }
                }
                result = "" + result;
            }
            
            if (!positif)
                result = "(" + result + ")";
            
            return result;
        }

        function rupiahToNumber(rp){
            var result = '';
            
            rp = rptrim(rp);
            var positif = true;
            var isvalid = true;
            if (rp.length > 0){
                if (rp.charAt(0) == "("){
                    positif = false;
                    rp = rp.substring(1, rp.length);
                    rp = rptrim(rp);
                }
                
                for (i = 0; isvalid && i < rp.length; i++){
                    var chr = rp.charAt(i);
                    var asc = chr.charCodeAt(0);
                    
                    if (asc >= 48 && asc <= 57)
                    {
                        result = result + chr;
                    }
                    else
                    {
                        isvalid = (asc == 82 || asc == 114 || asc == 80 || asc == 112 || asc == 32 || asc == 46 || asc == 40 || asc == 41);
                        //if (!isvalid)
                            //alert(chr + " " + asc);
                    }
                }
            }
            
            if (isvalid){
                if (positif)
                    return result;
                else
                    return "-" + result;
            }
            else{
                return rp;
            }
        }

        function rpIsNumber(input){
            var isnum = true;
            for (i = 0; isnum && i < input.length; i++)
            {
                var asc = input.charCodeAt(i);
                isnum = (asc >= 48 && asc <= 57);
            }
            
            return isnum;
        }

        function formatRupiah(id){
            var num = id.value;    
            id.value = numberToRupiah(num);
        }

        function unformatRupiah(id){
            var num = id.value;
            id.value = rupiahToNumber(num);
        }

        function deletechecked(link){
            var answer = confirm('yakin ingin menghapusnya?')
            if (answer){
                window.location = link;
            }
            return false;
        }

        window.onload = function (){
            <?php if(isset($_GET['cetak'])): ?>
            var success = new PDFObject({ url: "<?=base_url('template/'.getClientIP().'.pdf');?>" }).embed("pdf");
            $('#myModal2').modal('show');
            <?php endif; ?>
        }
    </script>
<!-- js -->
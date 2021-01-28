<?php
    function toIndo($tgl, $bulan = 0){
        $tgl = explode('-', $tgl);
        $bln = array(null, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        if($bulan == 1)
            return $tgl[2].' '.($bln[(int)$tgl[1]]).' '.$tgl[0];
        return $tgl[2].'/'.$tgl[1].'/'.$tgl[0];
    }
?>
<style>
    .table>tbody>tr.ijo>td, .table>tbody>tr.ijo>th, .table>tbody>tr>td.ijo, .table>tbody>tr>th.ijo, .table>tfoot>tr.ijo>td, .table>tfoot>tr.ijo>th, .table>tfoot>tr>td.ijo, .table>tfoot>tr>th.ijo, .table>thead>tr.ijo>td, .table>thead>tr.ijo>th, .table>thead>tr>td.ijo, .table>thead>tr>th.ijo {
      background-color: #E2F7D9;
    }
</style>

<iframe name="iframe1" style="display: none;"></iframe>

<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row mb-none match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="row mt-3 mb-none match-height">

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-content collape show">
                                            <div class="card-header ">
                                                <h4 class="card-title">Data Siswa</h4>
                                            </div>

                                            <div class="card-body">
                                                <table style="width: 100%;">
                                                    <tr>
                                                        <td>
                                                            <select name="listKelas" class="form-control select2 listKelas" id="listKelas" style="width: 100%;" disabled>
                                                                <option value="<?=$id_kelas.'-'.$id_program_studi.'-'.$kode_kelas?>">
                                                                    <?=$kelas.' '.$id_program_studi.' '.$kode_kelas?>
                                                                </option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr><td style="height: 7px;"></td><td></td></tr>
                                                    <tr>
                                                        <td>
                                                            <select name="listKelas" class="form-control select2 list-siswa" id="listsiswa" style="width: 100%;" disabled>
                                                                <option value="<?= $nis ?>"><?= $nis.' - '.$nama  ?></option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                                    <a 
                                                        target="iframe1" 
                                                        href="<?=base_url('Keuangan/Pembayaran/cetakTunggaan?listKelas='.$kelas.'&nis='.$nis.'');?>" 
                                                        class="cetakBulannya btn btn-primary btn-block mt-3" 
                                                        style="color: #fff;cursor:pointer;margin: 0 7px;">
                                                        <span class="la la-print"></span>
                                                        &nbsp; Cetak Data Pembayaran
                                                    </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card" style="min-height: 300px;">
                                    <div class="card-content">
                                        <div class="card-content collape show">
                                            <div class="card-header">
                                                <div class="row" style="margin-bottom: -25px;">
                                                    <div class="col-md-6 col-12">
                                                        <h4 class="card-title">Data Pembayaran</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <div class="table-responsive" style="margin-top: -20px;">
                                                    <div class="nobiayaku" style="display: none;">1</div>
                                                    <form method="post" action="" class="formbiayaku" id="formbiayaku" style="display: none;">
                                                        <input type="text" name="nis" value="<?=$nis;?>">
                                                        <input type="text" name="kelas" value="<?=$kelas;?>">
                                                    </form>
                                                    <div class="scrollable" style="overflow-y: auto;max-height: 200px;">
                                                        <table id="tblistbulan" class="table table-bordered table-striped table-condensed mb-none">
                                                            <thead>
                                                                <tr class="panel panel-secondary">
                                                                    <th style="width: 5%">No</th>
                                                                    <th style="width: 10%">Tahun</th>
                                                                    <th style="width: 30%">Pembayaran</th>
                                                                    <th style="width: 25%">Harus Dibayar</th>
                                                                    <th style="width: 25%">Dibayar</th>
                                                                    <th style="width: 5%">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="biayaku">
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

                        <div class="row mt-none" style="margin-top: -50px;">
                            <div class="col-12">
                                <div class="form-group btn-group btn-group-md btn-block">
                                    <button class="btn btn-success white" id="onlinepay">
                                        Online Pay
                                    </button>
                                    <button class="btn btn-info" id="cashpay">
                                        Cash
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <ul class="nav nav-tabs">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="tabbulanan" data-toggle="tab" aria-controls="bulanan" href="#bulanan" aria-expanded="true">Bulanan</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="tabbebas" data-toggle="tab" aria-controls="bebas" href="#bebas" aria-expanded="false">Bebas</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <div role="tabpanel" aria-expanded="true" aria-labelledby="tabbulanan" class="tab-pane active" id="bulanan">
                                                            <br>
                                                            <table class="table table-bordered table-striped table-condensed mb-none">
                                                                <?php 
                                                                    $jenis = $this->db->query("
                                                                        SELECT
                                                                            CONCAT(LEFT(keuangan_jenis.tahun_ajaran,4),'/', (LEFT(keuangan_jenis.tahun_ajaran,4) + 1)) AS tahun,
                                                                            keuangan_pos.pos,
                                                                            keuangan_siswa_bulanan.id_siswa_bulanan,
                                                                            keuangan_siswa_bulanan.b1,
                                                                            keuangan_siswa_bulanan.b2,
                                                                            keuangan_siswa_bulanan.b4,
                                                                            keuangan_siswa_bulanan.b3,
                                                                            keuangan_siswa_bulanan.b5,
                                                                            keuangan_siswa_bulanan.b6,
                                                                            keuangan_siswa_bulanan.b7,
                                                                            keuangan_siswa_bulanan.b8,
                                                                            keuangan_siswa_bulanan.b9,
                                                                            keuangan_siswa_bulanan.b10,
                                                                            keuangan_siswa_bulanan.b11,
                                                                            keuangan_siswa_bulanan.b12,
                                                                            keuangan_jenis.id_jenis
                                                                        FROM
                                                                            keuangan_siswa_bulanan
                                                                        INNER JOIN 
                                                                            keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
                                                                        INNER JOIN 
                                                                            keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
                                                                        WHERE
                                                                            keuangan_siswa_bulanan.nis = '$nis' AND
                                                                            keuangan_jenis.tipe = 'bulanan'
                                                                        ORDER BY
                                                                            keuangan_jenis.tahun_ajaran,
                                                                            keuangan_pos.pos
                                                                    ");
                                                                    $bulan = array(null, 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni');

                                                                    echo '<tr class="panel panel-secondary">';
                                                                        echo '<th>No</th>';
                                                                        echo '<th>Tahun</th>';
                                                                        echo '<th>Pembayaran</th>';
                                                                        for($i=1;$i<=12;$i++){
                                                                            echo '<th>'.$bulan[$i].'</th>';
                                                                        }
                                                                    echo '</tr>';
                                                                    
                                                                    $no = 1;
                                                                    $idkolom = 1;

                                                                    foreach($jenis->result() as $jns){
                                                                        echo '<tr>';
                                                                            echo '<td>'.$no.'</td>';
                                                                            echo '<td>'.$jns->tahun.'</td>';
                                                                            echo '<td>'.$jns->pos.'</td>';
                                                                            $cekin = $this->db->query("SELECT * from pembayaran_siswa_bulanan where tahun_ajaran = '".substr($jns->tahun,0,4)."1' and nis = '".$nis."' and id_jenis = '".$jns->id_jenis."'");
                                                                            for($i=1;$i<=12;$i++){
                                                                                $testx = 'b'.$i;
                                                                                $nilai = '';
                                                                                if($cekin->num_rows() <= 0){
                                                                                    $nilai = number_format($jns->$testx,0,'.','.');
                                                                                    echo '<td class="danger" id="kolom'.$idkolom.'" style="cursor: pointer;" align="right" onClick="appendku(\''.$jns->id_jenis.'\', \'kolom'.$idkolom.'\', \''.$jns->tahun.'\', \''.$jns->pos.'\', \''.$i.'\', \''.$jns->id_siswa_bulanan.'\', \''.$jns->$testx.'\', \'bulanan\');">'.$nilai.'</td>';
                                                                                }else{
                                                                                    $hasilku = $cekin->result()[0]->$testx;
                                                                                    if($hasilku != '0000-00-00'){
                                                                                        $nilai = toIndo($hasilku);
                                                                                        echo '<td class="ijo" align="right">'.$nilai.'</td>';
                                                                                    }else{
                                                                                        $nilai = number_format($jns->$testx,0,'.','.');
                                                                                        echo '<td class="danger" id="kolom'.$idkolom.'" style="cursor: pointer;" align="right" onClick="appendku(\''.$jns->id_jenis.'\', \'kolom'.$idkolom.'\', \''.$jns->tahun.'\', \''.$jns->pos.'\', \''.$i.'\', \''.$jns->id_siswa_bulanan.'\', \''.$jns->$testx.'\', \'bulanan\');">'.$nilai.'</td>';
                                                                                    }
                                                                                }
                                                                                $idkolom += 1;
                                                                                //echo '</td>';
                                                                            }
                                                                        echo '</tr>';
                                                                        $no += 1;
                                                                    }
                                                                ?>
                                                            </table>
                                                        </div>

                                                        <div role="tabpanel" aria-expanded="true" aria-labelledby="tabbebas" class="tab-pane" id="bebas">
                                                            <br>
                                                            <table class="table table-bordered table-striped table-condensed mb-none">
                                                                <?php
                                                                    $jenis = $this->db->query("
                                                                        SELECT
                                                                        CONCAT(LEFT(keuangan_jenis.tahun_ajaran,4),'/', (LEFT(keuangan_jenis.tahun_ajaran,4) + 1)) AS tahun,
                                                                            keuangan_pos.pos,
                                                                            keuangan_siswa_bulanan.id_siswa_bulanan,
                                                                            keuangan_siswa_bulanan.b1,
                                                                            keuangan_jenis.id_jenis
                                                                        FROM
                                                                            keuangan_siswa_bulanan
                                                                        INNER JOIN 
                                                                            keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
                                                                        INNER JOIN 
                                                                            keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
                                                                        WHERE
                                                                            keuangan_siswa_bulanan.nis = '".$nis."' AND
                                                                            keuangan_jenis.tipe = 'bebas'
                                                                        ORDER BY
                                                                            keuangan_jenis.tahun_ajaran,
                                                                            keuangan_pos.pos
                                                                    ");
                                                                    $bulan = array(null, 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni');
                                                                    echo '<tr class="info">';
                                                                        echo '
                                                                        <th width="35">No</th>
                                                                        <th width="80">Tahun</th>
                                                                        <th>Nama Pembayaran</th>
                                                                        <th width="120">Bayar</th>
                                                                        <th width="120">Telah Di Bayar</th>
                                                                        <th width="120">Kurang</th>';
                                                                    echo '</tr>';
                                                                    $no = 1;
                                                                    foreach($jenis->result() as $jns){
                                                                        echo '<tr>
                                                                            <td>'.$no.'</td>
                                                                            <td>'.$jns->tahun.'</td>
                                                                            <td>'.$jns->pos.'</td>
                                                                            <td align="right">'.number_format($jns->b1,0,'.','.').'</td>
                                                                            <td align="right" style="cursor: pointer;" onClick="viewCicilan(\''.$jns->b1.'\', \''.base_url('Keuangan/Pembayaran/cicilan').'\', \''.$nis.'\', \''.substr($jns->tahun, 0, 4).'\', \''.$jns->id_jenis.'\')">';
                                                                            $cicilan = $this->db->query("select sum(cicilan) as tunggak from pembayaran_siswa_bebas where tahun_ajaran = '".substr($jns->tahun,0,4)."1' and nis = '".$nis."' and id_jenis = '".$jns->id_jenis."'");
                                                                            $cicilan = $cicilan->result()[0]->tunggak == '' ? 0 : $cicilan->result()[0]->tunggak;
                                                                            echo number_format($cicilan,0,'.','.');
                                                                            echo '</td>';
                                                                            if(($jns->b1 - $cicilan) == 0){
                                                                                echo '<td align="right">'.($jns->b1 - $cicilan).'</td>';
                                                                            }else{
                                                                                echo '<td align="right" id="kolomku'.$no.'" class="danger" style="cursor: pointer;" align="right" onClick="appendku(\''.$jns->id_jenis.'\', \'kolomku'.$no.'\', \''.$jns->tahun.'\', \''.$jns->pos.'\', \''.$jns->id_jenis.'\', \''.$jns->id_siswa_bulanan.'\', \''.($jns->b1 - $cicilan).'\', \'bebas\');">'.number_format(($jns->b1 - $cicilan),0,'.','.').'</td>';
                                                                            }
                                                                        echo '</tr>';
                                                                        $no += 1;
                                                                    }
                                                                ?>
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
        </div>
    </div>
<!-- CONTENT -->

<!-- js -->
    <script src="<?=base_url('app-assets/js/jquery.printElement.min.js');?>"></script>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-fNTUNd156Q0m4RyJ"></script>

    <script type="text/javascript">  
        $(document).ready(function () {
            $('#cashpay').click(function(event){
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    var datafrm = $('#formbiayaku').serializeArray();
                    datafrm.push({
                        "name": "via","value": "cash"
                    })
                    if ( $('#tblistbulan tbody').children().length > 0 ) {
                        $.ajax({
                            url : "<?= base_url('Keuangan/Pembayaran/proses');?>",
                            type:"POST",
                            data: datafrm,
                            dataType:"json",
                            success:function(event, data){
                                if (event.Error == true) {
                                    Swal.fire({
                                        title: "Information",
                                        animation: true,
                                        icon:"error",
                                        text: event.Message,
                                        confirmButtonText: "OK"
                                    });
                                }
                                else{
                                    var base_url = "<?= base_url();?>";
                                }
                            }
                        });
                    }
                    else{
                        Swal.fire({
                            title: "Information",
                            animation: true,
                            icon:"error",
                            text: 'Tidak ada bulan yang di pilih',
                            confirmButtonText: "OK"
                        });
                    }
                }
            });

            $('#onlinepay').click(function(event){
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    var datafrm = $('#formbiayaku').serializeArray();
                    datafrm.push({
                        "name": "via","value": "online"
                    })
                    if ( $('#tblistbulan tbody').children().length > 0 ) {
                        $.ajax({
                            url : "<?= base_url('Keuangan/Pembayaran/proses');?>",
                            type:"POST",
                            data: datafrm,
                            dataType:"json",
                            success:function(event, data){
                                if (event.Error == true) {
                                    Swal.fire({
                                        title: "Information",
                                        animation: true,
                                        icon:"error",
                                        text: event.Message,
                                        confirmButtonText: "OK"
                                    });
                                }
                                else{
                                    snap.pay(event.Redirect);
                                }
                            }
                        });
                    }
                    else{
                        Swal.fire({
                            title: "Information",
                            animation: true,
                            icon:"error",
                            text: 'Tidak ada bulan yang di pilih',
                            confirmButtonText: "OK"
                        });
                    }
                }
            });
        });

        $('#listKelas').select2({
            width:'100%',
            placeholder: 'Pilih Kelas'
        });
        $('#listsiswa').select2({
            width:'100%',
            placeholder: 'Pilih Siswa'
        });

        function rptrim(inputString) {
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

        function rupiahToNumber(rp) {
            var result = '';
            
            rp = rptrim(rp);
            var positif = true;
            var isvalid = true;
            if (rp.length > 0) 
            {
                if (rp.charAt(0) == "(")
                {
                    positif = false;
                    rp = rp.substring(1, rp.length);
                    rp = rptrim(rp);
                }
                
                for (i = 0; isvalid && i < rp.length; i++)
                {
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
            
            if (isvalid)
            {
                if (positif)
                    return result;
                else
                    return "-" + result;
            }
            else
            {
                return rp;
            }
        }

        function rpIsNumber(input) {
            var isnum = true;
            for (i = 0; isnum && i < input.length; i++)
            {
                var asc = input.charCodeAt(i);
                isnum = (asc >= 48 && asc <= 57);
            }
            
            return isnum;
            
            //return (!isNaN(parseInt(input))) ? true : false;
        }

        function formatRupiah(id) {
            var num = id.value;    
            id.value = numberToRupiah(num);
        }

        function unformatRupiah(id) {
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

        function hapus(link){
            var answer = confirm('yakin ingin menghapusnya?')
            if (answer){
                window.location = link;
            }
            return false;
        }
    </script>
<!-- js -->
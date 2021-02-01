<!-- style -->
    <style>
        .outer {
          display: table;
          position: absolute;
          top: 0;
          left: 0;
          height: 100%;
          width: 100%;
        }

        .middle {
          display: table-cell;
          vertical-align: middle;
        }

        .inner {
          margin-left: auto;
          margin-right: auto;
          width: 400px;
          /*whatever width you want*/
        }
    </style>
<!-- style -->

<!-- CONTENT -->
        <div class="row">
            <div class="col-5">
                <div class="row px-2">
                    <img id="pic" width="300"  alt="profile" src="">
                </div>
            </div>

            <div class="col-7">
                <div class="outer">
                    <div class="middle">
                        <div class="inner">
                            <div class="row">
                                <h4 class="col-lg-4 col-md-6 col-sm-12 card-title">NIS </h4>
                                <span class="col-lg-8 col-md-6 col-sm-12 font-medium-3" id="nis"></span>
                            </div>

                            <div class="row">
                                <h4 class="col-lg-4 col-md-6 col-sm-12 card-title">Nama </h4>
                                <span class="col-lg-8 col-md-6 col-sm-12 font-medium-3" id="nama"></span>
                            </div>

                            <div class="row">
                                <h4 class="col-lg-4 col-md-6 col-sm-12 card-title">Kelas </h4>
                                <span class="col-lg-8 col-md-6 col-sm-12 font-medium-3" id="kelas"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-12">
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
                        <table id="tbbulanan" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                        </table>
                    </div>

                    <div role="tabpanel" aria-expanded="true" aria-labelledby="tabbebas" class="tab-pane" id="bebas">
                        <br>
                        <div class="card" id="cardbebas">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="mx-2 form-group btn-group btn-group-md btn-block">
                <button id="kirimKartu"      class="btn btn-md bg-primary pull-up white"><i class="la la-paper-plane"></i> Kirim Kartu</button>
                <button id="kirimPengumuman" class="btn btn-md btn-warning pull-up white"><i class="la la-info-circle"></i> Kirim Pengumuman</button>
            </div>
        </div>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
		$(document).ready(function () {
			loaddata();
		});
		
		function loaddata(){
            var nis = $('#modal').data('nis');
            if (nis > 0) {
                var html_bulanan='',html_bebas='';

                $.getJSON("<?php echo base_url('Kartu/loadDetailSiswa');?>" + "/" + nis, function (data) {
                    // DATA SISWA
                        var data_siswa = data.data_siswa;
                        if (data_siswa.foto == null) {
                            $('#pic').prop('src', '<?= base_url('app-assets/images/no-profile.png') ?>');
                        }
                        else{
                            $('#pic').prop('src', '<?= base_url() ?>'+data_siswa.foto);
                        }
                        $('#nis').html(data_siswa.nis);
                        $('#nama').html(data_siswa.nama);
                        $('#kelas').html(data_siswa.kelas +' '+ data_siswa.program_studi +' '+ data_siswa.kode_kelas);
                    // DATA SISWA

                    // BULANAN
                        var bulanan = data.bulanan;
                        if (bulanan instanceof Array) {
                            bulanan.forEach(function (bulanan) {
                                html_bulanan += '<tr>';
                                html_bulanan +=     '<td colspan="4" class="bg-primary bg-accent-4 black" style="text-align: center;">'+bulanan.nama_pembayaran+'</td>';
                                html_bulanan += '<tr>';
                            
                                html_bulanan += '<tr>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">Juli</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b1)+'" style="text-align: right;">'+formatDataB(bulanan.b1)+'</td>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">Agustus</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b2)+'" style="text-align: right;">'+formatDataB(bulanan.b2)+'</td>';
                                html_bulanan += '</tr>';
                                html_bulanan += '</tr>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">September</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b3)+'" style="text-align: right;">'+formatDataB(bulanan.b3)+'</td>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">Oktober</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b4)+'" style="text-align: right;">'+formatDataB(bulanan.b4)+'</td>';
                                html_bulanan += '</tr>';
                                html_bulanan += '<tr>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">November</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b5)+'" style="text-align: right;">'+formatDataB(bulanan.b5)+'</td>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">Desember</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b6)+'" style="text-align: right;">'+formatDataB(bulanan.b6)+'</td>';
                                html_bulanan += '</tr>';

                                html_bulanan += '<tr>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">Januari</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b7)+'" style="text-align: right;">'+formatDataB(bulanan.b7)+'</td>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">Februari</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b8)+'" style="text-align: right;">'+formatDataB(bulanan.b8)+'</td>';
                                html_bulanan += '</tr>';
                                html_bulanan += '<tr>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">Maret</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b9)+'" style="text-align: right;">'+formatDataB(bulanan.b9)+'</td>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">April</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b10)+'" style="text-align: right;">'+formatDataB(bulanan.b10)+'</td>';
                                html_bulanan += '</tr>';
                                html_bulanan += '<tr>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">Mei</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b11)+'" style="text-align: right;">'+formatDataB(bulanan.b11)+'</td>';
                                html_bulanan +=     '<th class="bg-primary bg-darken-1 white" width="15%">Juni</th>';
                                html_bulanan +=     '<td class="black '+cekbayar(bulanan.b12)+'" style="text-align: right;">'+formatDataB(bulanan.b12)+'</td>';
                                html_bulanan += '</tr>';

                                html_bulanan += '<br>';
                            });
                        }
                        else{
                            html_bulanan += '<tr>';
                            html_bulanan +=     '<td colspan="4" class="bg-primary bg-accent-4 black" style="text-align: center;">No Data</td>';
                            html_bulanan += '<tr>';
                        }
                        $('#tbbulanan').append(html_bulanan);
                    // BULANAN

                    // BEBAS
                        var bebas   = data.bebas;
                        if (bebas instanceof Array) {
                            bebas.forEach(function (bebas) {
                                var detail = bebas.detail;

                                html_bebas += '<div class="card-header border-top-info border-top-5" id="headingA'+bebas.id_siswa_bulanan+'" data-toggle="collapse" data-target="#collapseA'+bebas.id_siswa_bulanan+'" aria-expanded="false" aria-controls="collapseA'+bebas.id_siswa_bulanan+'" style="cursor: pointer;">';
                                html_bebas +=   '<h5 class="mb-0">';
                                html_bebas +=       '<span class="float-left">'+bebas.nama_pembayaran+'</span>';
                                html_bebas +=       '<span class="float-right"><u>#'+bebas.id_siswa_bulanan+'</u></span>';
                                html_bebas +=   '</h5>';
                                html_bebas += '</div>';

                                html_bebas += '<div id="collapseA'+bebas.id_siswa_bulanan+'" class="collapse" aria-labelledby="headingA'+bebas.id_siswa_bulanan+'" style="">';
                                html_bebas +=   '<div class="card-body">';
                                html_bebas +=       '<table class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">';
                                html_bebas +=           '<tr>';
                                html_bebas +=               '<th class="text-center card-title">Tanggal</th>';
                                html_bebas +=               '<th class="text-center card-title">Cicilan</th>';
                                html_bebas +=               '<th class="text-center card-title">Petugas</th>';
                                html_bebas +=           '</tr>';

                                var totalterbayar = 0;

                                if (detail instanceof Array) {
                                    detail.forEach(function (detail) {
                                        totalterbayar = parseInt(totalterbayar) + parseInt(detail.cicilan);

                                        html_bebas +=       '<tr>';
                                        html_bebas +=           '<td>';
                                        html_bebas +=               '<span>'+detail.tanggal+'</span>';
                                        html_bebas +=           '</td>';

                                        html_bebas +=           '<td align="right">';
                                        html_bebas +=               '<span>'+formatDataB(detail.cicilan)+'</span>';
                                        html_bebas +=           '</td>';

                                        html_bebas +=           '<td align="right">';
                                        html_bebas +=               '<span>'+detail.petugas+'</span>';
                                        html_bebas +=           '</td>';
                                        html_bebas +=       '</tr>';
                                    });
                                }

                                html_bebas +=           '<tr>';
                                html_bebas +=               '<th colspan="2" class="text-center card-title">Jumlah terbayar </th>';
                                html_bebas +=               '<th class="text-center card-title">'+formatDataB(totalterbayar)+'</th>';
                                html_bebas +=           '</tr>';

                                html_bebas +=           '<tr>';
                                html_bebas +=               '<th colspan="2" class="text-center card-title">Jumlah yang harus dicicil </th>';
                                html_bebas +=               '<th class="text-center card-title">'+formatDataB(bebas.b1)+'</th>';
                                html_bebas +=           '</tr>';
                                
                                html_bebas +=        '</table>';
                                html_bebas +=   '</div>';
                                html_bebas += '</div>';
                                html_bebas += '<br>';
                            });
                        }
                        else{
                            html_bebas += '<table class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">';
                            html_bebas +=   '<tr>';
                            html_bebas +=       '<td colspan="4" class="bg-primary bg-accent-4 black" style="text-align: center;">No Data</td>';
                            html_bebas +=   '<tr>';
                            html_bebas += '</table>';
                        }
                        $('#cardbebas').append(html_bebas);
                    // BEBAS
                });
            }
        }

        function cekbayar(b) {
            var re;
            if (Number.isInteger(b)) {
                re =  'bg-warning bg-darken-2';
            }
            else{
                re =  'bg-primary bg-accent-3';
            }

            return re;
        }

        // ACTION
            $('#kirimKartu').click(function () {
                var nis = $('#modal').data('nis');
                $('#modal2').data('nis', nis);
                $('#modal2').data('tipe', 'kartu');

                $('#modalheader2').removeClass('bg-info').removeClass('bg-warning').addClass('bg-primary white');
                $('#modaltitle2').addClass('white');
                $('#modaldialog2').addClass('modal-lg');
                $('#modaltitle2').html('Kirim Kartu Ujian');
                $('#modalbody2').load("<?php echo base_url("Kartu/kirim");?>");
                $('#modal2').modal('show');
                $('#modalfooter2').hide();
            });

            $('#kirimPengumuman').click(function () {
               var nis = $('#modal').data('nis');
                $('#modal2').data('nis', nis);
                $('#modal2').data('tipe', 'pengumuman');

                $('#modalheader2').removeClass('bg-info').removeClass('bg-primary').addClass('bg-warning white');
                $('#modaltitle2').addClass('white');
                $('#modaldialog2').addClass('modal-lg');
                $('#modaltitle2').html('Kirim pengumuman pembayaran');
                $('#modalbody2').load("<?php echo base_url("Kartu/kirim");?>");
                $('#modal2').modal('show');
                $('#modalfooter2').hide();
            });
        // ACTION

        function formatDataB(data) {
            var callbacks;

            if (isNaN(data)) {
                var newDateTime = new Date(data);
                var month = ['January','February','March','April','May','June','July','August','September','October','November','December'];

                callbacks = newDateTime.getDate() +' '+ month[newDateTime.getMonth()] +' '+ newDateTime.getFullYear();
            }
            else{
                callbacks = 'Rp.' + new Number(data).toLocaleString("id-ID");
            }

            return callbacks;
        }

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT
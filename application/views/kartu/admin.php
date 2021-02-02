<style type="text/css">
    div.slider {
        display: none;
    }
</style>

<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12">
                    <h3 class="card-title text-bold-700 my-2 white"><i class="la menu-title" style="font-size: 30px;"></i> Kartu Ujian</h3>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="font-medium-3 card-title">Ujian</h3>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-content collapse show">
                                    <div class="table-responsive" id="kartuWrapper">
                                        <table id="tableDataUjian" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                            <thead>
                                                <th class="sorting_asc">No.</th>
                                                <th>Nama Ujian</th>
                                                <th>Tanggal</th>
                                                <th>Tipe</th>
                                                <!-- <th>Max siswa</th> -->
                                                <th>Detail</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="font-medium-3 card-title">Kirim Kartu</h3>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <select class="form-control select2 listkelas" name="listkelas" id="listkelas" onChange="tableSiswa(this.value);">
                                        <option value=""></option>
                                        <?php foreach ($listKelas as $value) { ?>
                                            <option value="<?= $value->id_kelas.'-'.$value->id_program_studi.'-'.$value->kode_kelas ?>"><?= $value->kelas.' '.$value->id_program_studi.' '.$value->kode_kelas ?></option>
                                        <?php } ?>
                                    </select>

                                    <h2 class="mt-4" id="siswaPlaceholder" align="center">Pilih Kelas</h2>

                                    <div class="table-responsive" id="siswaWrapper">
                                        <table id="tableKirimKartu" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                            <thead>
                                                <th class="sorting_asc">No.</th>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Detail</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                        <br>
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
    <script type="text/javascript">
        $(document).ready(function (e) {
           $('#siswaWrapper').hide(); 
        });

        // TABLE UJIAN
            var tableDataUjian = $('#tableDataUjian').DataTable({
                responsive : true,
                ajax : {
                    "url" : "<?php echo base_url('Kartu/getDataUjian');?>",
                    "dataSrc": "",
                    "type": "POST"
                },
                columns: [
                    {data:"id_ujian"},
                    {data:"nama_ujian"},
                    {data:"tanggal_ujian",
                        render: function (data, type, row) {
                            var newDateTime = new Date(data);
                            var month = ['January','February','March','April','May','June','July','August','September','October','November','December'];

                            var tanggal = newDateTime.getDate() +' '+ month[newDateTime.getMonth()] +' '+ newDateTime.getFullYear();
                            var jam     = newDateTime.getHours() +':'+ newDateTime.getMinutes();
                            return tanggal +' '+ jam;
                        }
                    },
                    {data:"tipe_ujian",
                        render: function (data, type, row) {
                            if (data == 'offline') {
                                ret = '<button class="btn btn-block btn-warning" style="cursor:default;">OFFLINE</button>';
                            }
                            else{
                                ret = '<button class="btn btn-block btn-success" style="cursor:default;">ONLINE</button>';
                            }
                            return ret;
                        }
                    },
                    // {data:"max_siswa"},
                    {data:"id_ujian",
                        render: function (data, type, row) {
                            return '<button class="btn btn-block btn-primary pull-up" onclick=detailUjian("'+data+'")><i class="la la-binoculars"></i></button>';
                        }
                    },
                ],
                language: {
                    "decimal": ",",
                    "thousands": ".",
                },
                dom: '<"toolbar tableDataUjian">frtip'
            });

            tableDataUjian.on( 'order.dt search.dt', function () {
                tableDataUjian.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();

            $("div.tableDataUjian").html(
                '<button class="btn btn-md btn-primary pull-up" style="margin-top: 5px" id="addUjian">Add</button>&nbsp;'+
                '<button class="btn btn-md btn-info pull-up"    style="margin-top: 5px" id="editUjian">Edit</button>&nbsp;'+
                '<button class="btn btn-md btn-danger"  style="margin-top: 5px" id="deleteUjian" disabled>Delete</button>&nbsp;'
            );

            tableDataUjian.on('click', 'tr', function() {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {

                    tableDataUjian.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });

            $('#addUjian').click(function () {
                $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
                $('#modaltitle').addClass('white');
                $('#modaldialog').addClass('modal-lg');
                $('#modaltitle').html('Tambah Ujian');
                $('#modalbody').load('<?php echo base_url("Kartu/addUjian/")?>');

                $('#modal').data('id', 0);
                $('#modal').modal('show');
                $('.modal-footer').children('#btnSaveModal').show();
            });

            $('#editUjian').click(function(){
                var rows = tableDataUjian.rows('.selected').indexes();
                if (rows.length < 1) {
                    Swal.fire("Information",'Please select a row',"warning");
                    return;
                }
                else{
                    var data = tableDataUjian.rows(rows).data();
                    var id = data[0].id_ujian;
                }

                $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
                $('#modaltitle').addClass('white');
                $('#modaldialog').addClass('modal-lg');
                $('#modaltitle').html('Edit Ujian');
                $('#modalbody').load('<?php echo base_url("Kartu/addUjian/")?>');

                $('#modal').data('id', id);
                $('#modal').modal('show');
                $('.modal-footer').children('#btnSaveModal').show();
            });

            $('#deleteUjian').click(function(){
                var rows = tableDataUjian.rows('.selected').indexes();
                if (rows.length < 1) {
                    Swal.fire("Information",'Please select a row',"warning");
                    return;
                }
                else{
                    var data = tableDataUjian.rows(rows).data();
                    var id = data[0].id_ujian;
                }

                $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
                $('#modaltitle').addClass('white');
                $('#modaldialog').addClass('modal-lg');
                $('#modaltitle').html('Edit Ujian');
                $('#modalbody').load('<?php echo base_url("Kartu/addUjian/")?>');

                $('#modal').data('id', id);
                $('#modal').modal('show');
            });

            function detailUjian(id) {
                $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
                $('#modaltitle').addClass('white');
                $('#modaldialog').addClass('modal-lg');
                $('#modaltitle').html('Detail');
                $('#modalbody').load("<?php echo base_url("Kartu/detailUjian");?>");
                $('#modal').data('id', id);
                $('#modal').modal('show');
                $('.modal-footer').children('#btnSaveModal').hide();
            }
        // TABLE UJIAN

        // KARTU UJIAN
            $('#listkelas').select2({
                placeholder: "Pilih Kelas",
                width:'100%'
            });

            function tableSiswa(kelas) {
                if ( $.fn.DataTable.isDataTable('#tableKirimKartu') ) {
                    $('#tableKirimKartu').DataTable().destroy();
                }

                $('#siswaPlaceholder').hide();
                $('#siswaWrapper').show();

                var tableKirimKartu = $('#tableKirimKartu').DataTable({
                    // paging : false,
                    responsive : true,
                    ajax : {
                        "url" : "<?php echo base_url('Kartu/getDataSiswa/');?>"+kelas,
                        "dataSrc": "",
                        "type": "POST"
                    },
                    columns: [
                        {data:"nis"},
                        {data:"nis"},
                        {data:"nama"},
                        {data:"nis",
                            render: function (data, type, row) {
                                return '<button class="btn btn-block btn-primary pull-up" onclick=detailSiswa("'+data+'")><i class="la la-binoculars"></i></button>';
                            }
                        },
                    ],
                    language: {
                        "decimal": ",",
                        "thousands": ".",
                    },
                    dom: '<"toolbar tableKirimKartu">frtip'
                });

                tableKirimKartu.on( 'order.dt search.dt', function () {
                    tableKirimKartu.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        cell.innerHTML = i+1;
                    } );
                }).draw();
            }

            function detailSiswa(nis) {
                $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
                $('#modaltitle').addClass('white');
                $('#modaldialog').addClass('modal-lg');
                $('#modaltitle').html('Detail');
                $('#modalbody').load("<?php echo base_url("Kartu/detailSiswa");?>");
                $('#modal').data('nis', nis);
                $('#modal').modal('show');
                $('.modal-footer').children('#btnSaveModal').hide();
            }
        // KARTU UJIAN
    </script>
<!-- js -->
<!-- CONTENT -->
    <div class="container">
        <div class="row">
            <div class="col-6 form-group">
                <label for="id_ujian">ID Ujian</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="id_ujian" name="id_ujian" class="form-control" placeholder="ID ujian akan otomatis terisi" disabled>
                    <div class="form-control-position">
                        <i class="la la-barcode"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 form-group">
                <label for="nama_ujian">Nama Ujian</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="nama_ujian" class="form-control" name="nama_ujian" placeholder="Nama Ujian" disabled>
                    <div class="form-control-position">
                        <i class="la la-font"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 form-group">
                <label for="tipe_ujian">Tipe Ujian</label>
                <div class="position-relative has-icon-left">
                    <input type="text" name="tipe_ujian" id="tipe_ujian" class="form-control" placeholder="Tipe Ujian" disabled>
                    <div class="form-control-position">
                        <i class="la la-sort-amount-asc"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 form-group">
                <label for="max_siswa">Max Siswa</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="max_siswa" class="form-control" name="max_siswa" placeholder="Max siswa per ruangan" disabled>
                    <div class="form-control-position">
                        <i class="la la-sliders"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 form-group">
                <label for="keterangan">Keterangan</label>
                <div class="position-relative has-icon-left">
                    <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan" disabled>
                    <div class="form-control-position">
                        <i class="la la-list"></i>
                    </div>
                </div>
            </div>
            <div class="col-6 form-group">
                <label for="tanggal_ujian">Waktu Ujian</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="tanggal_ujian" class="form-control" name="tanggal_ujian" placeholder="Waktu Ujian" disabled>
                    <div class="form-control-position">
                        <i class="la la-calendar"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="row">
            <div class="table-responsive">
                <table id="tableDataListSiswa" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                    <thead>            
                        <th class="sorting_asc">No.</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <!-- <th>Keterangan</th> -->
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
		$(document).ready(function () {
			loaddata();
        });

        var tableDataListSiswa = $('#tableDataListSiswa').DataTable({
            responsive : true,
            ajax : {
                "url" : "<?php echo base_url('Kartu/getDataDetailUjian/');?>" + $('#modal').data('id'),
                "dataSrc": "",
                "type": "POST"
            },
            columns: [
                {data:"id_kartu"},
                {data:"nama"},
                {data:"id_kartu",
                    render: function (data, type, row) {
                        return row.kelas +'-'+ row.program_studi +'-'+ row.kode_kelas;
                    }
                },
                // {data:"keterangan"},
                {data:"id_kartu",
                    render: function (data, type, row) {
                        var e = '<button class="btn btn-sm btn-primary" onclick=lihatKartuUjian("'+data+'")><i class="la la-eye"></i></button>';
                        var d = '<button class="btn btn-sm btn-danger" onclick=deleteSiswaFromUjian("'+data+'")><i class="la la-trash"></i></button>';

                        return '<div class="form-group btn-group btn-group-sm btn-block">'+
                                    e+d+
                                '</div>'
                        return '';
                    }
                },
            ],
            language: {
                "decimal": ",",
                "thousands": ".",
            },
            dom: '<"toolbar tableDataListSiswa">frtip'
        });

        $("div.tableDataListSiswa").html(
            '<button class="btn btn-md btn-primary" style="margin-top: 10px; cursor:default;">List Siswa</button>&nbsp;'
        );

        tableDataListSiswa.on( 'order.dt search.dt', function () {
            tableDataListSiswa.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        }).draw();

        function lihatKartuUjian(id) {
            
        }

        function deleteSiswaFromUjian(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            })
            .then((result) => {
                if (result.value) {
                    delKartuSiswa(id);
                }else{
                    block(false,'.content-body');
                }
            });
        }

        function delKartuSiswa(id){
           block(true,'.content-body');
            $.ajax({
                url : "<?php echo base_url('Kartu/deleteKartuSiswa');?>",
                type:"POST",
                data: { id: id },
                dataType:"json",
                success:function(event, data){
                    if (event.Error == false) {
                        Swal.fire("success",event.Message,"success");
                        block(false,'.content-body');
                        tableDataListSiswa.ajax.reload(null,true); 
                    }
                    else{
                        Swal.fire("Information",'error Save : '+event.Message,"warning");
                        block(false,'.content-body');
                    }
                },                    
                error: function(jqXHR, textStatus, errorThrown){        
                    Swal.fire("Information",textStatus+' Save : '+errorThrown,"warning");
                    block(false,'.content-body');
                }
            });
        }
		
		function loaddata(){
            var id = $('#modal').data('id');
            if (id > 0) {
                var html_bulanan='',html_bebas='';

                $.getJSON("<?php echo base_url('Kartu/loadDetailUjian');?>" + "/" + id, function (data) {
                    $('#id_ujian').val(data.id_ujian);
                    $('#nama_ujian').val(data.nama_ujian);
                    $('#tipe_ujian').val(data.tipe_ujian);
                    $('#max_siswa').val(data.max_siswa);
                    $('#keterangan').val(data.keterangan);
                    var newDateTime = new Date(data.tanggal_ujian);
                    var month = ['January','February','March','April','May','June','July','August','September','October','November','December'];

                    $('#tanggal_ujian').val(newDateTime.getDate() +' '+ month[newDateTime.getMonth()] +' '+ newDateTime.getFullYear() +' - '+ newDateTime.getHours() +':'+ newDateTime.getMinutes());
                });
            }
        }

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->
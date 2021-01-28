<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h3 class="card-title text-bold-700 my-2 white"><i class="la la-info" style="font-size: 30px;"></i>Pengumuman</h3>
                        <div class="card">            
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12" id="colTambahData">
                                                <h5 class="title">Buat Pengumuman : </h5>
                                                <div class="form-group btn-group btn-group-md btn-block">
                                                    <button class="btn btn-primary" id="addTingkat">Berdasarkan Tingkat</button>
                                                    <button class="btn btn-success" id="addKelas">Berdasarkan Kelas</button>
                                                    <button class="btn btn-warning" id="addSiswa">Berdasarkan Siswa</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table id="tabledatapengumuman" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                                    <thead>            
                                                        <!-- <th class="sorting_asc">No.</th> -->
                                                        <th>ID Pengumuman</th>
                                                        <th>Jumlah Penerima</th>
                                                        <th>Judul</th>
                                                        <!-- <th>Content</th> -->
                                                        <th>Tanggal</th>
                                                        <th>Aksi</th>
                                                    </thead>
                                                    <tbody>
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
<!-- CONTENT -->

<!-- js -->
    <script type="text/javascript">
        var tabledatapengumuman = $('#tabledatapengumuman').DataTable({
            "responsive":true,
            "bInfo" : false,
            "ajax" : {
                "url" : "<?php echo base_url('Pengumuman/getDataAdmin');?>",
                "dataSrc": "",
                "type": "POST"
            },
            "columns": [
                // {data:"id_pengumuman"},
                {data:"id_pengumuman"},
                {data:"jml"},
                {data:"judul"},
                // {data:"message"},
                {data:"tanggal"},
                {data:"id_pengumuman",
                    render: function (data, type, row) {
                        e = '<button class="btn btn-sm btn-primary pull-up" onclick=lihatPenerima("'+data+'")><i class="la la-eye"></i></button>';
                        d = '<button class="btn btn-sm btn-danger pull-up" onclick=deleteData("'+data+'")><i class="la la-trash"></i></button>';
                        return '<div class="form-group btn-group btn-group-sm btn-block">'+
                                    e+d+
                                '</div>'
                    }
                },
            ],
            "language": {
                "decimal": ",",
                "thousands": ".",
            },
            "dom": '<"toolbar tabledatapengumuman">frtip'
        });

        $('#addTingkat').click(function(){
            $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle').addClass('white');
            $('#modaldialog').addClass('modal-lg');
            $('#modaltitle').html('Tambah Berdasarkan Tingkat');
            $('#modalbody').load("<?php echo base_url("Pengumuman/add/");?>" + "tingkat");
            $('#modal').modal('show');
            $('.modal-footer').show();
            $('.modal-footer').children('#btnSaveModal').show();
        })

        $('#addKelas').click(function(){
            $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle').addClass('white');
            $('#modaldialog').addClass('modal-lg');
            $('#modaltitle').html('Tambah Berdasarkan Kelas');
            $('#modalbody').load("<?php echo base_url("Pengumuman/add/");?>" + "kelas");
            $('#modal').modal('show');
            $('.modal-footer').show();
            $('.modal-footer').children('#btnSaveModal').show();
        })

        $('#addSiswa').click(function(){
            $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle').addClass('white');
            $('#modaldialog').addClass('modal-lg');
            $('#modaltitle').html('Tambah Berdasarkan Siswa');
            $('#modalbody').load("<?php echo base_url("Pengumuman/add/");?>" + "siswa");
            $('#modal').modal('show');
            $('.modal-footer').show();
            $('.modal-footer').children('#btnSaveModal').show();
        })

        function lihatPenerima(id) {
            $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle').addClass('white');
            $('#modaldialog').addClass('modal-lg');
            $('#modaltitle').html('List Penerima');
            $('#modalbody').load("<?php echo base_url("Pengumuman/lihatPenerima/");?>" + id);
            $('#modal').modal('show');
            $('.modal-footer').hide();
            $('.modal-footer').children('#btnSaveModal').hide();
        }

        function deleteData(id){
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
                    del(id);
                }else{
                    block(false,'.content-body');
                }
            })
        }

        function del(id){
           block(true,'.content-body');
            $.ajax({
                url : "<?php echo base_url('Pengumuman/delete/hd');?>",
                type:"POST",
                data: { id: id },
                dataType:"json",
                success:function(event, data){
                    if (event.Error == false) {
                        Swal.fire("success",event.Message,"success");
                        block(false,'.content-body');
                        tabledatapengumuman.ajax.reload(null,true); 
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
    </script>
<!-- js -->
<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h3 class="card-title text-bold-700 my-2 white"><i class="la la-users" style="font-size: 30px;"></i> Data Pengguna</h3>
                        <div class="card">            
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tabledata" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                                <thead>            
                                                    <th class="sorting_asc">No.</th>
                                                    <th>Username</th>
                                                    <th>Nama</th>
                                                    <th>Status</th>
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

                <div class="row match-height">
                    <div class="col-md-12">
                        <div class="card">            
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tabledataSiswa" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <td rowspan="2">NIS</td>
                                                        <th colspan="4">SISWA</th>
                                                        <th colspan="4">WALI</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>ID</th>
                                                        <th>Username</th>
                                                        <th>Password</th>
                                                        <th>Nama</th>
                                                        <th>ID</th>
                                                        <th>Username</th>
                                                        <th>Password</th>
                                                    </tr>
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
<!-- CONTENT -->

<!-- js -->
    <script type="text/javascript">
        var tabledata = $('#tabledata').DataTable({
            "responsive":true,
            "ajax" : {
                "url" : "<?php echo base_url('Master/Pengguna/getTableAdmin');?>",
                "dataSrc": "",
                "type": "POST"
            },
            "columns": [
                {data:"username"},
                {data:"username"},
                {data:"nama_lengkap"},
                {data:"status"},
            ],
            "language": {
                "decimal": ",",
                "thousands": ".",
            },
            "dom": '<"toolbar tabledata">frtip'
        });

        var tabledataSiswa = $('#tabledataSiswa').DataTable({
            "responsive":true,
            "ajax" : {
                "url" : "<?php echo base_url('Master/Pengguna/getTableSiswa');?>",
                "dataSrc": "",
                "type": "POST"
            },
            "columns": [
                {data:"nis"},
                {data:"siswa_name"},
                {data:"siswa_idp"},
                {data:"siswa_uname"},
                {data:"siswa_pass", 
                    render:function(data, type, row) {
                        return '<button class="btn btn-sm btn-danger" onClick=resetPass("'+row.siswa_idp+'")>Reset</button>'
                    }
                },
                {data:"wali_name"},
                {data:"wali_idp"},
                {data:"wali_uname"},
                {data:"wali_pass", 
                    render:function(data, type, row) {
                        return '<button class="btn btn-sm btn-danger" onClick=resetPass("'+row.wali_idp+'")>Reset</button>'
                    }
                },
            ],
            "language": {
                "decimal": ",",
                "thousands": ".",
            },
            "dom": '<"toolbar tabledataSiswa">frtip'
        });

        $("div.tabledata").html(
            '<button id="add" class="btn btn-primary pull-up" style="margin-top: 5px">Add</button>&nbsp;'+
            '<button id="edit" class="btn btn-info pull-up" style="margin-top: 5px">Edit</button>&nbsp;'+
            '<button id="delete" class="btn btn-danger pull-up" style="margin-top: 5px">Delete</button>&nbsp;'
        );

        $("div.tabledataSiswa").html(
            '<h3 class="text-bold-700 my-2">Data Siswa</h3>'
        );

        tabledata.on( 'order.dt search.dt', function () {
            tabledata.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        }).draw();

        tabledata.on('click', 'tr', function() {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {

                tabledata.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });

        $('#add').click(function(){
            $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle').addClass('white');
            $('#modaltitle').html('Data Entry');
            $('#modalbody').load("<?php echo base_url("Master/Pengguna/add");?>");
            $('#modal').data('id', 0);
            $('#modal').modal('show');
            $('.modal-footer').show();
        })

        $('#edit').click(function(){
            var rows = tabledata.rows('.selected').indexes();
            if (rows.length < 1) {
                Swal.fire({
                    title: "Information",
                    animation: true,
                    icon:"warning",
                    text: 'Please select a row',
                    confirmButtonText: "OK"
                });
                return;
            } 
            var data = tabledata.rows(rows).data();
            var id = data[0].id_pengguna;

            var site_url = '<?php echo base_url("Master/Pengguna/add")?>';

            $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
            $('#modaltitle').addClass('white');
            $('#modaltitle').html('Data Edit');
            $('#modalbody').load(site_url);

            $('#modal').data('id', id);
            $('#modal').modal('show');
            $('.modal-footer').show();
        })

        $('#delete').click(function(){
            var rows = tabledata.rows('.selected').indexes();
            if (rows.length < 1) {
                Swal.fire({
                    title: "Information",
                    animation: true,
                    icon:"warning",
                    text: 'Please select a row',
                    confirmButtonText: "OK"
                });
                return;
            } 

            var data = tabledata.rows(rows).data();
            var id = data[0].id_pengguna;

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
                    Delete(id);
                }else{
                    block(false,'.content-body');
                }
            })
        })

        function resetPass(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "The password will be set to default",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!'
            })
            .then((result) => {
                if (result.value) {
                    res(id);
                }else{
                    block(false,'.content-body');
                }
            })
        }

        function res(id){
           block(true,'.content-body');
            $.ajax({
                url : "<?php echo base_url('Master/Pengguna/resetPass');?>",
                type:"POST",
                data: { id: id },
                dataType:"json",
                success:function(event, data){
                    if (event.Error == false) {
                        Swal.fire({
                            title: "Information",
                            animation: true,
                            icon:"success",
                            text: event.Message,
                            confirmButtonText: "OK"
                        });
                        block(false,'.content-body');
                    }
                    else{
                        Swal.fire({
                            title: "Information",
                            animation: true,
                            icon:"error",
                            text: event.Message,
                            confirmButtonText: "OK"
                        });
                        block(false,'.content-body');
                    }
                },                    
                error: function(jqXHR, textStatus, errorThrown){        
                    Swal.fire("Information",textStatus+' Save : '+errorThrown,"warning");
                    block(false,'.content-body');
                }
            });
        }

        function Delete(id) {
           block(true,'.content-body');
            $.ajax({
                url : "<?php echo base_url('Master/Pengguna/delete');?>",
                type:"POST",
                data: { id: id },
                dataType:"json",
                success:function(event, data){
                    if (event.Error == false) {
                        Swal.fire("success",event.Message,"success");
                        block(false,'.content-body');
                        tabledata.ajax.reload(null,true); 
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

        $("#modal").on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);
        });
    </script>
<!-- js -->

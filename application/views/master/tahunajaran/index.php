<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h3 class="card-title text-bold-700 my-2 white"><i class="la la-calendar" style="font-size: 30px;"></i> Tahun Ajaran</h3>
                        <div class="card">            
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tabledata" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                                <thead>            
                                                    <th class="sorting_asc">No.</th>
                                                    <th>Tahun Ajaran</th>
                                                    <th>Status</th>
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
<!-- CONTENT -->


<!-- js -->
    <script type="text/javascript">
        var tabledata = $('#tabledata').DataTable({
            "responsive":true,
            "ajax" : {
                "url" : "<?php echo base_url('Master/TahunAjaran/getTable');?>",
                "dataSrc": "",
                "type": "POST"
            },
            "paging":   false,
            "order": [1, "desc" ],
            "columns": [
                {data:"tahun_ajaran"},
                {data:"tahun_ajaran",
                    render: function (data) {
                        var tahun1 = parseInt(data.substr(0,4))
                        var tahun2 = tahun1+1
                        var tahun =  tahun1 + ' / ' + tahun2
                        return '<div class="btn btn-block btn-md btn-bg-gradient-x-blue-green">'+tahun+'</div>'
                    }
                },
                {data:"aktif", 
                    render: function (data) {
                        if (data == 'Y') {
                            return '<div class="btn btn-block btn-md btn-bg-gradient-x-purple-blue btn-glow">AKTIF</div>'
                        }
                        else{
                            return '<div class="btn btn-block btn-md btn-bg-gradient-x-red-pink">TIDAK AKTIF</div>'
                        }
                    }
                },
                {data:"aktif",
                    render: function (data, type, row) {
                        var tahun_ajaran = row.tahun_ajaran;
                        var a, e, d;
                        if (data == 'T') {
                            a = '<button class="btn btn-sm btn-success pull-up btn-glow" onclick=activate("'+tahun_ajaran+'")><i class="ft-check-square"></i></button>';
                            e = '<button class="btn btn-sm btn-primary pull-up"  onclick=edit("'+tahun_ajaran+'")><i class="la la-pencil-square"></i></button>';
                            d = '<button class="btn btn-sm btn-danger pull-up"  onclick=deletedata("'+tahun_ajaran+'")><i class="la la-trash"></i></button>';
                        }
                        else{
                            a = '<button class="btn btn-sm btn-secondary" disabled><i class="ft-x-square"></i></button>';
                            e = '<button class="btn btn-sm btn-primary disabled"><i class="la la-pencil-square"></i></button>';
                            d = '<button class="btn btn-sm btn-danger disabled"><i class="la la-trash"></i></button>';
                        }

                        return '<div class="form-group btn-group btn-group-sm btn-block">'+
                                    a+e+d+
                                '</div>'
                    }
                },
            ],
            "language": {
                "decimal": ",",
                "thousands": ".",
            },
            "dom": '<"toolbar tabledata">frti'
        });

        $("div.tabledata").html(
            '<button id="add" class="btn btn-primary pull-up" style="margin-top: 5px">Add</button>&nbsp;'
        );

        tabledata.on( 'order.dt search.dt', function () {
            tabledata.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        }).draw();

        $('#add').click(function(){
            $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle').addClass('white');
            $('#modaltitle').html('Data Entry');
            $('#modalbody').load("<?php echo base_url("Master/TahunAjaran/add");?>");
            $('#modal').data('id', 0);
            $('#modal').modal('show');
            $('.modal-footer').show();
        })

        function edit(tahun_ajaran){
            $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
            $('#modaltitle').addClass('white');
            $('#modaltitle').html('Data Edit');
            $('#modalbody').load("<?php echo base_url("Master/TahunAjaran/add")?>");

            $('#modal').data('id', tahun_ajaran);
            $('#modal').modal('show');
            $('.modal-footer').show();
        }

        function activate(tahun_ajaran){
            $.ajax({
                url : "<?php echo base_url('Master/TahunAjaran/activate/');?>",
                type:"POST",
                data: { id: tahun_ajaran },
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

        function deletedata(tahun_ajaran){
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
                    del(tahun_ajaran);
                }else{
                    block(false,'.content-body');
                }
            })
        }

        function del(id){
           block(true,'.content-body');
            $.ajax({
                url : "<?php echo base_url('Master/TahunAjaran/delete');?>",
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
<?php $listKelas = $this->db->query('select data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas, kelas.kelas from data_siswa inner join kelas on data_siswa.status_kelas = kelas.id_kelas group by data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas order by data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas');?>

<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h3 class="card-title text-bold-700 my-2 white"><i class="la la-credit-card" style="font-size: 30px;"></i> POS Keuangan</h3>
                        <div class="card">            
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tabledata" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                                <thead>            
                                                    <th class="sorting_asc">No.</th>
                                                    <th>Nama POS</th>
                                                    <th>Keterangan</th>
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
                "url" : "<?php echo base_url('Keuangan/POS/getTable');?>",
                "dataSrc": "",
                "type": "POST"
            },
            "columns": [
                {data:"id_pos"},
                {data:"pos"},
                {data:"keterangan"},
                {data:"id_pos",
                    render: function (data, type, row) {
                        var e, d;
                        if (data == '1') {
                            e = '<div class="btn btn-sm btn-primary disabled"><i class="la la-pencil-square"></i></div>';
                            d = '<div class="btn btn-sm btn-danger disabled"><i class="la la-trash"></i></div>';
                        }
                        else{
                            e = '<button class="btn btn-sm btn-primary pull-up"  onclick=edit("'+data+'")><i class="la la-pencil-square"></i></button>';
                            d = '<button class="btn btn-sm btn-danger pull-up"  onclick=deletedata("'+data+'")><i class="la la-trash"></i></button>';
                        }

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
            "dom": '<"toolbar tabledata">frtip'
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
            $('#modalbody').load("<?php echo base_url("Keuangan/POS/add");?>");
            $('#modal').data('id', 0);
            $('#modal').data('tipe', 'add');
            $('#modal').modal('show');
            $('.modal-footer').show();
        })

        function edit(id){
            var site_url = '<?php echo base_url("Keuangan/POS/add")?>';

            $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
            $('#modaltitle').addClass('white');
            $('#modaltitle').html('Data Edit');
            $('#modalbody').load(site_url);

            $('#modal').data('id', id);
            $('#modal').data('tipe', 'edit');
            $('#modal').modal('show');
            $('.modal-footer').show();
        }

        function deletedata(id){
            Swal.fire({
                title: 'Are you sure?',
                html: "This will also delete its offspring in table <strong>Keuangan Jenis AND Keuangan Siswa</strong> <br> You won't be able to revert this!",
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
                url : "<?php echo base_url('Keuangan/POS/delete');?>",
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
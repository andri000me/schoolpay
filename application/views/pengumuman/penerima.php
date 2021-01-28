<!-- CONTENT -->
    <div class="container mx-0 px-0">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary bg-accent-2" id="headingAOne" data-toggle="collapse" data-target="#collapseA1" aria-expanded="false" aria-controls="collapseA1" style="cursor: pointer;">
                        <h5 class="mb-0">
                            <span class="float-left"><?= $data_hd[0]->judul ?></span>
                            <span class="float-right"><u>#<?= $data_hd[0]->id_pengumuman ?></u></span>
                        </h5>
                    </div>

                    <div id="collapseA1" class="collapse" aria-labelledby="headingAOne" style="">
                        <div class="card-body">
                            <?= $data_hd[0]->message; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table id="tabledatapenerima" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                    <thead>            
                        <th class="sorting_asc">No.</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Dibaca</th>
                        <th>Aksi</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- CONTENT -->

<!-- js -->
    <script type="text/javascript">
        var tabledatapenerima = $('#tabledatapenerima').DataTable({
            "responsive":true,
            "bInfo" : false,
            "ajax" : {
                "url" : "<?php echo base_url('Pengumuman/getTablePenerima/').$id;?>",
                "dataSrc": "",
                "type": "POST",
            },
            "columns": [
                {data:"rowid"},
                {data:"nis"},
                {data:"nama"},
                {data:"rowid",
                    render: function (data, type, row) {
                        return row.kelas +' - '+ row.program_studi +' - '+ row.kode_kelas
                    }
                },
                {data:"is_read",
                    render: function (data, type, row) {
                        if (data == 'Y') {
                            var d = '<span class="btn btn-block btn-success"><i class="la la-check"></i></span>';
                        }
                        else{
                            var d = '<span class="btn btn-block btn-warning"><i class="la la-close"></i></span>';
                        }
                        return d
                    }
                },
                {data:"rowid",
                    render: function (data, type, row) {
                        return '<button class="btn btn-block btn-danger" onclick=deleteData("'+data+'")><i class="la la-trash"></i></button>';
                    }
                },
            ],
            "language": {
                "decimal": ",",
                "thousands": ".",
            },
            "dom": '<"toolbar tabledatapenerima">frtip'
        });

        tabledatapenerima.on( 'order.dt search.dt', function () {
            tabledatapenerima.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        }).draw();

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
                url : "<?php echo base_url('Pengumuman/delete/dt');?>",
                type:"POST",
                data: { id: id },
                dataType:"json",
                success:function(event, data){
                    if (event.Error == false) {
                        Swal.fire("success",event.Message,"success");
                        block(false,'.content-body');
                        tabledatapenerima.ajax.reload(null,true); 
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
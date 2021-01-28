<?php $listKelas = $this->db->query('select data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas, kelas.kelas from data_siswa inner join kelas on data_siswa.status_kelas = kelas.id_kelas group by data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas order by data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas');?>

<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12">
                    <h3 class="card-title text-bold-700 my-2 white"><i class="la la-group" style="font-size: 30px;"></i> Data Siswa</h3>
                </div>
                <div class="content-header-right col-md-6 col-12">
                    <form action="">
                        <select name="kelas" class="form-control float-right mt-2" onchange="this.form.submit()" style="width: 205px;">
                            <option value=''>Pilih Kelas</option>
                            <?php
                            foreach($listKelas->result() as $lkls):
                                $selected = '';
                                if(isset($_GET['kelas']))
                                    if($_GET['kelas'] == $lkls->status_kelas.'.'.$lkls->program_studi.'.'.$lkls->kode_kelas)
                                        $selected = ' selected';
                                echo '<option value="'.$lkls->status_kelas.'.'.$lkls->program_studi.'.'.$lkls->kode_kelas.'"'.$selected.'>'.$lkls->kelas.' '.$lkls->program_studi.' '.$lkls->kode_kelas.'</option>';
                            endforeach;
                            ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="card">            
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="tabledata" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                                                <thead>            
                                                    <th class="sorting_asc">No.</th>
                                                    <th>NIS</th>
                                                    <th>Nama</th>
                                                    <th>Kelas</th>
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
        var get = "?kelas=<?=  isset($_GET['kelas']) ? $_GET['kelas'] : '' ?>"
        var tabledata = $('#tabledata').DataTable({
            "responsive":true,
            "ajax" : {
                "url" : "<?php echo base_url('Siswa/Siswa/getTable');?>"+get,
                "dataSrc": "",
                "type": "POST"
            },
            "columns": [
                {data:"nis"},
                {data:"nis"},
                {data:"nama"},
                {data:"kelas", 
                    render: function (data, type, row) {
                        var kelas = row.kelas,
                            prodi = row.program_studi,
                            kode  = row.kode_kelas;
                        return '<div class="btn btn-block btn-md btn-bg-gradient-x-purple-blue">'+ kelas +' '+ prodi + ' ' + kode +'</div>'
                    }
                },
                {data:"lulus", 
                    render: function (data) {
                        if (data == 'Y') {
                            return 'Alumni'
                        }
                        else{
                            return ' Siswa'
                        }
                    }
                },
                {data:"nis",
                    render: function (data, type, row) {
                        var nis = row.nis;
                        var a, e, d;
                        if (row.aktif == 'Y') {
                            a = '<button class="btn btn-sm btn-warning pull-up btn-glow" onclick=activate("'+nis+'")><i class="ft-x-square"></i></button>';
                        }
                        else{
                            a = '<button class="btn btn-sm btn-success pull-up btn-glow" onclick=activate("'+nis+'")><i class="ft-check-square"></i></button>';
                        }
                            e = '<button class="btn btn-sm btn-primary pull-up"  onclick=edit("'+nis+'")><i class="la la-pencil-square"></i></button>';
                            d = '<button class="btn btn-sm btn-danger pull-up"  onclick=deletedata("'+nis+'")><i class="la la-trash"></i></button>';

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
            "dom": '<"toolbar tabledata">frtip'
        });

        $("div.tabledata").html(
            '<button id="add" class="btn btn-primary pull-up" style="margin-top: 5px">Add</button>&nbsp;'+
            '<button id="upload" class="btn btn-success pull-up" style="margin-top: 5px">Upload</button>&nbsp;'
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
            $('#modalbody').load("<?php echo base_url("Siswa/Siswa/add");?>");
            $('#modal').data('id', 0);
            $('#modal').data('tipe', 'add');
            $('#modal').modal('show');
            $('.modal-footer').show();
        })

        $('#upload').click(function(){
            $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle').addClass('white');
            $('#modaltitle').html('Upload Data Siswa');
            $('#modalbody').load("<?php echo base_url("Siswa/Siswa/upload");?>");
            $('#modal').modal('show');
            $('.modal-footer').hide();
        })

        function edit(nis){
            var site_url = '<?php echo base_url("Siswa/Siswa/add")?>';

            $('#modalheader').removeClass('bg-primary').addClass('bg-info white');
            $('#modaltitle').addClass('white');
            $('#modaltitle').html('Data Edit');
            $('#modalbody').load(site_url);

            $('#modal').data('id', nis);
            $('#modal').data('tipe', 'edit');
            $('#modal').modal('show');
            $('.modal-footer').show();
        }

        function activate(nis){
            $.ajax({
                url : "<?php echo base_url('Siswa/Siswa/activate/');?>",
                type:"POST",
                data: { id: nis },
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

        function deletedata(nis){
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
                    del(nis);
                }else{
                    block(false,'.content-body');
                }
            })
        }

        function del(id){
           block(true,'.content-body');
            $.ajax({
                url : "<?php echo base_url('Siswa/Siswa/delete');?>",
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
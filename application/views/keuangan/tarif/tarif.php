<?php $tarif = $this->db->query("SELECT tahun_ajaran, nama_pembayaran from keuangan_jenis where id_jenis = '".$jenis."'")->result()[0];?>

<!-- CONTENT -->
    <div class="container">
        <div class="row">
            <div class="col-6 form-group">
            	<input type="text" value="<?=substr($tarif->tahun_ajaran,0,4).'/'.(substr($tarif->tahun_ajaran,0,4)+1);?>" name="tahun_ajaran2" class="form-control" readonly>
                <input type="hidden" value="<?=$tarif->tahun_ajaran;?>" id="tahun_ajaran" name="tahun_ajaran">
            </div>
            <div class="col-6 form-group">
                <select class="reloadTarif select2 form-control" id="listkelas" name="listkelas">
                    <?php foreach ($kelas as $kls):?>
                        <option value="<?= $kls->id_kelas.'.'.$kls->id_program_studi.'.'.$kls->kode_kelas ?>"> 
                            <?= $kls->id_kelas.' - '.$kls->id_program_studi.' - '.$kls->kode_kelas ?> 
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="colTambahData">
                <h5 class="title">Tambah Data : </h5>
                <div class="form-group btn-group btn-group-md btn-block">
                    <button class="btn btn-primary" id="addTingkat">Berdasarkan Tingkat</button>
                    <button class="btn btn-success" id="addKelas">Berdasarkan Kelas</button>
                    <button class="btn btn-warning" id="addSiswa">Berdasarkan Siswa</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table id="tabledatatarif" class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
                    <thead>            
                        <th class="sorting_asc">No.</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <!-- <th>Kelas</th> -->
                        <th>Tarif</th>
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
        $(document).ready(function (){
            $('#listkelas').select2({
                width:'100%',
                placeholder: 'Pilih Kelas'
            });

            $('#listkelas').change(function (e){
                e.stopImmediatePropagation();
                tabledatatarif.draw();
            });
        });

        var tabledatatarif = $('#tabledatatarif').DataTable({
            "serverSide":true,
            "responsive":true,
            "bInfo" : false,
            "ajax" : {
                "url" : "<?php echo base_url('Keuangan/Jenis/getTableTarif');?>",
                "dataSrc": "",
                "type": "POST",
                "data": function(d) {
                    d.tahun_ajaran  = $('#tahun_ajaran').val();
                    d.listkelas     = ($('#listkelas option:selected').val() ? $('#listkelas option:selected').val() : '');;
                    d.jenis         = "<?= $jenis ?>";
                    d.tipe          = "<?= $tipe ?>";
                }
            },
            "columns": [
                {data:"nis"},
                {data:"nis"},
                {data:"nama"},
                // {data:"nis",
                //     render: function (data, type, row) {
                //         return row.kelasku +' - '+ row.program_studi +' - '+ row.kode_kelas
                //     }
                // },
                {data:"nis",
                    render: function (data, type, row) {
                        if (row.tipe == 'Bulanan') {
                            var tarif   = row.b1+'.'+row.b2+'.'+row.b3+'.'+row.b4+'.'+row.b5+'.'+row.b6+'.'+row.b7+'.'+row.b8+'.'+row.b9+'.'+row.b10+'.'+row.b11+'.'+row.b12;
                            var nama    = row.nama;
                            var nis     = row.nis;
                            var kelas   = row.kelasku +'.'+ row.program_studi +'.'+ row.kode_kelas;
                            return '<button class="btn btn-md btn-primary btn-block" onclick=lihatTarif("'+nis+'","'+kelas+'","'+tarif+'")>'+
                                            'Lihat'+
                                    '</button>'
                        }
                        else{
                            return 'Rp. ' + number_format(row.b1, 0, ',', '.');
                        }
                    }
                },
                {data:"nis",
                    render: function (data, type, row) {
                        var e = '<button class="btn btn-sm btn-primary" onclick=editdataTarif("'+row.id_siswa_bulanan+'")><i class="la la-pencil-square"></i></button>';
                        var d = '<button class="btn btn-sm btn-danger" onclick=deletedataTarif("'+row.id_siswa_bulanan+'")><i class="la la-trash"></i></button>';
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
            "dom": '<"toolbar tabledatatarif">frti'
        });

        function lihatTarif(nis, kelas, tarif){
            $('#modal2').data('nis',     nis);
            $('#modal2').data('kelas',   kelas);
            $('#modal2').data('tarif',   tarif);

            $('#modalheader2').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle2').addClass('white');
            $('#modaldialog2').addClass('modal-lg');
            $('#modaltitle2').html('Biaya Yang Harus Dibayar Siswa');
            $('#modalbody2').load("<?php echo base_url("Keuangan/Jenis/lihatTarif/");?>"+nis);
            $('#modal2').modal('show');
            $('#modalfooter2').show();
            $('#modalfooter2').children('#btnSaveModal2').hide();
        }

        function editdataTarif(id){
            $('#modal2').data('id', id);

            $('#modalheader2').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle2').addClass('white');
            $('#modaldialog2').addClass('modal-lg');
            $('#modaltitle2').html('Edit Tarif Siswa');
            $('#modalbody2').load("<?php echo base_url("Keuangan/Jenis/editTarif/");?>" + id);
            $('#modal2').modal('show');
            $('#modalfooter2').show();
            $('#modalfooter2').children('#btnSaveModal2').show();
        }

        $('#addTingkat').click(function(){
            $('#modalheader2').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle2').addClass('white');
            $('#modaldialog2').addClass('modal-lg');
            $('#modaltitle2').html('Tambah Berdasarkan Tingkat');
            $('#modalbody2').load("<?php echo base_url("Keuangan/Jenis/addTarif/");?>" + "<?=$jenis?>" + "/" + "<?=$tipe?>" + "/tingkat");
            $('#modal2').modal('show');
            $('#modalfooter2').show();
            $('#modalfooter2').children('#btnSaveModal2').show();
        })

        $('#addKelas').click(function(){
            $('#modalheader2').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle2').addClass('white');
            $('#modaldialog2').addClass('modal-lg');
            $('#modaltitle2').html('Tambah Berdasarkan Kelas');
            $('#modalbody2').load("<?php echo base_url("Keuangan/Jenis/addTarif/");?>" + "<?=$jenis?>" + "/" + "<?=$tipe?>" +"/kelas");
            $('#modal2').modal('show');
            $('#modalfooter2').show();
            $('#modalfooter2').children('#btnSaveModal2').show();
        })

        $('#addSiswa').click(function(){
            $('#modalheader2').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle2').addClass('white');
            $('#modaldialog2').addClass('modal-lg');
            $('#modaltitle2').html('Tambah Berdasarkan Siswa');
            $('#modalbody2').load("<?php echo base_url("Keuangan/Jenis/addTarif/");?>" + "<?=$jenis?>" + "/" + "<?=$tipe?>" +"/siswa");
            $('#modal2').modal('show');
            $('#modalfooter2').show();
            $('#modalfooter2').children('#btnSaveModal2').show();
        })

        function deletedataTarif(id){
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
                    delTarif(id);
                }else{
                    block(false,'.content-body');
                }
            })
        }

        function delTarif(id){
           block(true,'.content-body');
            $.ajax({
                url : "<?php echo base_url('Keuangan/Jenis/deleteTarif');?>",
                type:"POST",
                data: { id: id },
                dataType:"json",
                success:function(event, data){
                    if (event.Error == false) {
                        Swal.fire("success",event.Message,"success");
                        block(false,'.content-body');
                        tabledatatarif.ajax.reload(null,true); 
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

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
    </script>
<!-- js -->
<style>
    #tableSiswaWrapper{
        overflow-y:auto;
        max-height: 30vh;
    }
</style>

<!-- CONTENT -->
	<form class="form-horizontal" id="frmEditor" action="" method="post">
        <div class="form-body">
            <div class="row form-group">
                <input type="hidden" value="<?=$berdasarkan;?>" id="berdasarkan" name="berdasarkan">
                <div class="col-md-12" id="berdasarkanTingkat">
                    <label>Pilih Kelas</label>
                    <table class="table table-bordered">
                        <thead>
                            <th width="35">No</th>
                            <th width="35" class="text-center"><input type="checkbox" class="checkall"></th>
                            <!-- <input type="checkbox" class="cawalall2"> -->
                            <th>Kelas</th>
                            <th>Jumlah</th>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                foreach($tingkat as $tkt){?>
                                    <tr>
                                        <td><?=$no;?></td>
                                        <td class="text-center"><input type="checkbox" class="checkkall" name="kelas[]" value="<?=$tkt->id_kelas;?>"></td>
                                        <td class="text-center"><?=$tkt->kelas;?></td>
                                        <td><?=$tkt->jml_siswa;?> Siswa</td>
                                    </tr><?php 
                                    $no += 1;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12" id="berdasarkanKelas">
                    <label>Pilih Kelas</label>
                    <table class="table table-bordered">
                        <thead>
                            <th width="35">No</th>
                            <th width="35" class="text-center"><input type="checkbox" class="checkall"></th>
                            <th>Kelas</th>
                            <th>Jumlah</th>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                foreach($kelas as $lkls){?>
                                    <tr>
                                        <td><?=$no;?></td>
                                        <td class="text-center"><input type="checkbox" class="checkkall" name="kelas[]" value="<?=$lkls->status_kelas.'.'.$lkls->program_studi.'.'.$lkls->kode_kelas;?>"></td>
                                        <td class="text-center"><?=$lkls->kelas.' '.$lkls->program_studi.' '.$lkls->kode_kelas;?></td>
                                        <td><?=$lkls->jml_siswa;?> Siswa</td>
                                    </tr><?php
                                    $no += 1;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12" id="berdasarkanSiswa">
                    <label for="pilihkelas">Pilih Kelas</label>
                    <select class="select2 form-control" id="pilihkelas" name="pilihkelas">
                        <option value=""></option>
                        <?php foreach ($siswa as $kls):?>
                            <option value="<?= $kls->status_kelas.'.'.$kls->program_studi.'.'.$kls->kode_kelas ?>"> 
                                <?= $kls->kelas.' - '.$kls->program_studi.' - '.$kls->kode_kelas ?> 
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <div id="tableSiswaWrapper">
                        <table class="table table-bordered" id="tableSiswa">
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h4 class="btn btn-md btn-block btn-bg-gradient-x-blue-cyan">
                        Pengumuman
                    </h4>

                    <br>
                    
                    <div class="form-group">
                        <div class="position-relative has-icon-left">
                            <input type="text" id="judul" class="form-control" name="judul" placeholder="Judul" required>
                            <div class="form-control-position">
                                <i class="la la-font"></i>
                            </div>
                        </div>
                    </div>
                    <div class="position-relative has-icon-left">
                        <textarea rows="5" id="isi_pengumuman" class="form-control" name="isi_pengumuman" placeholder="Pengumuman" required></textarea>
                        <div class="form-control-position">
                            <i class="la la-bell"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<!-- CONTENT -->

<script type="text/javascript" src="<?= base_url('app-assets/vendors/js/editors/tinymce/tinymce.min.js'); ?>"></script>

<!-- SCRIPT -->
	<script type="text/javascript">
        $(document).ready(function() {
            tinymce.init({
                selector: 'textarea#isi_pengumuman',
                plugins: 'link,code,preview,autolink',             
                height: 200,
                toolbar: "undo redo | styleselect | bold italic| alignleft aligncenter alignright alignjustify | bullist numlist | link image | preview code"
            });

            $('.select2').select2({
                width:'100%',
                placeholder: 'Pilih Kelas'
            });

            var berdasarkan = "<?= $berdasarkan ?>";
            if (berdasarkan == 'tingkat') {
                $('#berdasarkanKelas').remove();
                $('#berdasarkanSiswa').remove();
            }
            else if(berdasarkan == 'kelas'){
                $('#berdasarkanTingkat').remove();
                $('#berdasarkanSiswa').remove();
            }
            else{
                $('#berdasarkanTingkat').remove();
                $('#berdasarkanKelas').remove();
            }

            $('#btnSaveModal').click(function(event){
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    tinyMCE.triggerSave();
                    var datafrm = $('#frmEditor').serializeArray();
                    $.ajax({
                        url : "<?php echo base_url('Pengumuman/save');?>",
                        type:"POST",
                        data: datafrm,
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
                                tabledatapengumuman.ajax.reload(null,true);
                                $('#modal').modal('hide');
                            }
                            else{
                                Swal.fire({
                                    title: "Information",
                                    animation: true,
                                    icon:"error",
                                    text: event.Message,
                                    confirmButtonText: "OK"
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            Swal.fire({
                                title: "Error",
                                animation: true,
                                icon:"error",
                                text: textStatus+' Save : '+errorThrown,
                                confirmButtonText: "OK"
                            });
                        }
                    });
                }
            });
        });

        $('.checkall').click(function(e){
            if(this.checked){
                $('.checkkall').each(function(){
                    this.checked = true;
                });
            }else{
                $('.checkkall').each(function(){
                    this.checked = false;
                });
            }
        });

        $('#pilihkelas').change(function (e){
            var kelas = $('#pilihkelas').val();
            // e.stopImmediatePropagation();
            $.ajax({
                url     : "<?= base_url('Keuangan/Jenis/getListSiswa/')?>" + kelas,
                type    : "POST",
                success:function(data, event){
                    var data = JSON.parse(data);
                    if (data.length > 0) {
                        var table = 
                        '<thead>'+
                            '<th width="35">No</th>'+
                            '<th width="35" class="text-center">Pilih</th>'+
                            '<th>Nama Siswa</th>'+
                        '</thead>'+
                        '<tbody>';
                        for (var i = 0; i < data.length; i++) {
                            table += '<tr>';
                            table += '  <td>'+ (parseInt(i)+1); +'</td>';
                            table += '  <td><input type="checkbox" class="checkkall" name="nis[]" value="'+ data[i].nis +'"></td>';
                            table += '  <td>'+ data[i].nama +'</td>';
                            table += '</tr>';
                        }
                        table += '</tbody>';
                        $('#tableSiswa').html(table);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                    Swal.fire({
                        title: "Error",
                        animation: true,
                        icon:"error",
                        text: textStatus+' error : '+errorThrown,
                        confirmButtonText: "OK"
                    });
                }
            });
        });

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
            tinymce.remove('#isi_pengumuman');
        });
	</script>
<!-- SCRIPT -->
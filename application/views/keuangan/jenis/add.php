<!-- CONTENT -->
	<form class="form-horizontal" id="frmEditor" action="" method="post">
        <div class="form-body">
            <input type="text" id="tipe" name="tipe">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pos">POS</label>
                        <div class="position-relative has-icon-left">
                            <select name="pos" id="pos" class="select2 form-control">
                                <option value=""></option>
                                <?php foreach ($keuangan_pos as $pos): ?>
                                    <option value="<?= $pos->id_pos ?>"> <?= $pos->pos ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahunajaran">Tahun Ajaran</label>
                        <div class="position-relative has-icon-left">
                            <select name="tahunajaran" id="tahunajaran" class="select2 form-control">
                                <option value=""></option> 
                                <?php foreach ($tahun_ajaran as $tahun):?>
                                    <option value="<?= $tahun->tahun_ajaran?>"> <?= (substr($tahun->tahun_ajaran, 0, 4).'/'.(substr($tahun->tahun_ajaran, 0, 4) + 1)) ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="nama_pembayaran">Nama Pembayaran</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="nama_pembayaran" class="form-control" name="nama_pembayaran" placeholder="Nama Pembayaran" required>
                    <div class="form-control-position">
                        <i class="la la-font"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="tipepembayaran">Tipe</label>
                <div class="custom-control custom-radio">
                    <input type="radio" value="Bulanan" id="bulanan" name="tipepembayaran" class="custom-control-input" checked>
                    <label class="custom-control-label" for="bulanan">Bulanan</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" value="Bebas" id="bebas" name="tipepembayaran" class="custom-control-input">
                    <label class="custom-control-label" for="bebas">Bebas</label>
                </div>
            </div>

        </div>
    </form>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
		$(document).ready(function () {
			loaddata();
            $('#pos').select2({
                width:'100%',
                placeholder: 'Pilih POS'
            });
            $('#tahunajaran').select2({
                width:'100%',
                placeholder: 'Pilih Tahun Ajaran'
            });

            $('#tipe').hide();

            $('#frmEditor').validate({
                ignore: "",
                rules: {
                    pos:{
                        required:true
                    },
                    tahunajaran:{
                        required:true
                    },
                    nama_pembayaran:{
                        required:true
                    }
                },
                messages: {
                    pos:{
                        required:"Harus di isi"
                    },
                    tahunajaran:{
                        required:"Harus di isi"
                    },
                    nama_pembayaran:{
                        required:"Harus di isi"
                    }
                },
                errorElement: "em",
                highlight: function (element, errorClass, validClass) {
                        $(element).addClass(errorClass); //.removeClass(errorClass);
                        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass(errorClass); //.addClass(validClass);
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                },
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } 
                    else if (element.hasClass('select2_demo_1') || element.hasClass('checkbox-inline') || element.hasClass('radio-inline')){
                        error.insertAfter(element.next('span'));
                    } 
                    else {
                        error.insertAfter(element);
                    }
                }
            });

            $('#btnSaveModal').click(function(event){
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    if ($('#frmEditor').valid()) {
                        var id = $('#modal').data('id');
                        var datafrm = $('#frmEditor').serializeArray();
                        datafrm.push({
                            "name": "id","value": id
                        })
                        $.ajax({
                            url : "<?php echo base_url('Keuangan/Jenis/save');?>",
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
                                    tabledata.ajax.reload(null,true);
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
                    else{
                    }
                }
            });
		});
		
		function loaddata(){
            var id = $('#modal').data('id');
            var tipe = $('#modal').data('tipe');
            $('#tipe').val(tipe);

            if (id > 0) {
                $.getJSON("<?php echo base_url('Keuangan/Jenis/getByID');?>" + "/" + id, function (data) {
                    $('#pos').val(data[0].pos).trigger('change');
                    $('#tahunajaran').val(data[0].tahun_ajaran).trigger('change');
                    $('#nama_pembayaran').val(data[0].nama_pembayaran);
                    if (data[0].tipe == 'Bulanan') {
                        $('#bulanan').val(data[0].tipe).trigger('click');
                    }
                    else{
                        $('#bebas').val(data[0].tipe).trigger('click');
                    }
                });
            }
        }

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->
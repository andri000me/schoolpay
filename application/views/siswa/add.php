<!-- CONTENT -->
	<form class="form-horizontal" id="frmEditor" action="" method="post">
        <div class="form-body">
            <input type="text" id="tipe" name="tipe">

            <div class="form-group">
                <label for="nis">Nomor Induk Siswa</label>
                <div class="position-relative has-icon-left" id="form-nis">
                    <input type="text" id="nis" class="form-control" name="nis" placeholder="Nomor Induk Siswa" required>
                    <div class="form-control-position">
                        <i class="la la-barcode"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="nama">Nama</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="nama" class="form-control" name="nama" placeholder="Nama" required>
                    <div class="form-control-position">
                        <i class="la la-font"></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <div class="position-relative has-icon-left">
                            <select name="kelas" id="kelas" class="select2 form-control">
                                <option value=""></option>
                                <?php foreach ($kelas as $kel): ?>
                                    <option value="<?= $kel->id_kelas ?>"> <?= $kel->kelas ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="prodi">Program Studi</label>
                        <div class="position-relative has-icon-left">
                            <select name="prodi" id="prodi" class="select2 form-control">
                                <option value=""></option> 
                                <?php foreach ($program_studi as $prodi): ?>
                                    <option value="<?= $prodi->id_program_studi ?>"> <?= $prodi->program_studi ?> </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="rombel">Rombongan Belajar</label>
                <div class="position-relative has-icon-left">
                    <select name="rombel" id="rombel" class="select2 form-control">
                        <option value=""></option> 
                        <?php foreach ($rombel as $ro): ?>
                            <option value="<?= $ro->id ?>"> <?= $ro->id ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <div class="custom-control custom-radio">
                    <input type="radio" value="Y" id="aktif" name="status" class="custom-control-input" checked>
                    <label class="custom-control-label" for="aktif">AKTIF</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" value="N" id="non" name="status" class="custom-control-input">
                    <label class="custom-control-label" for="non">NON-AKTIF</label>
                </div>
            </div>
        </div>
    </form>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
		$(document).ready(function () {
			loaddata();
            $('#tipe').hide();

			$('#kelas').select2({
				width:'100%',
				placeholder: 'Pilih Kelas'
			});
            $('#prodi').select2({
                width:'100%',
                placeholder: 'Pilih Program Studi'
            });
            $('#rombel').select2({
                width:'100%',
                placeholder: 'Pilih Rombongan Belajar'
            });

            $('#frmEditor').validate({
                ignore: "",
                rules: {
                    nis:{
                        required:true,
                        number:true,
                    },
                    nama:{
                        required:true
                    },
                    kelas:{
                        required:true
                    },
                    prodi:{
                        required:true
                    },
                    rombel:{
                        required:true
                    }
                },
                messages: {
                    nis:{
                        required:"Harus di isi",
                        number:"Harus berupa angka",
                    },
                    nama:{
                        required:"Harus di isi"
                    },
                    kelas:{
                        required:"Harus di isi"
                    },
                    prodi:{
                        required:"Harus di isi"
                    },
                    rombel:{
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
                        var datafrm = $('#frmEditor').serializeArray();
                        $.ajax({
                            url : "<?php echo base_url('Siswa/Siswa/save');?>",
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
                $.getJSON("<?php echo base_url('Siswa/Siswa/getByID');?>" + "/" + id, function (data) {
                    $('#nis').val(data[0].nis);
                    $('#nis').hide();
                    $('#form-nis').append('<h3 class="form-control">'+ data[0].nis +'</h3>');
                    $('#form-nis').closest('.form-group').addClass('disabled');
                    $('#nama').val(data[0].nama);
                    $('#kelas').val(data[0].status_kelas).trigger('change');
                    $('#prodi').val(data[0].program_studi).trigger('change');
                    $('#rombel').val(data[0].kode_kelas).trigger('change');
                    if (data[0].aktif == 'Y') {
                        $('#aktif').val(data[0].aktif).trigger('click');
                    }
                    else{
                        $('#non').val(data[0].aktif).trigger('click');
                    }
                });
            }
        }

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->
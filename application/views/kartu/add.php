<!-- sctipt -->
    <link href="<?= base_url('app-assets/css/plugins/pickers/datepicker/datepicker3.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('app-assets/css/plugins/pickers/clockpicker/bootstrap-clockpicker.min.css') ?>" rel="stylesheet">

    <script src="<?= base_url('app-assets/js/scripts/pickers/datepicker/datepicker.min.js') ?>"></script>
    <script src="<?= base_url('app-assets/js/scripts/pickers/clockpicker/bootstrap-clockpicker.min.js') ?>"></script>
<!-- sctipt -->

<!-- CONTENT -->
	<form class="form-horizontal" id="frmEditor" action="" method="post">
        <div class="form-body">
            <div class="form-group">
                <label for="id_ujian">ID UJIAN</label>
                <div class="position-relative has-icon-left">
                    <input type="hidden" id="id_ujian" name="id_ujian" value="0">
                    <input type="text" id="id_ujian2" name="id_ujian2" class="form-control" placeholder="ID ujian akan otomatis terisi" disabled>
                    <div class="form-control-position">
                        <i class="la la-barcode"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="nama">Nama Ujian</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="nama" class="form-control" name="nama" placeholder="Nama" required>
                    <div class="form-control-position">
                        <i class="la la-font"></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group date">
                        <label for="tanggal_ujian">Tanggal Ujian</label>
                        <div class="position-relative has-icon-left">
                            <input id="tanggal_ujian" name="tanggal_ujian" type="text" class="form-control" placeholder="Tanggal Ujian" value="<?= date('d F Y'); ?>" required>
                            <div class="form-control-position">
                                <i class="la la-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group clockpicker" data-autoclose="true">
                        <label for="jam_ujian">Jam Ujian</label>
                        <div class="position-relative has-icon-left">
                            <input id="jam_ujian" name="jam_ujian" type="text" class="form-control" placeholder="Jam Ujian" value="<?= date('H:i'); ?>" required>
                            <div class="form-control-position">
                                <i class="la la-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="tipe_ujian">Tipe Ujian</label>
                <div class="position-relative has-icon-left">
                    <select name="tipe_ujian" id="tipe_ujian" class="select2 form-control">
                        <option value="offline">OFFLINE</option>
                        <option value="online">ONLINE</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="max_siswa">Max Siswa</label>
                        <div class="position-relative has-icon-left">
                            <input type="number" id="max_siswa" class="form-control" name="max_siswa" placeholder="Max siswa per ruangan" value="20" required>
                            <div class="form-control-position">
                                <i class="la la-sliders"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <div class="position-relative has-icon-left">
                            <textarea id="keterangan" class="form-control" name="keterangan" placeholder="Keterangan" cols="30" rows="5"></textarea>
                            <div class="form-control-position">
                                <i class="la la-list"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
        $('#tanggal_ujian').datepicker({
            format: 'dd MM yyyy',
            autoclose: true,
            orientation: 'bottom',
            startDate: '0d',
            toggleActive: true,
            todayHighlight: true,
            maxViewMode: 'year'
        });

        $('#jam_ujian').clockpicker({
            placement: 'bottom',
            autoclose: true,
            donetext: 'Done'
        });

		$(document).ready(function () {
			loaddata();

			$('#tipe_ujian').select2({
				width:'100%',
				placeholder: 'Pilih Tipe Ujian'
			});

            $('#frmEditor').validate({
                ignore: "",
                rules: {
                    nis:{
                        required:true,
                    },
                    nama:{
                        required:true
                    },
                    tanggal_ujian:{
                        required:true
                    },
                    jam_ujian:{
                        required:true
                    },
                    tipe_ujian:{
                        required:true
                    },
                    max_siswa:{
                        required:true
                    }
                },
                messages: {
                    nis:{
                        required:"Harus di isi",
                    },
                    nama:{
                        required:"Harus di isi"
                    },
                    tanggal_ujian:{
                        required:"Harus di isi"
                    },
                    jam_ujian:{
                        required:"Harus di isi"
                    },
                    tipe_ujian:{
                        required:"Harus di isi"
                    },
                    max_siswa:{
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
                            url : "<?php echo base_url('Kartu/saveUjian');?>",
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
                                    tableDataUjian.ajax.reload(null,true);
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
            if (id > 0) {
                $.getJSON("<?php echo base_url('Kartu/getUjian');?>" + "/" + id, function (data) {
                    $('#id_ujian').val(data.id_ujian);
                    $('#id_ujian2').val(data.id_ujian);
                    $('#nama').val(data.nama_ujian);
                    $('#tanggal_ujian').val(data.tanggal_ujian);
                    $('#max_siswa').val(data.max_siswa);
                    $('#keterangan').val(data.keterangan);
                    $('#tipe_ujian').val(data.tipe_ujian).trigger('change');

                    var newDateTime = new Date(data.tanggal_ujian);
                    var month = ['January','February','March','April','May','June','July','August','September','October','November','December'];

                    var tanggal = newDateTime.getDate() +' '+ month[newDateTime.getMonth()] +' '+ newDateTime.getFullYear();
                    var jam     = newDateTime.getHours() +':'+ newDateTime.getMinutes();
                    
                    $('#tanggal_ujian').val(tanggal);
                    $('#jam_ujian').val(jam);
                });
            }
        }

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->
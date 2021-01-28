<!-- CONTENT -->
	<form class="form-horizontal" id="frmEditor" method="post">
        <div class="form-body">

            <div class="row">
                <div class="col-md-6" id="form-tahunAjaran">
                    <div class="form-group">
                        <label for="tahunAjaran">Tahun Ajaran</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="tahunAjaran" class="form-control" name="tahunAjaran" placeholder="Tahun Ajaran" autofocus required>
                            <div class="form-control-position">
                                <i class="la la-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6" id="form-tahunAjaran2">
                    <div class="form-group">
                        <label for="tahunAjaran">Tahun Ajaran</label>
                        <div class="position-relative has-icon-left">
                            <input type="text" id="tahunAjaran2" class="form-control" name="tahunAjaran2" placeholder="Tahun Ajaran" disabled>
                            <div class="form-control-position">
                                <i class="la la-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="form-status">
                <label for="status">Status</label>
                <div class="position-relative has-icon-left">
                    <select name="status" id="status" class="select2 form-control">
                        <option value=""></option> 
                        <option value="Y">AKTIF</option> 
                        <option value="T">TIDAK AKTIF</option> 
                    </select>
                </div>
            </div>

        </div>
    </form>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
		$(document).ready(function () {
			loaddata();

            $('#id_pengguna2').hide();

			$('.select2').select2({
				width:'100%',
				placeholder: 'Pilih status'
			});

            $('#frmEditor').validate({
                ignore: "",
                rules: {
                    tahunAjaran:{
                        required:true,
                        number:true,
                        maxlength:4
                    }
                },
                messages: {
                    tahunAjaran:{
                        required:"Harus di isi",
                        number:"Harus berupa angka",
                        maxlength:"Maksimal 4 angka"
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
                            "name": "tahun","value": id
                        })
                        $.ajax({
                            url : "<?php echo base_url('Master/TahunAjaran/save');?>",
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
            if (id > 0) {
                $.getJSON("<?php echo base_url('Master/TahunAjaran/getByID');?>" + "/" + id, function (data) {
                    var tahun1 = parseInt(data[0].tahun_ajaran.substr(0,4));
                    var tahun2 = tahun1+1;
                    $('#tahunAjaran').val(tahun1);
                    $('#tahunAjaran2').val(tahun2);
                    $('#form-status').hide();
                });
            }
            else{
                $('#form-tahunAjaran').removeClass("col-md-6").addClass("col-12");
                $('#form-tahunAjaran2').hide();
            }
        }

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->
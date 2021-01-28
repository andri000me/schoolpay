<!-- CONTENT -->
	<form class="form-horizontal" id="frmEditor" method="post">
        <div class="form-body">

            <div class="form-group">
                <label for="id_pengguna">ID Pengguna</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="id_pengguna" class="form-control" name="id_pengguna" placeholder="Automatically Generated" disabled>
                    <input type="text" id="id_pengguna2" class="form-control" name="id_pengguna">
                    <div class="form-control-position">
                        <i class="la la-credit-card"></i>
                    </div>
                </div>
            </div>
        
            <div class="form-group">
                <label for="username">Username</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="username" class="form-control" name="username" placeholder="Username">
                    <div class="form-control-position">
                        <i class="la la-user"></i>
                    </div>
                </div>
            </div>

            <div class="form-group" id="form-password">
                <label for="password">Password</label>
                <div class="position-relative has-icon-left">
                    <input type="password" id="password" class="form-control" name="password" placeholder="Password">
                    <div class="form-control-position">
                        <i class="la la-lock"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="namaLengkap">Nama Lengkap</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="namaLengkap" class="form-control" name="namaLengkap" placeholder="Nama Lengkap">
                    <div class="form-control-position">
                        <i class="la la-font"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <div class="position-relative has-icon-left">
                    <select name="status" id="status" class="select2 form-control" >
                        <option value=""></option> 
                        <option value="admin">ADMIN</option> 
                        <!-- <option value="siswa">SISWA</option> --> 
                        <option value="wali">WALI</option> 
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
                    username:{
                        required:true
                    },
                    namaLengkap:{
                        required:true
                    },
                    status:{
                        required:true
                    }
                },
                messages: {
                    username:{
                        required:"Harus di isi"
                    },
                    namaLengkap:{
                        required:"Harus di isi"
                    },
                    status:{
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
                            "name": "id_pengguna","value": id
                        })
                        $.ajax({
                            url : "<?php echo base_url('Master/Pengguna/save');?>",
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
                $('#form-password').remove();
                $.getJSON("<?php echo base_url('Master/Pengguna/getByID');?>" + "/" + id, function (data) {
                    $('#id_pengguna').val(data[0].id_pengguna);
                    $('#username').val(data[0].username);
                    $('#namaLengkap').val(data[0].nama_lengkap);
                    $('#status').val(data[0].status).trigger('change');
                });
            }
        }

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->
<!-- CONTENT -->
	<form class="form-horizontal" id="frmEditor" method="post">
        <div class="form-body">

            <div class="form-group">
                <label for="kodeprodi">Kode</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="kodeprodi" class="form-control" name="kodeprodi" placeholder="Kode Program Studi" required>
                    <div class="form-control-position">
                        <i class="la la-barcode"></i>
                    </div>
                </div>
            </div>

                    <input type="text" id="kode" class="form-control" name="kode">
                    <input type="text" id="tipe" class="form-control" name="tipe">

            <div class="form-group">
                <label for="namaprodi">Program Studi</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="namaprodi" class="form-control" name="namaprodi" placeholder="Program Studi" required>
                    <div class="form-control-position">
                        <i class="la la-font"></i>
                    </div>
                </div>
            </div>
        </div>
    </form>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
		$(document).ready(function () {
			loaddata();

            $('#kode').hide();
            $('#tipe').hide();

            $('#frmEditor').validate({
                ignore: "",
                rules: {
                    kodeprodi:{
                        required:true,
                        maxlength:5
                    },
                    namaprodi:{
                        required:true,
                        maxlength:30
                    }
                },
                messages: {
                    kodeprodi:{
                        required:"Harus di isi"
                    },
                    namaprodi:{
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
                            url
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
            var tipe = $('#modal').data('tipe');
            $('#tipe').val(tipe);
            
            var id = $('#modal').data('id');
            if (id != 0) {
                $.getJSO/"+ id, function (data) {
                    $('#kode').val(data[0].id_program_studi);
                    $('#kodeprodi').val(data[0].id_program_studi);
                    $('#namaprodi').val(data[0].program_studi);
                });
            }
        }

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->
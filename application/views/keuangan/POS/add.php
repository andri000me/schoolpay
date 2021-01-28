<!-- CONTENT -->
	<form class="form-horizontal" id="frmEditor" action="<?php echo base_url('Master/BiodataSekolah/editBio'); ?>" method="post">
        <div class="form-body">
            <input type="text" id="tipe" name="tipe">

            <div class="form-group">
                <label for="nama">Nama POS</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="nama" class="form-control" name="nama" placeholder="Nama POS" required>
                    <div class="form-control-position">
                        <i class="la la-font"></i>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <div class="position-relative has-icon-left">
                    <input type="text" id="keterangan" class="form-control" name="keterangan" placeholder="Keterangan" required>
                    <div class="form-control-position">
                        <i class="la la-comment"></i>
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
            $('#tipe').hide();

            $('#frmEditor').validate({
                ignore: "",
                rules: {
                    nama:{
                        required:true
                    },
                    keterangan:{
                        required:true
                    }
                },
                messages: {
                    nama:{
                        required:"Harus di isi"
                    },
                    keterangan:{
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
                            url : "<?php echo base_url('Keuangan/POS/save');?>",
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
                $.getJSON("<?php echo base_url('Keuangan/POS/getByID');?>" + "/" + id, function (data) {
                    $('#nama').val(data[0].pos);
                    $('#keterangan').val(data[0].keterangan);
                });
            }
        }

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->
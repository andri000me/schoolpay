<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <form class="form-horizontal" id="frmEditor" action="<?php echo base_url('Akun/editBio'); ?>" method="post">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-xl-12">
                            <h3 class="card-title text-bold-700 my-2 white"><i class="la la-info" style="font-size: 30px;"></i>Data Pengguna</h3>
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">&nbsp;</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="namaSekolah">Nama Sekolah</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="namaSekolah" class="form-control" placeholder="Nama Sekolah" name="namaSekolah">
                                                        <div class="form-control-position">
                                                            <i class="ft-user"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nisn">NISN / NSS</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" id="nisn" class="form-control" placeholder="NISN / NSS" name="nisn">
                                                        <div class="form-control-position">
                                                            <i class="la la-registered"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label for="alamat">Alamat</label>
                                            <div class="position-relative has-icon-left">
                                                <textarea id="alamat" class="form-control" name="alamat" placeholder="Alamat Sekolah"></textarea>
                                                <div class="form-control-position">
                                                    <i class="la la-map-marker"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($user_detail['status'] != 'admin') { ?>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Data Siswa</h4>
                                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content collapse">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="namaSekolah">Nama Sekolah</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" id="namaSekolah" class="form-control" placeholder="Nama Sekolah" name="namaSekolah">
                                                            <div class="form-control-position">
                                                                <i class="ft-user"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nisn">NISN / NSS</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" id="nisn" class="form-control" placeholder="NISN / NSS" name="nisn">
                                                            <div class="form-control-position">
                                                                <i class="la la-registered"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="form-group">
                                                <label for="alamat">Alamat</label>
                                                <div class="position-relative has-icon-left">
                                                    <textarea id="alamat" class="form-control" name="alamat" placeholder="Alamat Sekolah"></textarea>
                                                    <div class="form-control-position">
                                                        <i class="la la-map-marker"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Data Wali</h4>
                                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-plus"></i></a></li>
                                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content collapse">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="namaSekolah">Nama Sekolah</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" id="namaSekolah" class="form-control" placeholder="Nama Sekolah" name="namaSekolah">
                                                            <div class="form-control-position">
                                                                <i class="ft-user"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nisn">NISN / NSS</label>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" id="nisn" class="form-control" placeholder="NISN / NSS" name="nisn">
                                                            <div class="form-control-position">
                                                                <i class="la la-registered"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <div class="form-group">
                                                <label for="alamat">Alamat</label>
                                                <div class="position-relative has-icon-left">
                                                    <textarea id="alamat" class="form-control" name="alamat" placeholder="Alamat Sekolah"></textarea>
                                                    <div class="form-control-position">
                                                        <i class="la la-map-marker"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
<!-- CONTENT -->

<!-- js -->
    <script type="text/javascript">
        $(document).ready(function () {
            loaddata();

            $('#frmEditor').validate({
                ignore: "",
                rules: {
                    namaSekolah:{
                        required:true
                    },
                    nisn:{
                        required:true,
                        number:true
                    },
                    alamat:{
                        required:true
                    },
                    kelurahan:{
                        required:true
                    },
                    kecamatan:{
                        required:true
                    },
                    kabupaten:{
                        required:true
                    },
                    provinsi:{
                        required:true
                    },
                    kodePos:{
                        required:true,
                        number:true
                    },
                    website:{
                        required:true
                    },
                    email:{
                        required:true,
                        email:true
                    },
                    tlp:{
                        required:true,
                        number:true
                    },
                    kepalaSekolah:{
                        required:true
                    },
                    nip:{
                        required:true,
                        number:true
                    },
                },
                messages: {
                    namaSekolah:{
                        required:"Harus di isi"
                    },
                    nisn:{
                        required:"Harus di isi",
                        number:"Harus berupa angka"
                    },
                    alamat:{
                        required:"Harus di isi"
                    },
                    kelurahan:{
                        required:"Harus di isi"
                    },
                    kecamatan:{
                        required:"Harus di isi"
                    },
                    kabupaten:{
                        required:"Harus di isi"
                    },
                    provinsi:{
                        required:"Harus di isi"
                    },
                    kodePos:{
                        required:"Harus di isi",
                        number:"Harus berupa angka"
                    },
                    website:{
                        required:"Harus di isi"
                    },
                    email:{
                        required:"Harus di isi",
                        email:"Harus berupa e-mail"
                    },
                    tlp:{
                        required:"Harus di isi",
                        number:"Harus berupa angka"
                    },
                    kepalaSekolah:{
                        required:"Harus di isi"
                    },
                    nip:{
                        required:"Harus di isi",
                        number:"Harus berupa angka"
                    },
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

            $('#btnSubmit').click(function(event){
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    if ($('#frmEditor').valid()) {
                        var datafrm = $('#frmEditor').serializeArray();
                        $.ajax({
                            url : "<?php echo base_url('Master/BiodataSekolah/editBio');?>",
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

            function loaddata(){
                var status = '<?= $this->session->userdata('status'); ?>'
                $.getJSON("<?php echo base_url('Akun/getData/');?>"+status, function (data) {
                    $('#namaSekolah').val(data[0].sekolah);
                    $('#nisn').val(data[0].nisn);
                    $('#alamat').val(data[0].alamat);
                    $('#kelurahan').val(data[0].kelurahan);
                    $('#kecamatan').val(data[0].kecamatan);
                    $('#kabupaten').val(data[0].kabupaten);
                    $('#provinsi').val(data[0].provinsi);
                    $('#kodePos').val(data[0].kode_pos);
                    $('#website').val(data[0].website);
                    $('#email').val(data[0].email);
                    $('#tlp').val(data[0].tlp);
                    $('#kepalaSekolah').val(data[0].kepala_sekolah);
                    $('#nip').val(data[0].nip_kepala_sekolah);
                });
            }

        });
    </script>
<!-- js -->
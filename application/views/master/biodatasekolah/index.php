<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h3 class="card-title text-bold-700 my-2 white"><i class="la la-info" style="font-size: 30px;"></i>Biodata Sekolah</h3>
                        <div class="card">            
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <form class="form-horizontal" id="frmEditor" action="<?php echo base_url('Master/BiodataSekolah/editBio'); ?>" method="post" enctype="multipart/form-data">
                                            <div class="form-body">

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

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="kelurahan">Kelurahan</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="kelurahan" class="form-control" name="kelurahan" placeholder="Kelurahan">
                                                                <div class="form-control-position">
                                                                    <i class="la la-map-signs"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="kecamatan">Kecamatan</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="kecamatan" class="form-control" name="kecamatan" placeholder="Kecamatan">
                                                                <div class="form-control-position">
                                                                    <i class="la la-map-signs"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="kabupaten">Kabupaten / Kota</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="kabupaten" class="form-control" name="kabupaten" placeholder="Kabupaten / Kota">
                                                                <div class="form-control-position">
                                                                    <i class="la la-map-signs"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="provinsi">Provinsi</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="provinsi" class="form-control" name="provinsi" placeholder="Provinsi">
                                                                <div class="form-control-position">
                                                                    <i class="la la-map-signs"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label for="kodePos">Kode Pos</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="kodePos" class="form-control" name="kodePos" placeholder="Kode Pos">
                                                                <div class="form-control-position">
                                                                    <i class="la la-road"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="website">Website</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="website" class="form-control" name="website" placeholder="Website">
                                                                <div class="form-control-position">
                                                                    <i class="la la-globe"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="email">E-mail</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="email" class="form-control" name="email" placeholder="E-mail">
                                                                <div class="form-control-position">
                                                                    <i class="ft-mail"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="tlp">Telepon</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="tlp" class="form-control" name="tlp" placeholder="Telepon">
                                                                <div class="form-control-position">
                                                                    <i class="ft-phone"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="kepalaSekolah">Kepala Sekolah</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="kepalaSekolah" class="form-control" name="kepalaSekolah" placeholder="Kepala Sekolah">
                                                                <div class="form-control-position">
                                                                    <i class="la la-user"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="nip">NIP Kepala Sekolah</label>
                                                            <div class="position-relative has-icon-left">
                                                                <input type="text" id="nip" class="form-control" name="nip" placeholder="NIP Kepala Sekolah">
                                                                <div class="form-control-position">
                                                                    <i class="la la-barcode"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="logosekolah">Logo</label>
                                                            <input type="file" accept="image/x-png,image/jpeg,image/jpg" id="logosekolah" class="form-control" name="logosekolah" placeholder="Logo">
                                                        </div>
                                                        <br>
                                                        <img id="img-logo" class="media" src="" alt="logo" width="300">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stample">STAMPLE</label>
                                                            <input type="file" accept="image/x-png,image/jpeg,image/jpg" id="stample" class="form-control" name="stample" placeholder="Stample">
                                                        </div>
                                                        <br>
                                                        <img id="img-stample" class="media" src="" alt="stample" width="300">
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" id="btnSubmit" class="btn btn-success col-12">
                                                    <i class="la la-check-circle"></i> Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                        var logosekolah = $('#logosekolah').prop('files')[0];
                        var stample = $('#stample').prop('files')[0];
                        
                        var form_data = new FormData($('#frmEditor')[0]);
                        form_data.append('logosekolah', logosekolah);
                        form_data.append('stample', stample);

                        $.ajax({
                            url : "<?php echo base_url('Master/BiodataSekolah/editBio');?>",
                            type:"POST",
                            data: form_data,
                            dataType:"json",
                            cache: false,
                            contentType: false,
                            processData: false,
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
                $.getJSON("<?php echo base_url('Master/biodataSekolah/getData');?>", function (data) {
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
                    $('#img-logo').attr('src', '<?= base_url() ?>' + data[0].logo);
                    $('#img-stample').attr('src', '<?= base_url() ?>' + data[0].stample);
                });
            }

        });
    </script>
<!-- js -->
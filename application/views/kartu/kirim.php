<!-- CONTENT -->
    <form class="form-horizontal" id="formKirimKartu" action="" method="post">
        <input type="hidden" id="nis_kartu" class="form-control" name="nis_kartu">

        <div class="form-body">
            <div class="form-group">
                <label for="id_ujian">Pilih Ujian</label>
                <select class="select2 form-control" id="id_ujian" name="id_ujian" required>
                    <option value=""></option>
                    <?php foreach ($data_ujian as $ujian):?>
                        <option value="<?= $ujian->id_ujian ?>"> 
                            <?= date_format(date_create($ujian->tanggal_ujian),"d F Y H:i").' - ('.$ujian->tipe_ujian.') - '.$ujian->nama_ujian ?> 
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <hr>

            <button id="btnKirimKartu" class="btn btn-lg btn-block btn-bg-gradient-x-blue-cyan pull-up">Kirim <i class="la la-paper-plane"></i></button>
        </div>
    </form>

    <form class="form-horizontal" id="formKirimPengumuman" action="" method="post">
        <input type="hidden" id="nis_pengumuman" class="form-control" name="nis_pengumuman">

        <div class="form-body" id="formKirimPengumuman">
            <h4 class="btn btn-md btn-block btn-warning">
                Pengumuman
            </h4>

            <br>
            <div class="form-group">
                <div class="position-relative has-icon-left">
                    <input type="text" class="form-control" value="Pemberitahuan Pembayaran" disabled>
                    <div class="form-control-position">
                        <i class="la la-font"></i>
                    </div>
                </div>
            </div>
            <div class="position-relative has-icon-left">
                <textarea rows="5" id="pemberitahuan_pembayaran" class="form-control" name="pemberitahuan_pembayaran" placeholder="Pengumuman" value="Mohon segera selesaikan pembayaran"></textarea>
                <div class="form-control-position">
                    <i class="la la-bell"></i>
                </div>
            </div>

            <hr>

            <button id="btnKirimPengumuman" class="btn btn-lg btn-block btn-bg-gradient-x-orange-yellow pull-up">Kirim <i class="la la-paper-plane"></i></button>
        </div>
    </form>
<!-- CONTENT -->

<!-- SCRIPT -->
    <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/editors/tinymce/tinymce.min.js'); ?>"></script>
	<script type="text/javascript">
        $(document).ready(function (){
            loaddata();

            $('#id_ujian').select2({
                width:'100%',
                placeholder: 'Pilih Ujian'
            });

            $('#btnKirimKartu').click(function () {
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    var datafrm = $('#formKirimKartu').serializeArray();
                    $.ajax({
                        url : "<?php echo base_url('Kartu/kirimKartu');?>",
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
                                $('#modal2').modal('hide');
                            }
                            else{
                                Swal.fire({
                                    title: "Information",
                                    animation: true,
                                    icon:"warning",
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

            $('#btnKirimPengumuman').click(function () {
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    tinyMCE.triggerSave();
                    var datafrm = $('#formKirimPengumuman').serializeArray();
                    $.ajax({
                        url : "<?php echo base_url('Kartu/kirimPengumuman');?>",
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
                                $('#modal2').modal('hide');
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

        function loaddata() {
            var tipe  = $('#modal2').data('tipe');
            var nis = $('#modal2').data('nis')
            
            if (tipe == 'kartu') {
                $('#formKirimPengumuman').remove();
                $('#nis_kartu').val(nis);
            }
            else{
                $('#formKirimKartu').remove();
                $('#nis_pengumuman').val(nis);

                tinymce.init({
                    selector: 'textarea#pemberitahuan_pembayaran',
                    plugins: 'link,code,preview,autolink',             
                    height: 200,
                    toolbar: "undo redo | styleselect | bold italic| alignleft aligncenter alignright alignjustify | bullist numlist | link image | preview code"
                });
            }
        }

        $('#modal2').one('hidden.bs.modal', function (e) {
            $(this).removeData();
            tinymce.remove('#pemberitahuan_pembayaran');
        });
	</script>
<!-- SCRIPT -->


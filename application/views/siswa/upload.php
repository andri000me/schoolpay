<link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/vendors/css/file-uploaders/dropzone.min.css')?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/plugins/file-uploaders/dropzone.css')?>">
<script type="text/javascript" src="<?= base_url('app-assets/vendors/js/extensions/dropzone.min.js')?>"></script>

<!-- CONTENT -->
    <br>
    <div class="row">
        <div class="col-12">
            <h5 align="center">Unduh template upload siswa <u><a href="<?=base_url('template/siswa.xlsx');?>">disini</a></u></h5>
        </div>
    </div>
    <br>
	<form class="dropzone dropzone-previews" id="form" action="" method="POST" enctype="multipart/form-data">
        <div class="" id="file">
            <div class="dz-message" id="textHelper">Drop File Here Or Click To Upload</div>
        </div>
    </form>
    <br>
    <div class="row">
        <div class="col-12">
            <div id="uploaded-file" class="dropzone-previews"></div>
        </div>
    </div>
    <br>
    <button type="submit" id="btn" class="btn btn-success btn-block btn-md">UPLOAD</button>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
		var drop = new Dropzone(document.getElementById('file'), {
            paramName: 'file',
            acceptedFiles: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel',
            url: "<?= base_url('Siswa/Siswa/uploadProses') ?>",
            clickable: "#form",
            addRemoveLinks:true,
            maxFiles: 1,
            maxFilesize: 5,
            autoProcessQueue: false,
            accept: function(file, done) {
                // console.log(file.name);
                filename = file.name;
                done();    // !Very important
            },
            init: function() {
                var myDropzone = this,
                submitButton = document.getElementById('btn');

                submitButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processQueue();
                });

                this.on("success", function (data,response) {
                    response = JSON.parse(response);
                    if (response.Error == false) {
                        Swal.fire({
                            title: "Information",
                            animation: true,
                            icon:"success",
                            text: response.Message,
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
                            text: response.Message,
                            confirmButtonText: "OK"
                        });
                    }
                })

                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });

                this.on("addedfile", function(file) {
                    $('#textHelper').hide(); 
                });

                this.on("removedfile", function(file) {
                    $('#textHelper').show(); 
                });
            }
        });

        $('#modal').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->
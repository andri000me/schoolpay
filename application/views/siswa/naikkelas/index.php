<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h3 class="card-title text-bold-700 my-2 white"><i class="la la-level-up" style="font-size: 30px;"></i> Naik Kelas</h3>
                        <div class="card">            
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <form action="<?=base_url('Siswa/NaikKelas/siswanaik');?>" method="post" id="siswalulus">
                                                    <button type="submit" name="simpan" value="ok" class="btn-block btn-lg btn-bg-gradient-x-purple-blue btn-glow">
                                                        <i class="la la-cloud-upload" style="font-size: 30px;"></i> Naikkan Semua Siswa
                                                    </button>
                                                </form>

                                                <div class="clearfix"><br></div>

                                                <div class="bs-callout bs-callout-danger">
                                                    <h3 style="color: #d9534f;">
                                                        <i class="la la-info-sign"></i> Peringatan !!!
                                                    </h3>
                                                    <hr>
                                                    Apabila menekan tombol (<i class="la la-cloud-upload"></i> berwarna biru) diatas, 
                                                    maka semua siswa di semua jurusan dan tingkat akan dinaikkan. 
                                                    Dan apabila ada siswa yang tidak naik gunakan menu pindah kelas untuk mengembalikan ke kelas sebelumnya.
                                                </div>
                                            </div>
                                        </div>
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
        $('#awalkelas').select2({
            width:'100%',
            placeholder: 'Pilih Kelas'
        });
        $('#targetkelas').select2({
            width:'100%',
            placeholder: 'Pilih Kelas'
        });
    </script>
<!-- js -->
<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12">
                    <h3 class="card-title text-bold-700 my-2 white"><i class="la menu-title" style="font-size: 30px;"></i> Kartu Ujian</h3>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-header">
                                        <h4 class="card-title">Data Siswa</h4>
                                    </div>

                                    <div class="card-body">
                                        <select name="listkelas" class="form-control select2 listkelas" id="listkelas">
                                        </select>
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
        $('#listkelas').select2({
            placeholder: "Pilih Kelas"
            width:'100%'
        });
    </script>
<!-- js -->
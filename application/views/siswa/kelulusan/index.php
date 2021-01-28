<!-- CONTENT -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="row match-height">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <h3 class="card-title text-bold-700 my-2 white"><i class="la la-graduation-cap" style="font-size: 30px;"></i> Kelulusan</h3>
                        <div class="card">            
                            <div class="card-content">
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <div class="row match-height">
                                            <div class="col-5">
                                                <form action="<?= base_url('Siswa/Kelulusan')?>" method="GET">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <select name="awalkelas" id="awalkelas" class="select2 form-control" onchange="this.form.submit()">
                                                                <option value="">Pilih Kelas</option>
                                                                <?php
                                                                foreach($listKelas as $lkls):
                                                                    $selected = '';
                                                                    if(isset($_GET['awalkelas']))
                                                                        if($_GET['awalkelas'] == $lkls->status_kelas.'.'.$lkls->program_studi.'.'.$lkls->kode_kelas)
                                                                            $selected = ' selected';
                                                                    echo '<option value="'.$lkls->status_kelas.'.'.$lkls->program_studi.'.'.$lkls->kode_kelas.'"'.$selected.'>'.$lkls->kelas.' '.$lkls->program_studi.' '.$lkls->kode_kelas.'</option>';
                                                                endforeach;
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </form>
                                                
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <th width="35">No</th>
                                                        <th width="35" class="text-center"><input type="checkbox" class="cawalall"></th>
                                                        <th>NIS</th>
                                                        <th>Nama</th>
                                                    </thead>
                                                    <tbody>
                                                        <form action="<?=base_url('Siswa/Kelulusan/siswalulus');?>" method="post" id="siswalulus">
                                                        <?php
                                                        if(isset($_GET['awalkelas'])):
                                                            echo '<input type="hidden" name="kelas" value="'.$_GET['awalkelas'].'">';
                                                            $awalkelas = explode('.', $_GET['awalkelas']);
                                                            $akls = $this->db->query("select nis, nama from data_siswa where data_siswa.status_kelas = '".$awalkelas[0]."' and data_siswa.program_studi = '".$awalkelas[1]."' and data_siswa.kode_kelas = '".$awalkelas[2]."' and lulus = 't' order by nis asc");
                                                            $no = 1;
                                                            foreach($akls->result() as $row):
                                                        ?>
                                                        <tr>
                                                            <td width="35"><?=$no;?></td>
                                                            <td width="35" class="text-center"><input type="checkbox" class="cawal" name="nis[]" value="<?=$row->nis;?>"></td>
                                                            <td><?=$row->nis;?></td>
                                                            <td><?=$row->nama;?></td>
                                                        </tr>
                                                        <?php
                                                            $no += 1;
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                        </form>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="col-2">
                                                <button class="btn btn-info" style="width:100%;" onClick="$('#siswalulus').submit();">
                                                    <i class="la la-angle-double-right"></i>
                                                </button>
                                                <br><br>
                                                <button class="btn btn-warning" style="width:100%;" onClick="$('#siswabalik').submit();">
                                                    <i class="la la-angle-double-left"></i>
                                                </button>
                                            </div>

                                            <div class="col-5">
                                                <div class="card">
                                                    <div class="card-content collapse show">
                                                        <h3>Data Kelulusan </h3><br>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <th width="35">No</th>
                                                                <th width="35" class="text-center"><input type="checkbox" class="cawalall2"></th>
                                                                <th>NIS</th>
                                                                <th>Nama</th>
                                                            </thead>
                                                            <tbody>
                                                                <form action="<?=base_url('Siswa/Kelulusan/siswabalik');?>" method="post" id="siswabalik">
                                                                <?php
                                                                if(isset($_GET['awalkelas'])):
                                                                    echo '<input type="hidden" name="kelas" value="'.$_GET['awalkelas'].'">';
                                                                    $awalkelas = explode('.', $_GET['awalkelas']);
                                                                    $akls = $this->db->query("select nis, nama from data_siswa where data_siswa.status_kelas = '".$awalkelas[0]."' and data_siswa.program_studi = '".$awalkelas[1]."' and data_siswa.kode_kelas = '".$awalkelas[2]."' and lulus = 'y' order by nis asc limit 100");
                                                                    $no = 1;
                                                                    foreach($akls->result() as $row):
                                                                ?>
                                                                <tr>
                                                                    <td width="35"><?=$no;?></td>
                                                                    <td width="35" class="text-center"><input type="checkbox" class="cawal2" name="nis[]" value="<?=$row->nis;?>"></td>
                                                                    <td><?=$row->nis;?></td>
                                                                    <td><?=$row->nama;?></td>
                                                                </tr>
                                                                <?php
                                                                    $no += 1;
                                                                    endforeach;
                                                                endif;
                                                                ?>
                                                                </form>
                                                            </tbody>
                                                        </table>
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
    </div>
<!-- CONTENT -->


<!-- js -->
    <script type="text/javascript">
        $('#awalkelas').select2({
            width:'100%',
            placeholder: 'Pilih Kelas'
        });
    </script>
<!-- js -->
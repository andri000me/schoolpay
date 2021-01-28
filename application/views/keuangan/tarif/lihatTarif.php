<!-- CONTENT -->
    <div class="form-body">
        <table class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">
            <tr>
                <th class="bg-primary bg-darken-1 white" width="30%">NIS</td>
                <td id="nis">NIS</td>
            </tr>
            <tr>
                <th class="bg-primary bg-darken-1 white" width="30%">Nama</td>
                <td id="nama">Nama</td>
            </tr>
            <tr>
                <th class="bg-primary bg-darken-1 white" width="30%">Kelas</td>
                <td id="kelas">Kelas</td>
            </tr>
        </table>
        <div id="isi"></div>
    </div>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
        $(document).ready(function (){
            $('#nis').text($('#modal2').data('nis'));
            $('#nama').text('<?=$nama;?>');
            $('#kelas').text($('#modal2').data('kelas').replace(/\./g, ' - '));

            var biaya = $('#modal2').data('tarif').split('.');
            var bulan = [0, 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];

            var table = '<table class="table table-bordered table-hover dataTables" cellspacing="0" width="100%">';
            for(var i=1;i<=6;i++){
                table += '<tr>';
                    table += '<th class="bg-primary bg-darken-1 white" width="15%">' + bulan[i] + '</th>';
                    table += '<th class="bg-primary bg-accent-3 black" style="text-align: right;">Rp. ' + number_format(biaya[i-1], 2, ',', '.') + '</th>';
                    table += '<th class="bg-primary bg-darken-1 white" width="15%">' + bulan[6 + i] + '</th>';
                    table += '<th class="bg-primary bg-accent-3 black" style="text-align: right;">Rp. ' + number_format(biaya[6 + i - 1], 2, ',', '.') + '</th>';
                table += '</tr>';
            }
            table += '</table>';

            $('#isi').html(table);
        });

        $('#modal2').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });
	</script>
<!-- SCRIPT -->


function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k).toFixed(prec);
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function renumberingbiayaku(kolom){
    $(kolom).attr('class', 'danger');
    $(kolom+'xxx').remove();
    var no = 1;
    $('.biayaku > tr').each(function(){
        var status = true;
        $(this).find('td').each(function(){
            if(status){
                $(this).text(no);
                status = false;
            }
        });
        no += 1;
    });
}

function appendku(id_jenis, kolom, tahun, nama, bulan, id, jumlah, jenis){
    var no = $('.nobiayaku').text();
    if(jenis == 'bulanan' && $('#'+kolom).attr('class') == 'danger'){
        var bulanku = [0, 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        $('.biayaku').append('<tr><td align="center">'+no+'</td><td>'+tahun+'</td><td>'+nama+' ('+bulanku[bulan]+')</td><td align="right">'+number_format(jumlah, 0, '.', '.')+'</td><td align="right">'+number_format(jumlah, 0, '.', '.')+'</td><td align="center"><a href="#" onClick="$(this).parent().parent().remove();$(\'.nobiayaku\').text(parseInt($(\'.nobiayaku\').text()) - 1);renumberingbiayaku(\'#'+kolom+'\');"><span class="la la-remove"></span></a></td></tr>');
        $('#'+kolom).attr('class', 'warning');
        $('.formbiayaku').append('<div id="'+kolom+'xxx"><input type="text" name="id_jenis[]" value="'+id_jenis+'"><input type="text" name="tahun[]" value="'+tahun+'"><input type="text" name="bayaran[]" value="'+bulan+'"><input type="text" name="id_pembayaran[]" value="'+id+'"><input type="text" name="jumlah[]" value="'+jumlah+'"><input type="text" name="jenis[]" value="'+jenis+'"></div>');
    }
    if(jenis == 'bebas' && $('#'+kolom).attr('class') == 'danger'){
        $('.biayaku').append('<tr><td align="center">'+no+'</td><td>'+tahun+'</td><td>'+nama+'</td><td align="right">'+number_format(jumlah, 0, '.', '.')+'</td><td align="right"><input type="text" style="text-align: right;" onKeyUp="$(\'#'+kolom+'xxx > #jumlahbayaran\').val(rupiahToNumber(this.value))" value="'+number_format(jumlah, 0, '.', '.')+'"></td><td align="center"><a href="#" onClick="$(this).parent().parent().remove();$(\'.nobiayaku\').text(parseInt($(\'.nobiayaku\').text()) - 1);renumberingbiayaku(\'#'+kolom+'\');"><span class="la la-remove"></span></a></td></tr>');
        $('#'+kolom).attr('class', 'warning');
        $('.formbiayaku').append('<div id="'+kolom+'xxx"><input type="text" name="id_jenis[]" value="'+id_jenis+'"><input type="text" name="tahun[]" value="'+tahun+'"><input type="text" name="bayaran[]" value="'+bulan+'"><input type="text" name="id_pembayaran[]" value="'+id+'"><input type="text" name="jumlah[]" id="jumlahbayaran" value="'+jumlah+'"><input type="text" name="jenis[]" value="'+jenis+'"></div>');
    }
    $('.nobiayaku').text(parseInt(no) + 1)
}

function viewCicilan(bayar, url, nis, tahun, id_jenis){
    $('#cicilanku').load(url+'?nis='+nis+'&tahun='+tahun+'1&id_jenis='+id_jenis+'&bayar='+bayar);
    $('#myTarifModal').modal('show');
}

$(function(){
    // $('#dpx, #dpy, #dpz').datetimepicker();
    
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        var isVisible = $("#wrapper").is( ":visible" );

        console.log(isVisible);
        $("#wrapper").toggleClass("toggled");
    });
    
    $('.cawalall').click(function(e){
        if(this.checked){
        	$('.cawal').each(function(){
        		this.checked = true;
        	});
        }else{
        	$('.cawal').each(function(){
        		this.checked = false;
        	});
        }
    });

    $('.cawalall2').click(function(e){
        if(this.checked){
        	$('.cawal2').each(function(){
        		this.checked = true;
        	});
        }else{
        	$('.cawal2').each(function(){
        		this.checked = false;
        	});
        }
    });
    
    $('.openModal1').click(function(e){
        $('#myModal1').modal('show');
    });

    $('.openModal10').click(function(e){
        $('.transkasiPlus').html('');
        $('#myModal10').modal('show');
    });

    $('.openModal2').click(function(e){
        $('#myModal2').modal('show');
    });

    $('.openTarifModal').click(function(e){
        $('#myTarifModal').modal('show');
    });

    $('.lihatTarifSiswa').click(function(e){
        $('.m_nis').text($(this).attr('nis'));
        $('.m_nama').text($(this).attr('nama'));
        $('.m_kelas').text($(this).attr('kelas'));

        var biaya = $(this).attr('tarif').split('.');
        var bulan = [0, 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        var table = '<table class="table table-bordered">';
        for(var i=1;i<=6;i++){
            table += '<tr>';
                table += '<td class="label-success" style="color: #fff;">' + bulan[i] + '</td>';
                table += '<td style="text-align: right;">Rp. ' + number_format(biaya[i-1], 2, ',', '.') + '</td>';
                table += '<td class="label-success" style="color: #fff;">' + bulan[6 + i] + '</td>';
                table += '<td style="text-align: right;">Rp. ' + number_format(biaya[6 + i - 1], 2, ',', '.') + '</td>';
            table += '</tr>';
        }
        table += '</table>';

        $('.m_isi').html(table);
        $('#myBiayaModal').modal('show');
    });
    
    $('.tombolPlus').click(function(){
        var jmlBaris = 0;
        $('.transkasiPlus tr').each(function(){
            jmlBaris += 1;
        });
        $tambahan = '<tr>';
        $tambahan += '<td class="text-center" style="vertical-align: middle;">'+(jmlBaris + 1)+'</td>';
        $tambahan += '<td><input type="text" name="keterangan[]" class="form-control input-sm" id="keterangan" placeholder="Keterangan"></td>';
        $tambahan += '<td><input type="text" value="0" name="debit[]" class="form-control input-sm" id="debit" onfocus="unformatRupiah(this)" onblur="formatRupiah(this);"></td>';
        $tambahan += '<td><input type="text" value="0" name="kredit[]" class="form-control input-sm" id="kredit" onfocus="unformatRupiah(this)" onblur="formatRupiah(this);"></td>';
        $tambahan += '</tr>';
        $('.transkasiPlus').append($tambahan);
    });
});
<!-- CONTENT -->
	<form class="form-horizontal" id="frmEditor" action="" method="post">
        <div class="form-body">
            <div class="row">
                <input type="hidden" value="edit" id="berdasarkan" name="berdasarkan">
                <input type="hidden" value="<?=$datasiswa->tipe;?>" id="tipe" name="tipe">
                <input type="hidden" value="<?=$datasiswa->id_jenis;?>" id="jenis" name="jenis">

                <input type="hidden" value="<?=$datasiswa->tahun_ajaran;?>" id="tahun_ajaran" name="tahun_ajaran">
                <input type="hidden" value="<?= $datasiswa->kelas .'.'. $datasiswa->program_studi .'.'. $datasiswa->kode_kelas;?>" id="kelas" name="kelas">
                <input type="hidden" value="<?=$datasiswa->nis;?>" id="nis" name="nis">
                <input type="hidden" value="<?=$datasiswa->nama;?>" id="nama" name="nama">

                <div class="col-md-6 form-group">
                    <label for="">Tahun Ajaran</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" value="<?=substr($datasiswa->tahun_ajaran,0,4).'/'.(substr($datasiswa->tahun_ajaran,0,4)+1);?>" class="form-control" disabled>
                        <div class="form-control-position">
                            <i class="la la-hourglass"></i>
                        </div>
                    </div>

                    <label for="">Kelas</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" value="<?= $datasiswa->kelas .' - '. $datasiswa->program_studi .' - '. $datasiswa->kode_kelas;?>" class="form-control" disabled>
                        <div class="form-control-position">
                            <i class="la la-building"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 form-group">
                    <label for="">NIS</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" value="<?=$datasiswa->nis;?>"class="form-control" disabled>
                        <div class="form-control-position">
                            <i class="la la-barcode"></i>
                        </div>
                    </div>

                    <label for="">Nama</label>
                    <div class="position-relative has-icon-left">
                        <input type="text" value="<?=$datasiswa->nama;?>"class="form-control" disabled>
                        <div class="form-control-position">
                            <i class="la la-font"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h4 class="btn btn-md btn-block btn-bg-gradient-x-blue-cyan">
                        Tarif Yang di Kenakan
                    </h4>
                    <br>
                    <?php if($datasiswa->tipe == 'Bulanan'): ?>
                            <h4 align="center">Tarif Setiap Bulan Sama</h4>
                            <label for="tarifsama">Tarif</label>
                            <div class="position-relative has-icon-left">
                                <input 
                                    type="text" 
                                    id="tarifsama" 
                                    name="tarifsama" 
                                    class="form-control groupOfTexbox" 
                                    placeholder="Tarif Pembayaran" 
                                    onfocus="unformatRupiah('tarifsama')" 
                                    onblur="formatRupiah('tarifsama');samakan('tarifsama');" 
                                    onChange="formatRupiah('tarifsama');samakan('tarifsama');"
                                >
                                <div class="form-control-position">
                                    <i class="la la-money"></i>
                                </div>
                            </div>

                            <br>
                            
                            <h4 align="center">Tarif Setiap Bulan Tidak Sama</h4>
                            <br>
                            <?php $bulan=array(null, 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni');
                                for($i=1; $i <= 6; $i++){ 
                                    $tes1 = 'b'.$i; $tes2 = 'b'.(6 + $i); ?>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="position-relative has-icon-left">
                                                <input
                                                    value="<?= number_format($datasiswa->$tes1, 0, ',', '.') ?>" 
                                                    placeholder="Tarif Bulan <?= $bulan[$i] ?>"
                                                    class="form-control"
                                                    type="text" 
                                                    name="bln<?=$i?>" 
                                                    id="bln<?=$i?>" 
                                                    onKeyPress="return focusNext('bln<?=($i+1)?>',event)"
                                                    onfocus="unformatRupiah('bln<?=$i?>')"
                                                    onblur="formatRupiah('bln<?=$i?>');"
                                                >
                                                <div class="form-control-position">
                                                    <i class="la la-money"></i>
                                                </div>
                                            </div>
                                        </div>
                                      
                                        <div class="col-6">
                                            <div class="position-relative has-icon-left">
                                                <input
                                                    value="<?= number_format($datasiswa->$tes2, 0, ',', '.') ?>" 
                                                    placeholder="Tarif Bulan <?= $bulan[6 + $i] ?>"
                                                    class="form-control"
                                                    type="text" 
                                                    name="bln<?=(6 + $i)?>" 
                                                    id="bln<?=(6 + $i)?>" 
                                                    onKeyPress="return focusNext('bln<?=(6+$i+1)?>',event)"
                                                    onfocus="unformatRupiah('bln<?=(6 + $i)?>')"
                                                    onblur="formatRupiah('bln<?=(6 + $i)?>');"
                                                >
                                                <div class="form-control-position">
                                                    <i class="la la-money"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <br>
                                    <?php
                                } 
                            ?>
                    <?php endif; ?>

                    <?php if($datasiswa->tipe == 'Bebas'): ?>
                        <label for="tarifsama">Tarif</label>
                        <div class="position-relative has-icon-left">
                            <input 
                                type="text" 
                                id="tarifsama" 
                                class="form-control" 
                                name="tarifsama" 
                                placeholder="Tarif Pembayaran" 
                                required
                                onfocus="unformatRupiah('tarifsama')" 
                                onblur="formatRupiah('tarifsama');"
                            >
                            <div class="form-control-position">
                                <i class="la la-money"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>
<!-- CONTENT -->

<!-- SCRIPT -->
	<script type="text/javascript">
        $(document).ready(function() {
            $('#frmEditor').validate({
                ignore: "",
                rules: {
                    <?php if($datasiswa->tipe == 'Bulanan'): for($i=1; $i <= count($bulan)-1; $i++){ ?>
                        bln<?=$i?>:{
                            required:true,
                        },
                    <?php } endif; ?>
                },
                messages: {
                    <?php if($datasiswa->tipe == 'Bulanan'): for($i=1; $i <= count($bulan)-1; $i++){ ?>
                        bln<?=$i?>:{
                            required:"Harus di isi",
                        },
                    <?php } endif; ?>
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

            $('#btnSaveModal2').click(function(event){
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    if ($('#frmEditor').valid()) {
                        var datafrm = $('#frmEditor').serializeArray();
                        $.ajax({
                            url : "<?php echo base_url('Keuangan/Jenis/saveTarif');?>",
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
                                    tabledatatarif.ajax.reload(null,true);
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
                    else{
                    }
                }
            });

            $('.groupOfTexbox').keypress(function (event) {
                return isNumber(event, this)
            });

            $(".groupOfTexbox").bind("paste",function(e) {  
                 e.preventDefault();  
            });
        });

        $('#modal2').one('hidden.bs.modal', function (e) {
            $(this).removeData();
        });

        function isNumber(evt, element) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if ( (charCode != 46) && (charCode < 48 || charCode > 57) ){
                return false;
            }
            return true;
        }

        function rptrim(inputString){
            var returnString = inputString + "";
            var removeChar = ' ';

            if (removeChar.length) 
            {
                while('' + returnString.charAt(0) == removeChar) 
                {
                    returnString = returnString.substring(1, returnString.length);
                }
                while('' + returnString.charAt(returnString.length - 1) == removeChar) 
                {
                    returnString = returnString.substring(0, returnString.length - 1);
                }
            }
            
            return returnString;
        }

        function removeLeadingZero(number){
            if (number.length > 1)
            {
                while('' + number.charAt(0) == '0') 
                {
                    number = number.substring(1, number.length);
                }
                
                if (number.length == 0)
                    number = "0";
            }
            return number;
        }

        function numberToRupiah(number){
            var original = number;
            number = rptrim(number);
            
            var positif = true;
            if (number.charAt(0) == '-')
            {
                positif = false;
                number = number.substring(1, number.length);
                number = rptrim(number);
            }
            
            number = removeLeadingZero(number);
            
            if (!rpIsNumber(number))
                return original;
            
            var result = "";
            if (number.length < 4)
            {
                result = "" + number;
            }
            else
            {
                var count = 0;
                for(i = number.length - 1; i >= 0; i--) 
                {
                    result = number.charAt(i) + result;
                    count++;
                    
                    if ((count == 3) && (i > 0)) 
                    {
                        result = '.' + result;
                        count = 0;
                    }
                }
                result = "" + result;
            }
            
            if (!positif)
                result = "(" + result + ")";
            
            return result;
        }

        function rupiahToNumber(rp){
            var result = '';
            
            rp = rptrim(rp);
            var positif = true;
            var isvalid = true;
            if (rp.length > 0) 
            {
                if (rp.charAt(0) == "(")
                {
                    positif = false;
                    rp = rp.substring(1, rp.length);
                    rp = rptrim(rp);
                }
                
                for (i = 0; isvalid && i < rp.length; i++)
                {
                    var chr = rp.charAt(i);
                    var asc = chr.charCodeAt(0);
                    
                    if (asc >= 48 && asc <= 57)
                    {
                        result = result + chr;
                    }
                    else
                    {
                        isvalid = (asc == 82 || asc == 114 || asc == 80 || asc == 112 || asc == 32 || asc == 46 || asc == 40 || asc == 41);
                        //if (!isvalid)
                            //alert(chr + " " + asc);
                    }
                }
            }
            
            if (isvalid)
            {
                if (positif)
                    return result;
                else
                    return "-" + result;
            }
            else
            {
                return rp;
            }
        }

        function rpIsNumber(input){
            var isnum = true;
            for (i = 0; isnum && i < input.length; i++)
            {
                var asc = input.charCodeAt(i);
                isnum = (asc >= 48 && asc <= 57);
            }
            
            return isnum;
            
            //return (!isNaN(parseInt(input))) ? true : false;
        }

        function formatRupiah(id){
            var num = document.getElementById(id).value;    
            document.getElementById(id).value = numberToRupiah(num);
        }

        function unformatRupiah(id){
            var num = document.getElementById(id).value;
            document.getElementById(id).value = rupiahToNumber(num);
        }

        function focusNext(elemName, evt){
            evt = (evt) ? evt : event;
            var charCode = (evt.charCode) ? evt.charCode :
                ((evt.which) ? evt.which : evt.keyCode);
            if (charCode == 13) {
                document.getElementById(elemName).focus();
                return false;
            }
            return true;
        }

        function samakan(hasil){
            var uang = document.getElementById(hasil).value;
            for(var i=1;i<=12;i++){
                document.getElementById('bln'+i).value = uang;
            }
        }
	</script>
<!-- SCRIPT -->
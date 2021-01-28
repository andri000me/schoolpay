<!-- CONTENT -->
    <div class="container" id="container">
    </div>
<!-- CONTENT -->


<!-- js -->
    <script type="text/javascript">
        $(document).ready(function() {
            loadPengguna();
        });

        function loadPengguna(){
            $.ajax({
                url : "<?php echo base_url('Pengumuman/loadPengumuman');?>",
                type:"post",
                success:function(data){
                    $('#container').html(data);
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

        function markAsRead(rowid){
            $.ajax({
                url : "<?php echo base_url('Pengumuman/markAsRead/');?>"+rowid,
                type:"POST",
                dataType:"json"
            });
        }
    </script>
<!-- js -->

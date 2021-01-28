<!-- link -->
    <!DOCTYPE html>
    <html class="loading" lang="en" data-textdirection="ltr">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
            <title>Login</title>
            
            <!-- ICON & FONT -->
                <link rel="apple-touch-icon" href="<?= base_url('app-assets/images/ico/favicon.ico'); ?>">
                <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('app-assets/images/ico/favicon.ico'); ?>">
                <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/fonts/line-awesome/css/line-awesome.min.css'); ?>">
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/fonts/feather/style.min.css'); ?>">
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/fonts/simple-line-icons/style.min.css'); ?>">
            <!-- ICON & FONT -->

            <!-- LINK -->
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/vendors.css'); ?>">
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/vendors/css/vendors.min.css'); ?>">

                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/bootstrap.css'); ?>">
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/bootstrap-extended.css'); ?>">
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/colors.css'); ?>">
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/components.css'); ?>">
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/core/colors/palette-gradient.css'); ?>">
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/core/menu/menu-types/vertical-menu-modern.css'); ?>">
                
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/vendors/css/ui/perfect-scrollbar.min.css'); ?>" />
                <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/vendors/css/modal/sweetalert2.min.css'); ?>">
            <!-- LINK -->
            <!-- SCRIPTS -->
                <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/vendors.min.js'); ?>"></script>
                <script type="text/javascript" src="<?= base_url('app-assets/js/core/libraries/jquery.min.js'); ?>"></script>
                <script type="text/javascript" src="<?= base_url('app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js'); ?>"></script>
                <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/ui/jquery.sticky.js'); ?>"></script>
                <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/forms/validation/jquery.validate.min.js'); ?>"></script>
                
                <script type="text/javascript" src="<?= base_url('app-assets/js/core/libraries/bootstrap.min.js'); ?>"></script>
                
                <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/forms/icheck/icheck.min.js'); ?>"></script>
                <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/modal/sweetalert2.min.js'); ?>"></script>
                <script type="text/javascript" src="<?= base_url('app-assets/js/core/app-menu.js'); ?>"></script>
            <!-- SCRIPTS -->
        </head>
<!-- link -->

<!-- content -->
    <body class="vertical-layout vertical-menu-modern 1-column  bg-full-screen-image menu-expanded blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-color="bg-gradient-x-purple-red" data-col="1-column">\
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-wrapper-before"></div>
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-md-4 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                                <div class="card-header border-0">
                                    <div class="text-center mb-1">
                                        <img src="<?= base_url('app-assets/images/logo/logo-dark.png'); ?>" alt="branding logo">
                                    </div>
                                    <div class="font-large-1  text-center">                       
                                        SchoolPay Login
                                    </div>
                                </div>

                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form-horizontal" id="frmEditor" action="<?php echo base_url(); ?>Auth/login" method="post">

                                            <!-- <input type="hidden" 
                                                name="<?php echo $this->security->get_csrf_token_name(); ?>" 
                                                value="<?php echo $this->security->get_csrf_hash(); ?>"
                                            > -->
                                            
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control round" id="username" name="username" placeholder="Enter Username" required>
                                                <div class="form-control-position">
                                                    <i class="ft-user"></i>
                                                </div>
                                            </fieldset>

                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="password" class="form-control round" id="password" name="password" placeholder="Enter Password" required>
                                                <div class="form-control-position">
                                                    <i class="ft-lock"></i>
                                                </div>
                                            </fieldset>

                                            <div class="form-group text-center">
                                                <button type="submit" id="loginBtn" class="btn round btn-block btn-glow btn-bg-gradient-x-purple-blue col-12 mr-1 mb-1">
                                                    Login
                                                </button>    
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    </body>
    </html>
<!-- content -->

<!-- js -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#frmEditor').validate({
                ignore: "",
                rules: {
                    password: { 
                        required: true
                    },        
                    username:{
                        required: true
                    }
                },
                messages: {
                    username: {
                        required: "Username is required"
                    },
                    password: {
                        required: "Password is required"
                    }
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

            $('#loginBtn').click(function(event){
                event.preventDefault();
                if (event.handled !== true) {
                    event.handled = true;
                    if ($('#frmEditor').valid()) {
                        var datafrm = $('#frmEditor').serializeArray();
                        $.ajax({
                            url : "<?php echo base_url('Auth/login'); ?>",
                            type:"POST",
                            data: datafrm,
                            dataType:"json",
                            success:function(event, data){
                                if (event.Error == false) {
                                    if (event.Message == 'success') {
                                        window.location.href = "<?= base_url();?>";
                                    }
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
        });
    </script>
<!-- js -->
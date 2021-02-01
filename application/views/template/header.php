<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="author" content="SchoolPay Team">

        <title><?= $this->session->userdata('title'); ?></title>

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
            <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/vendors/css/forms/icheck/icheck.css'); ?>">
            <!-- <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/vendors/css/forms/selects/select2.min.css'); ?>"> -->

            <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/vendors/css/tables/datatable/datatables.min.css'); ?>">
            <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css'); ?>">

            <link rel="stylesheet" type="text/css" href="<?=base_url('app-assets/css/pages/chat-application.css')?>">
            <link rel="stylesheet" type="text/css" href="<?= base_url('app-assets/css/app.css'); ?>">
        <!-- LINK -->

        <style>
            /*GLOBAL*/
                ::-webkit-scrollbar {
                    width: 7px;
                }
                ::-webkit-scrollbar-button {
                    display: none;
                }
                ::-webkit-scrollbar-track {
                    background: #f1f1f1;
                }
                ::-webkit-scrollbar-thumb {
                    background: #888;
                }
                ::-webkit-scrollbar-thumb:hover {
                    background: #555;
                }
            /*GLOBAL*/
        </style>
    </head>

    <body class="vertical-layout vertical-menu-modern 2-columns menu-expanded fixed-navbar" data-open="hover" data-menu="vertical-menu-modern" data-color="bg-gradient-x-purple-red" data-col="2-columns">

    <!-- MODAL -->
        <div class="modal fade" id="modal">
            <div class="modal-dialog" id="modaldialog">
                <div class="modal-content">
                    <div class="modal-header" id="modalheader">
                        <h4 class="modal-title" id="modaltitle">Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </div>
                    <div class="modal-body" id="modalbody"></div>
                    <div class="modal-footer" id="modalfooter">
                        <button type="button" class="btn grey btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="btnSaveModal">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal2">
            <div class="modal-dialog" id="modaldialog2">
                <div class="modal-content">
                    <div class="modal-header" id="modalheader2">
                        <h4 class="modal-title" id="modaltitle2">Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </div>
                    <div class="modal-body" id="modalbody2"></div>
                    <div class="modal-footer" id="modalfooter2">
                        <button type="button" class="btn grey btn-secondary" id="btnCloseModal2" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="btnSaveModal2">Save</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- MODAL -->
    
    <!-- NAVBAR -->
        <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark">
            <div class="navbar-wrapper">
                <div class="navbar-container content">
                    <div class="collapse navbar-collapse show" id="navbar-mobile">
                        <ul class="nav navbar-nav mr-auto float-left">
                            <li class="nav-item mobile-menu d-md-none mr-auto">
                                <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
                                    <i class="ft-menu font-large-1"></i>
                                </a>
                            </li>
                            <li class="nav-item d-none d-md-block">
                                <a class="nav-link nav-link-expand" href="#">
                                    <i class="ficon ft-maximize"></i>
                                </a>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav float-right d-none">
                            <li class="dropdown dropdown-notification nav-item">
                                <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
                                    <i class="ficon ft-bell bell-shake" id="notification-navbar-link"></i>
                                    <span class="badge badge-pill badge-sm badge-info badge-default badge-up badge-glow">5</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                    <div class="arrow_box_right">
                                        <li class="dropdown-menu-header">
                                            <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span></h6>
                                        </li>
                                        <li class="scrollable-container media-list w-100">
                                            <a href="javascript:void(0)">
                                                <div class="media">
                                                    <div class="media-left align-self-center"><i class="ft-plus-square icon-bg-circle bg-cyan"></i></div>
                                                    <div class="media-body">
                                                        <h6 class="media-heading">Test Notification</h6>
                                                        <p class="notification-text font-small-3 text-muted">Lorem ipsum dolor sit amet, consectetuer elit.</p><small>
                                                        <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">30 minutes ago</time></small>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li class="dropdown-menu-footer">
                                            <a class="dropdown-item text-muted text-center" href="javascript:void(0)">Read all notifications</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>

                            <li class="dropdown dropdown-user nav-item">
                                <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                    <span class="avatar avatar-online">
                                        <img src="<?= base_url('app-assets/images/portrait/small/avatar-s-').rand(1,20).".png"?>" alt="avatar"><i></i>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <div class="arrow_box_right">
                                        <a class="dropdown-item disabled" href="<?= base_url('Akun')?>">
                                            <span class="avatar avatar-online">
                                                <img src="<?= base_url('app-assets/images/portrait/small/avatar-s-').rand(1,20).".png"?>" alt="avatar">
                                                <span class="user-name text-bold-700 ml-1">John Doe</span>
                                            </span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?= base_url('Akun')?>"><i class="ft-user"></i> Edit Profile</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?= base_url('logout')?>"><i class="ft-power"></i> Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    <!-- NAVBAR -->

    <!-- SIDEBAR LEFT -->
        <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true" data-img="<?= base_url('app-assets/images/backgrounds/04.jpg') ?>">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row position-relative">
                    <li class="nav-item mr-auto">
                        <a class="navbar-brand" href="<?= base_url('Dashboard'); ?>">
                            <img class="brand-logo" alt="Chameleon admin logo" src="<?= base_url('app-assets/images/logo/logo.png') ?>"/>
                            <h3 class="brand-text">SchoolPay</h3>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block nav-toggle">
                        <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                            <i class="toggle-icon ft-disc font-medium-3" data-ticon="ft-disc"></i>
                        </a>
                    </li>
                    <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
                </ul>
            </div>

            <div class="navigation-background"></div>
            
            <div class="main-menu-content">
                <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                    <?php 
                        $segment = $this->uri->segment(2);
                    ?>
                    <li class="nav-item">
                        <a href="<?= base_url('Dashboard'); ?>">
                            <i class="ft-home"></i>
                            <span class="menu-title" data-i18n="">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('Pesan'); ?>">
                            <i class="ft-inbox"></i>
                            <span class="menu-title" data-i18n="">Pesan</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a 
                            <?php if ($user_detail['status'] != 'admin') { ?>
                                onClick="bukaPengumuman();" href="#" 
                            <?php } else{ ?>
                                href="<?= base_url('Pengumuman'); ?>" 
                            <?php } ?>
                        >
                            <i class="ft-bell"></i>
                            <span class="menu-title" data-i18n="">Pengumuman</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a 
                            <?php if ($user_detail['status'] != 'admin') { ?>
                                onClick="bukaKartu();" href="#" 
                            <?php } else{ ?>
                                href="<?= base_url('Kartu/admin'); ?>" 
                            <?php } ?>
                        >
                            <i class="ft-layout"></i>
                            <span class="menu-title" data-i18n="">Kartu Ujian</span>
                        </a>
                    </li>

                    <?php if ($user_detail['status'] != 'admin') { ?>
                        <li class="nav-item">
                            <a href="<?= base_url('Keuangan/Pembayaran');?>">
                                <i class="la la-money"></i>
                                <span class="menu-title" data-i18n="">Pembayaran</span>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if ($user_detail['status'] == 'admin') { ?>
                        <li class="nav-item has-sub">
                            <a href="#"><i class="la la-money"></i>
                                <span class="menu-title" data-i18n="">Keuangan</span>
                            </a>
                            <ul class="menu-content">
                                <li><a class="menu-item" href="<?= base_url('Keuangan/Pembayaran'); ?>">Pembayaran Siswa</a></li>
                                <div style="height: 7px;"></div>
                                <li><a class="menu-item" href="<?= base_url('Keuangan/Jurnal'); ?>">Jurnal Umum</a></li>
                                <li><a class="menu-item" href="<?= base_url('Keuangan/Rekapitulasi'); ?>">Rekapitulasi</a></li>
                                <div style="height: 7px;"></div>
                                <li><a class="menu-item" href="<?= base_url('Keuangan/POS'); ?>">POS Keuangan</a></li>
                                <li><a class="menu-item" href="<?= base_url('Keuangan/Jenis'); ?>">Jenis Pembayaran</a></li>
                            </ul>
                        </li>

                        <li class="nav-item has-sub">
                            <a href="#"><i class="ft-users"></i>
                                <span class="menu-title" data-i18n="">Siswa</span>
                            </a>
                            <ul class="menu-content">
                                <li><a class="menu-item" href="<?= base_url('Siswa/Siswa'); ?>">Data Siswa</a></li>
                                <li><a class="menu-item" href="<?= base_url('Siswa/Kelulusan'); ?>">Kelulusan</a></li>
                                <li><a class="menu-item" href="<?= base_url('Siswa/PindahKelas'); ?>">Pindah Kelas</a></li>
                                <li><a class="menu-item" href="<?= base_url('Siswa/NaikKelas'); ?>">Kenaikan Kelas</a></li>
                            </ul>
                        </li>

                        <li class="nav-item has-sub">
                            <a href="#"><i class="ft-settings"></i>
                                <span class="menu-title" data-i18n="">Master Data</span>
                            </a>
                            <ul class="menu-content">
                                <li><a class="menu-item" href="<?= base_url('Master/BiodataSekolah'); ?>">Biodata Sekolah</a></li>
                                <li><a class="menu-item" href="<?= base_url('Master/Pengguna'); ?>">Data Pengguna</a></li>
                                <li><a class="menu-item" href="<?= base_url('Master/TahunAjaran'); ?>">Data Tahun Ajaran</a></li>
                                <li><a class="menu-item" href="<?= base_url('Master/ProgramStudi'); ?>">Data Program Studi</a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <br><br>
                    
                    <li class="nav-item">
                        <a href="<?= base_url('Akun'); ?>">
                            <i class="ft-user"></i>
                            <!-- <span class="menu-title" data-i18n="">Akun</span> -->
                            <span class="menu-title" data-i18n=""><?= $this->session->userdata("nama_lengkap"); ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('logout'); ?>">
                            <i class="ft-log-out"></i>
                            <span class="menu-title" data-i18n="">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <!-- SIDEBAR LEFT -->

    <!-- SCRIPTS -->
        <script type="text/javascript" src="<?= base_url('app-assets/js/core/libraries/jquery.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/vendors.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/ui/jquery.sticky.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/ui/jquery.matchHeight-min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/forms/validation/jquery.validate.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/js/core/libraries/bootstrap.min.js'); ?>"></script>
        
        <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/tables/datatable/datatables.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js'); ?>"></script>

        <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/forms/icheck/icheck.min.js'); ?>"></script>
        <!-- <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/forms/select/select2.min.js'); ?>"></script> -->
        <script type="text/javascript" src="<?= base_url('app-assets/vendors/js/modal/sweetalert2.min.js'); ?>"></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
        
        <script type="text/javascript" src="<?= base_url('app-assets/js/core/app-menu.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/js/core/app.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/js/scripts/extensions/jquery.blockUI.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('app-assets/js/custom.js'); ?>"></script>
    <!-- SCRIPTS -->

    <script type="text/javascript">
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

        function bukaPengumuman(){
            $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle').addClass('white');
            $('#modaldialog').addClass('modal-lg');
            $('#modaltitle').html('Pengumuman');
            $('#modalbody').load("<?php echo base_url("Pengumuman/index");?>");
            $('#modal').modal('show');
            $('.modal-footer').hide();
            $('.modal-footer').children('#btnSaveModal').hide();
        }

        function bukaKartu(){
            $('#modalheader').removeClass('bg-info').addClass('bg-primary white');
            $('#modaltitle').addClass('white');
            $('#modaldialog').addClass('modal-lg');
            $('#modaltitle').html('Kartu Ujian');
            $('#modalbody').load("<?php echo base_url("Kartu/index");?>");
            $('#modal').modal('show');
            $('.modal-footer').hide();
            $('.modal-footer').children('#btnSaveModal').hide();
        }

        $(document).ready(function() {

            var $body    = $('body');
            var menuType = $body.data('menu');

            $('body[data-open="hover"] .dropdown').on('mouseenter', function(){
                if (!($(this).hasClass('show'))) {
                    $(this).addClass('show');
                }
                else{
                    $(this).removeClass('show');
                }
            }).on('mouseleave', function(event){
                $(this).removeClass('show');
            });
        });

        function block(boelan,div){
            if (boelan==true) {
                $.blockUI({
                    message: 'Loading...',
                    fadeIn: 1000,
                    fadeOut: 1000,
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: '10px 15px',
                        color: '#fff',
                        width: 'auto',
                        backgroundColor: '#333',
                        marginLeft : 'auto'
                    }
                });
            }
            else{
                $.unblockUI();
            }
        }
        
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

        $(document).on('show.bs.modal', '.modal', function (event) {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        });
    </script>

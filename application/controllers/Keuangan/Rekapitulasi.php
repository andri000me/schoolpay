<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Rekapitulasi extends Core_Controller{
    public function __construct(){
        parent::__construct();
        $this->auth_check();
        $this->adminOnly();
        $this->session->set_userdata('title', 'Rekapitulasi');
    }

    public function index(){
    	if(isset($_GET['cetak'])){
            if($_GET['cetak'] == 'siswa'){
                $this->cetakSiswa();
                redirect('keuangan/Rekapitulasi?cetak=085708094363&listKelas='.$_GET['listKelas']);
            }
        }
        if(isset($_GET['cetak'])){
            if($_GET['cetak'] == 'tanggal'){
                $this->cetakTanggal();
                redirect('keuangan/Rekapitulasi?cetak=085708094363');
            }
        }

       $this->load_content_admin('keuangan/rekapitulasi/index');
    }

    function getClientIP(){
        if ( isset($_SERVER['HTTP_CLIENT_IP']) && ! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }

        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        $ip = ($ip === false) ? '0.0.0.0' : $ip;
        $ip = ($ip === '::1') ? '127.0.0.1' : $ip;
        return $ip;
    }

    function toIndo($tgl){
        $tgl = explode('-', $tgl);
        $bln = array('Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        return $tgl[2].' '.$bln[(int)$tgl[1]].' '.$tgl[0];
    }

    function cetakSiswa(){
        $sekolah = $this->db->query("select * from biodata_sekolah where id = '1'")->result()[0];
        $kelas = explode('.', $_GET['listKelas']);
        $kls = $this->db->query("select kelas from kelas where id_kelas = '".$kelas[0]."'")->result()[0]->kelas;
        $jenisnya = $this->db->query("
            SELECT
                keuangan_siswa_bulanan.id_siswa_bulanan,
                keuangan_siswa_bulanan.id_jenis,
                keuangan_jenis.nama_pembayaran,
                keuangan_pos.id_pos,
                keuangan_pos.pos,
                kelas.kelas,
                keuangan_siswa_bulanan.id_program_studi,
                keuangan_siswa_bulanan.kode_kelas
            FROM
                keuangan_siswa_bulanan
                INNER JOIN keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
                INNER JOIN data_siswa ON data_siswa.nis = keuangan_siswa_bulanan.nis
                INNER JOIN keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
                INNER JOIN kelas ON kelas.id_kelas = keuangan_siswa_bulanan.id_kelas
            WHERE
                data_siswa.aktif = 'Y' AND
                data_siswa.lulus = 'T' AND
                data_siswa.status_kelas = '".$kelas[0]."' and 
                data_siswa.program_studi = '".$kelas[1]."' and 
                data_siswa.kode_kelas = '".$kelas[2]."'
            GROUP BY
                keuangan_jenis.id_jenis
            ORDER BY
                keuangan_jenis.pos,
                keuangan_jenis.id_jenis
        ");

        $content = '
            <table align="center" style="border-collapse: collapse;width: 99%;">
                <tr>
                <th style="width: 120mm;border-bottom:1px solid #000;" colspan="'.(3 + $jenisnya->num_rows()).'">
                    <div align="center">
                        <h3>'.$sekolah->sekolah.'</h3>
                    </div>
                    <div align="center" style="margin-top: -12px;">
                        '.$sekolah->alamat.'
                        <br>
                        Telp. '.$sekolah->tlp.'
                        <hr>
                        <strong>Rekapan Data Siswa Kelas '.$kls.' '.$kelas[1].' '.$kelas[2].'</strong>
                    </div>
                    <br>
                </th>
            </tr>
        ';

        $content .= '
            <tr>
                <th style="font-size: 10px;border:1px solid #000;padding: 5px;width:10px;">No</th>
                <th style="font-size: 10px;border:1px solid #000;padding: 5px;width:50px;">NIS</th>
                <th style="font-size: 10px;border:1px solid #000;padding: 5px;width:100px;">Nama</th>
        ';

        $lebar = round((1000 - 10 - 50 - 100) / $jenisnya->num_rows());
        $listJenis = array();

        foreach($jenisnya->result() as $jn){
            $content .= '<th style="font-size: 10px;border:1px solid #000;padding: 5px;width:'.$lebar.'px;">'.$jn->pos.($jn->id_pos == 1 ? ' '.$jn->kelas.' '.$jn->id_program_studi.' '.$jn->kode_kelas : '').'</th>';
            $listJenis[] = $jn->id_jenis;
        }

        $content .= '</tr>';
        
        $no = 1;
        $tables = $this->db->query("
            SELECT
                data_siswa.nis, 
                data_siswa.nama
            FROM
                data_siswa
            WHERE
                data_siswa.status_kelas = '".$kelas[0]."' and 
                data_siswa.program_studi = '".$kelas[1]."' and 
                data_siswa.kode_kelas = '".$kelas[2]."' and
                data_siswa.aktif = 'Y' AND
                data_siswa.lulus = 'T'
            ORDER BY 
                data_siswa.nis
        ");

        $satu = 0;
        $dua  = 0;
        $totalTunggaanku = array();

        for($i=0;$i<count($listJenis);$i++){
            $totalTunggaanku[$i] = 0;
        }

        foreach($tables->result() as $table){
            $content .= '
                <tr>
                    <td style="font-size: 10px;border:1px solid #000;padding: 5px;">'.$no.'</td>
                    <td style="font-size: 10px;border:1px solid #000;padding: 5px;">'.$table->nis.'</td>
                    <td style="font-size: 10px;border:1px solid #000;padding: 5px;">'.$table->nama.'</td>
            ';

            for($i=0;$i<count($listJenis);$i++){
                $ygHarusDibayar = $this->db->query("
                    SELECT
                        keuangan_siswa_bulanan.tahun_ajaran,
                        keuangan_jenis.tipe,
                        (keuangan_siswa_bulanan.b1+
                        keuangan_siswa_bulanan.b2+
                        keuangan_siswa_bulanan.b3+
                        keuangan_siswa_bulanan.b4+
                        keuangan_siswa_bulanan.b5+
                        keuangan_siswa_bulanan.b6+
                        keuangan_siswa_bulanan.b7+
                        keuangan_siswa_bulanan.b8+
                        keuangan_siswa_bulanan.b9+
                        keuangan_siswa_bulanan.b10+
                        keuangan_siswa_bulanan.b11+
                        keuangan_siswa_bulanan.b12) as jmlPembayaran
                    FROM
                        keuangan_siswa_bulanan
                        INNER JOIN keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
                        INNER JOIN data_siswa ON data_siswa.nis = keuangan_siswa_bulanan.nis
                        INNER JOIN keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
                        INNER JOIN kelas ON kelas.id_kelas = keuangan_siswa_bulanan.id_kelas
                    WHERE
                        data_siswa.aktif = 'Y' AND
                        data_siswa.lulus = 'T' AND
                        data_siswa.nis = '".$table->nis."' AND
                        data_siswa.status_kelas = '".$kelas[0]."' and 
                        data_siswa.program_studi = '".$kelas[1]."' and 
                        data_siswa.kode_kelas = '".$kelas[2]."' and
                        keuangan_siswa_bulanan.id_jenis = '".$listJenis[$i]."'
                ");

                if($ygHarusDibayar->num_rows() > 0){
                    $ygHarusDibayarNya = $ygHarusDibayar->result()[0];
                    $ygHarusDibayarNyaOk = $ygHarusDibayarNya->jmlPembayaran;

                    if($ygHarusDibayarNya->tipe == 'Bulanan'){
                        $hugf = $this->db->query("
                            SELECT
                                (pembayaran_siswa_bulanan.l1+
                                pembayaran_siswa_bulanan.l2+
                                pembayaran_siswa_bulanan.l3+
                                pembayaran_siswa_bulanan.l4+
                                pembayaran_siswa_bulanan.l5+
                                pembayaran_siswa_bulanan.l6+
                                pembayaran_siswa_bulanan.l7+
                                pembayaran_siswa_bulanan.l8+
                                pembayaran_siswa_bulanan.l9+
                                pembayaran_siswa_bulanan.l10+
                                pembayaran_siswa_bulanan.l11+
                                pembayaran_siswa_bulanan.l12) as jmlUtang
                            FROM
                                pembayaran_siswa_bulanan
                            WHERE
                                pembayaran_siswa_bulanan.tahun_ajaran = '".$ygHarusDibayarNya->tahun_ajaran."' AND
                                pembayaran_siswa_bulanan.nis = '".$table->nis."' AND
                                pembayaran_siswa_bulanan.id_jenis = '".$listJenis[$i]."'
                        ");

                        if($hugf->num_rows() > 0){
                            $ygHarusDibayarNyaOk -= $hugf->result()[0]->jmlUtang;
                        }
                    }

                    if($ygHarusDibayarNya->tipe == 'Bebas'){
                        $hugf = $this->db->query("
                            SELECT
                                sum(cicilan) as jmlUtang
                            FROM
                                pembayaran_siswa_bebas
                            WHERE
                                pembayaran_siswa_bebas.tahun_ajaran = '".$ygHarusDibayarNya->tahun_ajaran."' AND
                                pembayaran_siswa_bebas.nis = '".$table->nis."' AND
                                pembayaran_siswa_bebas.id_jenis = '".$listJenis[$i]."'
                        ");

                        if($hugf->num_rows() > 0){
                            $ygHarusDibayarNyaOk -= $hugf->result()[0]->jmlUtang;
                        }
                    }

                    $content .= '<td style="font-size: 10px;border:1px solid #000;padding: 5px;" align="right">'.number_format($ygHarusDibayarNyaOk, 0, ',', '.').'</td>';
                    $totalTunggaanku[$i] += $ygHarusDibayarNyaOk;
                }
                else{
                    $content .= '<td style="font-size: 10px;border:1px solid #000;padding: 5px;" align="right">'.number_format(0, 0, ',', '.').'</td>';
                }
            }

            $content .= '</tr>';
            $no += 1;
        }

        $content .= '
            <tr>
                <td colspan="3" style="border:1px solid #000;padding: 5px;"><strong>Total</strong></td>
        ';

        for($i=0;$i<count($listJenis);$i++){
            $content .= '<td style="font-size: 10px;border:1px solid #000;padding: 5px;" align="right">'.number_format($totalTunggaanku[$i], 0, ',', '.').'</td>';
        }

        $content .= '</tr>';
        $content .= '
            </table>
            <table align="right" style="margin-right: 1%;">
                <tr>
                    <td colspan="'.(3 + $jenisnya->num_rows()).'" style=""><br>( Petugas )</td>
                </tr>
                <tr>
                    <td colspan="'.(3 + $jenisnya->num_rows()).'" style="">'.$sekolah->kabupaten.', '.$this->toIndo(date('Y-m-d')).'</td>
                </tr>
                <tr>
                    <td colspan="'.(3 + $jenisnya->num_rows()).'"><br><br><br></td>
                </tr>
                <tr>
                    <td colspan="'.(3 + $jenisnya->num_rows()).'" style="">'.$this->session->userdata('nama_lengkap').'</td>
                </tr>
            </table>
        ';

        $header = '<style>';
        $header .= '
            @page { size: landscape; }
            /* reset.css */
            /*! normalize.css v3.0.2 | MIT License | git.io/normalize */

            /**
             * 1. Set default font family to sans-serif.
             * 2. Prevent iOS text size adjust after orientation change, without disabling
             *    user zoom.
             */

            html {
              font-family: sans-serif; /* 1 */
              -ms-text-size-adjust: 100%; /* 2 */
              -webkit-text-size-adjust: 100%; /* 2 */
            }

            /**
             * Remove default margin.
             */

            body {
              margin: 0;
            }

            /* HTML5 display definitions
               ========================================================================== */

            /**
             * Correct `block` display not defined for any HTML5 element in IE 8/9.
             * Correct `block` display not defined for `details` or `summary` in IE 10/11
             * and Firefox.
             * Correct `block` display not defined for `main` in IE 11.
             */

            article,
            aside,
            details,
            figcaption,
            figure,
            footer,
            header,
            hgroup,
            main,
            menu,
            nav,
            section,
            summary {
              display: block;
            }

            /**
             * 1. Correct `inline-block` display not defined in IE 8/9.
             * 2. Normalize vertical alignment of `progress` in Chrome, Firefox, and Opera.
             */

            audio,
            canvas,
            progress,
            video {
              display: inline-block; /* 1 */
              vertical-align: baseline; /* 2 */
            }

            /**
             * Prevent modern browsers from displaying `audio` without controls.
             * Remove excess height in iOS 5 devices.
             */

            audio:not([controls]) {
              display: none;
              height: 0;
            }

            /**
             * Address `[hidden]` styling not present in IE 8/9/10.
             * Hide the `template` element in IE 8/9/11, Safari, and Firefox < 22.
             */

            [hidden],
            template {
              display: none;
            }

            /* Links
               ========================================================================== */

            /**
             * Remove the gray background color from active links in IE 10.
             */

            a {
              background-color: transparent;
            }

            /**
             * Improve readability when focused and also mouse hovered in all browsers.
             */

            a:active,
            a:hover {
              outline: 0;
            }

            /* Text-level semantics
               ========================================================================== */

            /**
             * Address styling not present in IE 8/9/10/11, Safari, and Chrome.
             */

            abbr[title] {
              border-bottom: 1px dotted;
            }

            /**
             * Address style set to `bolder` in Firefox 4+, Safari, and Chrome.
             */

            b,
            strong {
              font-weight: bold;
            }

            /**
             * Address styling not present in Safari and Chrome.
             */

            dfn {
              font-style: italic;
            }

            /**
             * Address variable `h1` font-size and margin within `section` and `article`
             * contexts in Firefox 4+, Safari, and Chrome.
             */

            h1 {
              font-size: 2em;
              margin: 0.67em 0;
            }

            /**
             * Address styling not present in IE 8/9.
             */

            mark {
              background: #ff0;
              color: #000;
            }

            /**
             * Address inconsistent and variable font size in all browsers.
             */

            small {
              font-size: 80%;
            }

            /**
             * Prevent `sub` and `sup` affecting `line-height` in all browsers.
             */

            sub,
            sup {
              font-size: 75%;
              line-height: 0;
              position: relative;
              vertical-align: baseline;
            }

            sup {
              top: -0.5em;
            }

            sub {
              bottom: -0.25em;
            }

            /* Embedded content
               ========================================================================== */

            /**
             * Remove border when inside `a` element in IE 8/9/10.
             */

            img {
              border: 0;
            }

            /**
             * Correct overflow not hidden in IE 9/10/11.
             */

            svg:not(:root) {
              overflow: hidden;
            }

            /* Grouping content
               ========================================================================== */

            /**
             * Address margin not present in IE 8/9 and Safari.
             */

            figure {
              margin: 1em 40px;
            }

            /**
             * Address differences between Firefox and other browsers.
             */

            hr {
              -moz-box-sizing: content-box;
              box-sizing: content-box;
              height: 0;
            }

            /**
             * Contain overflow in all browsers.
             */

            pre {
              overflow: auto;
            }

            /**
             * Address odd `em`-unit font size rendering in all browsers.
             */

            code,
            kbd,
            pre,
            samp {
              font-family: monospace, monospace;
              font-size: 1em;
            }

            /* Forms
               ========================================================================== */

            /**
             * Known limitation: by default, Chrome and Safari on OS X allow very limited
             * styling of `select`, unless a `border` property is set.
             */

            /**
             * 1. Correct color not being inherited.
             *    Known issue: affects color of disabled elements.
             * 2. Correct font properties not being inherited.
             * 3. Address margins set differently in Firefox 4+, Safari, and Chrome.
             */

            button,
            input,
            optgroup,
            select,
            textarea {
              color: inherit; /* 1 */
              font: inherit; /* 2 */
              margin: 0; /* 3 */
            }

            /**
             * Address `overflow` set to `hidden` in IE 8/9/10/11.
             */

            button {
              overflow: visible;
            }

            /**
             * Address inconsistent `text-transform` inheritance for `button` and `select`.
             * All other form control elements do not inherit `text-transform` values.
             * Correct `button` style inheritance in Firefox, IE 8/9/10/11, and Opera.
             * Correct `select` style inheritance in Firefox.
             */

            button,
            select {
              text-transform: none;
            }

            /**
             * 1. Avoid the WebKit bug in Android 4.0.* where (2) destroys native `audio`
             *    and `video` controls.
             * 2. Correct inability to style clickable `input` types in iOS.
             * 3. Improve usability and consistency of cursor style between image-type
             *    `input` and others.
             */

            button,
            html input[type="button"], /* 1 */
            input[type="reset"],
            input[type="submit"] {
              -webkit-appearance: button; /* 2 */
              cursor: pointer; /* 3 */
            }

            /**
             * Re-set default cursor for disabled elements.
             */

            button[disabled],
            html input[disabled] {
              cursor: default;
            }

            /**
             * Remove inner padding and border in Firefox 4+.
             */

            button::-moz-focus-inner,
            input::-moz-focus-inner {
              border: 0;
              padding: 0;
            }

            /**
             * Address Firefox 4+ setting `line-height` on `input` using `!important` in
             * the UA stylesheet.
             */

            input {
              line-height: normal;
            }

            /**
             * Its recommended that you dont attempt to style these elements.
             * Firefoxs implementation doesnt respect box-sizing, padding, or width.
             *
             * 1. Address box sizing set to `content-box` in IE 8/9/10.
             * 2. Remove excess padding in IE 8/9/10.
             */

            input[type="checkbox"],
            input[type="radio"] {
              box-sizing: border-box; /* 1 */
              padding: 0; /* 2 */
            }

            /**
             * Fix the cursor style for Chromes increment/decrement buttons. For certain
             * `font-size` values of the `input`, it causes the cursor style of the
             * decrement button to change from `default` to `text`.
             */

            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
              height: auto;
            }

            /**
             * 1. Address `appearance` set to `searchfield` in Safari and Chrome.
             * 2. Address `box-sizing` set to `border-box` in Safari and Chrome
             *    (include `-moz` to future-proof).
             */

            input[type="search"] {
              -webkit-appearance: textfield; /* 1 */
              -moz-box-sizing: content-box;
              -webkit-box-sizing: content-box; /* 2 */
              box-sizing: content-box;
            }

            /**
             * Remove inner padding and search cancel button in Safari and Chrome on OS X.
             * Safari (but not Chrome) clips the cancel button when the search input has
             * padding (and `textfield` appearance).
             */

            input[type="search"]::-webkit-search-cancel-button,
            input[type="search"]::-webkit-search-decoration {
              -webkit-appearance: none;
            }

            /**
             * Define consistent border, margin, and padding.
             */

            fieldset {
              border: 1px solid #c0c0c0;
              margin: 0 2px;
              padding: 0.35em 0.625em 0.75em;
            }

            /**
             * 1. Correct `color` not being inherited in IE 8/9/10/11.
             * 2. Remove padding so people arent caught out if they zero out fieldsets.
             */

            legend {
              border: 0; /* 1 */
              padding: 0; /* 2 */
            }

            /**
             * Remove default vertical scrollbar in IE 8/9/10/11.
             */

            textarea {
              overflow: auto;
            }

            /**
             * Dont inherit the `font-weight` (applied by a rule above).
             * NOTE: the default cannot safely be changed in Chrome and Safari on OS X.
             */

            optgroup {
              font-weight: bold;
            }

            /* Tables
               ========================================================================== */

            /**
             * Remove most spacing between table cells.
             */

            table {
              border-collapse: collapse;
              border-spacing: 0;
            }

            td,
            th {
              padding: 0;
            }

            h1, h2, h3, h4, h5, h6 {
              margin-bottom: 15px;
            }
            * {
                font-size: 10px;
            }
            table {
                margin: 5px 0;
            }
            .myTable td, .myTable td strong, .myTable td div {
                font-size: 9px;
            }
            hr {
                border: none;
                border-bottom: 1px solid #000000;
            }
        ';

        $header .= '</style>';
        $footer = '<script>';
        $footer .= 'window.print();';
        $footer .= '</script>';

        echo $header.$content.$footer;
        exit(0);

        include(BASEPATH.'../application/libraries/PDF.php');
        $html2pdf = new HTML2PDF('L',array('210','330'),'en');
        $html2pdf->setTestTdInOnePage(false);
        $html2pdf->WriteHTML($content);
        $html2pdf->Output('template/'.$this->getClientIP().'.pdf', 'F');
		sleep(3);
    }

    public function cetakTanggal(){
        $tanggal = explode('-', $_GET['tanggal']);
        $tglku   = $tanggal[0];
        $bulanku = (int)$tanggal[1];
        $tahunku = $tanggal[2];
        $saldoku = 0;
        $debitku = 0;
        $kreditku= 0;

        $sekolah = $this->db->query("select * from biodata_sekolah where id = '1'")->result()[0];
        $bulan = array('Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        $content = '
            <table style="width: 100%" align="center">
                <tr>
                    <td>
                    <table align="center" style="border-collapse: collapse;" width="1024">
                        <thead>
                            <tr>
                                <th style="width: 120mm;border-bottom:1px solid #000;" colspan="6">
                                    <div align="center">
                                        <h3>'.$sekolah->sekolah.'</h3>
                                    </div>
                                    <div align="center" style="margin-top: -13px;">
                                        '.$sekolah->alamat.'
                                        <br>
                                        Telp. '.$sekolah->tlp.'
                                        <hr>
                                        <strong>Jurnal '.$tglku.' '.$bulan[$bulanku].' '.substr($tahunku, 0, 4).'</strong>
                                    </div>
                                    <br>
                                </th>
                            </tr>
                            <tr>
                                <th width="15" style="border:1px solid #000;padding: 5px; width: 5mm;"">No</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;"">Tanggal</th>
                                <th style="border:1px solid #000;padding: 5px; width: 70mm;">Keterangan</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;">Penerimaan</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;">Pengeluaran</th>
                                <th style="border:1px solid #000;padding: 5px; width: 20mm;">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $no = 1;
                            $jurnal = $this->db->query("select * from keuangan_jurnal where tanggal = '".($tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0])."' order by tanggal, keterangan");
                            foreach($jurnal->result() as $jnl):
                            $debitku += $jnl->debit;
                            $kreditku += $jnl->kredit;
                            $saldoku = $debitku - $kreditku;
                            $content .= '<tr>
                                <td class="text-center" style="border:1px solid #000;padding: 5px;" align="center">'.$no.'</td>
                                <td style="border:1px solid #000;padding: 5px;" align="center">'.$this->toIndo($jnl->tanggal).'</td>
                                <td style="border:1px solid #000;padding: 5px;">'.$jnl->keterangan.'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">'.number_format($jnl->debit, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">'.number_format($jnl->kredit, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">'.number_format($saldoku, 0, ',', '.').'</td>
                            </tr>';
                            $no += 1;
                            endforeach;
                            $content .= '<tr class="alert-info">
                                <td colspan="3" style="border:1px solid #000;padding: 5px;"><strong>Total</strong></td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">';
                                    $content .=  number_format($debitku, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">';
                                    $content .=  number_format($kreditku, 0, ',', '.').'</td>
                                <td style="border:1px solid #000;padding: 5px;text-align:right;">';
                                    $content .=  number_format($saldoku, 0, ',', '.').'</td>
                            </tr>';
                       $content .= '</tbody>
                    </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table align="right">
                            <tr>
                                <td style="">( Petugas )</td>
                            </tr>
                            <tr>
                                <td style="">'.$sekolah->kabupaten.', '.$this->toIndo(date('Y-m-d')).'</td>
                            </tr>
                            <tr>
                                <td><br><br><br></td>
                            </tr>
                            <tr>
                                <td style="">'.$this->session->userdata('nama_lengkap').'</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        ';

        $header = '<style>';
        $header .= '
            @page { size: landscape; }
            /* reset.css */
            /*! normalize.css v3.0.2 | MIT License | git.io/normalize */

            /**
             * 1. Set default font family to sans-serif.
             * 2. Prevent iOS text size adjust after orientation change, without disabling
             *    user zoom.
             */

            html {
              font-family: sans-serif; /* 1 */
              -ms-text-size-adjust: 100%; /* 2 */
              -webkit-text-size-adjust: 100%; /* 2 */
            }

            /**
             * Remove default margin.
             */

            body {
              margin: 0;
            }

            /* HTML5 display definitions
               ========================================================================== */

            /**
             * Correct `block` display not defined for any HTML5 element in IE 8/9.
             * Correct `block` display not defined for `details` or `summary` in IE 10/11
             * and Firefox.
             * Correct `block` display not defined for `main` in IE 11.
             */

            article,
            aside,
            details,
            figcaption,
            figure,
            footer,
            header,
            hgroup,
            main,
            menu,
            nav,
            section,
            summary {
              display: block;
            }

            /**
             * 1. Correct `inline-block` display not defined in IE 8/9.
             * 2. Normalize vertical alignment of `progress` in Chrome, Firefox, and Opera.
             */

            audio,
            canvas,
            progress,
            video {
              display: inline-block; /* 1 */
              vertical-align: baseline; /* 2 */
            }

            /**
             * Prevent modern browsers from displaying `audio` without controls.
             * Remove excess height in iOS 5 devices.
             */

            audio:not([controls]) {
              display: none;
              height: 0;
            }

            /**
             * Address `[hidden]` styling not present in IE 8/9/10.
             * Hide the `template` element in IE 8/9/11, Safari, and Firefox < 22.
             */

            [hidden],
            template {
              display: none;
            }

            /* Links
               ========================================================================== */

            /**
             * Remove the gray background color from active links in IE 10.
             */

            a {
              background-color: transparent;
            }

            /**
             * Improve readability when focused and also mouse hovered in all browsers.
             */

            a:active,
            a:hover {
              outline: 0;
            }

            /* Text-level semantics
               ========================================================================== */

            /**
             * Address styling not present in IE 8/9/10/11, Safari, and Chrome.
             */

            abbr[title] {
              border-bottom: 1px dotted;
            }

            /**
             * Address style set to `bolder` in Firefox 4+, Safari, and Chrome.
             */

            b,
            strong {
              font-weight: bold;
            }

            /**
             * Address styling not present in Safari and Chrome.
             */

            dfn {
              font-style: italic;
            }

            /**
             * Address variable `h1` font-size and margin within `section` and `article`
             * contexts in Firefox 4+, Safari, and Chrome.
             */

            h1 {
              font-size: 2em;
              margin: 0.67em 0;
            }

            /**
             * Address styling not present in IE 8/9.
             */

            mark {
              background: #ff0;
              color: #000;
            }

            /**
             * Address inconsistent and variable font size in all browsers.
             */

            small {
              font-size: 80%;
            }

            /**
             * Prevent `sub` and `sup` affecting `line-height` in all browsers.
             */

            sub,
            sup {
              font-size: 75%;
              line-height: 0;
              position: relative;
              vertical-align: baseline;
            }

            sup {
              top: -0.5em;
            }

            sub {
              bottom: -0.25em;
            }

            /* Embedded content
               ========================================================================== */

            /**
             * Remove border when inside `a` element in IE 8/9/10.
             */

            img {
              border: 0;
            }

            /**
             * Correct overflow not hidden in IE 9/10/11.
             */

            svg:not(:root) {
              overflow: hidden;
            }

            /* Grouping content
               ========================================================================== */

            /**
             * Address margin not present in IE 8/9 and Safari.
             */

            figure {
              margin: 1em 40px;
            }

            /**
             * Address differences between Firefox and other browsers.
             */

            hr {
              -moz-box-sizing: content-box;
              box-sizing: content-box;
              height: 0;
            }

            /**
             * Contain overflow in all browsers.
             */

            pre {
              overflow: auto;
            }

            /**
             * Address odd `em`-unit font size rendering in all browsers.
             */

            code,
            kbd,
            pre,
            samp {
              font-family: monospace, monospace;
              font-size: 1em;
            }

            /* Forms
               ========================================================================== */

            /**
             * Known limitation: by default, Chrome and Safari on OS X allow very limited
             * styling of `select`, unless a `border` property is set.
             */

            /**
             * 1. Correct color not being inherited.
             *    Known issue: affects color of disabled elements.
             * 2. Correct font properties not being inherited.
             * 3. Address margins set differently in Firefox 4+, Safari, and Chrome.
             */

            button,
            input,
            optgroup,
            select,
            textarea {
              color: inherit; /* 1 */
              font: inherit; /* 2 */
              margin: 0; /* 3 */
            }

            /**
             * Address `overflow` set to `hidden` in IE 8/9/10/11.
             */

            button {
              overflow: visible;
            }

            /**
             * Address inconsistent `text-transform` inheritance for `button` and `select`.
             * All other form control elements do not inherit `text-transform` values.
             * Correct `button` style inheritance in Firefox, IE 8/9/10/11, and Opera.
             * Correct `select` style inheritance in Firefox.
             */

            button,
            select {
              text-transform: none;
            }

            /**
             * 1. Avoid the WebKit bug in Android 4.0.* where (2) destroys native `audio`
             *    and `video` controls.
             * 2. Correct inability to style clickable `input` types in iOS.
             * 3. Improve usability and consistency of cursor style between image-type
             *    `input` and others.
             */

            button,
            html input[type="button"], /* 1 */
            input[type="reset"],
            input[type="submit"] {
              -webkit-appearance: button; /* 2 */
              cursor: pointer; /* 3 */
            }

            /**
             * Re-set default cursor for disabled elements.
             */

            button[disabled],
            html input[disabled] {
              cursor: default;
            }

            /**
             * Remove inner padding and border in Firefox 4+.
             */

            button::-moz-focus-inner,
            input::-moz-focus-inner {
              border: 0;
              padding: 0;
            }

            /**
             * Address Firefox 4+ setting `line-height` on `input` using `!important` in
             * the UA stylesheet.
             */

            input {
              line-height: normal;
            }

            /**
             * Its recommended that you dont attempt to style these elements.
             * Firefoxs implementation doesnt respect box-sizing, padding, or width.
             *
             * 1. Address box sizing set to `content-box` in IE 8/9/10.
             * 2. Remove excess padding in IE 8/9/10.
             */

            input[type="checkbox"],
            input[type="radio"] {
              box-sizing: border-box; /* 1 */
              padding: 0; /* 2 */
            }

            /**
             * Fix the cursor style for Chromes increment/decrement buttons. For certain
             * `font-size` values of the `input`, it causes the cursor style of the
             * decrement button to change from `default` to `text`.
             */

            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
              height: auto;
            }

            /**
             * 1. Address `appearance` set to `searchfield` in Safari and Chrome.
             * 2. Address `box-sizing` set to `border-box` in Safari and Chrome
             *    (include `-moz` to future-proof).
             */

            input[type="search"] {
              -webkit-appearance: textfield; /* 1 */
              -moz-box-sizing: content-box;
              -webkit-box-sizing: content-box; /* 2 */
              box-sizing: content-box;
            }

            /**
             * Remove inner padding and search cancel button in Safari and Chrome on OS X.
             * Safari (but not Chrome) clips the cancel button when the search input has
             * padding (and `textfield` appearance).
             */

            input[type="search"]::-webkit-search-cancel-button,
            input[type="search"]::-webkit-search-decoration {
              -webkit-appearance: none;
            }

            /**
             * Define consistent border, margin, and padding.
             */

            fieldset {
              border: 1px solid #c0c0c0;
              margin: 0 2px;
              padding: 0.35em 0.625em 0.75em;
            }

            /**
             * 1. Correct `color` not being inherited in IE 8/9/10/11.
             * 2. Remove padding so people arent caught out if they zero out fieldsets.
             */

            legend {
              border: 0; /* 1 */
              padding: 0; /* 2 */
            }

            /**
             * Remove default vertical scrollbar in IE 8/9/10/11.
             */

            textarea {
              overflow: auto;
            }

            /**
             * Dont inherit the `font-weight` (applied by a rule above).
             * NOTE: the default cannot safely be changed in Chrome and Safari on OS X.
             */

            optgroup {
              font-weight: bold;
            }

            /* Tables
               ========================================================================== */

            /**
             * Remove most spacing between table cells.
             */

            table {
              border-collapse: collapse;
              border-spacing: 0;
            }

            td,
            th {
              padding: 0;
            }

            h1, h2, h3, h4, h5, h6 {
              margin-bottom: 15px;
            }
            * {
                font-size: 10px;
            }
            table {
                margin: 5px 0;
            }
            .myTable td, .myTable td strong, .myTable td div {
                font-size: 9px;
            }
            hr {
                border: none;
                border-bottom: 1px solid #000000;
            }
        ';

        $header .= '</style>';
        $footer = '<script>';
        $footer .= 'window.print();';
        $footer .= '</script>';
        echo $header.$content.$footer;
        exit(0);

        include(BASEPATH.'../application/libraries/PDF.php');
        
        $html2pdf = new HTML2PDF('P',array('210','330'),'en');
        $html2pdf->setTestTdInOnePage(false);
        $html2pdf->WriteHTML($content);
        $html2pdf->Output('template/'.$this->getClientIP().'.pdf', 'F');
		sleep(3);
    }

    function rekapAll(){
        require_once APPPATH."/libraries/PHPExcel.php";
        require_once APPPATH."/libraries/PHPExcel/Writer/Excel2007.php";
        $objPHPExcel = new PHPExcel();
        $debitku = 0;
        $kreditku = 0;

        $aktifSheet = 0;
        $data_huruf = 'A B C D E F G H I J K L M N O P Q R S T U V W X Y Z AA AB AC AD AE AF AG AH AI AJ AK AL AM AN AO AP AQ AR AS AT AU AV AW AX AY AZ BA BB BC BD BE BF BG BH BI BJ BK BL BM BN BO BP BQ BR BS BT BU BV BW BX BY BZ CA CB CD CC CE CF CG CH CI CJ CK CL CM CN CO CP CQ CR CS CT CU CV CW CX CY CZ DA DB DC DD DE DF DG DH DI DJ DK DL DM DN DO DP DQ DR DS DT DU DV DW DX DY DZ EA EB EC ED EE EF EH EI EJ EK EL EM EN EO EP EQ ER ES ET EU EV EW EX EY EZ
        ';
        $data_huruf = explode(' ', $data_huruf);
        
        $objPHPExcel->setActiveSheetIndex($aktifSheet);
        $ws = $objPHPExcel->getActiveSheet();
        $ws->setTitle('Jurnal');
		$ws->getColumnDimension('A')->setWidth(4);
		$ws->getColumnDimension('B')->setAutoSize(true);
		$ws->getColumnDimension('C')->setAutoSize(true);
		$ws->getColumnDimension('D')->setAutoSize(true);
		$ws->getColumnDimension('E')->setAutoSize(true);
		$ws->getColumnDimension('F')->setAutoSize(true);
		$ws->getColumnDimension('G')->setAutoSize(true);

        $posisiY = 1;
        $posisiX = 0;
        
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'No');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Tanggal');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Petugas');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Keterangan');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Penerimaan');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Pengeluaran');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Saldo');
        
        $bulan = array('Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $data['bulanku'] = isset($_GET['bulan']) ? (int)$_GET['bulan'] : (int)date('m');
        $data['tahunku'] = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)$this->db->query("select tahun_ajaran from tahun_ajaran where aktif = 'Y'")->result()[0]->tahun_ajaran;
        $kasku = $this->lastKas($data['bulanku'], $data['tahunku']);
        $kaskulagi = $this->nowKas($data['bulanku'], $data['tahunku']);

        $posisiY += 1;
        $posisiX = 0;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, '1');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, '#');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, '#');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Kas Bulan '.$bulan[$data['bulanku'] - 1].' '.($data['bulanku'] - 1 == 0 ? (substr($data['tahunku'], 0, 4) - 1) : (substr($data['tahunku'], 0, 4))));
        $posisiX += 1;
        $hasil = $kasku >= 0 ? $kasku : '0';
        $debitku += $hasil;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($hasil, 2, ',', '.'));
        $posisiX += 1;
        $hasil = $kasku < 0 ? (-1 * $kasku) : '0';
        $kreditku += $hasil;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($hasil, 2, ',', '.'));
        $posisiX += 1;
        $saldoku = $debitku - $kreditku;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($saldoku, 2, ',', '.'));

        $posisiY += 1;
        $posisiX = 0;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, '2');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, '#');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, '#');
        $posisiX += 1;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Pembayaran Siswa Bulan '.$bulan[$data['bulanku']].' '.substr($data['tahunku'], 0, 4));
        $posisiX += 1;
        $hasil = $kasku >= 0 ? $kaskulagi : '0';
        $debitku += $hasil;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($hasil, 2, ',', '.'));
        $posisiX += 1;
        $hasil = $kasku < 0 ? (-1 * $kaskulagi) : '0';
        $kreditku += $hasil;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($hasil, 2, ',', '.'));
        $posisiX += 1;
        $saldoku = $debitku - $kreditku;
        $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($saldoku, 2, ',', '.'));
        
        $no = 3;
        $jurnal = $this->db->query("select keuangan_jurnal.*, pengguna.nama_lengkap from keuangan_jurnal inner join pengguna on keuangan_jurnal.petugas = pengguna.id_pengguna where year(tanggal) = '".(substr($data['tahunku'], 0, 4))."' and month(tanggal) = '".$data['bulanku']."' order by tanggal, id_jurnal, keterangan");
        foreach($jurnal->result() as $jnl){
            $posisiY += 1;
            $posisiX = 0;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, $no);
            $posisiX += 1;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, $this->toIndo($jnl->tanggal));
            $posisiX += 1;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, $jnl->nama_lengkap);
            $posisiX += 1;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, $jnl->keterangan);
            $posisiX += 1;
            $debitku += $jnl->debit;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($jnl->debit, 2, ',', '.'));
            $posisiX += 1;
            $kreditku += $jnl->kredit;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($jnl->kredit, 2, ',', '.'));
            $posisiX += 1;
            $saldoku = $debitku - $kreditku;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($saldoku, 2, ',', '.'));
            $no += 1;
        }
            $posisiY += 1;
            $posisiX = 0;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Total');
            $posisiX += 1;
            $posisiX += 1;
            $posisiX += 1;
            $posisiX += 1;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($debitku, 2, ',', '.'));
            $posisiX += 1;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($kreditku, 2, ',', '.'));
            $posisiX += 1;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($saldoku, 2, ',', '.'));
			$ws->getStyle('E2:G'.$posisiY)->applyFromArray(array(
                                                    'alignment' => array(
                                                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                                        'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    ),
                                                ));

        $listKelas = $this->db->query('select data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas, kelas.kelas from data_siswa inner join kelas on data_siswa.status_kelas = kelas.id_kelas group by data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas order by data_siswa.status_kelas, data_siswa.program_studi, data_siswa.kode_kelas');
        foreach($listKelas->result() as $lkls):
        	$aktifSheet += 1;
        	$objPHPExcel->createSheet($aktifSheet);
	        $objPHPExcel->setActiveSheetIndex($aktifSheet);
	        $ws = $objPHPExcel->getActiveSheet();
	        $ws->setTitle($lkls->kelas.' '.$lkls->program_studi.' '.$lkls->kode_kelas);
            $ws->getColumnDimension('A')->setWidth(4);
            $ws->getColumnDimension('B')->setAutoSize(true);
            $ws->getColumnDimension('C')->setAutoSize(true);
            $ws->getColumnDimension('D')->setAutoSize(true);
            $ws->getColumnDimension('E')->setAutoSize(true);
            $ws->getColumnDimension('F')->setAutoSize(true);
            $ws->getColumnDimension('G')->setAutoSize(true);
            $ws->getColumnDimension('H')->setAutoSize(true);
            $ws->getColumnDimension('I')->setAutoSize(true);
            $ws->getColumnDimension('J')->setAutoSize(true);
            $ws->getColumnDimension('K')->setAutoSize(true);
            $ws->getColumnDimension('L')->setAutoSize(true);
            $ws->getColumnDimension('M')->setAutoSize(true);
            $ws->getColumnDimension('N')->setAutoSize(true);
            $ws->getColumnDimension('O')->setAutoSize(true);
            $ws->getColumnDimension('P')->setAutoSize(true);
            $ws->getColumnDimension('P')->setAutoSize(true);
            $posisiY = 0;
            $posisiX = 0;
            
            $kelas = explode(' ', $lkls->kelas.' '.$lkls->program_studi.' '.$lkls->kode_kelas);
            $kelas[0] = $this->db->query("select id_kelas from kelas where kelas = '".$kelas[0]."'")->result()[0]->id_kelas;
            $jenisnya = $this->db->query("SELECT
                                        keuangan_siswa_bulanan.id_siswa_bulanan,
                                        keuangan_siswa_bulanan.id_jenis,
                                        keuangan_jenis.nama_pembayaran,
                                        keuangan_pos.id_pos,
                                        keuangan_pos.pos,
                                        kelas.kelas,
                                        keuangan_siswa_bulanan.id_program_studi,
                                        keuangan_siswa_bulanan.kode_kelas
                                    FROM
                                        keuangan_siswa_bulanan
                                        INNER JOIN keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
                                        INNER JOIN data_siswa ON data_siswa.nis = keuangan_siswa_bulanan.nis
                                        INNER JOIN keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
                                        INNER JOIN kelas ON kelas.id_kelas = keuangan_siswa_bulanan.id_kelas
                                    WHERE
                                        data_siswa.aktif = 'Y' AND
                                        data_siswa.lulus = 'T' AND
                                        data_siswa.status_kelas = '".$kelas[0]."' and 
                                        data_siswa.program_studi = '".$kelas[1]."' and 
                                        data_siswa.kode_kelas = '".$kelas[2]."'
                                    GROUP BY
                                        keuangan_jenis.id_jenis
                                    ORDER BY
                                        keuangan_jenis.pos,
                                        keuangan_jenis.id_jenis");
            $posisiY += 1;
            $posisiX = 0;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'No');
            $posisiX += 1;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'NIS');
            $posisiX += 1;
            $ws->setCellValue($data_huruf[$posisiX].$posisiY, 'Nama');

            $listJenis = array();
            foreach($jenisnya->result() as $jn){
                $posisiX += 1;
                $ws->setCellValue($data_huruf[$posisiX].$posisiY, $jn->pos.($jn->id_pos == 1 ? ' '.$jn->kelas.' '.$jn->id_program_studi.' '.$jn->kode_kelas : ''));
                $listJenis[] = $jn->id_jenis;
            }
            $no = 1;
            $tables = $this->db->query("SELECT
                                            data_siswa.nis, 
                                            data_siswa.nama
                                        FROM
                                            data_siswa
                                        WHERE
                                            data_siswa.status_kelas = '".$kelas[0]."' and 
                                            data_siswa.program_studi = '".$kelas[1]."' and 
                                            data_siswa.kode_kelas = '".$kelas[2]."' and
                                            data_siswa.aktif = 'Y' AND
                                            data_siswa.lulus = 'T'
                                        ORDER BY 
                                            data_siswa.nis
                                        ");
            $satu = 0;
            $dua  = 0;
            $totalTunggaanku = array();
            for($i=0;$i<count($listJenis);$i++){
                $totalTunggaanku[$i] = 0;
            }
            foreach($tables->result() as $table){
                $posisiY += 1;
                $posisiX = 0;
                $ws->setCellValue($data_huruf[$posisiX].$posisiY, $no);
                $posisiX += 1;
                $ws->setCellValue($data_huruf[$posisiX].$posisiY, $table->nis);
                $posisiX += 1;
                $ws->setCellValue($data_huruf[$posisiX].$posisiY, $table->nama);
                for($i=0;$i<count($listJenis);$i++){
                    $ygHarusDibayar = $this->db->query("SELECT
                                                            keuangan_siswa_bulanan.tahun_ajaran,
                                                            keuangan_jenis.tipe,
                                                            (keuangan_siswa_bulanan.b1+
                                                            keuangan_siswa_bulanan.b2+
                                                            keuangan_siswa_bulanan.b3+
                                                            keuangan_siswa_bulanan.b4+
                                                            keuangan_siswa_bulanan.b5+
                                                            keuangan_siswa_bulanan.b6+
                                                            keuangan_siswa_bulanan.b7+
                                                            keuangan_siswa_bulanan.b8+
                                                            keuangan_siswa_bulanan.b9+
                                                            keuangan_siswa_bulanan.b10+
                                                            keuangan_siswa_bulanan.b11+
                                                            keuangan_siswa_bulanan.b12) as jmlPembayaran
                                                        FROM
                                                            keuangan_siswa_bulanan
                                                            INNER JOIN keuangan_jenis ON keuangan_siswa_bulanan.id_jenis = keuangan_jenis.id_jenis
                                                            INNER JOIN data_siswa ON data_siswa.nis = keuangan_siswa_bulanan.nis
                                                            INNER JOIN keuangan_pos ON keuangan_jenis.pos = keuangan_pos.id_pos
                                                            INNER JOIN kelas ON kelas.id_kelas = keuangan_siswa_bulanan.id_kelas
                                                        WHERE
                                                            data_siswa.aktif = 'Y' AND
                                                            data_siswa.lulus = 'T' AND
                                                            data_siswa.nis = '".$table->nis."' AND
                                                            data_siswa.status_kelas = '".$kelas[0]."' and 
                                                            data_siswa.program_studi = '".$kelas[1]."' and 
                                                            data_siswa.kode_kelas = '".$kelas[2]."' and
                                                            keuangan_siswa_bulanan.id_jenis = '".$listJenis[$i]."'
                                                        ");
                    if($ygHarusDibayar->num_rows() > 0){
                        $ygHarusDibayarNya = $ygHarusDibayar->result()[0];
                        $ygHarusDibayarNyaOk = $ygHarusDibayarNya->jmlPembayaran;
                        if($ygHarusDibayarNya->tipe == 'Bulanan'){
                            $hugf = $this->db->query("SELECT
                                (pembayaran_siswa_bulanan.l1+
                                pembayaran_siswa_bulanan.l2+
                                pembayaran_siswa_bulanan.l3+
                                pembayaran_siswa_bulanan.l4+
                                pembayaran_siswa_bulanan.l5+
                                pembayaran_siswa_bulanan.l6+
                                pembayaran_siswa_bulanan.l7+
                                pembayaran_siswa_bulanan.l8+
                                pembayaran_siswa_bulanan.l9+
                                pembayaran_siswa_bulanan.l10+
                                pembayaran_siswa_bulanan.l11+
                                pembayaran_siswa_bulanan.l12) as jmlUtang
                            FROM
                                pembayaran_siswa_bulanan
                            WHERE
                                pembayaran_siswa_bulanan.tahun_ajaran = '".$ygHarusDibayarNya->tahun_ajaran."' AND
                                pembayaran_siswa_bulanan.nis = '".$table->nis."' AND
                                pembayaran_siswa_bulanan.id_jenis = '".$listJenis[$i]."'
                            ");
                            if($hugf->num_rows() > 0){
                                //echo $ygHarusDibayarNyaOk;
                                $ygHarusDibayarNyaOk -= $hugf->result()[0]->jmlUtang;
                            }
                        }
                        if($ygHarusDibayarNya->tipe == 'Bebas'){
                            $hugf = $this->db->query("SELECT
                                sum(cicilan) as jmlUtang
                            FROM
                                pembayaran_siswa_bebas
                            WHERE
                                pembayaran_siswa_bebas.tahun_ajaran = '".$ygHarusDibayarNya->tahun_ajaran."' AND
                                pembayaran_siswa_bebas.nis = '".$table->nis."' AND
                                pembayaran_siswa_bebas.id_jenis = '".$listJenis[$i]."'
                            ");
                            if($hugf->num_rows() > 0){
                                //echo $ygHarusDibayarNyaOk;
                                $ygHarusDibayarNyaOk -= $hugf->result()[0]->jmlUtang;
                            }
                        }
                        $posisiX += 1;
                        $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($ygHarusDibayarNyaOk, 2, ',', '.'));
                        $totalTunggaanku[$i] += $ygHarusDibayarNyaOk;
                    }else{
                        $posisiX += 1;
                        $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format(0, 2, ',', '.'));
                    }
                }
                $no += 1;
            }
            $posisiY += 1;
            $posisiX = 2;

            for($i=0;$i<count($listJenis);$i++){
                $posisiX += 1;
                $ws->setCellValue($data_huruf[$posisiX].$posisiY, number_format($totalTunggaanku[$i], 2, ',', '.'));
            }
			$ws->getStyle('D2:'.$data_huruf[$posisiX].$posisiY)->applyFromArray(array(
                                                    'alignment' => array(
                                                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                                                        'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                                    ),
                                                ));
        endforeach;
      
        $writer = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $tahun = $this->db->query("select tahun_ajaran from tahun_ajaran where aktif = 'Y'")->result()[0]->tahun_ajaran;
        $tahun = substr($tahun, 0, 4).'-'.(substr($tahun, 0, 4)+1);
        $writer->save(APPPATH.'../rekap_keuangan/'.$tahun.'.xlsx');
        redirect('rekap_keuangan/'.$tahun.'.xlsx');
    }
    
    public function lastKas($bulan, $tahun){
        $kasku = 0;
        $bulan = $bulan - 1;
        $tahun = substr($tahun, 0, 4);

        // ambil dari pembayaran bebas
        $jmlPembayaranBebas = $this->db->query("select sum(cicilan) as jumlah from pembayaran_siswa_bebas where tanggal < last_day('".$tahun."-".$bulan."-1')")->result()[0]->jumlah;
        $kasku += $jmlPembayaranBebas;

        // ambil dari jurnal
        $jmlKeuanganJurnal = $this->db->query("select sum(debit - kredit) as jumlahKas from keuangan_jurnal where tanggal < last_day('".$tahun."-".$bulan."-1')")->result()[0]->jumlahKas;
        
        $kasku += $jmlKeuanganJurnal;
        // ambil dari bulanan
        $jmlPembayaranBulanan = 0;
        $cariPembayar = $this->db->query("select tahun_ajaran, nis, id_jenis, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12 from pembayaran_siswa_bulanan");
        foreach($cariPembayar->result() as $row1){
            if($row1->b1 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b1 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b1."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b1")->result()[0]->b1;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b2 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b2 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b2."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b2")->result()[0]->b2;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b3 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b3 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b3."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b3")->result()[0]->b3;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b4 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b4 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b4."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b4")->result()[0]->b4;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b5 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b5 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b5."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b5")->result()[0]->b5;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b6 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b6 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b6."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b6")->result()[0]->b6;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b7 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b7 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b7."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b7")->result()[0]->b7;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b8 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b8 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b8."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b8")->result()[0]->b8;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b9 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b9 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b9."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b9")->result()[0]->b9;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b10 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b10 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b10."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b10")->result()[0]->b10;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b11 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b11 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b11."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b11")->result()[0]->b11;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b12 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b12 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and '".$row1->b12."' < last_day('".$tahun."-".$bulan."-1') union select 0 as b12")->result()[0]->b12;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
        }
        $kasku += $jmlPembayaranBulanan;

        return $kasku;
    }

    public function nowKas($bulan, $tahun){
        $kasku = 0;
        $bulan = $bulan;
        $tahun = substr($tahun, 0, 4);

        // ambil dari pembayaran bebas
        $jmlPembayaranBebas = $this->db->query("select sum(cicilan) as jumlah from pembayaran_siswa_bebas where year(tanggal) = '".(substr($tahun, 0, 4))."' and month(tanggal) = '".$bulan."'")->result()[0]->jumlah;
        $kasku += $jmlPembayaranBebas;

        // ambil dari bulanan
        $jmlPembayaranBulanan = 0;
        $cariPembayar = $this->db->query("select tahun_ajaran, nis, id_jenis, b1, b2, b3, b4, b5, b6, b7, b8, b9, b10, b11, b12 from pembayaran_siswa_bulanan");
        foreach($cariPembayar->result() as $row1){
            if($row1->b1 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b1 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b1."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b1."') = '".$bulan."' union select 0 as b1")->result()[0]->b1;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b2 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b2 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b2."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b2."') = '".$bulan."' union select 0 as b2")->result()[0]->b2;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b3 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b3 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b3."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b3."') = '".$bulan."' union select 0 as b3")->result()[0]->b3;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b4 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b4 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b4."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b4."') = '".$bulan."' union select 0 as b4")->result()[0]->b4;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b5 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b5 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b5."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b5."') = '".$bulan."' union select 0 as b5")->result()[0]->b5;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b6 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b6 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b6."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b6."') = '".$bulan."' union select 0 as b6")->result()[0]->b6;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b7 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b7 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b7."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b7."') = '".$bulan."' union select 0 as b7")->result()[0]->b7;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b8 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b8 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b8."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b8."') = '".$bulan."' union select 0 as b8")->result()[0]->b8;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b9 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b9 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b9."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b9."') = '".$bulan."' union select 0 as b9")->result()[0]->b9;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b10 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b10 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b10."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b9."') = '".$bulan."' union select 0 as b10")->result()[0]->b10;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b11 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b11 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b11."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b11."') = '".$bulan."' union select 0 as b11")->result()[0]->b11;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
            if($row1->b12 != '0000-00-00'){
                $cariPembayarLagi = $this->db->query("select b12 from keuangan_siswa_bulanan where tahun_ajaran = '".$row1->tahun_ajaran."' and nis = '".$row1->nis."' and id_jenis = '".$row1->id_jenis."' and year('".$row1->b12."') = '".(substr($tahun, 0, 4))."' and month('".$row1->b12."') = '".$bulan."' union select 0 as b12")->result()[0]->b12;
                $jmlPembayaranBulanan += $cariPembayarLagi;
            }
        }
        $kasku += $jmlPembayaranBulanan;

        return $kasku;
    }
}

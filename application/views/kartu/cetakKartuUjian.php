
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Kartu Peserta <?= $singkatan_ujian ." - ". $nama_ujian ?></title>

		<style type="text/css">
			/*INIT*/
				@media print {
				  	@page { margin: 0; }
				  	body { margin: 1.6cm; }
				}
				html{
					font-family: sans-serif;
				}
			/*INIT*/

			.container{
				border: solid 1px black;
				padding: 15px 10px 15px 10px;
				width: 350px;
			}

			/*HEADER*/
				.header{
					display: flex;
				}
				.header-left{
					width: 20%;
				}
				.header-left > img{
					display: block;
					margin-left: auto;
					margin-right: auto;
				}
				.header-right{
					width: 80%;
					padding-top: 10px;
				}
				.header-right > h4{
					text-align: center;
					margin: 0px;
				}
			/*HEADER*/

			/*CONTENT*/
				.content-wrapper{
					margin-top: 15px;
					padding-left: 15px;
					font-size: 13px;
				}
				.content{
					display: flex;
				}
				.content-left{
					width: 20%;
				}
				.content-right{
					width: 80%;
				}
			/*CONTENT*/

			/*FOOTER*/
				.footer{
					display: flex;
					margin-top: 15px;
				}
				.footer-left{
					width: 20%;
				}
				.footer-left > img {
					display: block;
					margin-left: auto;
					margin-right: auto;
				}
				.footer-right{
					width: 80%;
					text-align: center;
					font-size: 11px;
					display: block;
				}
				.footer-right > img {
					display: block;
					margin-left: auto;
					margin-right: auto;
				}
			/*FOOTER*/
		</style>
	</head>
	<body>
		<div class="container">
			<div class="header">
				<div class="header-left">
					<img src="<?= base_url().$logo_sekolah; ?>" width="50" >
				</div>
				<div class="header-right">
					<h4 class="singkatan">KARTU PESERTA <?= $singkatan_ujian ?></h4>
					<h4 class="nama_ujian"><?= strtoupper($nama_ujian) ?></h4>
				</div>
			</div>

			<div class="content-wrapper">
				<div class="content">
					<span class="content-left">Nama</span>
					<span class="content-right"> : <?= $nama_siswa ?></span>
				</div>

				<div class="content">
					<span class="content-left">Jurusan</span>
					<span class="content-right"> : <?= $prodi ." (". $kelas .")"; ?></span>
				</div>

				<div class="content">
					<span class="content-left"><?= ($tipe_ujian == 'offline') ? 'Ruangan' : 'Sesi' ?></span>
					<span class="content-right"> : <?= $ruangan ?></span>
				</div>

				<?php if ($tipe_ujian == 'online') { ?>
					<div class="content">
						<span class="content-left">Username</span>
						<span class="content-right"> : <?= $username ?></span>
					</div>

					<div class="content">
						<span class="content-left">Password</span>
						<span class="content-right"> : <?= $password ?></span>
					</div>
				<?php } ?>
			</div>

			<div class="footer">
				<div class="footer-left">
					<img src="<?= base_url().$foto ?>" width="50" alt="foto siswa">
				</div>
				<div class="footer-right">
					<span><?= $nama_sekolah ?></span>
					<img src="<?= base_url().$stample ?>" width="50" alt="">
					<span><?= $nama_kepsek ?></span>
				</div>
			</div>
		</div>
	</body>
</html>

<script>
	window.print();
	document.margin='none';
</script>
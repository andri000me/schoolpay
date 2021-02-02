<!DOCTYPE html>
<html>
	<head>
		<title>List siswa</title>
	</head>
	<body>
		<style type="text/css">
			body{
				font-family: sans-serif;
			}
			table{
				margin: 20px auto;
				border-collapse: collapse;
			}
			table th,
			table td{
				border: 1px solid #3c3c3c;
				padding: 3px 8px;

			}
			a{
				background: blue;
				color: #fff;
				padding: 8px 10px;
				text-decoration: none;
				border-radius: 2px;
			}
		</style>

		<?php
			header("Content-type: application/vnd-ms-excel");
			header("Content-Disposition: attachment; filename=listSiswa.xls");
		?>

		<table border="1" cellpadding="5">
			<tr>
				<th>No</th>
				<th>NAMA</th>
				<th>KELAS</th>
				<th>USERNAME</th>
				<th>PASSWORD</th>
			</tr>
			<?php $no=1; foreach ($dataujian as $val) { ?>
				<tr>
					<td><?= $no ?></td>
					<td><?= $val->nama ?></td>
					<td><?= $val->kelas."-".$val->program_studi."-".$val->kode_kelas ?></td>
					<td><?= $val->username ?></td>
					<td><?= $val->password ?></td>
				</tr>
			<?php $no++; } ?>
		</table>
	</body>
</html>
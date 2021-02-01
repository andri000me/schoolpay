<?php
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=ListSiswaUjian.xls");
?>

<table border="1">
	<thead>
		<tr>
			<th>USERNAME</th>
			<th>PASSWORD</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($dataujian as $val) { ?>
			<tr>
				<td><?= $val->username ?></td>
				<td><?= $val->password ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
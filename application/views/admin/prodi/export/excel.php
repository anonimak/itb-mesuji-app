<?php
header("Content-type: application/vnd-ms-excel");

header("Content-Disposition:attachment; filename=" . $title . ".xls");

header("Pragma: no-cache");

header("Expires:0");
?>



<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Jurusan</th>
            <th>ID Prodi</th>
            <th>Prodi</th>
            <th>Jenjang</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;
        foreach ($allData as $prodi) : ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $prodi->major_name; ?></td>
                <td><?= $prodi->id; ?></td>
                <td><?= $prodi->name; ?></td>
                <td><?= $prodi->degree; ?></td>
                <td><?= $prodi->email; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
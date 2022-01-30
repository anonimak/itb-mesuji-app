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
            <th>Prodi</th>
            <th>ID Matakuliah</th>
            <th>Matakuliah</th>
            <th>Kode</th>
            <th>SKS</th>
            <th>Semester</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;
        foreach ($allData as $item) : ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $item->prodi_name; ?></td>
                <td><?= $item->id; ?></td>
                <td><?= $item->name; ?></td>
                <td><?= $item->code; ?></td>
                <td><?= $item->sks; ?></td>
                <td><?= $item->semester; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
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
            <th>NPM</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Jurusan</th>
            <th>Prodi</th>
            <th>Alamat</th>
            <th>Tanggal Lahir</th>
            <th>Nomor Hp</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;
        foreach ($allData as $student) : ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $student->npm; ?></td>
                <td><?= $student->fullname; ?></td>
                <td><?= $student->email; ?></td>
                <td><?= $student->major_name; ?></td>
                <td><?= $student->prodi_name; ?></td>
                <td><?= $student->address; ?></td>
                <td><?= $student->birth_date; ?></td>
                <td><?= $student->no_hp; ?></td>
                <td>
                    <?php if ($student->status === 'active') : ?>
                        Aktif
                    <?php elseif ($student->status === 'graduated') : ?>
                        Lulus
                    <?php else : ?>
                        Tidak Aktif
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
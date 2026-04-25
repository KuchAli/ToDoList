<?php
include '../config/database.php';
include '../includes/function.php';

if($_SERVER['REQUEST_METHOD']=== 'POST'){

    $nama = trim($_POST['nama_kegiatan']);
    $jenis = trim($_POST['jenis_kegiatan']);
    $tanggal =trim($_POST['tanggal_kegiatan']);
    $status =trim($_POST['status']);

    if ($nama === '') {
        header("Location: ../index.php?error=Nama kosong");
        exit;
    }

    if(kegiatan($conn,$nama,$jenis,$tanggal,$status)){
         header("Location: ../index.php?success=Berhasil menambahkan kegiatan");
    } else {
        header("Location: ../index.php?error=Gagal menambahkan kegiatan");

    }


}

?>
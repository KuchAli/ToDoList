<?php
include '../config/database.php';
include '../includes/function.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $id = $_POST['id_kegiatan'] ?? '';
    $status = $_POST['status'] ?? '';

    if($id == '' || $status == ''){
        header("Location: ../index.php?error=Data tidak valid");
        exit;
    }

    if(updateStatus($conn,$status,$id)){
         header("Location: ../index.php?success=status berubah");
    }else {
        header("Location: ../index.php?error=status tidak berubah");
    }

}

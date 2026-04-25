<?php

// ini saya akan buat fungsi backend sederhana untuk kegiatan
function kegiatan($conn, $nama,$jenis,$tanggal,$status){
    $nama = mysqli_real_escape_string($conn,trim($nama));

    $checkSql = "SELECT id_kegiatan FROM kegiatan WHERE nama_kegiatan = ?";
    $checkStmt = mysqli_prepare($conn ,$checkSql);
    mysqli_stmt_bind_param($checkStmt,"s",$nama);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);


    if(mysqli_stmt_num_rows($checkStmt) > 0){
        mysqli_stmt_close($checkStmt);
        return false;
    }
    mysqli_stmt_close($checkStmt);

    $sql="INSERT INTO kegiatan (nama_kegiatan,jenis_kegiatan,tanggal_kegiatan,status) VALUES(?,?,?,?) ";
    $stmt =mysqli_prepare($conn,$sql);
    mysqli_stmt_bind_param($stmt,"ssss",$nama,$jenis,$tanggal,$status);


    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;


}

function updateStatus($conn,$status,$id){
    $sql = "UPDATE kegiatan SET status=? WHERE id_kegiatan=?";
    $stmt = mysqli_prepare($conn,$sql);
    mysqli_stmt_bind_param($stmt, "si", $status,$id);

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}

function getKegiatan($conn, $tanggal = null, $limit = 3, $offset = 0){

    if(!empty($tanggal)){
        $sql = "SELECT * FROM kegiatan 
                WHERE tanggal_kegiatan = ? 
                LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $tanggal, $limit, $offset);
    } else {
        $sql = "SELECT * FROM kegiatan 
                LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
    }

    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function countKegiatan($conn, $tanggal = null){

    if(!empty($tanggal)){
        $sql = "SELECT COUNT(*) as total FROM kegiatan WHERE tanggal_kegiatan = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $tanggal);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan");
    }

    $row = mysqli_fetch_assoc($result);
    return $row['total'];
}



?>
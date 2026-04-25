<?php
include 'config/database.php';
include 'includes/function.php';
include 'includes/header.php';
?>

<div class="container">
    <div class="row justify-content-start">
        <div class="col-md-6 col-lg-5">
            <form action="proccess/tambah_kegiatan.php" method="POST">

                <div class="mb-3 mt-3">
                    <label class="fs-5 mb-2">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="fs-5 mb-2">Jenis Kegiatan</label>
                    <select name="jenis_kegiatan" class="form-select" required>
                        <option value="">-Pilih Kegiatan-</option>
                        <option value="olahraga">Olahraga</option>
                        <option value="belajar">Belajar</option>
                        <option value="hobi">Hobi</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="fs-5 mb-2">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal_kegiatan" class="form-control-plaintext" required>
                </div>

                <div class="mb-3">
                    <label class="fs-5 mb-2">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="proses">Proses</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button class="btn btn-dark w-25">Kirim</button>
                </div>

            </form>
        </div>
    </div>
</div>
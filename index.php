<?php
include 'config/database.php';
include 'includes/function.php';

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 3;
$offset = ($page - 1) * $limit;

if (isset($_GET['tanggal_kegiatan']) && $_GET['tanggal_kegiatan'] !== '') {
    $tanggal = $_GET['tanggal_kegiatan'];
} else {
    $tanggal = date('Y-m-d');
    }
    
            
$result = getKegiatan($conn, $tanggal, $limit, $offset);
$totalData = countKegiatan($conn, $tanggal);
$totalPages = ceil($totalData / $limit);

$prev = $page - 1;
$next = $page + 1;

// Convert SupabaseResult to array for template compatibility
$kegiatan_data = [];
if ($result instanceof SupabaseResult) {
    // Get all data from result
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $kegiatan_data[] = $row;
    }
} else {
    // Fallback for mysqli result (legacy support)
    while ($row = mysqli_fetch_array($result)) {
        $kegiatan_data[] = $row;
    }
}

include 'includes/header.php';
?>

<div class="container">

    <!-- FILTER -->
    <form method="GET" class="mb-3 filter-section" >
        <div class="filter-group">
            <label for="">  Tanggal </label>
            <input type="date" name="tanggal_kegiatan" value="<?= htmlspecialchars($tanggal) ?>">
            <button type="submit" class="btn btn-dark"><i class="fas fa-filter"></i> Filter</button>
        </div>
    </form>

    <!-- DATA -->
    <div class="row">
        <?php if(count($kegiatan_data) > 0): ?>

            <?php foreach($kegiatan_data as $data): ?>
                
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">

                            <h5><?= $data['nama_kegiatan']; ?></h5>
                            <small><?= $data['jenis_kegiatan']; ?></small>
                            <p><?= $data['tanggal_kegiatan']; ?></p>

                            <span class="badge <?= 
                                $data['status']=='selesai'?'bg-success':
                                ($data['status']=='proses'?'bg-warning':
                                ($data['status']=='tidak_selesai'?'bg-info':'bg-danger')) ?>">
                                <?= $data['status']; ?>
                            </span>

                            <?php if($data['status'] === 'proses'): ?>
                                <form action="proccess/update_status.php" method="POST" class="mt-3 d-flex gap-2">
                                    <input type="hidden" name="id_kegiatan" value="<?= $data['id_kegiatan']; ?>">

                                    <button name="status" value="selesai" class="btn btn-sm btn-success">Selesai</button>
                                    <button name="status" value="tidak_selesai" class="btn btn-sm btn-danger">Tidak Selesai</button>
                                </form>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        <?php else: ?>
            <p class="text-center text-muted">Tidak ada kegiatan</p>
        <?php endif; ?>
    </div>

    <!-- PAGINATION -->
    <div class="mt-3 d-flex gap-2">
        <nav>
            <ul class="pagination justify-content-center">

                <!-- PREVIOUS -->
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $prev ?>&tanggal_kegiatan=<?= $tanggal ?>">
                    Previous
                </a>
                </li>

                <!-- ANGKA -->
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&tanggal_kegiatan=<?= $tanggal ?>">
                    <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>

                <!-- NEXT -->
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $next ?>&tanggal_kegiatan=<?= $tanggal ?>">
                    Next
                </a>
                </li>

            </ul>
        </nav>

    </div>

</div>

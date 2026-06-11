<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins' , sans-serif;
        }

        :root {
        --primary: #4361ee;
        --primary-dark: #3a56d4;
        --secondary: #4cc9f0;
        --success: #4CAF50;
        --warning: #FF9800;
        --danger: #F44336;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --border-radius: 12px;
        --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
        }


        body{
            background-color: #f5f7fb;
        }

        /* Header Styles */
        .header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-top: 2rem;
            margin-bottom: 10px;
            border-bottom: 1px solid var(--light-gray);

        }

        .header-left h1{
            color: black;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap:10px;
        }
        .header-left p{
            color: grey;
            font-size: 0.9rem;
        }

        /* Navbar section */
        .nav-menu{
            display: flex;
            gap: 5px;
            background-color: white;
            padding: 5px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
        }

        .nav-item{
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 20px;
            display: flex;
            gap: 14px;
            align-items: center;
            font-weight: 500;
            color: black;
        }

        .nav-item:hover {
            background-color: black;
            color: white;
        }
        .nav-item.active {
            background-color: black;
            color: white;
        }

        /* Pesan Notifikasi */

        .message{
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .message-success{
            color: green;
            border-left: 4px solid green ;
            background-color: rgba(76, 175, 80, 0.15);
        }
        .message-error{
            background-color: rgba(244, 67, 54, 0.15);
            color: red;
            border-left: 4px solid red ;
        }

        .card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

         /* Filter Section */
        .filter-section {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }

        .filter-group {
            flex: 1;
            min-width: 200px;
        }

         .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .filter-group select, .filter-group input {
            width: 50%;
            padding: 10px 11px;
            border: 1px solid var(--light-gray);
            border-radius: 100px;
            font-size: 1rem;
            background-color: white;
        }

        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }

        .pagination .page-link {
            color: black;
            border-color: black;
        }

        .pagination .page-link:hover {
            background-color: black;
            color: white;
            border-color: black;
        }

        .pagination .page-item.active .page-link {
            background-color: black;
            border-color: black;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #999;
            border-color: #ccc;
        }


        /* RESPONSIVE MOBILE */
        @media (max-width: 768px) {

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .header-left h1 {
                font-size: 1.4rem;
            }

            .nav-menu {
                flex-direction: column;
                gap: 10px;
            }

            .nav-item {
                width: 100%;
                justify-content: center;
            }

            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group input,
            .filter-group select {
                width: 100%;
            }

        }



        </style>
</head>
<body>
   <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="header-left">
                <h1> <i class="fas fa-list"></i>Aplikasi Pencatatan Kegiatan Harian</h1>
                <p>Menyediakan Catatan Produktivitas Harian</p>
            </div>
        </header>

        <!-- Navigation Menu -->
        <nav class="nav-menu">
            <a href="index.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '';?>">Beranda</a>
            <a href="list.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'list.php' ? 'active' : '';?>">Kegiatan</a>
        </nav>

        <!-- Filter Section -->

        <!-- Notifikasi pesan -->
        <?php if(isset($_GET['success'])): ?>
            <div class="message message-success">
                <i class="bi bi-check-circle"></i>
                <?php
                $success_messeges = [
                    'tambah' => 'Kegiatan berhasil ditambahkan!',
                    'selesai' => 'Kegiatan telah selesai',
                    'tidak' => 'Kegiatan tidak selesai'
                ];
                echo $success_messeges[$_GET['success']] ?? 'Operasi Berhasil '; 
                ?>
            </div>
        <?php endif?>

        <?php if(isset($_GET['error'])): ?>
            <div class="message message-error">
                <i class="bi bi-exclamation-circle"></i>
                <?php
                $error_messeges = [
                    'tambah' => 'Kegiatan gagal ditambahkan!',
                    'update' => 'Gagal mengubah status'
                   
                ];
                echo $error_messeges[$_GET['error']] ?? 'Terjadi Kesalahan'; 
                ?>
            </div>
        <?php endif?>
   </div>
</body>
</html>
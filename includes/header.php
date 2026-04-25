<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins' , sans-serif;
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
            border-bottom: #f5f7fb;
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
            padding: 5px;
            margin-bottom: 20px;
        }

        .nav-item{
            text-decoration: none;
            padding: 12px 25px;
            display: flex;
            gap: 8px;
            align-items: center;
            font-weight: 500;
        }

        .nav-item:hover{
            background-color: grey;
            color: white;
        }
        .nav-item.active{
            background-color: grey;
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
        </style>
</head>
<body>
   <div class="container">
        <!-- Header Section -->
        <header class="header">
            <div class="header-left">
                <h1>Aplikasi To Do List</h1>
                <p>Halaman beranda</p>
            </div>
        </header>

        <!-- Navigation Menu -->
        <nav class="nav-menu">
            <a href="index.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '';?>">Beranda</a>
        </nav>

        <!-- Notifikasi pesan -->
        <?php if(isset($_GET['success'])): ?>
            <div class="message message-success">
                <i class="bi bi-check-circle"></i>
                <?php
                $success_messeges = [
                    1 => 'Kegiatan berhasil ditambahkan!',
                    2 => 'Kegiatan telah selesai',
                    3 => 'Kegiatan tidak selesai'
                ];
                echo $success_messeges[$_GET['success']] ?? 'Operasi Berhasil'; 
                ?>
            </div>
        <?php endif?>

        <?php if(isset($_GET['error'])): ?>
            <div class="message message-error">
                <i class="bi bi-exclamation-circle"></i>
                <?php
                $error_messeges = [
                    1 => 'Kegiatan gagal ditambahkan!',
                   
                ];
                echo $error_messeges[$_GET['error']] ?? 'Terjadi Kesalahan'; 
                ?>
            </div>
        <?php endif?>
   </div>
</body>
</html>
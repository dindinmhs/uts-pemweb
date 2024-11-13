<?php
session_start();
include '../backend/backend.php';
$database = new Database();
$db = $database->getConnection();

$service = new Service($db);

$services = $service->read();

$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFB Salon | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="aside.css" rel="stylesheet">
    <style>
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div class="toggle-nav" style="width: 30px; display: inline-flex; flex-direction: column; height: 30px; justify-content: space-around; cursor: pointer;">
                    <div style="height: 2px; background-color: white;"></div>
                    <div style="height: 2px; background-color: white;"></div>
                    <div style="height: 2px; background-color: white;"></div>
                </div>
                <a class="navbar-brand">Halo, <?php echo $_SESSION['nama']; ?></a>
            </div>
            <form class="d-flex" role="search" method="POST" action="../backend/auth.php">
                <button class="btn btn-danger" name="logout" type="submit">Logout</button>
            </form>
        </div>
    </nav>
    <div style="width: 200px;" class="aside hide">
        <div class="close" style="display: flex; justify-content: end;">
            <span style="cursor: pointer; font-size: larger;">x</span>
        </div>
        <h3 class="text-center">IFB Salon</h3>
        <div class="menu">
            <a style="text-decoration: none; color: white;" href="karyawan.php">Karyawan</a>
            <a style="text-decoration: none; color: white;" href="pelanggan.php">Pelanggan</a>
            <a style="text-decoration: none; color: white;" href="reservasi.php">Reservasi</a>
            <a style="text-decoration: none; color: white;" href="layanan.php">Layanan</a>
        </div>
    </div>
    <div class="container mt-5 content">
        <!-- Menampilkan pesan error jika ada -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        <?php if ($status) { ?>
            <div class="alert alert-success" role="alert">
                <?php echo $status; ?>
            </div>
        <?php } ?>

    <h4>Data Layanan</h4>

    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addService">Tambah Layanan</button>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga (RP)</th>
                <th>Durasi (Menit)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($row = $services->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td class="description-cell"><?php echo $row['deskripsi']; ?></td>
                    <td><?php echo $row['harga']; ?></td>
                    <td><?php echo $row['durasi']; ?></td>
                    <td>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateService<?php echo $row['id']; ?>">Edit</button>
                        <a href="../backend/backend.php?delete&type=service&id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>

                <!-- Update Modal -->
                <div class="modal fade" id="updateService<?php echo $row['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Layanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="../backend/backend.php" method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" name="nama" class="form-control" value="<?php echo $row['nama']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <input type="text" name="deskripsi" class="form-control" value="<?php echo $row['deskripsi']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga (RP)</label>
                                        <input type="number" min="0" name="harga" class="form-control" value="<?php echo $row['harga']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="durasi" class="form-label">Durasi (Menit)</label>
                                        <input type="number" min="1" name="durasi" class="form-control" value="<?php echo $row['durasi']; ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="update_service" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </tbody>
    </table>

    <!-- Create service Modal -->
    <div class="modal fade" id="addService" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="../backend/backend.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" name="deskripsi" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga (RP)</label>
                            <input type="number" min="0" name="harga" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi (Menit)</label>
                            <input type="number" min="1" name="durasi" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_service" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p>&copy; 2024 IFB Salon | All rights reserved</p>
            <p>Follow Us: 
                <a href="#" class="text-white">Facebook</a> |
                <a href="#" class="text-white">Instagram</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>
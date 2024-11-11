<?php
session_start();
include '../backend/backend.php';
$database = new Database();
$db = $database->getConnection();

$customer = new Customer($db);
$service = new Service($db);
$appointment = new Appointment($db);

$customers = $customer->read();
$services = $service->read();
$appointments = $appointment->read();

$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFB Salon | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Halo, <?php echo $_SESSION['nama']; ?></a>
            <form class="d-flex" role="search" method="POST" action="../backend/auth.php">
                <button class="btn btn-danger" name="logout" type="submit">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container mt-5">
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

        <h4>Data Customer</h4>

        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addCustomer">Tambah Customer</button>

        <!-- Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>Saldo (RP)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($row = $customers->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['alamat']; ?></td>
                        <td><?php echo $row['saldo']; ?></td>
                        <td>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateCustomer<?php echo $row['id']; ?>">Edit</button>
                            <a href="../backend/backend.php?delete&type=customer&id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>

                    <!-- Update Modal -->
                    <div class="modal fade" id="updateCustomer<?php echo $row['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Customer</h5>
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
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="alamat" class="form-label">Alamat</label>
                                            <input type="text" name="alamat" class="form-control" value="<?php echo $row['alamat']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="saldo" class="form-label">Saldo (RP)</label>
                                            <input type="number" min="0" name="saldo" class="form-control" value="<?php echo $row['saldo']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update_customer" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </tbody>
        </table>

        <!-- Create Customer Modal -->
        <div class="modal fade" id="addCustomer" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="../backend/backend.php" method="POST">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" name="alamat" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="saldo" class="form-label">Saldo (RP)</label>
                                <input type="number" min="0" name="saldo" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add_customer" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <h4>Data Reservasi</h4>

    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addAppointment">Tambah Reservasi</button>

    <!-- Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Pekerja</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while ($row = $appointments->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['appointment_id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['employee_name']; ?></td>
                    <td><?php echo $row['service_name']; ?></td>
                    <td><?php echo $row['tanggal']; ?></td>
                    <td><?php echo $row['jam']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                    <?php if ($row['status'] == 'pending') { ?>
                        <form class="d-inline" action="../backend/backend.php" method="POST">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
                            <button type="submit" name="terima" class="btn btn-success">Terima</button>
                        </form>

                        <form class="d-inline" action="../backend/backend.php" method="POST">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
                            <button type="submit" name="batalkan" class="btn btn-danger">Batalkan</button>
                        </form>
                    <?php } elseif ($row['status'] == 'diterima') { ?>
                        <button class="btn btn-success" disabled>diterima</button>
                    <?php } elseif ($row['status'] == 'dibatalkan') { ?>
                        <button class="btn btn-danger" disabled>dibatalkan</button>
                    <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Create reservation Modal -->
    <?php 
    $customers_query = "SELECT id, nama FROM customers";
    $employees_query = "SELECT id, nama FROM employees";
    $services_query = "SELECT id, nama FROM services";

    $customers_result = $db->query($customers_query);
    $employees_result = $db->query($employees_query);
    $services_result = $db->query($services_query);

    $current_date = date('Y-m-d'); 
    $tomorrow_date = date('Y-m-d', strtotime('+1 day')); 
    ?>

    <div class="modal fade" id="addAppointment" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Reservasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="../backend/backend.php" method="POST">
                    <div class="modal-body">
                        <!-- Customer ID (Dropdown) -->
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Pilih Customer</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Customer</option>
                                <?php while ($customer = $customers_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $customer['id']; ?>"><?php echo $customer['nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Employee ID (Dropdown) -->
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Pilih Employee</label>
                            <select name="employee_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Employee</option>
                                <?php while ($employee = $employees_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $employee['id']; ?>"><?php echo $employee['nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Service ID (Dropdown) -->
                        <div class="mb-3">
                            <label for="service_id" class="form-label">Pilih Layanan</label>
                            <select name="service_id" class="form-select" required>
                                <option value="" disabled selected>Pilih Layanan</option>
                                <?php while ($service = $services_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $service['id']; ?>"><?php echo $service['nama']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Tanggal (Input Date) -->
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required min="<?php echo $tomorrow_date; ?>">
                        </div>

                        <!-- Jam (Input Time) -->
                        <div class="mb-3">
                            <label for="jam" class="form-label">Jam</label>
                            <input type="time" name="jam" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_reservation" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
</body>

</html>
<?php
session_start();
include '../backend/backend.php';
$database = new Database();
$db = $database->getConnection();

$appointment = new Appointment($db);

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
    <link href="aside.css" rel="stylesheet">
    <style>
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }
    </style>
</head>

<body">
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
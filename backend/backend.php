<?php
class Database
{
    private $host = 'localhost';
    private $db_name = 'ifb_salon';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
}

class Employee
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM employees";
        return $this->conn->query($query);
    }

}

class Customer
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function create($nama, $email, $alamat, $saldo)
    {
        $sql = "SELECT COUNT(*) FROM customers WHERE email = '$email'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count); 
        $stmt->fetch();

        if ($count > 0) {
            header("Location: ifb-salon/frontend/dashboard.php?error=Email sudah terdaftar!");
            exit();
        }

        $query = "INSERT INTO customers (nama, email, alamat, saldo) VALUES ('$nama', '$email', '$alamat', '$saldo')";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil menambahkan data customer';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }

    public function read()
    {
        $query = "SELECT * FROM customers";
        return $this->conn->query($query);
    }

    public function update($id, $nama, $email, $alamat, $saldo)
    {
        $query = "UPDATE customers SET nama = '$nama', email = '$email', alamat = '$alamat', saldo = '$saldo' WHERE id = '$id'";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil update data customer';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }

    public function delete($id)
    {
        $query = "DELETE FROM customers WHERE id = '$id'";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil hapus data customer';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }
}

class Service
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function create($nama, $deskripsi, $harga, $durasi)
    {
        $query = "INSERT INTO services (nama, deskripsi, harga, durasi) VALUES ('$nama', '$deskripsi', '$harga', '$durasi')";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil menambahkan data layanan';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }

    public function read()
    {
        $query = "SELECT * FROM services";
        return $this->conn->query($query);
    }

    public function update($id, $nama, $deskripsi, $harga, $durasi)
    {
        $query = "UPDATE services SET nama = '$nama', deskripsi = '$deskripsi', harga = '$harga', durasi = '$durasi' WHERE id = '$id'";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil update data layanan';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }

    public function delete($id)
    {
        $query = "DELETE FROM services WHERE id = '$id'";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil hapus data layanan';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }
}

class Appointment
{
    private $conn;
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function create($customer_id, $employe_id, $service_id, $tanggal, $jam)
    {
        $query = "INSERT INTO appointments (customer_id, employe_id, service_id, tanggal, jam) VALUES ('$customer_id', '$employe_id', '$service_id', '$tanggal', '$jam')";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil reservasi';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }

    public function read()
    {
        $query = "
            SELECT
                appointments.id AS appointment_id,
                customers.nama AS customer_name,
                employees.nama AS employee_name,
                services.nama AS service_name,
                appointments.tanggal,
                appointments.jam,
                appointments.status
            FROM appointments
            JOIN customers ON appointments.customer_id = customers.id
            JOIN employees ON appointments.employe_id = employees.id
            JOIN services ON appointments.service_id = services.id;
            ";
        return $this->conn->query($query);
    }

    public function accept($id)
    {
        // Ambil data appointment berdasarkan id
        $query = "SELECT * FROM appointments WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);  // Mengikat ID appointment sebagai integer
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $appointment = $result->fetch_assoc();
            
            // Ambil data customer_id dan service_id dari appointment
            $customer_id = $appointment['customer_id'];
            $service_id = $appointment['service_id'];

            // Ambil harga dari tabel services berdasarkan service_id
            $query = "SELECT harga FROM services WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $service_id);  // Mengikat service_id sebagai integer
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $service = $result->fetch_assoc();
                $harga = $service['harga'];

                // Cek saldo customer
                $query = "SELECT saldo FROM customers WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("i", $customer_id);  // Mengikat customer_id sebagai integer
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $customer = $result->fetch_assoc();
                    $saldo = $customer['saldo'];

                    // Jika saldo cukup
                    if ($saldo >= $harga) {
                        // Kurangi saldo customer
                        $new_saldo = $saldo - $harga;
                        $query = "UPDATE customers SET saldo = ? WHERE id = ?";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param("di", $new_saldo, $customer_id);  // Mengikat saldo baru dan customer_id
                        $stmt->execute();

                        // Update status appointment menjadi 'accepted'
                        $query = "UPDATE appointments SET status = 'diterima' WHERE id = ?";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param("i", $id);  // Mengikat id appointment
                        if ($stmt->execute()) {
                            $status = 'Berhasil menerima janji temu dan saldo customer telah diperbarui';
                        } else {
                            $status = 'Gagal memperbarui status appointment';
                        }
                    } else {
                        // Jika saldo tidak cukup
                        $status = 'Saldo customer tidak cukup untuk menerima janji temu';
                    }
                } else {
                    $status = 'Customer tidak ditemukan';
                }
            } else {
                $status = 'Layanan tidak ditemukan';
            }
        } else {
            $status = 'Appointment tidak ditemukan';
        }

        return $status;
    }


    public function cancel($id)
    {
        $query = "UPDATE appointments SET status = 'dibatalkan' WHERE id = '$id'";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            $status = 'Berhasil batal pesanan';
        } else {
            $status = $stmt->mysqli_error();
        }
        return $status;
    }
}

$database = new Database();
$db = $database->getConnection();
$customer = new Customer($db);
$services = new Service($db);
$appointment = new Appointment($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_customer'])) {
        $create = $customer->create($_POST['nama'], $_POST['email'], $_POST['alamat'], $_POST['saldo']);
        header('Location: ifb-salon/frontend/dashboard.php?status=' . $create . '');
    } elseif (isset($_POST['update_customer'])) {
        $update = $customer->update($_POST['id'], $_POST['nama'], $_POST['email'], $_POST['alamat'], $_POST['saldo']);
        header('Location: ifb-salon/frontend/dashboard.php?status=' . $update . '');
    } elseif (isset($_POST['add_service'])) {
        $create = $services->create($_POST['nama'], $_POST['deskripsi'], $_POST['harga'], $_POST['durasi']);
        header('Location: ifb-salon/frontend/dashboard.php?status=' . $create . '');
    } elseif (isset($_POST['update_service'])) {
        $update = $services->update($_POST['id'], $_POST['nama'], $_POST['deskripsi'], $_POST['harga'], $_POST['durasi']);
        header('Location: ifb-salon/frontend/dashboard.php?status=' . $update . '');
    } elseif (isset($_POST['add_reservation'])) {
        $create = $appointment->create($_POST['customer_id'], $_POST['employee_id'], $_POST['service_id'], $_POST['tanggal'], $_POST['jam']);
        header('Location: ifb-salon/frontend/dashboard.php?status=' . $create . '');
    } elseif (isset($_POST['batalkan'])) {
        $cancel = $appointment->cancel($_POST['appointment_id']);
        header('Location: ifb-salon/frontend/dashboard.php?status=' . $cancel . '');
    } elseif (isset($_POST['terima'])) {
        $accept = $appointment->accept($_POST['appointment_id']);
        header('Location: ifb-salon/frontend/dashboard.php?status=' . $accept . '');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {
    $type = $_GET['type'];
    if ($type == 'customer') {
        $delete = $customer->delete($_GET['id']);
        header('Location: ifb-salon/frontend/dashboard.php?status=' . $delete . '');
    } elseif ($type == 'service') {
        $delete = $services->delete($_GET['id']);
        header('Location: ifb-salon/frontend/dashboard.php?status=' . $delete . '');
    } 
}

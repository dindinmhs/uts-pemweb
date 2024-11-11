<?php

class Auth
{
    private $host = 'localhost';
    private $db_name = 'ifb_salon';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    public function register($nama, $email, $password)
    {
        $sql = "SELECT COUNT(*) FROM employees WHERE email = '$email'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count); 
        $stmt->fetch();

        if ($count > 0) {
            header("Location: /ifb-salon/frontend/register.php?error=Email sudah terdaftar!");
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO employees (nama, email, password) VALUES ('$nama', '$email', '$hashed_password')";
        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute()) {
            header("Location: /frontend/login.php");
            exit();
        } else {
            return false;
        }
    }

    public function login($email, $password)
    {
        session_start();

        $sql = "SELECT * FROM employees WHERE email = '$email'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['nama'] = $user['nama'];

                header("Location: /ifb-salon/frontend/dashboard.php");
                exit();
            } else {
                header("Location: /ifb-salon/frontend/login.php?error=Email atau Password salah!");
                exit();
            }
        } else {
            header("Location: /ifb-salon/frontend/login.php?error=Email atau Password salah!");
            exit();
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location : ifb-salon/frontend/login.php");
        exit();
    }
}

$auth = new Auth;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $auth->register($nama, $email, $password);
    } else if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $auth->login($email, $password);
    } else if (isset($_POST['logout'])) {
        $auth->logout();
    }
}
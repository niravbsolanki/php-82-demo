<?php
require_once '../config/connection.php';
require_once '../config/helpers.php';

class LoginController{

    private mysqli $conn;

    public function __construct()
    {
        // 👇 Get connection directly
        $this->conn = Connection::getInstance()->getConnection();
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_POST)) {

            // Sanitize inputs
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $_SESSION["error"] = [];

            // Validation
            if (empty($email)) {
                $_SESSION["error"]["email"] = "<span style='color:red;'>Please enter email</span>";
            }

            if (empty($password)) {
                $_SESSION["error"]["password"] = "<span style='color:red;'>Please enter password</span>";
            }

            if (!empty($_SESSION["error"])) {
                header("Location: /admin/login");
                exit;
            }

            // Escape inputs & hash password
            $emailEscaped = mysqli_real_escape_string($this->conn, $email);
            $pwdEscaped = mysqli_real_escape_string($this->conn, md5($password));

            // Query
            $sql = "SELECT * FROM admins WHERE email = '$emailEscaped' AND password = '$pwdEscaped'";
            $qry = mysqli_query($this->conn, $sql);

            if ($qry && mysqli_num_rows($qry) > 0) {
                $user = mysqli_fetch_assoc($qry);

                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_username'] = $user['username'];

                header("Location: /admin/dashboard");
            } else {
                header("Location: /admin/login?msg=Invalid credentials");
            }

        } else {
            header("Location: /admin/login");
            exit;
        }
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header("Location: /admin/login?msg=Logged out successfully");
    }

}

?>
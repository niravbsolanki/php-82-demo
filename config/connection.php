<?php
declare(strict_types=1);

class Connection
{
    private static ?Connection $instance = null;
    private mysqli $conn;

    private function __construct()
    {
        $host = "localhost";
        $user = "root";
        $pwd  = "";
        $db   = "core_demo";

        $this->conn = new mysqli($host, $user, $pwd, $db);

        if ($this->conn->connect_error) {
            die("Database Connection failed: " . $this->conn->connect_error);
        }

        // echo "Connected";
    }

    // Singleton entry point
    public static function getInstance(): Connection
    {
        if (self::$instance === null) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}

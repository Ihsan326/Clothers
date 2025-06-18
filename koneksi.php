<?php
class Database {
    private $host     = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'clothers';
    private $conn     = null;

    private function connect() {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            if ($this->conn->connect_error) {
                throw new Exception("Koneksi gagal: " . $this->conn->connect_error);
            }
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            throw new Exception("Kesalahan koneksi: " . $e->getMessage());
        }
    }

    public function getConnection() {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
            if ($this->conn->connect_error) {
                throw new Exception("Koneksi gagal: " . $this->conn->connect_error);
            }
            return $this->conn;
        } catch (Exception $e) {
            error_log($e->getMessage());
            die("Koneksi database bermasalah.");
        }
    }

    public function closeConnection() {
        if ($this->conn !== null) {
            $this->conn->close();
            $this->conn = null;
        }
    }
}
?>

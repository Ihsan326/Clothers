<?php

class UserService {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn; // langsung simpan koneksi mysqli
    }

    public function register($uid, $namaDepan, $namaBelakang, $usrname, $email, $password) {
    $stmt = $this->conn->prepare("
        INSERT INTO users (uid, nama_depan, nama_belakang, usrname, email, password)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssss", $uid, $namaDepan, $namaBelakang, $usrname, $email, $password);
    return $stmt->execute();
    }

    public function login($usrname, $password) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE usrname = ?");
    $stmt->bind_param("s", $usrname);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
    }


}

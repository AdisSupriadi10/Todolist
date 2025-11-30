<?php
// core/Database.php

class Database
{
    private $host = 'localhost';
    private $db_name = 'todolist_db';
    private $username = 'root'; // Ganti dengan username MySQL Anda
    private $password = '';     // Ganti dengan password MySQL Anda
    public $conn;

    /**
     * Membuat objek koneksi PDO ke database.
     * @return PDO|null Objek koneksi PDO atau null jika gagal.
     */
    public function getConnection()
    {
        $this->conn = null;
        try {
            // Perbaikan syntax PDO connection string yang salah di dokumen
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mengaktifkan exception
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
            return null;
        }
        return $this->conn;
    }
}

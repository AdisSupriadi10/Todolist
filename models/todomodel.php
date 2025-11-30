<?php
// models/TodoModel.php

require_once 'core/db.php';

class TodoModel
{
    private $conn;
    private $table_name = "todos";

    /**
     * Konstruktor: Membuat objek koneksi PDO ke database.
     */
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Mendapatkan semua todo yang ada di database.
     * @return array Array yang berisi semua todo.
     */
    public function getAllTodos()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Membuat todo baru.
     * @param string $task Teks dari tugas.
     * @return boolean True jika berhasil, False jika gagal.
     */
    public function createTodo($task)
    {
        // Perbaikan: Binding parameter task
        $query = "INSERT INTO " . $this->table_name . " (task) VALUES (:task)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":task", $task);
        return $stmt->execute();
    }

    /**
     * Memperbarui status todo (is_completed).
     * @param int $id ID dari todo.
     * @param int $is_completed Status (0 atau 1).
     * @return boolean True jika berhasil, False jika gagal.
     */
    public function updateTodoStatus($id, $is_completed)
    {
        // Perbaikan: Penambahan klausa WHERE dan binding parameter
        $query = "UPDATE " . $this->table_name . " SET is_completed = :is_completed WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":is_completed", $is_completed);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    /**
     * Menghapus todo.
     * @param int $id ID dari todo yang akan dihapus.
     * @return boolean True jika berhasil, False jika gagal.
     */
    public function deleteTodo($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}

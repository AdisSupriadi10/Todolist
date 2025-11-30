<?php
// controllers/TodoController.php

require_once 'models/todomodel.php';

class TodoController
{
    private $model;

    /**
     * Konstruktor: Membuat instance dari TodoModel.
     */
    public function __construct()
    {
        $this->model = new TodoModel();
    }

    /**
     * Mengembalikan semua todo.
     * @return array
     */
    public function index()
    {
        return $this->model->getAllTodos();
    }

    /**
     * Membuat todo baru.
     * @param string $task Teks tugas.
     * @return boolean
     */
    public function add($task)
    {
        // Tambahkan validasi sederhana sebelum menyimpan
        if (!empty(trim($task))) {
            return $this->model->createTodo($task);
        }
        return false;
    }

    /**
     * Menandai todo sebagai selesai.
     * @param int $id ID todo.
     * @return boolean
     */
    public function markAsCompleted($id)
    {
        return $this->model->updateTodoStatus($id, 1); // 1 = Selesai
    }

    /**
     * Menghapus todo.
     * @param int $id ID todo.
     * @return boolean
     */
    public function delete($id)
    {
        return $this->model->deleteTodo($id);
    }
}

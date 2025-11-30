<?php
// index.php

require_once 'controllers/todocontroller.php';

$controller = new TodoController();
$action = $_GET['action'] ?? null;

// --- 1. Tangani Aksi yang Mengubah Data (POST/GET) ---

switch ($action) {
    case 'add':
        // Pastikan ini adalah permintaan POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $_POST['task'] ?? '';
            $result = $controller->add($task);

            // Selalu redirect setelah POST, terlepas dari hasilnya, 
            // untuk mencegah form resubmission (masalah F5)
            $status = $result ? 'success' : 'fail';
            header("Location: index.php?status={$status}&last_action=add");
            exit();
        }
        break;

    case 'complete':
    case 'delete':
        // Ini adalah permintaan GET yang seharusnya hanya dieksekusi sekali.
        $id = $_GET['id'] ?? '';
        $result = false;

        if ($action === 'complete') {
            $result = $controller->markAsCompleted($id);
        } elseif ($action === 'delete') {
            // Perbaikan utama: Panggil delete dan simpan hasilnya
            $result = $controller->delete($id);
        }

        // Jika aksi selesai, redirect ke halaman bersih
        $status = $result ? 'success' : 'fail';
        header("Location: index.php?status={$status}&last_action={$action}");
        exit();
        break;

    // Jika tidak ada aksi yang dikenali, lanjut ke bawah
    default:
        // Tidak ada aksi, lanjut ke proses tampilan
        break;
}

// --- 2. Ambil Data dan Render Tampilan ---

// Dapatkan daftar tugas terbaru
$todos = $controller->index();

// Merender tampilan
require 'views/listodo.php';

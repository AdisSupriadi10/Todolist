<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List Sederhana</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>

<body>
    <div class="container">
        <h1>🚀 TodoList ADIS SUPRIADI</h1>

        <form id="add-todo-form" method="POST" action="?action=add">
            <input type="text" name="task" placeholder="Tambah tugas baru..." required>
            <button type="submit">Tambah</button>
        </form>

        <ul id="todo-list">
            <?php
            if (empty($todos)):
                // Tampilkan pesan jika tidak ada todo
            ?>
                <li class="empty-list">🎉 Semua tugas sudah selesai! Tambahkan yang baru.</li>
                <?php
            else:
                foreach ($todos as $todo):
                ?>
                    <li class="todo-item <?= $todo['is_completed'] ? 'completed' : '' ?>" data-id="<?= $todo['id'] ?>">

                        <span class="task-text"><?= htmlspecialchars($todo['task']) ?></span>

                        <div class="actions">
                            <?php
                            // Link untuk menandai sebagai selesai (Hanya jika belum selesai)
                            if (!$todo['is_completed']):
                            ?>
                                <a href="?action=complete&id=<?= $todo['id'] ?>" class="complete-btn" title="Selesai">✓</a>
                            <?php
                            endif;
                            ?>

                            <a href="?action=delete&id=<?= $todo['id'] ?>" class="delete-btn" title="Hapus">✕</a>
                        </div>
                    </li>
            <?php
                endforeach;
            endif;
            ?>
        </ul>
    </div>
</body>

</html>
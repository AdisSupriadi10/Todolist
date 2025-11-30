// assets/js/script.js (Revisi)

document.addEventListener("DOMContentLoaded", () => {
  // ... (kode delete confirmation Anda)

  const urlParams = new URLSearchParams(window.location.search);

  if (urlParams.get("status") === "success") {
    // Ambil last_action yang ditambahkan di index.php
    const action = urlParams.get("last_action");
    let message = "";

    if (action === "add") {
      message = "✅ Tugas berhasil ditambahkan!";
    } else if (action === "complete") {
      message = "👍 Tugas berhasil diselesaikan!";
    } else if (action === "delete") {
      message = "🗑️ Tugas berhasil dihapus!";
    }

    if (message) {
      alert(message);
    }

    // Hapus parameter status dan action dari URL tanpa me-refresh halaman
    history.replaceState({}, document.title, window.location.pathname);
  }
});

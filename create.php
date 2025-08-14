<?php 
include 'db.php'; 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Tambah Tugas Baru</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Prioritas</label>
            <select name="priority" class="form-select">
                <option value="Rendah">Rendah</option>
                <option value="Sedang">Sedang</option>
                <option value="Tinggi">Tinggi</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>
        <button type="submit" name="submit" class="btn btn-success">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // Ambil ID user yang login
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];
    $created_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO tasks (id, title, description, priority, deadline, created_at)
              VALUES ('$user_id', '$title', '$description', '$priority', '$deadline', '$created_at')";

    mysqli_query($conn, $query);

    header("Location: index.php");
    exit;
}
?>

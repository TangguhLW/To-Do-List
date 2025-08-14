<?php
include 'db.php';
$id = $_GET['id'];

$task = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tasks WHERE id = $id"));

if (!$task) {
    die("Tugas tidak ditemukan.");
}

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    $update = "UPDATE tasks SET title='$title', description='$desc', priority='$priority', deadline='$deadline' WHERE id=$id";
    mysqli_query($conn, $update);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Edit Tugas</h2>

    <?php
    $today = date('Y-m-d');
    $isPastDeadline = strtotime($task['deadline']) < strtotime($today);
    if ($isPastDeadline) {
        echo "<div class='alert alert-danger'>Tugas ini sudah melewati deadline dan tidak bisa diedit.</div>";
    } else {
    ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="<?= $task['title'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control"><?= $task['description'] ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Prioritas</label>
            <select name="priority" class="form-select">
                <option value="Rendah" <?= $task['priority'] == 'Rendah' ? 'selected' : '' ?>>Rendah</option>
                <option value="Sedang" <?= $task['priority'] == 'Sedang' ? 'selected' : '' ?>>Sedang</option>
                <option value="Tinggi" <?= $task['priority'] == 'Tinggi' ? 'selected' : '' ?>>Tinggi</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="deadline" class="form-control" value="<?= $task['deadline'] ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>

    <?php } ?>
</div>
</body>
</html>

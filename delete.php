<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']);           // Amankan dari SQL Injection
$user_id = $_SESSION['user_id'];     // Ambil user yang login

// Hapus task hanya milik user yang login
mysqli_query($conn, "DELETE FROM tasks WHERE id = $id AND id = '$user_id'");

header("Location: index.php");
exit;
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

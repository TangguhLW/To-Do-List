<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Ambil ID user yang sedang login

// Handle update status kalau ada request AJAX
if (isset($_POST['ajax']) && $_POST['ajax'] == '1') {
    $taskId = intval($_POST['id']);
    $isDone = intval($_POST['is_done']);
    mysqli_query($conn, "UPDATE tasks SET is_done = $isDone WHERE id = $taskId AND user_id = '$user_id'");
    echo "OK";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .expired { color: red; font-weight: bold; }
        .countdown { color: green; font-weight: bold; }
        .done { text-decoration: line-through; color: gray; }
        * { font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;}
        .form-check-input {
            width: 20px;
            height: 20px;
            cursor: pointer;
            border: 2px solid #ccc;
            border-radius: 4px;
            transition: 0.2s;
        }
        .form-check-input:checked {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="#" style="font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;">📋 To-Do List</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container py-4">
    <a href="create.php" class="btn btn-primary mb-3">+ Tambah Tugas</a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Selesai</th>
                    <th>Kegiatan</th>
                    <th>Keterangan</th>
                    <th>Prioritas</th>
                    <th>Dibuat Pada</th>
                    <th>Deadline</th>
                    <th>Sisa Waktu</th>
                    <th>Status</th>
                    <th>Alat</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Filter task sesuai user yang login
            $query = "SELECT * FROM tasks WHERE id = '$user_id' ORDER BY deadline ASC";
            $result = mysqli_query($conn, $query);

            while ($task = mysqli_fetch_assoc($result)) {
                $id = $task['id'];
                $deadline = $task['deadline'];
                $createdAt = date('d-m-Y H:i', strtotime($task['created_at']));
                $deadlineDate = $deadline . " 23:59:59";
                $deadlineTime = strtotime($deadlineDate);
                $now = time();

                $isPastDeadline = $now > $deadlineTime;
                $status = $isPastDeadline 
                    ? "<span class='badge bg-danger'>Deadline! ❌</span>" 
                    : "<span class='badge bg-success'>Belum Deadline ✅</span>";

                $checked = $task['is_done'] ? "checked" : "";
                $doneClass = $task['is_done'] ? "done" : "";

                echo "<tr data-id='$id'>";
                echo "<td><input type='checkbox' class='form-check-input task-checkbox' data-id='{$task['id']}' $checked></td>";
                echo "<td class='title $doneClass'>{$task['title']}</td>";
                echo "<td class='desc $doneClass'>{$task['description']}</td>";
                echo "<td>{$task['priority']}</td>";
                echo "<td>$createdAt</td>";
                echo "<td>$deadline</td>";
                echo "<td><span id='countdown-$id' class='countdown'></span></td>";
                echo "<td>$status</td>";
                echo "<td>";
                if (!$isPastDeadline) {
                    echo "<a href='edit.php?id={$task['id']}' class='btn btn-warning btn-sm me-1'>Edit</a>";
                }
                echo "<a href='delete.php?id={$task['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>";
                echo "</td>";
                echo "</tr>";

                echo "<script>
                const countdown$id = setInterval(() => {
                    const deadline = new Date('$deadlineDate').getTime();
                    const now = new Date().getTime();
                    const distance = deadline - now;

                    const el = document.getElementById('countdown-$id');
                    if (distance <= 0) {
                        el.innerHTML = ' Sudah lewat';
                        el.className = 'expired';
                        clearInterval(countdown$id);
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    el.innerHTML = days + 'h ' + hours + 'j ' + minutes + 'm ' + seconds + 'd';
                }, 1000);
                </script>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- AJAX dan coret teks -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('change', '.task-checkbox', function() {
    let row = $(this).closest('tr');
    let taskId = $(this).data('id');
    let isDone = $(this).is(':checked') ? 1 : 0;

    // Kirim ke server
    $.post('', { ajax: 1, id: taskId, is_done: isDone }, function(res) {
        console.log(res);
    });

    // Coret / hilangkan coret di UI
    if (isDone) {
        row.find('.title, .desc').addClass('done');
    } else {
        row.find('.title, .desc').removeClass('done');
    }
});
</script>

</body>
</html>

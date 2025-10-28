<?php
// activity_delete.php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// CSRF 驗證
if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF token 驗證失敗');
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// 權限檢查，但非 M 角色不做刪除，改為顯示提示
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    echo "<script>alert('你的角色無法刪除此活動'); window.location.href='index.php';</script>";
    exit;
}

$sql = "DELETE FROM activity WHERE id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header("Location: index.php");
        exit;
    } else {
        $err = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        die("刪除失敗: " . htmlspecialchars($err));
    }
} else {
    mysqli_close($conn);
    die("資料庫錯誤: " . htmlspecialchars(mysqli_error($conn)));
}

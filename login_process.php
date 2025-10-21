<?php
session_start();
require_once "db.php"; // 連線資料庫 ($conn)

// 1️⃣ 檢查輸入欄位是否有值
if (empty($_POST['account']) || empty($_POST['password'])) {
    header("Location: login.php?err=2"); // 未輸入帳密
    exit;
}

// 2️⃣ 使用 mysqli_real_escape_string() 避免 SQL Injection
$account = mysqli_real_escape_string($conn, $_POST['account']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// 3️⃣ 撈取資料（帳號唯一 → 只需查一筆）
// ⚠️ 注意：這裡是明碼比對，符合目前資料庫內容
$sql = "SELECT * FROM user WHERE account = '$account'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("資料庫查詢錯誤：" . mysqli_error($conn));
}

// 4️⃣ 判斷帳號是否存在
if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

// 檢查密碼是否正確
if ($row['password'] === $password) {
    // ✅ 登入成功 → 建立 session
    $_SESSION['login'] = true;
    $_SESSION['uid'] = $row['id'];
    $_SESSION['account'] = $row['account'];
    $_SESSION['name'] = $row['name'];
    $role = $row['role']=="T" ? "teacher" : "student";
    $_SESSION['role'] = $role;


    header("Location: index.php");
    exit;
} else {
    // ❌ 密碼錯誤
    header("Location: login.php?err=1");
    exit;
}
} else {
    // ❌ 查無帳號
    header("Location: login.php?err=1");
    exit;
}
?>
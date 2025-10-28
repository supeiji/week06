<?php
session_start();
require_once 'db.php'; // 確保 $conn

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account = trim($_POST['account'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($account === '' || $password === '') {
        $error = '請輸入帳號與密碼';
    } else {
        $stmt = $conn->prepare('SELECT account, name, role, password FROM user WHERE account = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $account);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res && $res->num_rows === 1) {
                $row = $res->fetch_assoc();
                $stored_hash = $row['password'];
                $password_ok = false;

                if (password_verify($password, $stored_hash) || $password === $stored_hash) {
                    $password_ok = true;
                }

                if ($password_ok) {
                    $_SESSION['account'] = $row['account'];
                    $_SESSION['name'] = $row['name'];
                    // 統一角色為 T/M/S
                    switch($row['role']) {
                        case 'teacher': $_SESSION['role'] = 'T'; break;
                        case 'admin':   $_SESSION['role'] = 'M'; break;
                        case 'student': $_SESSION['role'] = 'S'; break;
                        default:        $_SESSION['role'] = 'S'; break;
                    }
                    $_SESSION['login'] = true;
                    header('Location: success.php');
                    exit;
                } else {
                    $error = '帳號或密碼錯誤';
                }
            } else {
                $error = '帳號或密碼錯誤';
            }
            $stmt->close();
        } else {
            $error = '資料庫錯誤';
        }
    }
}
?>
<!doctype html>
<html lang="zh-Hant">
<head>
<meta charset="utf-8">
<title>登入</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width:520px;margin-top:80px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-3">登入</h3>
            <?php if ($error) echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>'; ?>
            <form method="post" action="login_process.php">
                <div class="mb-3">
                    <label class="form-label">帳號</label>
                    <input type="text" name="account" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">密碼</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">登入</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

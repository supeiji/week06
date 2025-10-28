<?php
// login.php - 只負責顯示表單 (POST 送到 login_process.php)
session_start();

// 若已經登入，轉到 success.php
if (isset($_SESSION['account']) && $_SESSION['login'] === true) {
    header("Location: success.php");
    exit;
}

$err = $_GET['err'] ?? '';
$msg = '';
if ($err == 1) $msg = "帳號或密碼錯誤";
elseif ($err == 2) $msg = "請輸入帳號與密碼";
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>登入</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow">
        <div class="card-body">
          <h4 class="card-title mb-4">登入</h4>
          <form method="post" action="login_process.php">
            <div class="mb-3">
              <label for="account" class="form-label">帳號</label>
              <input type="text" class="form-control" id="account" name="account" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">密碼</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">登入</button>
          </form>

          <?php if ($msg): ?>
            <div class="alert alert-danger mt-3"><?= htmlspecialchars($msg) ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

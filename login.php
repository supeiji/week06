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
<?php
// $sql = "select * from user where account ='$account' and password ='$password'";
// $account = mysqli_real_escape_string($conn, $_POST[ 'account'])
// $password = mysqli_real_escape_string($conn, $_POST[ 'password'])
$users = [
  "root"  => ["password" => "password", "name" => "老師", "role" => "teacher"],
  "user1" => ["password" => "pw1", "name" => "小明",   "role" => "student"],
  "user2" => ["password" => "pw2", "name" => "小華",   "role" => "student"],
  "user3" => ["password" => "pw3", "name" => "小美",   "role" => "student"],
  "user4" => ["password" => "pw4", "name" => "小強",   "role" => "student"],
];
if ($_POST) {
  $account = $_POST["account"] ?? "";
  $password = $_POST["password"] ?? "";
  if (isset($users[$account]) && $users[$account]["password"] === $password) {
    session_start();
    $_SESSION["account"] = $account;
    $_SESSION["name"] = $users[$account]["name"]; // 存入名字

    header("Location: success.php");
    exit;
  } else {
    header("Location: login.php?msg=帳號或密碼錯誤");
    exit;
  }
} else {
  $msg = $_GET["msg"] ?? "";
?>
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
            <div class="alert alert-danger mt-3" role="alert">
              <?=htmlspecialchars($msg)?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
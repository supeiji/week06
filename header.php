<?php
// week02/header.php
$current = basename($_SERVER['PHP_SELF']);
function nav_active($file) {
    global $current;
    return $current === $file ? ' active' : '';
}
session_start();

?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>主頁</title>
  <!-- Font Awesome icons (free version)-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light px-4" id="mainNav">
    <a class="navbar-brand" href="#">活動報名系統</a>
    <div class="collapse navbar-collapse d-flex w-100" id="navbarResponsive" style="display: flex; justify-content: space-between; align-items: center;">
      <ul class="navbar-nav nav-underline flex-row" style="gap: 10px;">
        <li class="nav-item">
          <a class="nav-link<?php echo nav_active('index.php'); ?>" aria-current="page" href="index.php">首頁</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php echo nav_active('迎新.php'); ?>" href="迎新.php">迎新茶會</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php echo nav_active('一日營.php'); ?>" href="一日營.php">一日營</a>
        </li>
        <li class="nav-item">
          <a class="nav-link<?php echo nav_active('job.php'); ?>" href="job.php">求職</a>
        </li>
      </ul>
      <ul class="navbar-nav flex-row" style="gap: 10px; margin-left: auto;">
        <?php if (isset($_SESSION["account"])): ?>
          <li class="nav-item">
            <span class="navbar-text me-3"><?= htmlspecialchars($_SESSION["name"]) ?> 您好</span>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">登出</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="login_process.php">登入</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>
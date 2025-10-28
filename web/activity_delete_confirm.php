<?php
// activity_delete_confirm.php
require_once 'header.php';
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// 權限檢查
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'A') {
    header("Location: activity.php");
    exit;
}

// 取得 id（GET）
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: activity.php");
    exit;
}

$sql = "SELECT id, title, content, pdate FROM activity WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
} else {
    header("Location: activity.php");
    exit;
}

if (!$row) {
    header("Location: activity.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$csrf_token = $_SESSION['csrf_token'];
?>
<div class="container mt-4">
  <h3 class="mb-3">確認刪除活動</h3>

  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
      <p class="card-text"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
      <p class="text-muted">刊登日期：<?= htmlspecialchars($row['pdate']) ?></p>
    </div>
  </div>

  <div class="mb-3">
    <form method="post" action="activity_delete.php" style="display:inline;">
      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <button type="submit" class="btn btn-danger">確認刪除（永久）</button>
    </form>
    <a href="activity.php" class="btn btn-secondary">取消，回列表</a>
  </div>
</div>

<?php
mysqli_close($conn);
require_once 'footer.php';

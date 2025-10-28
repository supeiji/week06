<?php
require_once "header.php";
require_once "db.php";
session_start();

// 權限檢查：僅管理員 M
if (!isset($_SESSION['account']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    echo "<script>alert('你的角色無法新增此活動'); window.location.href='activity.php';</script>";
    exit;
}

$msg = "";
$title = "";
$content = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === '' || $content === '') {
        $msg = '<div class="alert alert-danger">請填寫標題與內容。</div>';
    } else {
        $sql = "INSERT INTO activity (title, content) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $title, $content);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: activity.php");
                exit;
            } else {
                $msg = '<div class="alert alert-danger">新增失敗：' . htmlspecialchars(mysqli_stmt_error($stmt)) . '</div>';
            }
            mysqli_stmt_close($stmt);
        } else {
            $msg = '<div class="alert alert-danger">資料庫錯誤：' . htmlspecialchars(mysqli_error($conn)) . '</div>';
        }
    }
}
?>
<div class="container mt-4">
  <h3>新增活動</h3>
  <?php if ($msg) echo $msg; ?>
  <form method="post" action="activity_insert.php">
    <div class="mb-3">
      <label class="form-label">標題</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">內容</label>
      <textarea name="content" class="form-control" rows="8" required><?= htmlspecialchars($content) ?></textarea>
    </div>
    <button class="btn btn-primary" type="submit">送出</button>
    <a href="activity.php" class="btn btn-secondary">取消</a>
  </form>
</div>
<?php
mysqli_close($conn);
require_once "footer.php";

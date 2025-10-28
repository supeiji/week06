<?php
require_once "header.php";
require_once "db.php"; // 確保 db.php 會建立 $conn (mysqli)

$msg = "";
$company = "";
$content = "";

// 若要，下面是建表的參考 SQL（只需執行一次）
/*
CREATE TABLE `job` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. 取得並簡單驗證輸入
    $company = trim($_POST['company'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($company === "" || $content === "") {
        $msg = '<div class="alert alert-danger">請完整填寫公司名稱與求才內容。</div>';
    } else {
        // 2. 使用 prepared statement 防止 SQL injection
        $sql = "INSERT INTO `job` (`company`, `content`) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $company, $content);
            $exec = mysqli_stmt_execute($stmt);
            if ($exec) {
                $msg = '<div class="alert alert-success">刊登成功！</div>';
                // 清空欄位（或保留看需求）
                $company = "";
                $content = "";
            } else {
                // 執行失敗，顯示錯誤（開發階段可顯示 mysqli_error）
                $msg = '<div class="alert alert-danger">刊登失敗：' . htmlspecialchars(mysqli_stmt_error($stmt)) . '</div>';
            }
            mysqli_stmt_close($stmt);
        } else {
            $msg = '<div class="alert alert-danger">資料庫準備錯誤：' . htmlspecialchars(mysqli_error($conn)) . '</div>';
        }
    }
}
?>
<div class="container">
  <h3 class="mt-4 mb-3">刊登職缺</h3>

  <?php if ($msg): ?>
    <?= $msg ?>
  <?php endif; ?>

  <form action="job_insert.php" method="post">
    <div class="mb-3 row">
      <label for="_company" class="col-sm-2 col-form-label">求才廠商</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="company" id="_company" placeholder="公司名稱" required
               value="<?= htmlspecialchars($company) ?>">
      </div>
    </div>
    <div class="mb-3">
      <label for="_content" class="form-label">求才內容</label>
      <textarea class="form-control" name="content" id="_content" rows="10" required><?= htmlspecialchars($content) ?></textarea>
    </div>
    <input class="btn btn-primary" type="submit" value="送出">
  </form>
</div>

<?php
// 結束前關閉連線（若後續還會用到 $conn 則可以不用）
mysqli_close($conn);
include('footer.php');
?>

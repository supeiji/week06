<?php 
include('header.php');
include('db.php');

// 啟動 session 並生成 CSRF token
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$msg = "";

// 處理新增活動表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_activity'])) {
    if (!empty($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if ($title !== '' && $content !== '') {
            $sql_insert = "INSERT INTO activity (title, content) VALUES (?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql_insert)) {
                mysqli_stmt_bind_param($stmt, "ss", $title, $content);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $msg = '<div class="alert alert-success">活動新增成功！</div>';
            } else {
                $msg = '<div class="alert alert-danger">資料庫錯誤：' . htmlspecialchars(mysqli_error($conn)) . '</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">請填寫標題與內容。</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">CSRF 驗證失敗</div>';
    }
}

// 嘗試查詢動態活動資料
$result = mysqli_query($conn, "SELECT * FROM activity ORDER BY id ASC");
if (!$result) {
    $activity_error = "資料庫查詢失敗: " . mysqli_error($conn);
    $result = []; // 避免 mysqli_fetch_assoc 崩潰，改用空陣列
} else {
    $activity_error = "";
}
?>

<div class="container mt-4">    
    <h3 class="mb-3">選一個報名吧</h3>

    <!-- 顯示訊息 -->
    <?php if ($msg) echo $msg; ?>
    <?php if ($activity_error) echo '<div class="alert alert-danger">' . htmlspecialchars($activity_error) . '</div>'; ?>

    <!-- 新增活動表單 -->
    <div class="mb-4">
        <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#newActivityForm" aria-expanded="false">新增活動</button>
        <div class="collapse mt-3" id="newActivityForm">
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" name="new_activity" value="1">
                <div class="mb-3">
                    <label class="form-label">標題</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">內容</label>
                    <textarea name="content" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">新增</button>
            </form>
        </div>
    </div>

    <!-- 活動卡片列表 -->
    <div class="container my-5">
        <div class="card-row">

            <!-- 原有的靜態活動卡片 + 刪除按鈕 -->
            <div class="card custom-card mb-3">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title text-secondary">迎新茶會</h3>
                    <p class="card-text">
                        迎新茶會是專為新生設計的交流活動，讓新同學能夠認識師長與學長姐，了解資管系的學習環境與資源。活動中有輕鬆的茶點、趣味破冰遊戲，以及學長姐經驗分享，幫助新生快速融入大學生活。
                    </p>
                    <div class="mt-auto text-end d-flex justify-content-end gap-2">
                        <a href="迎新.php" class="btn btn-primary">報名去</a>
                        <form method="post" action="activity_delete.php" onsubmit="return confirm('確定要刪除這個活動嗎？');">
                            <!-- 假設這些原本靜態活動也有對應 ID，這裡先假設 1 -->
                            <input type="hidden" name="id" value="1">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <button type="submit" class="btn btn-danger">刪除</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card custom-card mb-3">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title text-secondary">資管一日營</h3>
                    <p class="card-text">
                        資管一日營邀請大一新生透過一整天的活動更大學資管系的課程與生活。活動內容包含常用網站介紹、校園導覽與學長姐座談、闖關遊戲，讓參加者為未來四年作好準備。
                    </p>
                    <div class="mt-auto text-end d-flex justify-content-end gap-2">
                        <a href="一日營.php" class="btn btn-primary">報名去</a>
                        <form method="post" action="activity_delete.php" onsubmit="return confirm('確定要刪除這個活動嗎？');">
                            <!-- 假設這個活動 ID 為 2 -->
                            <input type="hidden" name="id" value="2">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <button type="submit" class="btn btn-danger">刪除</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- 動態新增的活動卡片（來自資料庫） -->
            <?php 
            if ($result && mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="card custom-card mb-3">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title text-secondary"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="card-text"><?php echo htmlspecialchars($row['content']); ?></p>
                            <div class="mt-auto text-end">
                                <form method="post" action="activity_delete.php" onsubmit="return confirm('確定要刪除這個活動嗎？');">
                                    <input type="hidden" name="id" value="<?php echo intval($row['id']); ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                    <button type="submit" class="btn btn-danger">刪除</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php }
            }
            ?>
        </div>
    </div>
</div>

<?php 
mysqli_close($conn); 
include('footer.php'); 
?>

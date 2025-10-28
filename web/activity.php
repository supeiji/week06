<?php
// activity.php
require_once 'header.php';
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'A';

$order = $_POST['order'] ?? '';
$searchtxt = trim($_POST['searchtxt'] ?? '');
$date_start = $_POST['date_start'] ?? '';
$date_end = $_POST['date_end'] ?? '';

// 日期交換
if ($date_start && $date_end && $date_start > $date_end) {
    [$date_start, $date_end] = [$date_end, $date_start];
}

// 建構 WHERE
$where_clauses = [];
$params = [];
$types = '';

if ($searchtxt !== '') {
    $where_clauses[] = "(title LIKE ? OR content LIKE ?)";
    $like = "%{$searchtxt}%";
    $params[] = $like; $params[] = $like;
    $types .= 'ss';
}
if ($date_start !== '') {
    $where_clauses[] = "pdate >= ?";
    $params[] = $date_start;
    $types .= 's';
}
if ($date_end !== '') {
    $where_clauses[] = "pdate <= ?";
    $params[] = $date_end;
    $types .= 's';
}

$sql = "SELECT id, title, content, pdate FROM activity";
if (count($where_clauses) > 0) $sql .= " WHERE " . implode(' AND ', $where_clauses);

// 排序白名單
$allowed_orders = ['title','pdate'];
if ($order && in_array($order, $allowed_orders)) {
    $sql .= " ORDER BY {$order}";
} else {
    $sql .= " ORDER BY pdate DESC";
}

$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    if (!empty($params)) mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, $sql);
}

// CSRF token（activity 刪除會用）
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$csrf_token = $_SESSION['csrf_token'];
?>
<div class="container mt-4">
  <h3 class="mb-3">活動列表</h3>

  <form action="activity.php" method="post" class="mb-3">
    <div class="row g-2 align-items-center">
      <div class="col-auto">
        <select name="order" class="form-select">
          <option value="">排序（預設：刊登日期）</option>
          <option value="title" <?= ($order=='title') ? 'selected' : '' ?>>標題</option>
          <option value="pdate" <?= ($order=='pdate') ? 'selected' : '' ?>>刊登日期</option>
        </select>
      </div>
      <div class="col-auto">
        <input type="text" name="searchtxt" class="form-control" placeholder="搜尋標題或內容" value="<?= htmlspecialchars($searchtxt) ?>">
      </div>
      <div class="col-auto">
        <input type="date" name="date_start" class="form-control" value="<?= htmlspecialchars($date_start) ?>">
      </div>
      <div class="col-auto">~</div>
      <div class="col-auto">
        <input type="date" name="date_end" class="form-control" value="<?= htmlspecialchars($date_end) ?>">
      </div>
      <div class="col-auto">
        <button class="btn btn-primary" type="submit">搜尋</button>
      </div>
      <?php if ($is_admin): ?>
        <div class="col-auto">
          <a href="activity_insert.php" class="btn btn-success">新增活動</a>
        </div>
      <?php endif; ?>
    </div>
  </form>

  <table class="table table-bordered table-striped" id="activity_table">
    <thead>
      <tr>
        <th>標題</th>
        <th>內容</th>
        <th>刊登日期</th>
        <?php if ($is_admin) echo "<th>操作</th>"; ?>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) : ?>
      <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
        <td><?= htmlspecialchars($row['pdate']) ?></td>
        <?php if ($is_admin): ?>
        <td>
          <a href="activity_delete_confirm.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-danger">刪除</a>
        </td>
        <?php endif; ?>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function(){
  $('#activity_table').DataTable({ ordering:false, language:{ lengthMenu:"每頁顯示 _MENU_ 筆", zeroRecords:"查無資料", info:"顯示第 _START_ 到 _END_ 筆，共 _TOTAL_ 筆", search:"搜尋：", paginate:{ next:"下一頁", previous:"上一頁" } } });
});
</script>

<?php
if (isset($stmt) && $stmt) mysqli_stmt_close($stmt);
mysqli_close($conn);
require_once 'footer.php';

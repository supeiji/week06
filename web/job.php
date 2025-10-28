<?php
$title = "求才資訊列表";
require_once "header.php";
require_once "db.php";

// 確保 session 啟動（若 header.php 沒啟動）
if (session_status() === PHP_SESSION_NONE) session_start();

// 取得是否為管理員（role === 'A' 表示管理員）
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'A';

// 產生 CSRF token（若還沒有）
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

try {
  $order = $_POST["order"]??"";
  $searchtxt = mysqli_real_escape_string($conn, $_POST["searchtxt"] ?? "");
  $date_start = $_POST["date_start"] ?? "";
  $date_end = $_POST["date_end"] ?? "";
  // 日期區間相反時自動交換
  if ($date_start && $date_end && $date_start > $date_end) {
    [$date_start, $date_end] = [$date_end, $date_start];
  }
  $where = [];
  if ($searchtxt) {
    // 這裡用 escape，搜尋語法仍為 LIKE
    $where[] = "(company like '%$searchtxt%' or content like '%$searchtxt%')";
  }
  if ($date_start) {
    $where[] = "pdate >= '$date_start'";
  }
  if ($date_end) {
    $where[] = "pdate <= '$date_end'";
  }
  $sql = "select * from job";
  if (count($where) > 0) {
    $sql .= " where " . implode(' and ', $where);
  }
  // 只允許白名單排序欄位，避免 SQL Injection via order by
  $allowed_orders = ['company','content','pdate'];
  if ($order && in_array($order, $allowed_orders)) {
      $sql .= " order by $order";
  } else {
      // 若沒選排序，預設依 pdate 降冪
      $sql .= " order by pdate desc";
  }
  $result = mysqli_query($conn, $sql);
?>
<div class="container mt-4">    
    <h3 class="mb-3">求才資訊列表</h3>
<div class="container">
<form action="job.php" method="post">
  <div class="row g-2 align-items-center mb-2">
    <div class="col-auto">
      <select name="order" aria-label="選擇排序欄位" class="form-select">
        <option value="">選擇排序欄位</option>
        <option value="company" <?=($order=="company")?'selected':''?>>求才廠商</option>
        <option value="content" <?=($order=="content")?'selected':''?>>求才內容</option>
        <option value="pdate" <?=($order=="pdate")?'selected':''?>>刊登日期</option>
      </select>
    </div>
    <div class="col-auto">
      <input placeholder="搜尋廠商及內容" value="<?=htmlspecialchars($searchtxt)?>" type="text" name="searchtxt" class="form-control">
    </div>
    <div class="col-auto">
      <input type="date" name="date_start" class="form-control" value="<?=htmlspecialchars($date_start)?>" placeholder="起始日期">
    </div>
    <div class="col-auto">
      <span>~</span>
    </div>
    <div class="col-auto">
      <input type="date" name="date_end" class="form-control" value="<?=htmlspecialchars($date_end)?>" placeholder="結束日期">
    </div>
    <div class="col-auto">
      <input class="btn btn-primary" type="submit" value="搜尋">
    </div>
  </div>
</form>

<table class="table table-bordered table-striped" id="job_table">
 <thead>
   <tr>
    <th>求才廠商</th>
    <th>求才內容</th>
    <th>日期</th>
    <?php if ($is_admin) echo "<th>操作</th>"; ?>
   </tr>
 </thead>
 <tbody>
 <?php
 while($row = mysqli_fetch_assoc($result)) {?>
 <tr>
  <td><?=htmlspecialchars($row["company"])?></td>
  <td><?=nl2br(htmlspecialchars($row["content"]))?></td>
  <td><?=htmlspecialchars($row["pdate"])?></td>
  <?php if ($is_admin): ?>
  <td>
    <!-- 刪除採 POST 並帶 CSRF token； onsubmit 使用 confirm() 作為前端確認 -->
    <form method="post" action="job_delete.php" style="display:inline;" onsubmit="return confirm('確定要刪除此求才項目？此動作無法復原。');">
      <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
      <button type="submit" class="btn btn-sm btn-danger">刪除</button>
    </form>
  </td>
  <?php endif; ?>
 </tr>
 <?php
  }
 ?>
 </tbody>
</table>
</div>
<?php
  mysqli_close($conn);
}
//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
?>

<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
// ✅ 啟用 DataTables
$(document).ready(function () {
$('#job_table').DataTable({
dom: 'lfrtip',
searching: true,
ordering: false,
language: {
    lengthMenu: "每頁顯示 _MENU_ 筆資料",
    zeroRecords: "查無資料",
    info: "顯示第 _START_ 到 _END_ 筆，共 _TOTAL_ 筆",
    infoEmpty: "無資料可顯示",
    infoFiltered: "(從 _MAX_ 筆資料中篩選)",
    search: "搜尋：",
    paginate: {
        first: "第一頁",
        last: "最後一頁",
        next: "下一頁",
        previous: "上一頁"
    }
}
});
});
</script>

<?php require_once 'footer.php'; ?>

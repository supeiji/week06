<?php
include('header.php'); 
session_start();
?>

<div class="container mt-4">
<h3>一日營</h3>
<form action="一日營計算.php" method="post">
    <div class="mb-3">
        <label>用餐</label><br>
        <input type="radio" name="eat" value="yes" required> 用餐 ($60)
        <input type="radio" name="eat" value="no"> 不用餐 (免費)
    </div>
    <button type="submit" class="btn btn-primary">送出</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_SESSION['name'] ?? '';
    $role = $_SESSION['role'] ?? 'S';
    $eat = $_POST['eat'] ?? '';
    $fee = 0;

    if ($role === 'T' || $role === 'M') {
        $fee = 0;
    } else { // 學生
        $fee = ($eat === 'yes') ? 60 : 0;
    }

    echo "<div class='mt-3'>$name ，您要繳交 $fee 元</div>";
}
?>
</div>
<?php include('footer.php'); ?>

<?php
include('header.php'); 
session_start();
?>

<div class="container mt-4">
<h3>迎新</h3>
<form action="迎新計算.php" method="post">
    <div class="mb-3">
        <label>參加場次</label><br>
        <input type="checkbox" name="program[]" value="1"> 上午場 ($150)
        <input type="checkbox" name="program[]" value="2"> 下午場 ($100)
        <input type="checkbox" name="program[]" value="3"> 午餐 ($60)
    </div>
    <button type="submit" class="btn btn-primary">送出</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_SESSION['name'] ?? '';
    $role = $_SESSION['role'] ?? 'S';
    $programlist = $_POST['program'] ?? [];
    $fee = 0;

    if ($role === 'T' || $role === 'M') {
        $fee = 0;
    } else { // 學生
        $program_price = [0,150,100,60]; // 索引對應 1,2,3
        foreach($programlist as $p) {
            $fee += $program_price[$p] ?? 0;
        }
    }

    echo "<div class='mt-3'>$name ，您要繳交 $fee 元</div>";
}
?>
</div>
<?php include('footer.php'); ?>

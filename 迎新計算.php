<?php error_reporting(0); ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>迎新</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php 
include('header.php'); 
session_start();
?>

<h1>迎新</h1>
<div style="max-width:700px;margin:32px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
    <div class="container my-5">
        <form action="迎新計算.php" method="post" class="bg-white p-4 rounded shadow" style="max-width:500px;margin:auto;">
            <!-- <div class="mb-3">
                <label class="form-label">姓名：</label>
                <span><?= htmlspecialchars($_SESSION['name']) ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label">身分：</label>
                <span><?= ($_SESSION['role'] === 'teacher' ? '老師' : '學生') ?></span>
            </div> -->
            <div class="mb-3">
                <label class="form-label">參加場次</label><br>
                <input type="checkbox" name="program[]" value="1"> 上午場 ($150)
                <input type="checkbox" name="program[]" value="2"> 下午場 ($100)
                <input type="checkbox" name="program[]" value="3"> 午餐 ($60)
            </div>
            <button type="submit" class="btn btn-primary">送出報名</button>
        </form>
        </br>
        <?php
            $sql = "select * from user where account ='$account' and password ='$password'";
            if ($_POST){
                                $name = $_SESSION['name'];
                                $account = $_SESSION['account'];
                                // 重新取得 role
                                $users = [
                                    "root"  => ["password" => "password", "name" => "老師", "role" => "teacher"],
                                    "user1" => ["password" => "pw1", "name" => "小明",   "role" => "student"],
                                    "user2" => ["password" => "pw2", "name" => "小華",   "role" => "student"],
                                    "user3" => ["password" => "pw3", "name" => "小美",   "role" => "student"],
                                    "user4" => ["password" => "pw4", "name" => "小強",   "role" => "student"],
                                ];
                                $role = $users[$account]["role"] ?? 'student';
                $programlist = $_POST["program"]?? [];
                if ($role === "teacher") {
                    $price = 0;
                } else {
                    $price = 0;
                    $program_price = array(0, 150, 100, 60);
                    foreach( $programlist as $program ) {
                        $price += $program_price[$program];
                    }
                }
                echo "<div class='fee-box mt-4'>$name ，您要繳交 $price 元</div>";
            }
        ?>
    </div>
</div>
<?php include('footer.php'); ?>

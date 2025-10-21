<?php 
// error_reporting(0);
include('header.php'); 
// session_start();
?>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css" rel="stylesheet">
<div class="container mt-4">    
    <h3 class="mb-3">一日營</h3>
    <div style="max-width:700px;margin:32px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
        <div class="container my-5">
            <form action="一日營計算.php" method="post" class="bg-white p-4 rounded shadow" style="max-width:500px;margin:auto;">
                 <div class="mb-3">
                     <label class="form-label">用餐</label><br>
                     <input type="radio" id="eat" name="eat" value="yes" required> <label for="eat">用餐 ($60)</label>
                     <input type="radio" id="noeat" name="eat" value="no"> <label for="noeat">不用餐 (免費)</label>
                  </div>
                  <button type="submit" class="btn btn-primary">送出報名</button>
              </form>
</br>
<?php 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                echo "hello";
                var_dump($_SESSION);

                
                $account = $_SESSION['account'];
                $name = $_SESSION['name'] ?? '';
                $role = $_SESSION['role'];
                $eat = $_POST['eat'] ?? '';
                $fee = 0;
                vardump($_SESSION);
                die;
                if ($role === 'teacher') {
                        $fee = 0;
                } else if ($role === 'student') {
                        $fee = ($eat === 'yes') ? 60 : 0;
                }
                echo "<div class='fee-box mt-4'>$name ，您要繳交 $fee 元</div>";
        }
        else{
                echo "<div class='fee-box mt-4'>請先填寫報名表單</div>";
        }
?>
        </div>
    </div>

</div>
<?php include('footer.php'); ?>


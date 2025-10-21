
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>迎新</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css" rel="stylesheet">
</head>
<body>
<?php include('header.php'); ?>
    
<style>
.active a { font-weight: bold; color: red; }
</style>
<div class="container mt-4">    

    <h3 class="mb-3">迎新</h3>
<div style="max-width:700px;margin:32px auto;background:#fff;padding:24px;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
    <div class="container my-5">
    <form action="迎新計算.php" method="post" class="bg-white p-4 rounded shadow" style="max-width:500px;margin:auto;">
            <!-- <div class="mb-3">
                <label for="name" class="form-label">姓名:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">身分</label><br>
                <input type="radio" id="teacher" name="role" value="teacher" required> <label for="teacher">老師</label>
                <input type="radio" id="student" name="role" value="student"> <label for="student">學生</label>
            </div> -->
            <div class="mb-3">
                <label class="form-label">參加場次</label><br>
                <input type="checkbox" name="program[]" value="1"> 上午場 ($150)
                <input type="checkbox" name="program[]" value="2"> 下午場 ($100)
                <input type="checkbox" name="program[]" value="3"> 午餐 ($60)
            </div>
            <button type="submit" class="btn btn-primary">送出報名</button>
        </form>
    </div>
</div>
    <?php include('footer.php'); ?>
</body>
</html>

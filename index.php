<?php 
include('header.php');
include('db.php');
$sql="select * from job";
$result = mysqli_query($conn, $sql);
 ?>
 <div class="container mt-4">    

<h3 class="mb-3">選一個報名吧</h3>
<div class="container my-5">
			<div class="card-row">
				<div class="card custom-card">
					<div class="card-body d-flex flex-column">
						<h3 class="card-title text-secondary">迎新茶會</h3>
						<p class="card-text">
							迎新茶會是專為新生設計的交流活動，讓新同學能夠認識師長與學長姐，了解資管系的學習環境與資源。活動中有輕鬆的茶點、趣味破冰遊戲，以及學長姐經驗分享，幫助新生快速融入大學生活。
						</p>
						<div class="mt-auto text-end">
							<a href="迎新.php" class="btn btn-primary">報名去</a>
						</div>
					</div>
				</div>
				<div class="card custom-card">
					<div class="card-body d-flex flex-column">
						<h3 class="card-title text-secondary">資管一日營</h3>
						<p class="card-text">
							資管一日營邀請大一新生透過一整天的活動更大學資管系的課程與生活。活動內容包含常用網站介紹、校園導覽與學長姐座談、闖關遊戲，讓參加者為未來四年作好準備。
						</p>
						<div class="mt-auto text-end">
							<a href="一日營.php" class="btn btn-primary">報名去</a>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>
<?php 
mysqli_close($conn); 
include('footer.php'); 
?>

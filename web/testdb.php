<?php
$servername = "localhost";
$dbname = "practice";
$dbUsername = "root";
$dbPassword = "12";
try {
  $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
  echo "成功連線!";
  mysqli_close($conn);
}
catch(Exception $e) {
  echo "無法連線: {$e->getMessage()}";
}
?>
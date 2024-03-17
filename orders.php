<?php
//đặt hàng
@include 'config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
   header('Location: login.php'); // Chuyển hướng về trang đăng nhập
   exit(); // Đảm bảo kết thúc việc thực thi mã ngay tại đây
}
$user_id = $_SESSION['user_id'];
//Kiểm tra xem user_id đã được thiết lập hay chưa.  nếu chưa chuyển đến trang đăng nhập. 
if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đặt hàng</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/comment.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Đơn đặt hàng</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
      $select_orders->execute([$user_id]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <p> Đặt vào ngày: <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Tên : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Số điện thoại : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Địa chỉ : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Phương thức thanh toán : <span><?= $fetch_orders['method']; ?></span> </p>
      <p> Sản phẩm của bạn : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Tổng thành tiền : <span><?= $fetch_orders['total_price']; ?> VND</span> </p>
      <p> Nội dung thanh toán : <span style="color:<?php if($fetch_orders['payment_status'] == 'Chưa hoàn thành'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty1">Chưa có đơn hàng nào!</p>';
   }
   ?>

   </div>

</section>
<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
<script src="js/topbot.js"></script>

</body>
</html>
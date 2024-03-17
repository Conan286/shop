<?php
//giới thiệu và đánh giá sản phẩm
@include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('Location: login.php'); // Chuyển hướng về trang đăng nhập
   exit(); // Đảm bảo kết thúc việc thực thi mã ngay tại đây
}

$user_id = $_SESSION['user_id'];

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
   <title>giới thiệu_about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">
   <!-- Phần giới thiệu về cửa hàng -->

   <div class="row">

      <div class="box">
         <img src="images/shophoa.jpg" alt="">
         <h3>RẤT HÂN HẠNH ĐƯỢC PHỤC VỤ QUÝ KHÁCH</h3>
         <a href="contact.php" class="btn">Liên hệ với chúng tôi</a>
      </div>

      <div class="box">
         <img src="images/tiemhoa.jpg" alt="">
         <h3>Chúng tôi cung cấp những dịch vị tốt nhất cho quý khách</h3>
         <a href="shop.php" class="btn"> Đến cửa hàng</a>
      </div>

   </div>

</section>

<section class="reviews">
   <!-- Phần đánh giá sản phẩm -->

   <h1 class="title">Những điều cần quan tâm</h1>

   <div class="box-container">

      <div class="box">
      <p>Cửa hàng chúng tối là một trong những của hàng bán hàng tốt nhất. 
      </p>
      <h1>Đánh giá của các khách hàng </h1>
      <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
      </div>
   </div>

</section>









<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
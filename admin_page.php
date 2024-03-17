<?php
// trang chủ của hệ thống quản trị

//Kiểm tra đăng nhập và Sesion
@include 'config.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
   header('Location: login.php'); // Chuyển hướng về trang đăng nhập
   exit(); // Đảm bảo kết thúc việc thực thi mã ngay tại đây
}

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang chủ hệ thống</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg" />

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/quangcao.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="dashboard">

      <h1 class="title">bảng thống kê</h1>

      <div class="box-container">
         <!-- Hiển thị thông tin thống kê về đơn hàng, sản phẩm, người dùng -->


         <div class="box-container">
            <!-- Hiển thị thông tin thống kê về đơn hàng, sản phẩm, người dùng -->
            <div class="box">
               <?php
               // Truy vấn CSDL để lấy thông tin thống kê
               $total_pendings = 0;
               $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
               $select_pendings->execute(['Chưa hoàn thành']); // Sửa trạng thái thanh toán thành "Chưa hoàn thành"
               $total_pendings = $select_pendings->rowCount(); // Số đơn hàng có trạng thái "Chưa hoàn thành"
               ?>
               <h3><?= $total_pendings; ?></h3>
               <p>Đơn hàng đang chờ xử lý</p>
               <a href="admin_pending.php" class="btn">XEM</a>
            </div>
         </div>


         <div class="box-container">
            <!-- Hiển thị thông tin thống kê về đơn hàng, sản phẩm, người dùng -->
            <div class="box">
               <?php
               // Truy vấn CSDL để lấy thông tin thống kê
               $total_pendings = 0;
               $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
               $select_pendings->execute(['Đã hoàn thành']); // Sửa trạng thái thanh toán thành "Chưa hoàn thành"
               $total_pendings = $select_pendings->rowCount(); // Số đơn hàng có trạng thái "Chưa hoàn thành"
               ?>
               <h3><?= $total_pendings; ?></h3>
               <p>Đơn đặt hàng đã hoàn thành</p>
               <a href="admin_completed.php" class="btn">XEM</a>
            </div>
         </div>

         <div class="box">
            <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount();
            ?>
            <h3><?= $number_of_orders; ?></h3>
            <p>Tổng Đơn Đặt hàng</p>
            <a href="admin_orders.php" class="btn">XEM</a>
         </div>

         <div class="box">
            <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount();
            ?>

            <!-- Hiển thị số lượng và link để xem chi tiết:-->
            <h3><?= $number_of_products; ?></h3>
            <p>Sản phẩm đã được thêm </p>
            <a href="admin_products.php" class="btn">Xem sản phẩm</a>
         </div>

         <div class="box">
            <?php
            $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
            $select_users->execute(['user']);
            $number_of_users = $select_users->rowCount();
            ?>
            <h3><?= $number_of_users; ?></h3>
            <p>Tổng số người dùng</p>
            <a href="admin_sumusers.php" class="btn">Xem tài khoản</a>
         </div>

         <div class="box">
            <?php
            $select_admins = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
            $select_admins->execute(['admin']);
            $number_of_admins = $select_admins->rowCount();
            ?>
            <h3><?= $number_of_admins; ?></h3>
            <p>Tổng số admins</p>
            <a href="admin_sumadmin.php" class="btn">XEM</a>
         </div>

         <div class="box">
            <?php
            $select_accounts = $conn->prepare("SELECT * FROM `users`");
            $select_accounts->execute();
            $number_of_accounts = $select_accounts->rowCount();
            ?>
            <h3><?= $number_of_accounts; ?></h3>
            <p>Tổng số tài khoản</p>
            <a href="admin_users.php" class="btn">XEM</a>
         </div>

         <div class="box">
            <?php
            $select_messages = $conn->prepare("SELECT * FROM `message`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount();
            ?>
            <h3><?= $number_of_messages; ?></h3>
            <p>Tổng số đánh giá</p>
            <a href="admin_contacts.php" class="btn">Xem đánh giá</a>
         </div>

      </div>

   </section>
   <script src="js/script.js"></script>

</body>

</html>
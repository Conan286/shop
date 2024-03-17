<?php
// quản lý trang đơn hàng đã hoàn thành
@include 'config.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
   header('Location: login.php'); // Chuyển hướng về trang đăng nhập
   exit(); // Đảm bảo kết thúc việc thực thi mã ngay tại đây
}

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:index.php');
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đơn hàng đã hoàn thành</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/table.css">
   <link rel="stylesheet" href="css/quangcao.css">

</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="completed-orders">

   <h1 class="title">Đơn hàng đã hoàn thành</h1>

   <div class="box-containers">

      <table>
         <tr>
            <th>User ID</th>
            <th>Ngày đặt hàng</th>
            <th>Tên</th>
            <th>Email</th>
            <th>SĐT</th>
            <th>Địa chỉ</th>
            <th>Tổng số sản phẩm</th>
            <th>Tổng số tiền</th>
            <th>Phương thức thanh toán</th>
         </tr>
         <br>
         <?php
         // Hiển thị thông tin từng đơn hàng đã hoàn thành
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'Đã hoàn thành'");
         $select_orders->execute();
         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td><p><span><?= $fetch_orders['user_id']; ?></span> </p></td>
                    <td><p><span><?= $fetch_orders['placed_on']; ?></span> </p></td>
                    <td><p><span><?= $fetch_orders['name']; ?></span> </p></td>
                    <td><p><span><?= $fetch_orders['email']; ?></span> </p></td>
                    <td><p><span><?= $fetch_orders['number']; ?></span> </p></td>
                    <td><p><span><?= $fetch_orders['address']; ?></span> </p></td>
                    <td><p><span><?= $fetch_orders['total_products']; ?></span> </p></td>
                    <td><p><span><?= $fetch_orders['total_price']; ?>VND</span> </p></td>
                    <td><p><span><?= $fetch_orders['method']; ?></span> </p></td>
                </tr>
                <?php
            }
        } else {
            echo '<p class="empty">Không có đơn hàng đã hoàn thành nào!</p>';
        }
        ?>
    </table>
</div>
</section>
<script src="js/script.js"></script>

</body>
</html>

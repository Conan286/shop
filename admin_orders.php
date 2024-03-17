<?php
// quản lý trang đơn hàng
@include 'config.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
   header('Location: login.php'); // Chuyển hướng về trang đăng nhập
   exit(); // Đảm bảo kết thúc việc thực thi mã ngay tại đây
}

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};
//Cập nhật thanh toán và Xóa đơn hàng:
if(isset($_POST['update_order'])){

// Xử lý cập nhật trạng thái thanh toán đơn hàng
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
   $update_orders = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_orders->execute([$update_payment, $order_id]);
   $message[] = 'Thanh toán đã được cập nhật!';

};

if(isset($_GET['delete'])){
   // Xử lý xóa đơn hàng
   $delete_id = $_GET['delete'];
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_orders->execute([$delete_id]);
   header('location:admin_orders.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đặt hàng_ad order</title>

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

<section class="placed-orders">

   <h1 class="title">Đơn đặt hàng</h1>

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
            <th>Trạng thái đơn hàng</th>
            <th>Chỉnh sửa đơn hàng</th>
         </tr>
         <br>
         <?php
         // Hiển thị thông tin từng đơn hàng
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
         ?>
         <tr>
                        <td><p><span><?= $fetch_orders['user_id']; ?></span> </p></td>
                        <td><p><span><?= $fetch_orders['placed_on']; ?></span> </p></td>
                        <td><p><span><?= $fetch_orders['name']; ?></span> </p></td>
                        <td><p><span><?= $fetch_orders['email']; ?></span> </p></td>
                        <td><p><span><?= $fetch_orders['number']; ?></span> </p></td>
                        <td><p><span><?= $fetch_orders['address']; ?></span> </p></td>
                        <td><p><span><?= $fetch_orders['total_products']; ?></span> </p></td>
                        <td><p><span><?= number_format($fetch_orders['total_price'], 0, ',', '.'); ?> VND</span></p></td>
                        <td><p><span><?= $fetch_orders['method']; ?></span> </p></td>
                        
                        <!--Form cập nhật trạng thái thanh toán:-->
                        <form action="" method="POST">
                        <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                        <td><select name="update_payment" class="drop-down">
                        <option value="" selected disabled><?= $fetch_orders['payment_status'] == 'pending' ? 'Chưa giải quyết' : $fetch_orders['payment_status']; ?></option>
                           <option value="Chưa hoàn thành">Chưa hoàn thành</option>
                           <option value="Đã hoàn thành">Đã hoàn thành</option>
                        </select></td>
                        <td><div class="flex-btns">
                           <input type="submit" name="update_order" class="option-btn" value="Cập nhật">
                           <a href="admin_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Bạn muốn xóa đơn hàng này?');">Xóa đơn</a>
                        </div></td>
                     </form>

         
    

      
      
      <?php
         }
      }else{
         echo '<p class="empty">Bạn không có đơn hàng nào!</p>';
      }
      ?>
</tr>
  </table>
   </div>

</section>
<script src="js/script.js"></script>

</body>
</html>
<?php
//thanh toan 
@include 'config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
   header('Location: login.php'); // Chuyển hướng về trang đăng nhập
   exit(); // Đảm bảo kết thúc việc thực thi mã ngay tại đây
}
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){
   // (trích dẫn thông tin đặt hàng và làm sạch dữ liệu)
   // Kiểm tra và xử lý đơn hàng
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   // $address = $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' '. $_POST['country'] ;
   $address = filter_var($email, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

   if($cart_total == 0){
      $message[] = 'Giỏ của bạn trống';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'Đơn hàng đã được đặt!';
   }else{
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      $message[] = 'Đặt hàng thành công!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thủ tục thanh toán</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-orders">
    <!-- Hiển thị các sản phẩm trong giỏ hàng -->

   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= ''.$fetch_cart_items['price'].' x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">Giỏ hàng của bạn trống!</p>';
   }
   ?>
   <div class="grand-total">Tổng tiền : <span><?= $cart_grand_total; ?> VND</span></div>
</section>

<section class="checkout-orders">
   <!-- Form để người dùng nhập thông tin đặt hàng -->

   <form action="" method="POST">

      <h3>Địa chỉ nhận hàng</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Tên của bạn :</span>
            <input type="text" name="name" placeholder="Nhập tên của bạn" class="box" required>
         </div>
         <div class="inputBox">
            <span>Số điện thoại của bạn :</span>
            <input type="text" name="number" placeholder="Nhập số điện thoại" class="box" required>
         </div>
         <div class="inputBox">
            <span>Email của bạn :</span>
            <input type="email" name="email" placeholder="Nhập email của bạn" class="box" required>
         </div>
         <div class="inputBox">
            <span>Phương thức thanh toán :</span>
            <select name="method" class="box" required>
               <option value="Ví MoMo">Ví Momo</option>
               <option value="Chuyển khoản">Chuyển Khoản</option>
               
            </select>
         </div>
         <!-- <div class="inputBox">
            <span>Số nhà :</span>
            <input type="text" name="flat" placeholder="Nhập vào số nhà" class="box" required>
         </div>
         <div class="inputBox">
            <span>Địa chỉ :</span>
            <input type="text" name="street" placeholder="Nhập vào địa chỉ" class="box" required>
         </div>
         <div class="inputBox">
            <span>Thành phố :</span>
            <input type="text" name="city" placeholder="e.g. VinhLong" class="box" required>
         </div>
         <div class="inputBox">
            <span>Quốc gia :</span>
            <input type="text" name="country" placeholder="e.g. VietNam" class="box" required>
         </div> -->
         
      </div>

      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="Đặt hàng">

   </form>

</section>
<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
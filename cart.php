<?php
//quản lý giỏ hàng, 
//cho phép người dùng xem sản phẩm trong giỏ hàng, 
//cập nhật số lượng và xóa sản phẩm khỏi giỏ hàng.
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




if(isset($_GET['delete'])){
//xóa sản phẩm khỏi giỏ hàng
   $delete_id = $_GET['delete'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$delete_id]);
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   //xóa tất cả sản phẩm khỏi giỏ hàng
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){
   // cập nhật số lượng sản phẩm trong giỏ hàng
   $cart_id = $_POST['cart_id'];
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$p_qty, $cart_id]);
   $message[] = 'Số lượng giỏ hàng được cập nhật';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>giỏ hàng_cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
      <link rel="stylesheet" href="css/quangcao.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="shopping-cart">
<!-- Hiển thị sản phẩm trong giỏ hàng -->
   <h1 class="title">sản phẩm được thêm vào giỏ hàng</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="POST" class="box">
      <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('xóa khỏi giỏ hàng?');"></a>
      <a href="view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <div class="price"><?= $fetch_cart['price']; ?> VND</div>
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <div class="flex-btn">
         <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty">
         <input type="submit" value="Cập nhật" name="update_qty" class="option-btn">
      </div>
      <div class="sub-total"> Phụ thu: <span><?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?> VND</span> </div>
   </form>
   <?php
      $grand_total += $sub_total;
      }
   }else{
      echo '<p class="empty">Giỏ hàng của bạn trống</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>Tổng cộng : <span><?= $grand_total; ?> VND</span></p>
      <a href="shop.php" class="option-btn">Tiếp tục mua hàng</a>
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>">Xóa tất cả</a>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Xác nhận mua hàng</a>
   </div>

</section>
<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
<?php
//trang xem lại sản phẩm 
@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){
   //trích dẫn thông tin sản phẩm và làm sạch dữ liệu
   // Kiểm tra sản phẩm đã có trong danh sách yêu thích hoặc giỏ hàng chưa
   // Thêm sản phẩm vào danh sách yêu thích nếu chưa có
   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'đã được thêm vào danh sách yêu thích!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'đã được thêm vào giỏ hàng!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'Thêm vào danh sách yêu thích!';
   }

}

if(isset($_POST['add_to_cart'])){
   // trích dẫn thông tin sản phẩm và làm sạch dữ liệu
   // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
   // Thêm sản phẩm vào giỏ hàng nếu chưa có, hoặc cập nhật số lượng
   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'Đã thêm vào giỏ hàng!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'Thêm vào giỏ hàng';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Xem sản phẩm</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/comment.css">
   <link rel="stylesheet" href="css/tien.css">


</head>
<body>
 
<?php include 'header.php'; ?>

<section class="quick-view">
   <!-- Hiển thị thông tin sản phẩm -->
   <h1 class="title">Xem lại sản phẩm</h1>

   <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$pid]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="details"><?= $fetch_products['details']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <div class="tien"><span><?= number_format($fetch_products['price'], 0, ',', '.'); ?></span> VND</div>
      <?php if ($fetch_products['quantity'] > 0) { ?>
         <div class="name"><label for="">Số hàng trong kho: </label><strong><?=$fetch_products['quantity']?></strong></div>
         <input type="number" min="1" value="1" name="p_qty" class="qty">
         <input type="submit" value="Thêm vào yêu thích" class="option-btn" name="add_to_wishlist">
         <input type="submit" value="Thêm vào giỏ hàng" class="btn" name="add_to_cart">
      <?php } else { ?>
         <!-- cho biết trang thái sản phẩm  -->
         <div class="name">Trạng thái: Hết hàng</div> <?php } ?>
   </form>
   <?php
         }
      }else{
         //thông báo sản phẩm chưa được thêm vào 
         echo '<p class="empty">Sản phẩm chưa được thêm vào!</p>';
      }
   ?>

</section>


<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
<script src="js/cmt.js"></script>


</body>
</html>
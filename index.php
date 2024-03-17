<?php

@include 'config.php';


if(isset($_POST['add_to_wishlist'])){

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
      $message[] = 'Đã thêm vào danh sách yêu thích!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'Thêm vào danh sách yêu thích!';
   }

}

if(isset($_POST['add_to_cart'])){

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
      $message[] = 'Thêm vào giỏ hàng!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang chủ</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/slides.css">
   <link rel="stylesheet" href="css/quangcao.css">
   <link rel="stylesheet" href="css/tien.css">


</head>
<body>
   
<?php include 'header.php'; ?>
<div class="Slide_chay">
                <div class="slider">   
                    <div class="slides">
                      <input type="radio" name="radio-btn" id="radio1">
                      <input type="radio" name="radio-btn" id="radio2">
                      <input type="radio" name="radio-btn" id="radio3">
                      <input type="radio" name="radio-btn" id="radio4">

                      <div class="slide first">
                        <img src="anh/1.jpg" alt="">
                      </div>
                      <div class="slide">
                        <img src="anh/2.jpg" alt="">
                      </div>
                      <div class="slide">
                        <img src="anh/3.jpg" alt="">
                      </div>
                      <div class="slide">
                        <img src="anh/4.jpg" alt="">
                      </div>

                      <div class="navigation-auto">
                        <div class="auto-btn1"></div>
                        <div class="auto-btn2"></div>
                        <div class="auto-btn3"></div>
                        <div class="auto-btn4"></div>
                      </div>

                    </div>

                    <div class="navigation-manual">
                      <label for="radio1" class="manual-btn"></label>
                      <label for="radio2" class="manual-btn"></label>
                      <label for="radio3" class="manual-btn"></label>
                      <label for="radio4" class="manual-btn"></label>
                    </div>

                  </div>    
                  <script type="text/javascript">
                  var counter = 1;
                  setInterval(function(){
                    document.getElementById('radio' + counter).checked = true;
                    counter++;
                    if(counter > 4){
                      counter = 1;
                    }
                  }, 10000);
                  </script>
            </div>

<section class="home-category">

   <h1 class="title">Khóa Học</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/Hoatuoi1.jpg" alt="">
         <h3>Khóa Học Lập Trình</h3>
         <p>Các khóa học lập trình web với HTML, CSS, Js, lập trình hướng đối tượng với Java, lập trình C++, Python, Php MySQL, ...</p>
         <a href="category.php?category=Khoahoc1" class="btn">Khóa Học Lập Trình</a>
      </div>

      <div class="box">
         <img src="images/Hoacuoi.jpg" alt="">
         <h3>Khóa Học Ngoại Ngữ</h3>
         <p>Bao gồm các khóa học luyện thi Toeic, khóa luyện đề ETS, khóa học tiếng Nhật, ...</p>
         <a href="category.php?category=Khoahoc2" class="btn">Khóa Học Ngoại Ngữ</a>
      </div>

      <div class="box">
         <img src="images/Hoakhaitruong.jpg" alt="">
         <h3>Khóa Học Khác</h3>
         <p>Bao gồm các khóa học Tin Học Văn Phòng như Microsoft Word, Excel và các khóa học thiết kế đồ họa, Pts, ... </p>
         <a href="category.php?category=Khoahoc3" class="btn">List Khóa Học Khác</a>
      </div>

   </div>

</section>

<section class="products">

   <h1 class="title">Khóa học mới nhất</h1>
   <div class="box-containers">

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>"><img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""></a>
      <div class="name"><?= $fetch_products['name']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <div class="tien"><span><?= number_format($fetch_products['price'], 0, ',', '.'); ?></span> VND</div>
      <?php if ($fetch_products['quantity'] > 0) { ?>
         <div class="name"><label for="">Số hàng trong cửa hàng: </label><strong><?=$fetch_products['quantity']?></strong></div>
      <?php } else { ?>
         <div class="name">Trạng thái: Hết hàng</div> <?php } ?>
   </form>
   <?php
      }
   }else{
      // nếu chưa có sản phẩm thì hiển thị.
      echo '<p class="empty">Sản phẩm chưa được thêm vào!</p>';
   }
   ?>

   </div>
      </div>


</section>
<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
<script src="js/topbot.js"></script>
   <i class="fa fa-arrow-up" id="goto-top-page" onclick="gotoTop()"></i>


</body>
</html>
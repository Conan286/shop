<?php
// Initialize variables
$count_cart_items = null;
$count_wishlist_items = null;

// Check if $user_id is set before using it in queries
if (isset($user_id)) {
    $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $count_cart_items->execute([$user_id]);

    $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
    $count_wishlist_items->execute([$user_id]);
}
?>

<!-- Rest of your HTML code -->

<?php
// Tiêu đề 
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<div class="quangcao">
   <img src="anh/quangcao.jpg" alt="">
</div>

<header class="header">
   <div class="flex">
      <a href="index.php" class="logo">
         <img src="images/logo.jpg" alt="CuaHang" width="100" height="100">
      </a>

      <nav class="navbar">
         <a href="index.php">Trang chủ</a>
         <a href="shop.php">Sản phẩm</a>
         <a href="orders.php">Đơn đặt hàng</a>
         <a href="about.php">Giới thiệu</a>
         <a href="contact.php">Đánh giá sản phẩm</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php" class="fa-solid fa-magnifying-glass"></a>
         
         <?php
         // Check if $user_id is set before using it in queries
         if (isset($user_id)) {
            // Output only if $user_id is set
            echo '
            <a href="wishlist.php"><i class="fa-solid fa-heart"></i><span>' . $count_wishlist_items->rowCount() . '</span></a>
            <a href="cart.php"><i class="fa-solid fa-cart-plus"></i><span>' . $count_cart_items->rowCount() . '</span></a>';
         } else {
            // Output default values or handle the case when $user_id is not set
            echo '
            <a href="wishlist.php"><i class="fa-solid fa-heart"></i><span>0</span></a>
            <a href="cart.php"><i class="fa-solid fa-cart-plus"></i><span>0</span></a>';
         }
         ?>
         
         <div id="user-btn" class="fa-sharp fa-solid fa-circle-user"></div>
      </div>

      <div class="profile">
         <?php
         if (isset($user_id)) {
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         }
         ?>
         <?php if (isset($user_id)): ?>
            <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
            <p><?= $fetch_profile['name']; ?></p>
            <div class="flex-btn">
               <a href="user_profile_update.php" class="btn"><i class="fa-solid fa-pen-to-square"></i>Cập nhật</a>
               <a href="logout.php" class="delete-btn"><i class="fa-solid fa-right-from-bracket"></i>Đăng xuất</a>
            </div>
         <?php endif; ?>
      </div>
   </div>
</header>

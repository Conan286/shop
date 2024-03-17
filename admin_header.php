<?php
//mô tả phần header (tiêu đề) của trang quản trị.

//Hiển thị thông báo (nếu có):
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?>

<header class="header">
   <!-- Nội dung header -->
   <div class="flex">
    <!-- Phần logo và menu -->
      <a href="admin_page.php" class="logo"><span>Quản trị Viên</span></a>

      <nav class="navbar">
          <!-- Các liên kết menu -->
         <a href="admin_page.php">Trang chủ</a>
         <a href="admin_products.php">Sản phẩm</a>
         <a href="admin_orders.php">Đặt hàng</a>
         <a href="admin_users.php">Người dùng</a>
         <a href="admin_contacts.php">Phản hồi</a>
      </nav>

      <div class="icons">
         <!-- Biểu tượng menu và người dùng -->
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>
      
      <!-- Thông tin người dùng/đăng nhập -->
      <div class="profile ">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['name']; ?></p>
         <div class="flex-btn">
            <a href="admin_update_profile.php" class="btn"><i class="fa-solid fa-pen-to-square"></i>Cập nhật</a>
            <a href="logout.php" class="delete-btn"><i class="fa-solid fa-right-from-bracket"></i>Đăng xuất</a>
         </div>
      </div>
      <div class="profile ">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['name']; ?></p>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">Đăng nhập</a>
            <a href="register.php" class="option-btn">Đăng ký</a>
         </div>
      </div>

   </div>

</header>
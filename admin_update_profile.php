<?php
//trang cập nhật thông tin người dùng (admin)
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


// Xử lý cập nhật thông tin người dùng và ảnh
if(isset($_POST['update_profile'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $admin_id]);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   $old_image = $_POST['old_image'];

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Ảnh đại diện quá lớn!';
      }else{
         $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $admin_id]);
         if($update_image){
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'Cập nhật ảnh đại diện thành công!';
         };
      };
   };

   $old_pass = $_POST['old_pass'];
   $update_pass = md5($_POST['update_pass']);
   $update_pass = filter_var($update_pass, FILTER_SANITIZE_STRING);
   $new_pass = md5($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = md5($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if(!empty($update_pass) AND !empty($new_pass) AND !empty($confirm_pass)){
      if($update_pass != $old_pass){
         $message[] = 'Mật khẩu cũ không khớp!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'Xác nhận mật khẩu không khớp!';
      }else{
         $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass_query->execute([$confirm_pass, $admin_id]);
         $message[] = 'Cập nhật mật khẩu thành công!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cập nhật thông tin_ad update ptofile</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>
<body>
   <!-- Form cập nhật thông tin người dùng -->
<?php include 'admin_header.php'; ?>

<section class="update-profile">

   <h1 class="title">cập nhật thông tin</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
      <div class="flex">
         <div class="inputBox">
            <span>Tên ADMIN :</span>
            <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="Cập nhật tên admin" required class="box">
            <span>Email :</span>
            <input type="email" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="Cập nhật Email" required class="box">
            <span>Cập nhật ảnh :</span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
            <input type="hidden" name="old_image" value="<?= $fetch_profile['image']; ?>">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
            <span>Mật khẩu cũ :</span>
            <input type="password" name="update_pass" placeholder="Nhập mật khẩu" class="box">
            <span>Mật khẩu mới :</span>
            <input type="password" name="new_pass" placeholder="Nhập mật khẩu mới" class="box">
            <span>Nhập lại mật khẩu :</span>
            <input type="password" name="confirm_pass" placeholder="Nhập lại mật khẩu" class="box">
         </div>
      </div>
      <div class="flex-btn">
         <input type="submit" class="btn" value="CẬP NHẬT THÔNG TIN" name="update_profile">
         <a href="admin_page.php" class="option-btn">TRỞ LẠI</a>
      </div>
   </form>

</section>
<script src="js/script.js"></script>

</body>
</html>
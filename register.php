<?php
//trang đăng ký 
include 'config.php';
//Kiểm tra xem đã nhấn nút "submit" trên SQL chưa.
if(isset($_POST['submit'])){
//Lấy và lọc dữ liệu.
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = md5($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

//Kiểm tra tồn tại của email trong cơ sở dữ liệu.
   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);
//Xử lý lỗi khi email đã tồn tại.
   if($select->rowCount() > 0){
      $message[] = 'Email này đã được sử dụng !';
   }else{
 //Xử lý lỗi khi mật khẩu không khớp.
      if($pass != $cpass){
         $message[] = 'Xác nhận mật khẩu không khớp!';
      }else{
     //Chèn thông tin người dùng vào cơ sở dữ liệu nếu chuaư có trong SQL.
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password, image) VALUES(?,?,?,?)");
         $insert->execute([$name, $email, $pass, $image]);
         //Xử lý lỗi khi kích thước ảnh đại diện quá lớn. 
         if($insert){
            if($image_size > 2000000){
               $message[] = 'Ảnh đại diện quá lớN!';
            }else{
               move_uploaded_file($image_tmp_name, $image_folder);
               $message[] = 'Bạn đã đăng ký thành công!';
               header('location:login.php');
            }
         }

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
   <title>Đăng ký</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">
   <link rel="stylesheet" href="css/add-dk.css">

</head>
<body>

<?php

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
   
<section class="form-container">
   <form action="" enctype="multipart/form-data" method="POST">
      <h3>đăng ký </h3>
      <input type="Tên" name="name" class="box" placeholder="Nhập tên của bạn" required>
      <input type="email" name="email" class="box" placeholder="Nhập email của bạn" required>
      <input type="Mặt khẩu" name="pass" class="box" placeholder="Nhập mật khẩu của bạn" required>
      <input type="Nhập lại mật khẩu" name="cpass" class="box" placeholder="Nhập lại mật khẩu" required>
      <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="Đăng ký ngay bây giờ" class="btn" name="submit">
      <p>Bạn vừa tạo tài khoản mới <br> <a href="login.php">Đăng nhập ngay</a></p>
   </form>

</section>

            
</body>
</html>
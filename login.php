<?php
//trang đăng nhập 
@include 'config.php';

session_start();

if(isset($_POST['submit'])){

/*$_POST['email'] và $_POST['pass'] lấy giá trị email và mật khẩu từ form gửi đi.
FILTER_SANITIZE_STRING được sử dụng để loại bỏ các ký tự đặc biệt không hợp lệ từ email và mật khẩu để tránh các tấn công.*/
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

/*tạo câu truy vấn SQL để kiểm tra thông tin đăng nhập.*/
   $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email, $pass]);
   $rowCount = $stmt->rowCount();  

   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($rowCount > 0){
      /*để lưu thông tin người dùng đã đăng nhập vào session.*/
      if($row['user_type'] == 'admin'){

         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      }elseif($row['user_type'] == 'user'){

         $_SESSION['user_id'] = $row['id'];
         header('location:index.php');

      }else{
         $message[] = 'Không tìm thấy người dùng!';
      }

   }else{
      $message[] = 'Email hoặc mật khẩu không chính xác!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng nhập_login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">
   <link rel="stylesheet" href="css/add.css">

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

   <form action="" method="POST">
      <h3>Đăng nhập </h3>
      <input type="email" name="email" class="box" placeholder="Nhập email của bạn" required>
      <input type="password" name="pass"class="box" placeholder="Nhập mật khẩu của bạn" required>
      <input type="submit" value="Đăng nhập" class="btn" name="submit">
      <p>Bạn không có tài khoản? <a href="register.php">Đăng ký ngay </a></p>
   </form>
</section>



</body>
</html>
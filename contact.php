<?php
//thông tin liên lạc
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

if(isset($_POST['send'])){
   // ... (trích dẫn thông tin phản hồi và làm sạch dữ liệu)
   // Kiểm tra xem phản hồi đã được gửi hay chưa
   // Gửi phản hồi nếu chưa được gửi

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'Đã gửi tin nhắn!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO `message`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'Gửi đánh giá thành công!';

   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đánh giá sản phẩm</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="contact">
 <!-- Form để người dùng nhập thông tin liên lạc và phản hồi -->

   <h1 class="title">thông tin liên lạc</h1>

   <form action="" method="POST">
      <input type="Tên" name="name" class="box" required placeholder="Nhập tên của bạn">
      <input type="email" name="email" class="box" required placeholder="Nhập email của bạn">
      <input type="Sđt" name="number" min="0" class="box" required placeholder="Nhập sđt của bạn">
      <textarea name="msg" class="box" required placeholder="Nhập phản hồi" cols="30" rows="10"></textarea>
      <input type="submit" value="Gửi phản hồi" class="btn" name="send">
   </form>

</section>
<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
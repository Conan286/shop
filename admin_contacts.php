<?php
//quản lý phản hồi của khách hàng
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
//Xóa phản hồi của khách hàng:
if(isset($_GET['delete'])){
  // Lấy ID phản hồi cần xóa
   $delete_id = $_GET['delete'];
   // Xóa phản hồi từ cơ sở dữ liệu
   $delete_message = $conn->prepare("DELETE FROM `message` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   // Sau khi xóa, chuyển hướng lại đến trang quản lý phản hồi
   header('location:admin_contacts.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đánh giá_ad contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">


</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">
   <!-- Hiển thị thông tin các phản hồi của khách hàng -->
   <h1 class="title">Phản hồi của khách hàng</h1>

   <div class="box-container">

   <?php
      $select_message = $conn->prepare("SELECT * FROM `message`");
      $select_message->execute();
      if($select_message->rowCount() > 0){
         // Duyệt danh sách phản hồi và hiển thị thông tin
         while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> ID người dùng : <span><?= $fetch_message['user_id']; ?></span> </p>
      <p> Tên người dùng : <span><?= $fetch_message['name']; ?></span> </p>
      <p> Số điện thoại : <span><?= $fetch_message['number']; ?></span> </p>
      <p> Email : <span><?= $fetch_message['email']; ?></span> </p>
      <p> Nội dung : <span><?= $fetch_message['message']; ?></span> </p>
      <a href="admin_contacts.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('Bạn muốn xóa phản hồi này?');" class="delete-btn">Xóa phản hồi</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Bạn không có phản hồi nào!</p>';
      }
   ?>

   </div>

</section>
<script src="js/script.js"></script>

</body>
</html>
<?php
//trang quản lý người dùng (admin).
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
//xử lý xóa người dùng 
if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);
   header('location:admin_users.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quản trị - Người dùng</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>
   

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/table.css">
   <link rel="stylesheet" href="css/quangcao.css">




</head>
<body>

<?php include 'admin_header.php';
//Hiển thị danh sách người dùng ?>

<section class="user-accounts">

   <h1 class="title">Tài khoản người dùng</h1>
   

<table>
   <tr>
      <th>Ảnh đại diện</th>
      <th>ID người dùng</th>
      <th>Tên người dùng</th>
      <th>Email</th>
      <th>Kiểu tài khoản</th>
      <th>Thiết lập</th>
   </tr>
   <br>
   <?php
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
         while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
      ?>
   <tr>
      <div class="box-containers">
      <div class="box" style="<?php if($fetch_users['id'] == $admin_id){ echo 'display:none'; }; ?>">
         <td><img src="uploaded_img/<?= $fetch_users['image']; ?>" alt=""></td>
         <td><p><span><?= $fetch_users['id']; ?></span></p></td>
         <td><p><span><?= $fetch_users['name']; ?></span></p></td>
         <td><p><span><?= $fetch_users['email']; ?></span></p></td>
         <td><p><span style=" color:<?php if($fetch_users['user_type'] == 'admin'){ echo 'orange'; }; ?>"><?= $fetch_users['user_type']; ?></span></p></td>
         <td><a href="admin_users.php?delete=<?= $fetch_users['id']; ?>" onclick="return confirm('Bạn muốn xóa tài khoản này?');" class="delete-btn">Xóa</a></td>

      </div>
      
      <?php
      }
      ?>
      </div>
   </tr>
   <br>
</table>

</section>
<script src="js/script.js"></script>

</body>
</html>
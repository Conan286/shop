<?php
//trang quản lý sản phẩm
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

//Thêm sản phẩm mới
if(isset($_POST['add_product'])){

   // Lấy thông tin từ form
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $quantity = $_POST['quantity'];
   $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   // Thực hiện kiểm tra và thêm sản phẩm mới
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Tên sản phẩm đã tồn tại!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO `products`(name, category, details, price,quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_products->execute([$name, $category, $details, $price, $quantity, $image]);

      if($insert_products){
         if($image_size > 2000000){
            $message[] = 'Ảnh sản phẩm quá lớn!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Đã thêm sản phẩm mới!';
         }

      }

   }

};

// Xóa sản phẩm và các liên kết liên quan
if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_products.php');


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>sản phẩm</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" href="images/logo.jpg"/>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/quangcao.css">

   
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Thêm sản phẩm mới</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="Nhập tên sản phẩm">
         <select name="category" class="box" required>
            <option value="" selected disabled>Loại sản phẩm</option>
               <option value="Khoahoc1"> Khóa Học Lập Trình </option>
               <option value="Khoahoc2">Khóa Học Ngoại Ngữ</option>
               <option value="Khoahoc3">Khóa Học Khác</option>
         </select>
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="quantity" class="box" required placeholder="Nhập số lượng sản phẩm đang có">
         <input type="text" min="0" name="price" class="box" required placeholder="Nhập giá sản phẩm">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="Nhập các thông tin về sản phẩm" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="Thêm sản phẩm" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="title">Sản phẩm đã được thêm vào</h1>

   <div class="box-container">

   <?php
   //Hiển thị danh sách sản phẩm đã thêm
      $show_products = $conn->prepare("SELECT * FROM `products`");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="price"><?= $fetch_products['price']; ?> VND</div>
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="name"><?= $fetch_products['quantity']; ?></div>
      <div class="cat"><?= $fetch_products['category']; ?></div>
      <div class="name"><?= $fetch_products['details']; ?></div>
      <div class="flex-btn">
         <!--Hiển thị thông tin sản phẩm và tùy chọn cập nhật hoặc xóa-->
         <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Cập nhật</a>
         <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Xóa sản phẩm này?');">Xóa sản phẩm</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">Sản phẩm chưa được thêm vào!!!</p>';
   }
   ?>

   </div>

</section>
<script src="js/script.js"></script>

</body>
</html>
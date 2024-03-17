function checkGuiBinhLuan() {
  /*Kiểm tra xem người dùng hiện tại đã đăng nhập chưa.
Nếu người dùng chưa đăng nhập, nó yêu cầu người dùng đăng nhập. Nếu người dùng đăng nhập, nó gọi hàm guiBinhLuan().
Nếu có lỗi xảy ra trong quá trình này, nó sẽ hiển thị thông báo lỗi.*/
  getCurrentUser(
    (user) => {
      if (user == null) {
        Swal.fire({
          title: "Xin chào!",
          text: "Bạn cần đăng nhập để binh luận",
          type: "error",
          grow: "row",
          confirmButtonText: "Đăng nhập",
          cancelButtonText: "Trở về",
          showCancelButton: true,
        }).then((result) => {
          if (result.value) {
            showTaiKhoan(true);
          }
        });
      } else {
        guiBinhLuan(user);
      }
    },
    (error) => {
      Swal.fire({
        title: "Lỗi!",
        text: "Có lỗi khi đăng đánh giá",
        type: "error",
        grow: "row",
      });
    }
  );
}

function guiBinhLuan(nguoidung) {
  var soSao = $("input[name='star']:checked").val();
  var binhLuan = $("#inpBinhLuan").val();

  if (!soSao) {
    Swal.fire({
      title: "Thiếu!",
      text: "Bạn vui lòng đánh số sao",
      type: "warning",
      grow: "row",
    });
    return;
  }

  if (!binhLuan) {
    Swal.fire({
      title: "Thiếu!",
      text: "Bạn vui lòng để lại bình luận",
      type: "warning",
      grow: "row",
    });
    $("#inpBinhLuan")[0].focus();
    return;
  }

  $.ajax({
    type: "POST",
    url: "xulydanhgia.php",
    dataType: "json",
    timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
    data: {
      request: "thembinhluan",
      masp: maProduct,
      mand: nguoidung.MaND,
      sosao: soSao,
      binhluan: binhLuan,
      thoigian: new Date().toMysqlFormat(),
    },
    success: function (data, status, xhr) {
      $("#inpBinhLuan").val("");
      refreshBinhLuan();
    },
    error: function (e) {
      console.log(e);
    },
  });
}

function refreshBinhLuan() {
  $.ajax({
    type: "POST",
    dataType: "json",
    timeout: 1500, // sau 1.5 giây mà không phản hồi thì dừng => hiện lỗi
    data: {
      request: "getbinhluan",
      masp: maProduct,
    },
    success: function (data, status, xhr) {
      var div = document.getElementsByClassName("comment-content")[0];
      div.innerHTML = "";
      for (var b of data) {
        div.innerHTML += createComment(
          b.ND.TaiKhoan,
          b.BinhLuan,
          getRateStar(b.SoSao),
          b.NgayLap
        );
      }
    },
    error: function (e) {
      console.log(e);
    },
  });
}

// =====================================================

function getRateStar(num) {
  var result = "";
  for (var i = 1; i <= 5; i++) {
    if (i <= num) {
      result += `<i class="fa fa-star"></i>`;
    } else {
      result += `<i class="fa fa-star-o"></i>`;
    }
  }
  return result;
}

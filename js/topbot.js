// Di chuyển lên đầu trang
function gotoTop() {
  if (window.jQuery) {
    jQuery("html,body").animate(
      {
        scrollTop: 0,
      },
      1000
    );
  } else {
    document.getElementsByClassName("fa fa-arrow-up")[0].scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
  }
}

$(document).ready(function() {
  $('a[href="#photoModal"]').click(function(e) {
    var cap, ext, img, size_ext, name, title, src;
    img = $(this).children("img").first();
    title = $(this).data("title");
    src = img.prop("src");
    $("#photoModal .modal-body img").prop("src", src);
    cap = $(this).children(".caption").first();
    $("#photoModal .modal-footer").html(cap.html());
    $("#photoModal .modal-header .modal-title").html(title);

  });
});
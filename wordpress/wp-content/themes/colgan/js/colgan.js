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

  $('audio').mediaelementplayer({
    features: ['playpause','current','progress','duration'],
    audioWidth: 250,
     success: function(media, domObject) {
         media.addEventListener('play', function(e) {
            _gaq.push(['_trackEvent', 'Commentary plays', 'Play', $(this).data('category')]);
          }, true);
      }
  });
});
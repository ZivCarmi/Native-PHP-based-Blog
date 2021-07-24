$('#back-to-top').click(function () {
  $('body,html').animate({
    scrollTop: 0
  }, 600);
  return false;
})

$('.btn-delete-post').on('click', function () {
  if (confirm('Are you sure you want to delete your post?')) {
    return true;
  } else {
    return false;
  }
})

$('input[type="file"]').change(function (e) {
  var fileName = e.target.files[0].name;
  $('.custom-file-label').html(fileName);
});
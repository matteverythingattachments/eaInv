function openForm(id) {
  document.getElementById(id).style.display = 'block';
}

function closeForm(id) {
  document.getElementById(id).style.display = 'none';
}
$(document).ready(function () {
  $(window).scroll(function () {
    if ($(this).scrollTop() > 500) {
      $('#scroll').fadeIn();
    } else {
      $('#scroll').fadeOut();
    }
  });
  $('#scroll').click(function () {
    $('html, body').animate(
      {
        scrollTop: 0,
      },
      300
    );
    return false;
  });
});

function printError(Msg) {
  hintMsg.innerHTML = Msg;
}

function validateForm() {
  var name = document.forms['newCall']['name'];
  //var email = document.forms["newCall"]["email"];
  var phone = document.forms['newCall']['phone'];
  //var product = document.forms["newCall"]["product"];
  var what = document.forms['newCall']['rCall'];
  //var tractor = document.forms["newCall"]["tractor"];
  var inherit = document.forms['newCall']['inherit'];

  if (name.value == '') {
    printError("Please enter the caller's name.");
    name.focus();
    return false;
  }

  if (phone.value == '') {
    printError('Please enter a telephone number.');
    phone.focus();
    return false;
  }

  if (what.value == '') {
    printError('Add some detail about this call.');
    what.focus();
    return false;
  }
  if (inherit.selectedIndex < 1) {
    printError('Please select a Category.');
    inherit.focus();
    return false;
  }

  return true;
}

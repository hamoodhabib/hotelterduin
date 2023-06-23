// Client-side validation to prevent selecting invalid check-out dates
document.addEventListener("DOMContentLoaded", function () {
  var checkInDateInput = document.querySelector('input[name="check_in_date"]');
  var checkOutDateInput = document.querySelector(
    'input[name="check_out_date"]'
  );

  checkInDateInput.addEventListener("input", function () {
    var checkInDate = new Date(this.value);
    var checkOutDate = new Date(checkOutDateInput.value);

    if (checkOutDate <= checkInDate) {
      checkOutDateInput.value = "";
    }

    checkOutDateInput.min = formatDate(checkInDate, 1);
  });

  function formatDate(date, offsetDays) {
    date.setDate(date.getDate() + offsetDays);
    var year = date.getFullYear();
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    var day = ("0" + date.getDate()).slice(-2);
    return year + "-" + month + "-" + day;
  }
});

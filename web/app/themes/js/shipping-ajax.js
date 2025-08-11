jQuery(document).ready(function ($) {
  function updatePrice() {
    var country_id = $("#country_id").val();
    var weight = parseFloat($("#weight").val());

    if (country_id && weight > 0) {
      $.post(
        shipping_ajax_obj.ajax_url,
        {
          action: "get_price_per_kg",
          country_id: country_id,
        },
        function (response) {
          if (response.success) {
            var pricePerKg = response.data.price_per_kg;
            var totalPrice = (pricePerKg * weight).toFixed(2);
            $("#total_price").text(totalPrice + " ريال");
          } else {
            $("#total_price").text("سعر غير متوفر");
          }
        }
      );
    } else {
      $("#total_price").text("");
    }
  }

  $("#weight, #country_id").on("input change", updatePrice);
});

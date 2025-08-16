jQuery(document).ready(function ($) {
  $("#country-select").on("change", function () {
    var countryId = $(this).val();
    var weight = $("#weight-input").val();

    if (countryId && weight) {
      calculateShipping(countryId, weight);
    }
  });

  $("#weight-input").on("input", function () {
    var weight = $(this).val();
    var countryId = $("#country-select").val();

    if (countryId && weight) {
      calculateShipping(countryId, weight);
    }
  });

  function calculateShipping(countryId, weight) {
    $.ajax({
      url: marineShipping.ajax_url,
      type: "POST",
      data: {
        action: "get_price_per_kg",
        nonce: marineShipping.nonce,
        country_id: countryId,
      },
      success: function (response) {
        if (response.success) {
          var pricePerKg = response.data.price_per_kg;
          var totalPrice = weight * pricePerKg;
          $("#total-price").text(totalPrice.toFixed(2) + " $");
        } else {
          $("#total-price").text(response.data);
        }
      },
    });
  }
});

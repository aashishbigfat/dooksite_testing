$(document).on("focus", "[tts-car-from-city]", function(event) {

    $(this).autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function(request, response) {
            $.ajax({
                url: site_url + "car/car-city",
                dataType: "json",
                cache: false,
                data: {
                    term: request.term,
                },
                success: function(data) {
                    response(data);
                },
            });
        },
        open: function() {
            $(".ui-autocomplete").addClass("tts-autocomplete");
        },
        select: function(event, ui) {
            $('input[name="from_city_id"]').val(ui.item.id);
            $('input[name="city_id"]').val(ui.item.id);
            $("[tts-car-to-city]").focus();
        },
        change: function(event, ui) {
            $(this).val(ui.item ? ui.item.value : "");
        },
    });
});

$(document).on("focus", "[tts-car-to-city]", function(event) {
    $(this).autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function(request, response) {
            $.ajax({
                url: site_url + "car/car-city",
                dataType: "json",
                cache: false,
                data: {
                    term: request.term,
                },
                success: function(data) {
                    response(data);
                },
            });
        },
        open: function() {
            $(".ui-autocomplete").addClass("tts-autocomplete");
        },
        select: function(event, ui) {
            $('input[name="to_city_id"]').val(ui.item.id);
            $("[car-depart-date]").focus();
        },
        change: function(event, ui) {
            $(this).val(ui.item ? ui.item.value : "");
        },
    });
});

$(document).on(
    "click",
    "[tts-car-from-city],[tts-car-to-city]",
    function(event) {
        setTimeout(() => {
            event.target.select();
            $(event.target).autocomplete("search", " ");
        }, 100);
    }
);

$(document).on("keydown", "[tts-car-airport]", function(event) {
    $(this).autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function(request, response) {
            $.ajax({
                url: site_url + "car/car-airport",
                dataType: "json",
                cache: false,
                data: {
                    term: request.term,
                },
                success: function(data) {
                    response(data);
                },
            });
        },
        open: function() {
            $(".ui-autocomplete").addClass("tts-autocomplete");
        },
        select: function(event, ui) {
            $('input[name="airport_id"]').val(ui.item.id);
        },
        change: function(event, ui) {
            $(this).val(ui.item ? ui.item.value : "");
        },
    });
});

$(document).on("focus", "[car-depart-date]", function(event) {
    $("[car-depart-date]").datepicker({
        dateFormat: DateFormat,
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
        beforeShow: function(input, inst) {
            $(".ui-datepicker").addClass("calendarOuter");
        },
    });
});
$(document).on("focus", "[car-return-date]", function(event) {
    $("[car-return-date]").datepicker({
        dateFormat: DateFormat,
        changeMonth: false,
        numberOfMonths: 2,
        minDate: "0",
        maxDate: "+12m",
        beforeShow: function() {
            $(".ui-datepicker").addClass("calendarOuter");
            var dateObject = $("[car-depart-date]").datepicker("getDate");

            if (dateObject) {
                dateObject.setDate(dateObject.getDate() + 1);
                var dateString = $.datepicker.formatDate("dd-mm-yy", dateObject);
                var newdate = dateString.split("-").reverse().join("-");
                newdate = new Date(newdate);

                $(this).datepicker("option", "minDate", newdate);
            } else {
                $(this).datepicker("option", "minDate", new Date());
            }
        },
    });
});

// $(document).on("change", 'input[name="trip_type"]', function () {
//   $("[car-return-date]").val("");

//   $(".error-message").remove();
//   const tripType = $(this).val();
//   const formElements = $(
//     "#pickup_type, #airport, #from_city, #to_city, #departure_date, #return_date, #pickup_time, #drop_time"
//   );

//   formElements.addClass("d-none");
//   $(
//     "#from_city, #to_city, #departure_date, #pickup_time, #return_date, #drop_time"
//   )
//     .removeClass("")
//     .addClass("");

//   $("input[name='car_trip_type']").val(tripType);
//   switch (tripType) {
//     case "outstation-one-way":
//       $("#from_city, #to_city, #departure_date, #pickup_time").removeClass(
//         "d-none"
//       );
//       break;
//     case "outstation-round-trip":
//       $(
//         "#from_city, #to_city, #departure_date, #return_date, #pickup_time, #drop_time"
//       ).removeClass("d-none");
//       $(
//         "#from_city, #to_city, #departure_date, #return_date, #pickup_time, #drop_time"
//       )
//         .removeClass("")
//         .addClass("");
//       break;
//     case "airport-transfers":
//       $(
//         "#pickup_type, #airport, #to_city, #departure_date, #pickup_time"
//       ).removeClass("d-none");
//       $("#pickup_type, #departure_date, #pickup_time")
//         .removeClass("")
//         .addClass("");
//       break;
//     case "city-tour":
//       $("#from_city, #departure_date, #pickup_time").removeClass("d-none");
//       $("#from_city, #departure_date, #pickup_time")
//         .removeClass("")
//         .addClass("");
//       break;
//   }
// });

$(document).on("change", 'input[name="trip_type"]', function() {
    console.log("djhdk");

    const tripType = $(this).val();
    $(".error-message").remove();

    switch (tripType) {
        case "outstation-one-way":
            $("#outstation_trip").val('outstation-one-way');
            $("#outstation").show();
            $("#airport_transfers, #city_tour, #return_date, #drop_time").hide();
            break;
        case "outstation-round-trip":
            $("#outstation_trip").val('outstation-round-trip');
            $("#outstation,#return_date, #drop_time").show();
            $("#airport_transfers, #city_tour").hide();
            break;
        case "airport-transfers":
            $("#airport_transfers").show();
            $("#outstation, #city_tour").hide();
            break;
        case "city-tour":
            $("#city_tour").show();
            $("#outstation, #airport_transfers").hide();
            break;
    }
});

$(document).on("change", "[tts-car-airport-pickup-type]", function() {
    const pickupType = $(this).val();
    const airportDiv = $("#airport");
    const cityDiv = $("#city");

    if (pickupType === "airport_pickup") {
        $('#airport_icon').hide();
        $('#city_icon').show();
        airportDiv.insertBefore(cityDiv);
    } else if (pickupType === "airport_drop") {
        $('#airport_icon').show();
        $('#city_icon').hide();
        cityDiv.insertBefore(airportDiv);
    }
});

/* $(document).on("click", "[tts-car-return]", function (event) {
  $("input[name='trip_type'][value='outstation-round-trip']").prop(
    "checked",
    true
  );
  $("[tts-car-one-round]").removeClass("hide");
  $("[tts-car-airport-transfer]").addClass("hide");
  $("[tts-pick-time-class]").removeClass("col-md-2");
  $("[tts-pick-time-class]").addClass("col-md-1");
  $("[tts-drop-time-class]").removeClass("hide");
}); */

function checkCarSearchValidation(btn) {
    setTimeout(function() {
        $(".error-message").remove();

        var form = $(btn).closest("form");
        if (form.find(".error").length == 0) {
            $("[data-message]").removeClass().html("");
            var buttontxt;
            buttontxt = $("button[type=submit]", form).text();
            $("button[type=submit]", form).attr("disabled", true).html("Wait...");
            $("span.error-message", form).replaceWith("");

            $.ajax({
                url: site_url + "car/car-check-search-validation",
                dataType: "json",
                type: "POST",
                cache: false,
                data: form.serialize(),
                success: function(resp) {
                    $("button[type=submit]", form)
                        .attr("disabled", false)
                        .html(buttontxt);
                    if (resp.StatusCode == 1) {
                        var count = Object.keys(resp.ErrorMessage).length;
                        if (count > 0) {
                            $.each(resp.ErrorMessage, function(key, val) {
                                $('[name="' + key + '"]', form).after(
                                    '<span class="error-message">' + val + "</span>"
                                );
                            });
                        } else {
                            alert("Unexpected error! Try again.");
                        }
                    } else if (resp.StatusCode == 0) {
                        form.submit();
                    } else {
                        alert("Unexpected error! Try again.");
                    }
                },
                error: function(resp) {
                    $("button[type=submit]", form)
                        .attr("disabled", false)
                        .html(buttontxt);
                    alert("Unexpected error! Try again.");
                },
            });
        }
    }, 100);
    return false;
}

function checkCarSearchValidationAirport() {
    setTimeout(function() {
        $(".error-message").remove();
        var form = $("[name  =  'car-form-airport']");
        if ($("[name  =  'car-form-airport']").find(".error").length == 0) {
            $("[data-message]").removeClass().html("");
            var buttontxt;
            buttontxt = $("button[type=submit]", form).text();
            $("button[type=submit]", form).attr("disabled", true).html("Wait...");
            $("span.error-message", form).replaceWith("");

            $.ajax({
                url: site_url + "car/car-check-search-validation",
                dataType: "json",
                type: "POST",
                cache: false,
                data: form.serialize(),
                success: function(resp) {
                    $("button[type=submit]", form)
                        .attr("disabled", false)
                        .html(buttontxt);
                    if (resp.StatusCode == 1) {
                        var count = Object.keys(resp.ErrorMessage).length;
                        if (count > 0) {
                            $.each(resp.ErrorMessage, function(key, val) {
                                $('[name="' + key + '"]', form).after(
                                    '<span class="error-message">' + val + "</span>"
                                );
                            });
                        } else {
                            alert("Unexpected error! Try again.");
                        }
                    } else if (resp.StatusCode == 0) {
                        form.submit();
                    } else {
                        alert("Unexpected error! Try again.");
                    }
                },
                error: function(resp) {
                    $("button[type=submit]", form)
                        .attr("disabled", false)
                        .html(buttontxt);
                    alert("Unexpected error! Try again.");
                },
            });
        }
    }, 100);
    return false;
}



$(document).on("click", "[swape-city]", function(event) {
    var from_val = $("[tts-car-from-city]").val();
    var to_val = $("[tts-car-to-city]").val();

    var from_id = $("#from-city-id").val();
    var to_id = $("#to-city-id").val();

    if (from_id && to_id) {
        var originval = $("[tts-car-from-city]").next().val();
        var origintxt = $("[tts-car-from-city]").next().next().text();

        var destinationval = $("[tts-car-to-city]").next().val();
        var destinationtxt = $("[tts-car-to-city]").next().next().text();

        $("[tts-car-from-city]").val(to_val);
        $("[tts-car-to-city]").val(from_val);

        $("#from-city-id").val(to_id);
        $("#to-city-id").val(from_id);
        $("[tts-car-from-city]").next().val(destinationval);
        $("[tts-car-from-city]").next().next().text(destinationtxt);

        $("[tts-car-to-city]").next().val(originval);
        $("[tts-car-to-city]").next().next().text(origintxt);
    } else {
        $("[tts-car-from-city]").val("");
        $("[tts-car-to-city]").val("");
        $("#from-city-id").val("");
        $("#to-city-id").val("");

        $("[tts-car-from-city]").next().val("");
        $("[tts-car-from-city]").next().next().text("");

        $("[tts-car-to-city]").next().val("");
        $("[tts-car-to-city]").next().next().text("");
    }
});

$(document).on("click", "[tts-car-airport-swape]", function() {
    const pickupTypeField = $("[tts-car-airport-pickup-type]");
    if (pickupTypeField.length === 0) return; // Ensure the element exists

    let pickupType = pickupTypeField.val();
    const airportDiv = $("#airport");
    const cityDiv = $("#city");

    if (airportDiv.length === 0 || cityDiv.length === 0) return;

    // Toggle pickup type
    pickupType = (pickupType === "airport_pickup") ? "airport_drop" : "airport_pickup";
    pickupTypeField.val(pickupType);

    // Swap elements and toggle icons
    if (pickupType === "airport_pickup") {
        if (airportDiv.next("#city").length === 0) {
            cityDiv.insertBefore(airportDiv);
        }
        $('#airport_icon').hide();
        $('#city_icon').show();
    } else {
        if (cityDiv.next("#airport").length === 0) {
            airportDiv.insertBefore(cityDiv);
        }
        $('#airport_icon').show();
        $('#city_icon').hide();
    }
});


$(document).ready(function() {
    if (window.performance && window.performance.navigation.type === 2) {
        setTimeout(() => {
            if ($("[tts-car-from-city], [tts-car-to-city], #from-city-id, #to-city-id").length) {
                $("[tts-car-from-city], [tts-car-to-city], #from-city-id, #to-city-id").val("");
            }

            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);

            const formattedDate = tomorrow.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: '2-digit'
            });

            if ($("[car-depart-date]").length) {
                $("[car-depart-date]").val(formattedDate).trigger("change");
            }
        }, 50);
    }
});
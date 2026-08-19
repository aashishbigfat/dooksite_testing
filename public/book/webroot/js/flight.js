var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
var AirlinePreferredFlights=[];
$(document).on("focus", "[tts-flight-origin]", function (event) {
    $(event.target).autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'flight/get-airports',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        open: function () {
            var top =$(event.target).offset().top + $(event.target).outerHeight()-20;
            $(".ui-autocomplete").addClass('tts-autocomplete').css("top", top);
        },
        select: function (event, ui) {    
            
           

            var city=ui.item.city;
            var airportcode=ui.item.airport_code;
            var airportname=ui.item.airport_name;
            ui.item.value=city;

            $(event.target).val(city);
            $(event.target).next().val(ui.item.label);
           // $(event.target).next().next().text('['+airportcode+'] '+airportname);
            $(event.target).siblings(".flight_text_p").text('['+airportcode+'] '+airportname);

            
            if ($(event.target).attr('data-key') === undefined) {
                $("[tts-flight-destination]").trigger('focus');
            } else {
                setTimeout(() => {
                    var datakey = $(event.target).attr('data-key');
                    var placeholder = $(event.target).attr('placeholder');
                    if (placeholder == 'Origin') {
                        $("input[name='search_data[" + datakey + "][destination]']").trigger('focus');
                    }
                }, 10);

            }
        },

        create: function () {
            $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                var cityname = item.city;
                var airportcode = item.airport_code;
                var airportname = item.airport_name;
                var country_code = item.country_code;
                var countryName = item.countryName;

                return $("<li>")
                    .data("ui-autocomplete-item", item)
                    .append(
                        "<a>" +
                        "<div class='dest_left'>" +
                        "<i class='fa fa-plane'></i>" +
                        "<samp class='city'>" + cityname + "</samp>" +
                        "<samp class='airpotcode'>&nbsp;(" + airportname + ")&nbsp;</samp>" +
                        "</div>" +
                        "<div><samp class='aircode'>[" + airportcode + "]</samp>" +
                        "<i class='flag " + country_code.toLowerCase() + "'></i></div>" +
                        "</a>"
                    )
                    .appendTo(ul);
            };
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        },
        close: function( event, ui ) {
          if($(event.target).val()=='')
          {
            $(event.target).next().val('');
            $(event.target).next().next().text('');
          }
        }
    });
});

$(document).on("focus", "[tts-flight-destination]", function (event) {
    $(event.target).autocomplete({
        minLength: 0,
        maxResults: 15,
        source: function (request, response) {
            $.ajax({
                url: site_url + 'flight/get-airports',
                dataType: "json",
                cache: false,
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        open: function () {
            var top =$(event.target).offset().top + $(event.target).outerHeight()-20;
            $(".ui-autocomplete").addClass('tts-autocomplete').css("top", top);
        },
        select: function (event, ui) {

            var city=ui.item.city;
            var airportcode=ui.item.airport_code;
            var airportname=ui.item.airport_name;
            ui.item.value=city;

            $(event.target).val(city);
            $(event.target).next().val(ui.item.label);
           // $(event.target).next().next().text('['+airportcode+'] '+airportname);
           $(event.target).siblings(".flight_text_p").text('['+airportcode+'] '+airportname);

            if ($(event.target).attr('data-key') === undefined) {
                $("[flight-departure-date]").trigger('focus');

            } else {
                setTimeout(() => {
                    var datakey = $(event.target).attr('data-key');
                    var placeholder = $(event.target).attr('placeholder');
                    if (placeholder == 'Destination') {
   
                        var newdatakey = parseInt(datakey) + parseInt(1);
                        $("input[name='search_data[" + newdatakey + "][origin]']").prev().val(city);
                        $("input[name='search_data[" + newdatakey + "][origin]']").val(ui.item.label);
                        $("input[name='search_data[" + newdatakey + "][origin]']").next().text('['+airportcode+'] '+airportname);


                        $("input[name='search_data[" + datakey + "][departdate]']").trigger('focus');
                    }
                }, 20);
            }
        },

        create: function () {
            $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
                var cityname = item.city;
                var airportcode = item.airport_code;
                var airportname = item.airport_name;
                var country_code = item.country_code;
                var countryName = item.countryName;

                return $("<li>")
                    .data("ui-autocomplete-item", item)
                    .append(
                        "<a>" +
                        "<div class='dest_left'>" +
                        "<i class='fa fa-plane'></i>" +
                        "<samp class='city'>" + cityname + "</samp>" +
                        "<samp class='airpotcode'>&nbsp;(" + airportname + ")&nbsp;</samp>" +
                        "</div>" +
                        "<div><samp class='aircode'>[" + airportcode + "]</samp>" +
                        "<i class='flag " + country_code.toLowerCase() + "'></i></div>" +
                        "</a>"
                    )
                    .appendTo(ul);
            };
        },
        change: function (event, ui) {
            $(this).val((ui.item ? ui.item.value : ""));
        },
        close: function( event, ui ) {
            if($(event.target).val()=='')
            {
              $(event.target).next().val('');
              $(event.target).next().next().text('');
            }
          }
    });
});
$(document).on("focus", "[flight-departure-date]", function (event) {
    $(event.target).datepicker({
        dateFormat: DateFormat,
        changeMonth: false,
        numberOfMonths: 1,
        minDate: "0",
        maxDate: "+12m",
        beforeShow: function (input, inst) {
            $(".ui-datepicker").addClass('calendarOuter');
            if ($(input).attr('data-key') != undefined) {
                var datakey = parseInt($(input).attr('data-key'));
                if (datakey != 0) {
                    var newdatakey = parseInt(datakey) - parseInt(1);
                    var previousdate = $("input[name='search_data[" + newdatakey + "][departdate]']").val();
                    var newdate = new Date(previousdate);
                    $(this).datepicker("option", "minDate", newdate);
                }
            }
        },
        onClose: function (selectedDate,inst) {
            var d = new Date(selectedDate);
            var dayName = days[d.getDay()];
           // $(event.target).next().text(dayName);

            if($(event.target).siblings(".flight_text_p"))
            {
                $(event.target).siblings(".flight_text_p").text(dayName);
            }
    

            if ($(event.target).attr('data-key') === undefined) {
                const date = new Date(selectedDate);
                var newdate = $.datepicker.formatDate(DateFormat, new Date(date.setDate(date.getDate() + 1)))
        
                var flightjtype = $("[name='journeytype']").val();
                if (flightjtype == "Roundtrip") {
                    $("[flight-return-date]").val(newdate); 
                    $("[flight-return-date]").datepicker("option", "minDate", newdate).focus().select();
                    var d = new Date(newdate);
                    var dayName = days[d.getDay()];
                    $("[flight-return-date]").next().text(dayName);
                }
            } else {
                setTimeout(() => {
                    var datakey = $(event.target).attr('data-key');
                    var placeholder = $(event.target).attr('placeholder');
                    if (placeholder == 'Depart Date') {
                        var newdatakey = parseInt(datakey) + parseInt(1);
                        $("input[name='search_data[" + newdatakey + "][destination]']").trigger('focus');
                    }
                }, 10);
            }
        }
    });
});
$(document).on("focus", "[flight-return-date]", function (event) {
    $(event.target).datepicker({
        dateFormat: DateFormat,
        changeMonth: false,
        numberOfMonths: 1,
        minDate: "0",
        maxDate: "+12m",
        beforeShow: function () {
            var dateString = $('[flight-departure-date]').val();
            var newdate = dateString.split(" ").join("-");
            var newdate = new Date(newdate);
           $(".ui-datepicker").addClass('calendarOuter');
            $(this).datepicker("option", "minDate", newdate);
        },
        onClose: function (selectedDate) {
            var d = new Date(selectedDate);
            var dayName = days[d.getDay()];
            //$(event.target).next().text(dayName);

            if($(event.target).siblings(".flight_text_p"))
            {
                $(event.target).siblings(".flight_text_p").text(dayName);
            }
            

            $("[flight-departure-date]").datepicker("option", selectedDate);
        }
    });

});

$(document).on("click", "[tts-flight-origin],[tts-flight-destination]", function (event) {

    setTimeout(() => {
        event.target.select();
        $(event.target).autocomplete("search", " ");
    }, 50);
});

/*flight oneway and roundtrip validation*/
function checkflightJourneytype(value) {
    var datavalidation = 'data-validation';
    var addrequired = 'required';
    var addreadonly = 'readonly';
    if (value == "Oneway") {
        $("[flight-return-date]").addClass('flight-return-date-disable')
            .removeAttr(datavalidation, addrequired).attr(addreadonly);
        $("[name='journeytype']").val(value);
        $("[flight-multicity-form]").hide();
        $("[flight-oneway-roundtrip-form]").show();
        var departureDate = $('[flight-departure-date]').val();
        $("[flight-return-date]").val('');

        $("[flight-return-date]").siblings('.flight_text_p').text('Book a round trip to save more');

    } else if (value == "Roundtrip") {
        $("[flight-return-date]").removeClass('flight-return-date-disable').attr(
            datavalidation, addrequired);
        $("[name='journeytype']").val(value);
        $("[flight-multicity-form]").hide();
        $("[flight-oneway-roundtrip-form]").show();

      

        if($("[flight-return-date]").val()=='')
        {
            var dateString = $('[flight-departure-date]').val();
            var newdate = dateString.split(" ").join("-");
            var newdate = new Date(newdate);
            newdate.setDate(newdate.getDate() + 1);
            $("[flight-return-date]").datepicker("option", "minDate", newdate);

            var fnewdate=$.datepicker.formatDate(DateFormat, new Date(newdate));
            var d = new Date(fnewdate);
            var dayName = days[d.getDay()];
            $("[flight-return-date]").val(fnewdate);
         
        }
        if($("[flight-return-date]").val()!='')
        {
            var dateString = $('[flight-return-date]').val();
            var newdate = dateString.split(" ").join("-");
            var newdate = new Date(newdate);
            var fnewdate=$.datepicker.formatDate(DateFormat, new Date(newdate));
            var d = new Date(fnewdate);
            var dayName = days[d.getDay()];
        }
        $("[flight-return-date]").siblings('.flight_text_p').text(dayName);


    } else if (value == "Multicity") {
        $("[name='journeytype']").val(value);
        $("[flight-multicity-form]").show();
        $("[flight-oneway-roundtrip-form]").hide();

        $("[flight-return-date]").next().removeAttr("style").text('');
    } else {
        $("[flight-return-date]").addClass('flight-return-date-disable')
            .removeAttr(datavalidation, addrequired).attr(addreadonly);
        $("[name='journeytype']").val(value);
        $("[flight-multicity-form]").hide();
        $("[flight-oneway-roundtrip-form]").show();
        var departureDate = $('[flight-departure-date]').val();
        $("[flight-return-date]").val('');

    }
}

function selectroundtripDate(value) {
    $("input[type=radio][value=Roundtrip]").prop("checked", true);
    checkflightJourneytype(value);
    setTimeout(() => {
    $('[flight-return-date]').trigger('focus');
    }, 50);

}

$(document).on("click", "[swape-city]", function (event) {
    var from_val = $("[tts-flight-origin]").val();
    var to_val = $("[tts-flight-destination]").val();
    if (from_val !== "" && to_val !== "") {

        var originval=$("[tts-flight-origin]").next().val();
        var origintxt=$("[tts-flight-origin]").next().next().text();
     
        var destinationval=$("[tts-flight-destination]").next().val();
        var destinationtxt=$("[tts-flight-destination]").next().next().text();
        
        $("[tts-flight-origin]").val(to_val);
        $("[tts-flight-origin]").next().val(destinationval);
        $("[tts-flight-origin]").next().next().text(destinationtxt);

        $("[tts-flight-destination]").val(from_val);
        $("[tts-flight-destination]").next().val(originval);
        $("[tts-flight-destination]").next().next().text(origintxt);
    }
});

function changeCabinclass(cabinclassValue, cabinclassshowtextelement) {
    $("[" + cabinclassshowtextelement + "]").html(cabinclassValue);
}

$(document).on("click", "[data-adult-next]", function (event) {
    var child_val = $("[child-input]").val();
    var adt_val = $("[adult-input]").val();
    var infant_val = $("[infant-input]").val();
    var adt_child_inf_to = Number(adt_val) + Number(child_val) + Number(infant_val);
    if (adt_child_inf_to < 9) {
        adt_val++;
        $("[data-adult-count]").text(adt_val);
        $("[adult-input]").val(adt_val);
    }
    travelpaxcount();
});

$(document).on("click", "[data-adult-pre]", function (event) {
    var adt_val = $("[adult-input]").val();
    var infant_val = $("[infant-input]").val();
    if (adt_val > 1) {
        adt_val--;
        $("[data-adult-count]").text(adt_val);
        $("[adult-input]").val(adt_val);
        if (infant_val > adt_val) {
            $("[infant-input]").val(adt_val);
            $("[data-infant-count]").text(adt_val);
        }
    }
    travelpaxcount();
});

$(document).on("click", "[data-child-next]", function (event) {
    var child_val = $("[child-input]").val();
    var adt_val = $("[adult-input]").val();
    var infant_val = $("[infant-input]").val();
    var adt_child_inf_to = Number(adt_val) + Number(child_val) + Number(infant_val);
    var resultFareType = $("[resultfaretype]").val();
    if (adt_child_inf_to < 9 && ['RegularFare','StudentFare'].includes(resultFareType)) {
        child_val++;
        $("[data-child-count]").text(child_val);
        $("[child-input]").val(child_val);
    }
    travelpaxcount();
});

$(document).on("click", "[data-child-pre]", function (event) {
    var child_val = $("[child-input]").val();
    var resultFareType = $("[resultfaretype]").val();
    if (child_val > 0 && ['RegularFare','StudentFare'].includes(resultFareType)) {
        child_val--;
        $("[data-child-count]").text(child_val);
        $("[child-input]").val(child_val);
    }
    travelpaxcount();
});

$(document).on("click", "[data-infant-next]", function (event) {
    var child_val = $("[child-input]").val();
    var adt_val = $("[adult-input]").val();
    var infant_val = $("[infant-input]").val();
    var resultFareType = $("[resultfaretype]").val();
    var adt_child_inf_to = Number(adt_val) + Number(child_val) + Number(infant_val);
    if (adt_child_inf_to < 9 && infant_val < adt_val && resultFareType == "RegularFare") {
        infant_val++;
        $("[data-infant-count]").text(infant_val);
        $("[infant-input]").val(infant_val);
    }
    travelpaxcount()
});

$(document).on("click", "[data-infant-pre]", function (event) {
    var infant_val = $("[infant-input]").val();
    var resultFareType = $("[resultfaretype]").val();
    if (infant_val > 0 && resultFareType == "RegularFare") {
        infant_val--;
        $("[data-infant-count]").text(infant_val);
        $("[infant-input]").val(infant_val);
    }
    travelpaxcount()
});

function travelpaxcount() {
    var adult = 0;
    var child = 0;
    var infant = 0;
    var adult = parseInt(document.forms["flight-form"]["adults"].value);
    var child = parseInt(document.forms["flight-form"]["child"].value);
    var infant = parseInt(document.forms["flight-form"]["infant"].value);

    $("[data-total-pax]").html(adult + child + infant);

}

function checkFlightSearchValidation(formName) {

    setTimeout(function () {
            var form = $("[name  =  '" + formName + "']");
            if ($("[name  =    '" + formName + "']").find('.error').length == 0) {
                $("[data-message]").removeClass().html("");
                var buttontxt;
                buttontxt = $("button[type=submit]", form).text();
                $("button[type=submit]", form).attr('disabled', true).html('Wait...');
                $("span.error-message", form).replaceWith("");

                $.ajax({
                    url: site_url + 'flight/flight-check-search-validtion',
                    dataType: "json",
                    type: "POST",
                    cache: false,
                    data: form.serialize(),
                    success: function (resp) {
                        $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
                        if (resp.StatusCode == 1) {
                            var count = Object.keys(resp.ErrorMessage).length;
                            if (count > 0) {
                                $.each(resp.ErrorMessage, function (key, val) {
                                    var finalkey = key.split(".");
                                    if (finalkey[2]) {
                                        key = finalkey[0] + "[" + finalkey[1] + "]" + "[" + finalkey[2] + "]";
                                        $('[name="' + key + '"]', form).after('<span class="error-message">' + val + '</span>');
                                    } else {
                                        $('[name="' + key + '"]', form).after('<span class="error-message">' + val + '</span>');
                                    }

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
                    error: function (resp) {
                        $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
                        alert("Unexpected error! Try again.");
                    }
                });
            }
        }
        , 100);
    return false;
}

$(document).on("focus", "[flight_pass_issue]", function (event) {
    $("[flight_pass_issue]").datepicker({
        defaultDate: "",
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        maxDate: "-1D",
        onClose: function (selectedDate) {
          /*   $("[flight_pass_expiry]").focus(); */
        }
    });
});
$(document).on("focus", "[flight_pass_expiry]", function (event) {
    $("[flight_pass_expiry]").datepicker({
        defaultDate: "",
        minDate: 0,
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        beforeShow: function () {
            var flightpassportMinDate = $("[flightpassportMinDate]").val();
            var flightpassportMinDate = new Date(flightpassportMinDate);
            $(this).datepicker("option", "minDate", flightpassportMinDate);
        }
    });
});
$(document).on("focus", "[adult_dob_date]", function (event) {
    $("[adult_dob_date]").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: '-100y:c+nn',
        maxDate: "-12Y",
        numberOfMonths: 1,
        beforeShow: function (input, inst) {
            $(".ui-datepicker").addClass('calendarOuter');
        }
    });
});
$(document).on("focus", "[child_dob_date]", function (event) {
    $("[child_dob_date]").datepicker({
        dateFormat: "dd-mm-yy",
        minDate: "-12Y",
        changeMonth: true,
        changeYear: true,
        maxDate: "-2Y",
        numberOfMonths: 1,
         beforeShow: function (input, inst) {
            $(".ui-datepicker").addClass('calendarOuter');
        }
        
    });
});
$(document).on("focus", "[infant_dob_date]", function (event) {
    $("[infant_dob_date]").datepicker({
        dateFormat: "dd-mm-yy",
        minDate: "-2Y",
        changeMonth: true,
        changeYear: true,
        maxDate: "+0D",
        numberOfMonths: 1,
         beforeShow: function (input, inst) {
            $(".ui-datepicker").addClass('calendarOuter');
        }
    });
});

function getFareRule(searchTokeId, seletedFare) {

    var myModal = new bootstrap.Modal(document.getElementById('FareRulesModel'));
    myModal.show();
    $("[fareRuleDeatailLoading]").removeClass('d-none');
    $("[fareruleData]").html('');
    $.ajax({
        url: site_url + 'flight/fare-rule',
        dataType: "json",
        type: "POST",
        cache: false,
        data: {
            token: searchTokeId,
            FareId: seletedFare
        },
        success: function (resp) {
            if (!($("[fareRuleDeatailLoading]").hasClass("d-none"))) {
                $("[fareRuleDeatailLoading]").addClass('d-none');
            }
            if (resp.Error.ErrorCode == 0) {
                if (resp.Result.length > 0) {
                    var farerule = "";
                    $.each(resp.Result, function (key, val) {
                        farerule = farerule + '<div class="col-md-12"><button class="ars-activelist fare-rules-tabs">' + val.Origin + ' - ' + val.Destination + '</button></div>';
                        farerule = farerule + '<div class="col-md-12">' + val.FareRuleDetail + '</div>';
                    });
                    $("[fareruleData]").html(farerule);
                }
            } else {
                $("[fareruleData]").html('<div class  =  "col-md-12">' + resp.Error.ErrorMessage + "</div>");
            }
        },
        error: function (resp) {
            myModal.hide();
            if (!($("[fareRuleDeatailLoading]").hasClass("d-none"))) {
                $("[fareRuleDeatailLoading]").addClass('d-none');
            }
            alert("Unexpected error! Try again.");
        }
    });

}

function showHidefareRule(key, buttonkey = "") {
    let showHidefareRule = document.querySelector('[' + key + ']');
    if (showHidefareRule.classList.contains('d-none')) {
        showHidefareRule.classList.remove('d-none');
        if (buttonkey != "") {
            let viewFareRuleButton = document.querySelector('[' + buttonkey + ']' + ' i');
            if (viewFareRuleButton.classList.contains('fa-plus')) {
                viewFareRuleButton.classList.remove('fa-plus');
                viewFareRuleButton.classList.add('fa-minus');
            }
        }
    } else {
        showHidefareRule.classList.add('d-none');
        if (buttonkey != "") {
            let viewFareRuleButton = document.querySelector('[' + buttonkey + ']' + ' i');
            if (viewFareRuleButton.classList.contains('fa-minus')) {
                viewFareRuleButton.classList.remove('fa-minus');
                viewFareRuleButton.classList.add('fa-plus');
            }
        }
    }
}


$(document).on("click", "[add-mult-city]", function (event) {

    var counter = $("[multicity-addmore]").children().length;
    if (counter == 3) {
        $(this).hide();
    }

    var html = '<div class="row g-0 align-items-center border-top-0 flight_search_border" data-journey-key="' + counter + '">'
        + '<div class="col-lg-3 col-md-6 col-6 p0">'
        + '<div class="position-relative">'
        + '<label class="form-label">From Airport</label>'
        + '<input type="text" class="form-control tts__input__input tts__input__input1" placeholder="Origin" tts-flight-origin="true" data-validation="required" data-validation-error-msg="Please select from airport" data-key="' + counter + '">'
        +' <input type="hidden" name="search_data[' + counter + '][origin]" value="">'
        +'<div class="flight_text_p"></div>'
        + '</div>'
        + '</div>'
        + '<div class="col-lg-3 col-md-6 col-6 p0">'
        + '<div class="position-relative">'
        + '<label class="form-label">To Airport</label>'
        + '<input type="text" class="form-control  tts__input__input tts__input__input1 pl-md-4"  placeholder="Destination" tts-flight-destination="true" data-validation="required" data-validation-error-msg="Please select to airport" data-key="' + counter + '">'
        +' <input type="hidden" name="search_data[' + counter + '][destination]" value="">'
        +'<div class="flight_text_p"></div>'
        + '</div>'
        + '</div>'
        + '<div class="col-lg-2 col-md-6 col-6 p0">'
        + '<div class="position-relative">'
        + '<label class="form-label">Depart</label>'
        + '<input type="text" class="form-control tts__input__input tts__input__input1 tts__input__input2 tts-cursor-pointer" name="search_data[' + counter + '][departdate]" placeholder="Depart Date" data-validation="required" data-validation-error-msg="Please select departure date" flight-departure-date="true" data-key="' + counter + '" readonly>'
        +'<div class="flight_text_p"></div>'
        + '</div>'
        + '</div>'
        + '<div class="col-lg-1 col-md-6 col-6 p0">'
        + '<div class="fs-5 text-center " style="cursor: pointer;">'
        + '<i class="fa fa-times bg-secondary p-2 text-white" aria-hidden="true" remove-mult-city></i>'
        + '</div>'
        + '</div>'
        + '<div>';
    $("[multicity-addmore").append(html);

});

$(document).on("click", "[remove-mult-city]", function (event) {
    $(this).parent().parent().parent().remove();
    $("[add-mult-city]").show();
    var lists = document.querySelectorAll("[data-journey-key]");
    lists.forEach(function (index, key) {
        $(index).attr('data-journey-key', key);
        var input = $(index).find("input[type=text]");
        $.each(input, function (key1, val) {
            $(val).attr('data-key', key);
            if (key1 == 0) {
                $(val).attr('name', 'search_data[' + key + '][origin]');
            }
            if (key1 == 1) {
                $(val).attr('name', 'search_data[' + key + '][destination]');
            }
            if (key1 == 2) {
                $(val).attr('name', 'search_data[' + key + '][departdate]');
            }
        });
    });

});
$(document).on("click", "[check-direct-flight]", function (event) {
    $("[name='direct_flight']").val(0);
    if ($(this).prop('checked')) {
        $("[name='direct_flight']").val(1);
    }
});

$(document).on("change", "[result-fare-type-filter]", function (event) {
    $("[resultFareType]").val(this.value);
    resetTravelInfo();
});

function resetTravelInfo(){
    let adt_val=1,child_val=0,infant_val=0;
    $("[data-adult-count]").text(adt_val);
    $("[adult-input]").val(adt_val);
    $("[data-child-count]").text(child_val);
    $("[child-input]").val(child_val);
    $("[data-infant-count]").text(infant_val);
    $("[infant-input]").val(infant_val);
    travelpaxcount();
}

$(document).on("change", "[airline-filter]", function (event) {
    if(this.checked){
        AirlinePreferredFlights.push(this.value);
    }
    else {
        AirlinePreferredFlights = AirlinePreferredFlights.filter(item => item != this.value);
    }

    $("#PreferredCarriers").val(AirlinePreferredFlights);
});
$(document).on("change", "[result-fare-type-filter]", function (event) {
    $("[resultFareType]").val(this.value);
});
function form_fill_modify_search(frm, data,airportCodeDetail) {
    var airportCodeDetail =  JSON.parse(airportCodeDetail);
    $.each(data.search_data, function (key, value) {
        var origin = value.origin;
        var destination = value.destination;
        var departdate = value.departdate;
        var departdateday = value.DepartDateDay;
        var OriginCityCode = value.OriginCityCode;
        var DestinationCityCode = value.DestinationCityCode;
        var OriginCity = value.OriginCity;
        var DestinationCity = value.DestinationCity;
        $("[name='search_data[" + key + "][origin]'", frm).val(origin);
        $("[name='search_data[" + key + "][destination]'", frm).val(destination);
        $("[name='search_data[" + key + "][origin]'", frm).siblings(".flight_text_p").text('['+OriginCityCode+'] '+airportCodeDetail[OriginCityCode]);
        $("[name='search_data[" + key + "][origin]'", frm).siblings().val(OriginCity);
        $("[name='search_data[" + key + "][destination]'", frm).siblings(".flight_text_p").text('['+DestinationCityCode+'] '+airportCodeDetail[DestinationCityCode]);
        $("[name='search_data[" + key + "][destination]'", frm).siblings().val(DestinationCity);
        $("[name='search_data[" + key + "][departdate]'", frm).val(departdate);
        $("[name='search_data[" + key + "][departdate]'", frm).siblings(".flight_text_p").text(departdateday);
    });
}




@extends('frontend.layouts.master')
@push('title')  Booking Departure | . $bookingData['destination_title']@endpush
@section('content')

<style>
  /*  header nav.navbar.mainMenu {*/
  /*  background: #f4f4f7 !important;*/
  /*box-shadow: 0px 2px 15px rgb(0 0 0 / 10%);*/
  /*}*/
  /*.mainMenu li a {
  color: #000;
}*/
  .form-control {
    padding: 5px 6px !important;
    font-size: 13px !important;
  }

  .book_info {
    background: #fff;
    border: 1px solid rgba(156, 170, 179, 0.28);
    -webkit-box-shadow: 0 0 9px 0 rgba(0, 0, 0, 0.1);
    box-shadow: 0 0 9px 0 rgba(0, 0, 0, 0.1);
    padding: 10px 15px;
    border-radius: 5px;
  }

  .detail_info_an {
    font-size: 15px;
    padding-top: 20px;
    padding-bottom: 20px;
  }

  .payment_detail_an {
    border-radius: 5px;
    box-shadow: 0 1px 5px 0 #0000001a;
    background: #fff !important;
    padding: 15px;
  }

  .btn.disabled,
  .btn:disabled,
  fieldset:disabled .btn {
    background: red;
    border-color: red;
  }
</style>
<script>
  window.onload = function () {
        var d = new Date().getTime();
        document.getElementById("tid").value = d;
      };

</script>

<div class="container">
    <div class="row mt-4">
        <p class="color_gray"><a href="/" class="text-danger">Home</a> / Book Package</p>
      </div>
        <div class="row ">

            <h5>Traveller Details</h5>
            <form method="POST" name="customerData" action="{{route('frontend.place_order')}}">

              @csrf
              <div class="row">
                <div class="col-md-8 mt-4 mb-4 ">
                  <div class="row book_info">
                    <div class="col-md-12 pb-2">
                      <div class="row">
                        <div class="col-md-6 mt-2">
                         <div class="row">
                            <div class="col-md-2 tags">
                              <img src="{{ asset('assets/images/icons/file-text.png') }}" alt="">
                          </div>
                          <div class="col-md-10">
                          <p style="font-size:13px;" class="m-0">Package</p>
                          <p><b> {{ $bookingData['destination_title'] }}</b></p>
                        </div>
                         </div>
                        </div>
                        <div class="col-md-6 mt-2">
                          <div class="row">
                            <div class="col-md-2 tags">
                              <img src="{{asset('assets/images/icons/clock.png')}}" alt="">
                          </div>
                          <div class="col-md-10">
                              <p style="font-size:13px;" class="m-0">Duration</p>
                              <p><b> {{ $bookingData['no_of_nights'] }} </b></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row book_info mt-3">
                    <div class="col-md-12">
                      <p class="detail_info_an">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> Please make sure you enter the name as per your
                        Government ID
                      </p>
                      <div class="bg-light mb-5">
                        <div class="tab-content p-3 border bg-light" id="nav-tabContent">
                          <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="row mt-4">
                              <div class="col-md-3 mt-2">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" placeholder="Enter First Name" class="form-control"
                                  value="{{ $customerData['first_name'] }}" required>
                              </div>
                              <div class="col-md-3 mt-2">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name" placeholder="Enter Last Name"
                                  value="{{ $customerData['last_name'] }}" required>
                              </div>
                              <div class="col-md-3 mt-2">
                                <label for="email_id">Email</label>
                                <input type="email" class="form-control" name="email_id"
                                  value="{{ $customerData['email_id'] }}" placeholder="Enter valid Email address" required>
                                <small>(Eg: someone@gmail.com)</small>
                                <div id="emailValidationMsg" class="invalid-feedback"></div>
                              </div>
                              <div class="col-md-3 mt-2">
                                <label for="phone">Phone</label>
                                <input type="tel" name="phone" class="form-control" value="{{ $customerData['phone'] }}"
                                  placeholder="Enter Phone Number" required>
                                <div id="phoneValidationMsg" class="invalid-feedback"></div>
                              </div>
                              {{-- <div class="col-md-3 mt-3">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required>
                                <small>(Password must be at least 6 characters long)</small>
                                <div id="passwordValidationMsg" class="invalid-feedback"></div>
                              </div> --}}
                              <div class="col-md-12 mt-2">
                                <p class="pb-2 pt-2"><b>Are you booking this package for yourself?</b></p>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                                    value="option1">
                                  <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                                    value="option2">
                                  <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                              </div>
                              <div class="col-md-4">
                                {{-- <input type="button" id="btn" class="btn btn-primary mt-4" value="Continue"> --}}
                              </div>
                            </div>

                          </div>
                        </div>
                      </div>
                      <div class="bg-light" id="Create">
                        <div class="">
                          <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                              <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">Travellers Information</button>
                            </div>
                          </nav>
                          <div class="tab-content p-3 border bg-light" id="nav-tabContent">
                            <div class="tab-pane fade active show" id="nav-home" role="tabpanel"
                              aria-labelledby="nav-home-tab">
                              <?php $pax = $bookingData['pax'] ?>
                              <?php for ($i = 1; $i <= $pax; $i++): ?>
                              <div class="row mt-4">
                                <div class="col-md-2">
                                  <p><strong>Traveller
                                      <?php echo $i; ?>
                                    </strong></p>
                                </div>
                                <div class="col-md-2">
                                  <label for="title" id="title">Title</label>
                                  <select id="title_<?php echo $i; ?>" class="form-control title" name="title[]" required
                                    >
                                    <option>Select Title</option>
                                    <option value="mr">Mr.</option>
                                    <option value="miss">Miss.</option>
                                    <option value="mrs">Mrs.</option>

                                  </select>
                                </div>
                                <div class="col-md-2">
                                  <label for="fname">First Name</label>
                                  <input type="text" class="form-control" name="name[]" required >
                                </div>
                                <div class="col-md-2">
                                  <label for="lname">Last Name</label>
                                  <input type="text" class="form-control" name="last_name1[]" required >
                                </div>
                                <div class="col-md-2">
                                  <label for="gender" id="gender">Gender</label>
                                  <select id="gender_<?php echo $i; ?>" class="form-control gender" name="gender[]" required
                                    >
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="others">Others</option>
                                  </select>
                                </div>
                                <div class="col-md-2">
                                  <label for="pan">Pan Card</label>
                                  <input type="text" class="form-control" name="pan[]" required
                                    pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="Enter a valid PAN card number" maxlength="10"
                                    >
                                </div>
                              </div>
                              <div class="row mt-3">
                                <div class="col-md-2">
                                  <label for="passport">Passport Detail</label>
                                </div>
                                <div class="col-md-3">
                                  <input type="text" class="form-control" placeholder="Passport Number" name="passport_no[]"
                                    required maxlength="20" >
                                </div>
                                <div class="col-md-3">
                                  <input type="date" class="form-control issue_date" placeholder="Passport Issue Date"
                                    name="issue_date[]" required id="issue_date_<?php echo $i; ?>" >
                                </div>
                                <div class="col-md-3">
                                  <input type="date" class="form-control expiry_date" placeholder="Passport Expiry Date"
                                    name="expiry_date[]" required id="expiry_date_<?php echo $i; ?>" >
                                </div>
                              </div>
                              <?php endfor; ?>
                            </div>


                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4 mt-4">
                  <div class="row" style="position: sticky;top: 70px;width: 100%;margin-bottom: 25px;">
                    <div class="col-md-12 px-4">
                      
                        <div class="row payment_detail_an">
                          <h5 class="border-bottom pb-2 mb-4">Price Summary</h5>
                          <div class="col-md-6">
                            <p><img src="{{ asset('assets/images/icons/calendar.png') }}" alt="Date"> Departure Date</p>
                            <p><img src="{{ asset('assets/images/icons/users1.png') }}" alt="No of Travelers"> No. of Travelers</p>
                            @if ($bookingData['pax'] > 1)
                            <p><img src="{{ asset('assets/images/icons/tag.png') }}" alt="Price"> Price <small>(Double Occupancy)</small></p>
                            @endif
                            @php
                            $departureDate = date('d-m-Y', strtotime($bookingData['departure_date']));
                            $departureDateId = $bookingData['departure_dateid'];
                            @endphp

                            @if ($bookingData['pax'] % 2 != 0)
                            <p><img src="{{ asset('assets/images/icons/tag.png') }}" alt="Price Single"> Price <small>(Single Occupancy)</small></p>
                            @endif
                            @if (($departureDateId == 1119 && $departureDate == '30-12-2024')||
                            ($departureDateId == 1117 && $departureDate == '30-12-2024')||($departureDateId == 1118 &&
                            $departureDate == '28-12-2024') ||($departureDateId == 1141 && $departureDate == '24-12-2024') ||
                            ($departureDateId == 1219 && $departureDate == '26-12-2024') ||
                            ($departureDateId == 1220 && $departureDate == '27-12-2024') ||
                            ($departureDateId == 1221 && $departureDate == '28-12-2024') ||
                            ($departureDateId == 1222 && $departureDate == '29-12-2024') ||
                            ($departureDateId == 1223 && $departureDate == '30-12-2024') ||
                            ($departureDateId == 1120 && $departureDate == '23-12-2024') ||
                            ($departureDateId == 1121 && $departureDate == '30-12-2024') )
                            <p>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="5000" id="flexCheckDefault">
                              <label class="form-check-label pt-1" for="flexCheckDefault">
                                Block your seat at just
                              </label>
                            </div>
                            </p>
                            @endif
                          </div>
                          <div class="col-md-6">
                            @php
                            $pax = $bookingData['pax'];
                            $price = $bookingData['price'];
                            $single = $bookingData['singleprice'];

                            if ($pax % 2 == 0) {
                            $total = $pax * $price;
                            } else { // Odd number of passengers
                            $pairTotal = ($pax - 1) * $price;
                            $singleTotal = $single;
                            $total = $pairTotal + $singleTotal;
                            }

                            $formattedTotal = number_format($total, 2, '.', ',');
                            $departureDate = date('d-m-Y', strtotime($bookingData['departure_date']));
                            $departureDateId = $bookingData['departure_dateid'];
                            @endphp

                            <p>{{ $bookingData['departure_date'] }}</p>
                            <p>{{ $bookingData['pax'] }} </p>

                            @if ($bookingData['pax'] > 1)
                            <p>₹ {{ number_format($price, 2, '.', ',') }}</p>
                            @endif

                            @if ($pax % 2 != 0)
                            <p>₹ {{ number_format($single, 2, '.', ',') }}</p>
                            @endif
                          </div>
                          <hr>
                          <div class="row">
                            <div class="col-md-6">
                              <p>Total Amount</p>
                            </div>
                            <div class="col-md-6">
                              <!-- <input type="hidden" name="price" value="{{ $total }}"> -->
                              <input type="hidden" name="price" id="totalAmount" value="{{ $total }}">
                              <input type="hidden" name="pax" value="{{ $pax }}">
                              <input type="text" name="departure_dateid" value="{{ $bookingData['departure_dateid'] }}"
                                hidden />
                              <input type="text" name="departure_date" value="{{ $bookingData['departure_date'] }}" hidden />
                              <input type="text" name="destination_title" value="{{ $bookingData['destination_title'] }}"
                                hidden />
                              <input type="text" name="priceId" value="{{ $bookingData['priceId'] }}" hidden />
                              <input type="text" name="singlepriceid" value="{{ $bookingData['singlePriceIdInput'] }}"
                                hidden />

                              <p id="displayTotalAmount">₹ {{ $formattedTotal }}</p>
                              <!-- <p id="displayPaidAmount">₹ 0.00</p> -->
                              <!-- <p id="displayBalanceAmount">₹ {{ $formattedTotal }}</p> -->
                            </div>
                          </div>
                          <input type="submit" value="Book Now" class="aa-browse-btn btn btn-danger mt-4" id="btnPlaceOrder">
                        </div>
                      
                    </div>
                  </div>
            </form>
    
        </div>
</div>


@endsection
@section('footerSection')
<!-- Include jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Include jQuery UI library -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
  document.getElementById('flexCheckDefault').addEventListener('change', function() {
        var totalAmount = document.getElementById('totalAmount');
        var displayTotalAmount = document.getElementById('displayTotalAmount');
        var displayPaidAmount = document.getElementById('displayPaidAmount');
        var displayBalanceAmount = document.getElementById('displayBalanceAmount');

        var pax = {{ $pax }};

        if (this.checked) {
            totalAmount.value = 5000 * pax; 

            displayTotalAmount.textContent = '₹ {{ $formattedTotal }}';
            displayPaidAmount.textContent = '₹ ' + (5000 * pax).toFixed(2) + ' (To Be Paid)';

            var formattedTotal = parseFloat('{{ $formattedTotal }}'.replace(/,/g, '')); 
            var balanceAmount = formattedTotal - (5000 * pax);

            displayBalanceAmount.textContent = '₹ ' + balanceAmount.toFixed(2) + ' (Balance Amount)';
        } else {
            totalAmount.value = {{ $total }};

            displayTotalAmount.textContent = '₹ {{ $formattedTotal }}';
            displayPaidAmount.textContent = '₹ 0.00';

            var balanceAmount = parseFloat(totalAmount.value) - parseFloat('{{ $formattedTotal }}'.replace(/,/g, ''));
            displayBalanceAmount.textContent = '₹ ' + balanceAmount.toFixed(2);
        }
    });
</script>
<script>
  $(document).ready(function() {
      
        $('.issue_date').datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true 
            // maxDate: '0', 
            // minDate: '0'
        });
    });
    $(document).ready(function() {
        var departureDate = '<?php echo $bookingData['departure_date'] ?>';
        var noOfNights = <?php echo $bookingData['no_of_nights']; ?>;
        $('.expiry_date').datepicker({
            dateFormat: 'yy-mm-dd', 
             minDate: '0',
             changeYear: true,
            changeMonth: true 
            // minDate: minSelectableDate 
        });
    });

</script>

<script>
  $(document).ready(function () {
    function isValidEmail(email) {
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }   
    function isValidPhone(phone) {
          var phonePattern = /^\d{10,15}$/;
          return phonePattern.test(phone);
    }

    function checkRequiredFields() {
        var firstName = $("input[name='first_name']").val();
        var lastName = $("input[name='last_name']").val();
        var email = $("input[name='email_id']").val();
        var phone = $("input[name='phone']").val();
        // var password = $("input[name='password']").val();

        var isEmailValid = isValidEmail(email);
        var isPhoneValid = isValidPhone(phone);
        // var isPasswordValid = password.length >= 6; 

        if (!isEmailValid) {
            $("#emailValidationMsg").text("Please enter a valid email address.");
        } else {
            $("#emailValidationMsg").text("");
        }

        if (!isPhoneValid) {
            $("#phoneValidationMsg").text("Please enter a valid 10-digit phone number.");
        } else {
            $("#phoneValidationMsg").text("");
        }

        // if (!isPasswordValid) {
        //     $("#passwordValidationMsg").text("Password must be at least 6 characters long.");
        // } else {
        //     $("#passwordValidationMsg").text("");
        // }

        return firstName && lastName && isEmailValid && isPhoneValid && isPasswordValid;
    }

    // $("#btn").prop("disabled", !checkRequiredFields());

    // $("input[name='first_name'], input[name='last_name'], input[name='email_id'], input[name='phone']").on("input", function () {

    //     $("#btn").prop("disabled", !checkRequiredFields());
    // });
    $("input[name='inlineRadioOptions']").change(function() {
        if ($(this).val() === "option1") {
            var firstName = $("input[name='first_name']").val();
            var lastName = $("input[name='last_name']").val();
            $(".tab-pane.active .row input[name='name[]']").val(firstName);
            $(".tab-pane.active .row input[name='last_name1[]']").val(lastName);
            $("#Create").show();
        } else {
            $(".tab-pane.active .row input[name='name[]']").val("");
            $(".tab-pane.active .row input[name='last_name1[]']").val("");
            $("#Create").show();
        }
    });
});

</script>
<script>
  $(document).ready(function(){

    function validateForm() {
        var isValid = true;
        $('form[name="customerData"] input[type="text"], form[name="customerData"] select').each(function(){
            if($(this).val() === ''){
                isValid = false;
                return false; 
            }
        });
        return isValid;
    }

  // Initially disable the button
    $('#btnPlaceOrder').prop('disabled', true);

    // When the form fields change or when the user types in an input field
    $('form[name="customerData"] input[type="text"], form[name="customerData"] select').on('input change', function(){
        // Check if the form is valid
        if(validateForm()){
            $('#btnPlaceOrder').prop('disabled', false);  // Enable the button
            $('#btn').hide();  // Hide any other elements (e.g., a loading button)
        } else {
            $('#btnPlaceOrder').prop('disabled', true);  // Disable the button
            $('#btn').show();  // Show the other elements (e.g., a loading button)
        }
    });
     $('.title').change(function() {
        var title = $(this).val();
        var genderSelect = $(this).closest('.row').find('.gender');
        if (title === 'mr') {
            genderSelect.val('male');
        } else if (title === 'miss' || title === 'mrs') {
            genderSelect.val('female');
        } else {
            genderSelect.val('');
        }
    });

});
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const enableCreateSection = () => {
        // Retrieve field values
        const firstName = document.querySelector('input[name="first_name"]').value.trim();
        const lastName = document.querySelector('input[name="last_name"]').value.trim();
        const email = document.querySelector('input[name="email_id"]').value.trim();
        const phone = document.querySelector('input[name="phone"]').value.trim();
        const bookingForSelf = document.querySelector('input[name="inlineRadioOptions"]:checked');

        // Check if all required fields are filled
        if (firstName && lastName && email && phone && bookingForSelf) {
            document.getElementById('Create').style.display = 'block'; // Show the #Create section
            const fields = document.querySelectorAll('#Create .form-control');
            fields.forEach(field => field.removeAttribute('disabled')); // Enable fields
        }
    };

    // Add event listeners to check fields on change
    document.querySelector('input[name="first_name"]').addEventListener('input', enableCreateSection);
    document.querySelector('input[name="last_name"]').addEventListener('input', enableCreateSection);
    document.querySelector('input[name="email_id"]').addEventListener('input', enableCreateSection);
    document.querySelector('input[name="phone"]').addEventListener('input', enableCreateSection);
    document.querySelectorAll('input[name="inlineRadioOptions"]').forEach(radio => {
        radio.addEventListener('change', enableCreateSection);
    });
});
</script>


@endsection
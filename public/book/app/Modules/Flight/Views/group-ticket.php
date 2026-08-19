<style>
    .flight-group-section {
        background: linear-gradient(135deg, #3f87a6, #ebf8e1);
        padding: 20px 0;
    }

    .flight-group-section .flight-title {
        font-size: 25px;
        font-weight: bold;
        color: #fff;
        text-transform: capitalize;
        letter-spacing: 2px;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
    }

    /*flight modify search*/
    .flight-modify-search {
        background-color: #f8f8f8;
    }

   

    /* ===================
booking sidebar
=================== */

    .booking-sidebar-filter {
        background: #ffffff;
        border: 1px solid #dedede;
        border-radius: 10px;
        padding: 20px;
    }

    .booking-sidebar-filter .booking-item {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #dedede;
    }

    .booking-sidebar-filter .booking-item:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .booking-sidebar-filter .booking-title {
        font-size: 17px;
        position: relative;
        margin-bottom: 10px;
        padding-bottom: 10px;
    }

    .booking-sidebar-filter .form-check-input {
        margin-top: 6px;
        box-shadow: none;
        border-color: #ccc;
    }

    .booking-sidebar-filter .form-check-input:checked {
        background-color: var(--tts-buttton-bg);
    }

    .booking-sidebar-filter .form-check-input:focus {
        border-color: var(--tts-buttton-bg);
    }

    .booking-sidebar-filter .form-check-label {
        width: 100%;
        color: #222222;
    }

    .booking-sidebar-filter .form-check-label span {
        float: right;
    }

    .booking-sidebar-filter .form-check {
        margin: 12px 0;
    }

    .booking-sidebar-filter .flight-time .form-check {
        padding: 10px 15px 10px 38px;
        border-radius: 8px;
        background: rgba(113, 103, 255, .1);
    }

    .booking-sidebar-filter .flight-time .form-check-label i {
        border-left: 1px solid rgba(0, 0, 0, .1);
        padding-left: 12px;
        margin-left: 5px;
        margin-right: 5px;
    }


    /* =====================
price range slider
===================== */

    .price-range-slider,
    .Duration-range-slider {
        margin-bottom: 10px;
    }

    .price-range-slider .price-range-info,
    .Duration-range-slider .Duration-range-info {
        margin-bottom: 20px;
    }

    .priceRange,
    .durationRange {
        background: transparent;
        border: none;
        font-weight: 800;
        outline: none;
        color: var(--tts-buttton-bg);
    }

    .price-range-slider label,
    .Duration-range-slider label {
        color: #222222;
        font-weight: 500;
    }

    .price-range-slider .ui-slider-handle,
    .Duration-range-slider .ui-slider-handle {
        top: -0.36em !important;
        border-radius: 50px;
        background: #fff !important;
        border: 4px solid var(--tts-buttton-bg) !important;
        width: 1.1em;
        height: 1.1em;
        outline: none;
    }

    .price-range-slider .ui-widget.ui-widget-content,
    .Duration-range-slider .ui-widget.ui-widget-content {
        background: #ededed;
        border: none;
        border-radius: 50px;
        padding: 0;
        height: 0.4em;
    }

    .price-range-slider .ui-widget-header,
    .Duration-range-slider .ui-widget-header {
        background: var(--tts-buttton-bg);
    }


    /*New result*/
    .booking-sort {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;

    }

    .booking-sort h5 {
        margin: 0;
        font-size: 18px;
    }

    .booking-sort .pagination {
        margin: 0;
        justify-content: end;
    }

    /* ===================
pagination css 
====================== */

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 40px;
    }

    .pagination .page-link {
        width: 40px;
        height: 40px;
        line-height: 28px;
        text-align: center;
        transition: all .5s ease-in-out;
        color: #222222;
    }

    .pagination .page-link:hover,
    .pagination .page-link:focus,
    .pagination .page-item.active .page-link {
        background: var(--tts-buttton-bg);
        color: var(--tts-buttton-txt);
        border-color: var(--tts-buttton-bg);
        box-shadow: none;
    }

    .pagination-showing {
        text-align: center;
        margin-top: 10px;
        color: #222;
    }

    /* ===================
flight booking css 
====================== */

    .flight-booking-item {
        background: #fff;
        border-radius: 10px;
        margin-bottom: 25px;
        border: 1px solid #dedede;
    }

    .flight-booking-wrapper {
        display: flex;
        justify-content: space-between;
    }

    .flight-booking-info {
        flex: 1;
        height: 100%;
        padding: 20px 20px 15px 20px;
    }

    .flight-booking-item .flight-booking-airline {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .flight-booking-item .flight-airline-img {
        width: 60px;
        height: 60px;
        padding: 15px;
        border-radius: 50%;
        text-align: center;
        border: 1px solid #dedede;
    }

    .flight-booking-item .flight-airline-img img {
        width: 100%;
        height: 100%;
    }

    .flight-booking-item .flight-booking-airline .flight-airline-name {
        font-size: 18px;
        margin: 0;
    }

    .flight-booking-item .flight-booking-time .start-time,
    .flight-booking-item .flight-booking-time .end-time {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .flight-booking-item .flight-booking-time .start-time-text,
    .flight-booking-item .flight-booking-time .end-time-text {
        color: #222;
        font-weight: 700;
    }



    .flight-booking-item .flight-destination {
        color: #787878;
        font-weight: 500;
    }

    .flight-booking-item .flight-booking-time {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
        width: 100%;
    }

    .flight-booking-item .start-time-icon {
        font-size: 25px;
        color: #222;
    }

    .flight-booking-item .flight-stop {
        text-align: center;
    }

    .flight-booking-item .flight-stop-number {
        color: #787878;
        font-weight: 500;
    }

    .flight-booking-item .flight-stop-arrow {
        margin-top: 5px;
        margin-bottom: 5px;
        border-top: 2px solid #222;
        position: relative;
        width: 140px;
    }

    .flight-booking-item .flight-stop-arrow::before {
        content: "";
        position: absolute;
        border-style: solid;
        border-width: 10px 10px 0 0;
        border-color: #222 transparent transparent transparent;
        right: -1.5px;
        top: -9.6px;
        transform: scaleY(-1);
    }

    .flight-booking-item .flight-has-stop::after {
        content: "\e122";
        position: absolute;
        left: 50%;
        top: -15px;
        font-family: "Font Awesome 6 Pro";
        color: #222;
        font-weight: bold;
    }

    .flight-booking-item .flight-booking-return .flight-has-stop::after {
        top: -16px;
    }

    .flight-booking-item .flight-booking-duration .duration-text {
        color: #787878;
        font-weight: 500;
    }

    .flight-booking-item .price-info {
        margin-bottom: 0px;
    }

    .flight-booking-item .price-info .price-amount {
        color: #222222;
        font-weight: 600;
        font-size: 20px;
    }

    .flight-booking-item .price-info .discount-price {
        margin-right: 5px;
        color: #222;
    }

    .flight-booking-item .flight-booking-price {
        padding: 20px 20px 15px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-left: 1px solid #dedede;
        gap: 20px;
        height: 100%;
    }

    .flight-booking-item .flight-booking-price .theme-btn,
    .flight-booking-item .flight-booking-time .end-time .theme-btn {
        font-size: 14px;
        color: var(--tts-buttton-txt);
        padding: 11px 20px;
        transition: all 0.5s;
        text-transform: capitalize;
        position: relative;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        text-align: center;
        overflow: hidden;
        border: none;
        background: var(--tts-buttton-bg);
        box-shadow: 0 3px 24px rgb(0 0 0 / 12%);
        z-index: 1;
        display: block;
    }

    .flight-booking-item .flight-booking-price .theme-btn i,
    .flight-booking-item .flight-booking-time .end-time .theme-btn i {
        margin-left: 5px;
    }

    .flight-booking-item .flight-booking-return {
        margin-top: 20px;
    }

    .flight-booking-item .flight-booking-return .flight-stop-arrow::before {
        right: unset;
        left: -1.5px;
        top: -9.6px;
        border-width: 0 0 10px 10px;
        border-color: transparent transparent #222 transparent;
        transform: scaleY(1);
    }



    .flight-booking-detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .flight-booking-detail-header p {
        color: #222;
        margin: 0;
    }

    .flight-booking-detail-header a {
        color: #222;
        font-weight: 500;
    }

    .flight-booking-detail-wrapper {
        border-top: 1px solid #dedede;
        padding: 20px 20px 15px 20px;
    }

    .flight-booking-detail-wrapper .nav-tabs .nav-item .nav-link {
        font-weight: 500;
        color: #222;
        border: none;
        border-bottom: 2px solid transparent;
    }

    .flight-booking-detail-wrapper .nav-tabs .nav-link.active {
        border-color: var(--tts-buttton-bg);
        color: var(--tts-buttton-bg);
    }

    .flight-booking-detail-left .flight-booking-airline {
        margin-top: 15px;
        margin-bottom: 35px;
    }

    .flight-booking-detail-left .flight-airline-model {
        color: #222;
        font-weight: 500;
        font-size: 14px;
    }

    .flight-booking-detail-left .flight-airline-class {
        color: #222;
        font-weight: 500;
    }

    .flight-booking-detail-left .flight-full-date {
        font-size: 14px;
        color: #222;
        font-weight: 500;
        margin: 0;
    }

    .flight-booking-detail-left .flight-booking-time {
        gap: unset;
        justify-content: space-between;
    }

    .flight-booking-detail-left .flight-stop-arrow {
        width: 100px;
    }

    .flight-booking-detail-right {
        position: relative;
        height: 100%;
        padding-bottom: 65px;
    }

    .flight-booking-detail-info {
        padding-top: 15px;
    }

    .flight-booking-detail-info .table {
        color: #222;
    }

    .flight-booking-detail-price {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: absolute;
        width: 100%;
        background: rgba(113, 103, 255, .1);
        border-radius: 0 0 5px 0;
        left: 0;
        bottom: 0;
        padding: 8px 18px;
        border-radius: 7px;
    }

    .flight-booking-detail-price .flight-booking-detail-price-title {
        margin: 0;
    }

    .flight-detail-price-amount {
        color: #c92e2a;
        font-weight: 600;
        font-size: 18px;
    }

    .flight-booking-policy ul li {
        font-size: 14px;
        margin: 6.1px 0;
        color: #222;
    }

    .flight-valid {
        color: #787878;
        font-weight: 500;
    }

    .countdown {
        display: flex;
        justify-content: space-around;
        gap: 10px
    }

    .countdown div {
        font-size: 12px;
        text-align: center;
    }

    .countdown span {
        display: block;
        font-size: 16px;
        color: #000;
        font-weight: 600;
        border: 1px solid #dedede;
        width: 40px;
        text-align: center;
        padding: 5px;
    }


    .flight-progress {
        width: 100%;
    }

    .flight-progress-slider {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 10px;
    }

    .progress-range-info {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .progress-range-info label {
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }

    .progress-range-info span {
        font-size: 12px;
        color: #666;
        display: block;
    }

    .flight-progress-slider .slider {
        width: 100%;
        height: 8px;
        border-radius: 5px;
        background: linear-gradient(90deg, #4caf50 0%, #ddd 0%);
        position: relative;
        overflow: hidden;
        transition: background 0.3s ease;
    }

    .flight-progress-slider .slider::before {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #4caf50;
        border-radius: 50%;
        top: -1px;
        left: 0%;
        transform: translateX(-50%);
        transition: left 0.3s ease;
    }



    .room-item {
        position: relative;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin: 20px 0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 20px;
        border: 1px solid #dedede;
    }

    .room-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .room-image {
        position: relative;
        height: 250px;
        margin-bottom: 20px;
    }

    .room-image img {
        width: 100%;
        height: 100%;
    }

    .room-content h3 {
        font-size: 20px;
        margin-bottom: 15px;
        color: #333;
    }

    .room-content h4 {
        font-size: 14px;
        margin-bottom: 10px;
        color: #000;
    }

    .room-content div {
        margin-bottom: 15px;
    }

    .room-content ul {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }


    .room-content ul li span {
        font-weight: bold;
    }


    .room-content .makkah-hotels ul li:last-child {
        border: 0;
        margin: 0;
        padding: 0;
    }

    .room-links {
        margin-bottom: 15px;
    }

    .room-links .read-more {
        color: #000;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .room-links .read-more:hover {
        color: #0056b3;
    }

    .room-btn {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .room-btn a {
        text-decoration: none;
        padding: 8px 20px;
        background: var(--tts-buttton-bg);
        color: var(--tts-buttton-txt);
        border-radius: 5px;
        transition: background 0.3s ease;
        width: 100%;
        display: block;
        text-align: center;
    }

    .room-btn a:hover {
        background: var(--tts-buttton-bg1);
    }

    .room-btn a.room-btn-two {
        background: var(--tts-buttton-bg1);
        color: var(--tts-buttton-txt1);
    }
</style>


<section class="flight-group-section">
    <div class="container">
        <div class="flight-title">Flight Booking</div>
    </div>
</section>
<section class="flight-modify-search">
    <div class="container">
        <?php echo view('Modules/Flight/Views/FlightBookingtemplate\new_modify_search.php'); ?>
    </div>
</section>


<section class="flight-booking flight-list">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3 mb-4">
                <?php echo view('Modules/Flight/Views/FlightBookingtemplate\new_flight_filter.php'); ?>
            </div>
            <div class="col-lg-8 col-xl-9">
                <?php echo view('Modules/Flight/Views/FlightBookingtemplate\new_flight_result.php'); ?>
            </div>
        </div>
    </div>
</section>


<section class="available-package">
    <div class="container">
        <div class="section-title d-flex align-items-center justify-content-between">
            <h2>Available group land packages deals</h2>
            <a href="#">View More</a>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="room-item">
                    <div class="room-image">
                        <img src="https://d1muf25xaso8hp.cloudfront.net/https%3A%2F%2Fdc47bb296b9a623eb631daf5083539fa.cdn.bubble.io%2Ff1707926412655x605327517696664600%2FRectangle%25206.jpg?w=768&h=934&auto=compress&dpr=1&fit=max" alt="Suite Room">
                    </div>
                    <div class="room-content">
                        <h3>Suite Room</h3>

                        <div class="room-pricing">
                            <h4>Price</h4>
                            <ul>
                                <li><span class="fa-solid fa-person"></span> 6500</li>
                                <li><span class="fa-solid fa-person"></span> 8500</li>
                                <li><span class="fa-solid fa-person"></span> 10500</li>
                                <li><span class="fa-solid fa-person"></span> 12500</li>
                            </ul>
                        </div>

                        <div class="makkah-hotels">
                            <h4>Makkah Hotels</h4>
                            <ul>
                                <li>Hyatt Regency</li>
                                <li>Shaza Makkah</li>
                                <li>Hilton Suites</li>

                            </ul>
                        </div>

                        <div class="madinah-hotels">
                            <h4>Madinah Hotels</h4>
                            <ul>
                                <li>Hyatt Regency</li>
                                <li>Dar Al Iman Intercontinental</li>
                                <li>Hilton Madinah</li>

                            </ul>
                        </div>

                        <div class="inclusions">
                            <h4>Inclusions</h4>
                            <ul>
                                <li>Daily Breakfast</li>
                                <li>Airport Transfers</li>
                                <li>Guided City Tours</li>
                            </ul>
                        </div>

                        <div class="room-links">
                            <a href="#" class="read-more">Read More...</a>
                        </div>

                        <div class="room-btn">
                            <a href="#" class="room-btn-one">View Packages</a>
                            <a href="#" class="room-btn-two">Select Packages</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="room-item">
                    <div class="room-image">
                        <img src="https://d1muf25xaso8hp.cloudfront.net/https%3A%2F%2Fdc47bb296b9a623eb631daf5083539fa.cdn.bubble.io%2Ff1707926412655x605327517696664600%2FRectangle%25206.jpg?w=768&h=934&auto=compress&dpr=1&fit=max" alt="Suite Room">
                    </div>
                    <div class="room-content">
                        <h3>Suite Room</h3>

                        <div class="room-pricing">
                            <h4>Price</h4>
                            <ul>
                                <li><span class="fa-solid fa-person"></span> 6500</li>
                                <li><span class="fa-solid fa-person"></span> 8500</li>
                                <li><span class="fa-solid fa-person"></span> 10500</li>
                                <li><span class="fa-solid fa-person"></span> 12500</li>
                            </ul>
                        </div>

                        <div class="makkah-hotels">
                            <h4>Makkah Hotels</h4>
                            <ul>
                                <li>Hyatt Regency</li>
                                <li>Shaza Makkah</li>
                                <li>Hilton Suites</li>

                            </ul>
                        </div>

                        <div class="madinah-hotels">
                            <h4>Madinah Hotels</h4>
                            <ul>
                                <li>Hyatt Regency</li>
                                <li>Dar Al Iman Intercontinental</li>
                                <li>Hilton Madinah</li>

                            </ul>
                        </div>

                        <div class="inclusions">
                            <h4>Inclusions</h4>
                            <ul>
                                <li>Daily Breakfast</li>
                                <li>Airport Transfers</li>
                                <li>Guided City Tours</li>
                            </ul>
                        </div>

                        <div class="room-links">
                            <a href="#" class="read-more">Read More...</a>
                        </div>

                        <div class="room-btn">
                            <a href="#" class="room-btn-one">View Packages</a>
                            <a href="#" class="room-btn-two">Select Packages</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="room-item">
                    <div class="room-image">
                        <img src="https://d1muf25xaso8hp.cloudfront.net/https%3A%2F%2Fdc47bb296b9a623eb631daf5083539fa.cdn.bubble.io%2Ff1707926412655x605327517696664600%2FRectangle%25206.jpg?w=768&h=934&auto=compress&dpr=1&fit=max" alt="Suite Room">
                    </div>
                    <div class="room-content">
                        <h3>Suite Room</h3>

                        <div class="room-pricing">
                            <h4>Price</h4>
                            <ul>
                                <li><span class="fa-solid fa-person"></span> 6500</li>
                                <li><span class="fa-solid fa-person"></span> 8500</li>
                                <li><span class="fa-solid fa-person"></span> 10500</li>
                                <li><span class="fa-solid fa-person"></span> 12500</li>
                            </ul>
                        </div>

                        <div class="makkah-hotels">
                            <h4>Makkah Hotels</h4>
                            <ul>
                                <li>Hyatt Regency</li>
                                <li>Shaza Makkah</li>
                                <li>Hilton Suites</li>

                            </ul>
                        </div>

                        <div class="madinah-hotels">
                            <h4>Madinah Hotels</h4>
                            <ul>
                                <li>Hyatt Regency</li>
                                <li>Dar Al Iman Intercontinental</li>
                                <li>Hilton Madinah</li>

                            </ul>
                        </div>

                        <div class="inclusions">
                            <h4>Inclusions</h4>
                            <ul>
                                <li>Daily Breakfast</li>
                                <li>Airport Transfers</li>
                                <li>Guided City Tours</li>
                            </ul>
                        </div>

                        <div class="room-links">
                            <a href="#" class="read-more">Read More...</a>
                        </div>

                        <div class="room-btn">
                            <a href="#" class="room-btn-one">View Packages</a>
                            <a href="#" class="room-btn-two">Select Packages</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(function() {
        $("#price-range1").slider({
            range: true,
            min: 0,
            max: 1000,
            values: [100, 500],
            slide: function(event, ui) {
                $("#priceRange1").val("$" + ui.values[0] + " - $" + ui.values[1]);
            }
        });

        $("#priceRange1").val("$" + $("#price-range1").slider("values", 0) +
            " - $" + $("#price-range1").slider("values", 1));
    });
</script>


<script>
    $(function() {
        $("#duration1-slider").slider({
            range: true,
            min: 0,
            max: 48,
            values: [1, 24],
            slide: function(event, ui) {
                $("#duration1").val(ui.values[0] + " - " + ui.values[1] + " hrs");
            }
        });

        // Set initial value in the input field
        $("#duration1").val($("#duration1-slider").slider("values", 0) +
            " - " + $("#duration1-slider").slider("values", 1) + " hrs");
    });
</script>

<script>
    var countDownDate = new Date("Oct 15, 2024 15:00:00").getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        document.getElementById("days").innerHTML = days;
        document.getElementById("hours").innerHTML = hours;
        document.getElementById("minutes").innerHTML = minutes;
        document.getElementById("seconds").innerHTML = seconds;
        if (distance < 0) {
            clearInterval(x);
            document.querySelector(".countdown").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>

<script>
    function updateProgress(remainingBuyers, totalBuyers) {
        const slider = document.getElementById('progress-range1');
        const progressPercentage = ((totalBuyers - remainingBuyers) / totalBuyers) * 100;
        const remainingText = document.getElementById('remaining-buyers');
        remainingText.textContent = `${remainingBuyers} More buyers needed`;
        slider.style.background = `linear-gradient(90deg, #4caf50 ${progressPercentage}%, #ddd ${progressPercentage}%)`;

        const handle = document.createElement('div');
        handle.style.position = 'absolute';
        handle.style.width = '10px';
        handle.style.height = '10px';
        handle.style.backgroundColor = '#4caf50';
        handle.style.borderRadius = '50%';
        handle.style.top = '-1px';
        handle.style.left = `${progressPercentage}%`;
        handle.style.transform = 'translateX(-50%)';

        slider.innerHTML = '';
        slider.appendChild(handle);
    }

    updateProgress(18, 20);
</script>
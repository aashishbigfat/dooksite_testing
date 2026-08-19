@extends('layouts.app')
@section('title', 'Booking Details')

@section('content')
<style>
    .accordion-header {
        background: #e9e9ed;
        padding: 8px;
    }
    .an_bg {
        margin: 15px 0;
        box-shadow: 1px 1px 15px rgb(0 0 0 / 10%);
        border-radius: 12px;
        padding: 38px;
        background-color: #fff;
    }
    @media print {
        #printable_div_id {
            display: block !important;
        }
        .printPage {
            display: none !important;
        }
    }
</style>
<style type="text/css" media="print">
  @page {
   size: landscape; 
   size: auto;   /* auto is the initial value */
    margin: 0; 
  }

</style>
<section class="position-relative">
    <picture class="homeBannerimg">
        <img src="https://adm.dookinternational.com/dook/images/landing/BAY2T1671519116.jpg" class="home_banner img-fit" alt="">
        <div class="bannerOverlay"></div>
    </picture>
    <div class="BannerCenterSection TopMargin">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="banner-text text-white">
                        <h1>Order</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>     
<main class="mt-5">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Details</li>
            </ol>
        </nav>
    </div>   

    <section id="cart-view">
        <div class="container">
            <div class="row">
                <div class="col-md-12" id='printable_div_id'>
                    <div class="accordion an_bg" id="accordionExample">
                        <div class="accordion-item mb-4">
                            <h6 class="accordion-header bg-white" id="headingOne">
                                Booking Details ( DF0000{{ $departures['BookingId'] }} )
                            </h6>
                            <div></div>
                        </div>
                        <div class="accordion-item mb-2">
                            <h6 class="accordion-header" id="headingOne">
                                Booking Basic Detail
                            </h6>
                            <div>
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Booking Ref Number: DF0000{{ $departures['BookingId'] }} 
                                        </div>
                                        <div class="col-md-4">
                                            Amount Paid: ₹{{ $departures['InvoiceAmount'] }}
                                        </div>
                                        <div class="col-md-4">
                                            Booking Status: {{ $departures['BookingStatus'] }}
                                        </div>
                                        <div class="col-md-4">
                                            Payment Status: {{ $departures['PaymentStatus'] }}
                                        </div>
                                        <div class="col-md-4">
                                            Booking Date: {{ $departures['InvoiceCreatedOn'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2">
                            <h6 class="accordion-header" id="headingTwo">
                                Booking Details
                            </h6>
                            <div>
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6><b>Depature Name</b></h6>
                                            {{ $departures['DepartureName'] }}
                                        </div>
                                        <div class="col-md-2">
                                            <h6><b>Category</b></h6>
                                            {{ $departures['PackageCategory'] }}
                                        </div>
                                        <div class="col-md-2">
                                            <h6><b>Travel Date</b></h6>
                                            {{ $departures['TravelDate'] }}
                                        </div>
                                        <div class="col-md-2">
                                            <h6><b>Duration</b></h6>
                                            {{ $departures['Duration'] . " days" }}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <h6><b>Passenger Details</b></h6>
                                        <table class="table table-bordered mt-2">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Sr.</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Pax Type</th>
                                                    <th scope="col">Pan</th>
                                                    <th scope="col">Passport</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($departures['PricingDetail'][0]['Passenger'] as $index => $pass)
                                                <tr>
                                                    <th scope="row">{{ $index + 1 }}</th>
                                                    <td>{{ $pass['Title'] }} {{ $pass['FirstName'] }} {{ $pass['LastName'] }}</td>
                                                    <td>{{ $departures['PricingDetail'][0]['PaxType'] }}</td>
                                                    <td>{{ $pass['PanCard'] }}</td>
                                                    <td>{{ $pass['PassportNumber'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2">
                            <h6 class="accordion-header" id="headingThree">
                                Fixed Departure Details
                            </h6>
                            <div>
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <h6><b>Depature Date</b></h6>
                                            {{ date("d-M-Y", strtotime($departures['PricingDetail'][0]['DepartureDate'])) }}
                                        </div>
                                        <div class="col-md-2">
                                            <h6><b>Return Date</b></h6>
                                            {{ date("d-M-Y", strtotime($departures['PricingDetail'][0]['ReturnDate'])) }}
                                        </div>
                                        <div class="col-md-2">
                                            <h6><b>Room Sharing</b></h6>
                                            {{ $departures['PricingDetail'][0]['RoomSharing'] }}
                                        </div>
                                        <div class="col-md-2">
                                            <h6><b>Flight Class</b></h6>
                                            {{ $departures['PricingDetail'][0]['FlightClass'] }}
                                        </div>
                                        <div class="col-md-2">
                                            <h6><b>Transport Type</b></h6>
                                            {{ $departures['PricingDetail'][0]['TransportType'] }}
                                        </div>
                                        <div class="col-md-2">
                                            <h6><b>Airport Transfers</b></h6>
                                            {{ $departures['PricingDetail'][0]['AirportTransport'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2">
                            <h6 class="accordion-header" id="headingThree">
                                User Information
                            </h6>
                            <div>
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            Contact's Email: {{ $departures['Passenger'][0]['Email'] }}
                                        </div>
                                        <div class="col-md-6">
                                            Pax Contact: {{ $departures['Passenger'][0]['MobileNo'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2">
                            <h6 class="accordion-header" id="headingThree">
                                Fare Summary
                            </h6>
                            <div>
                                <div class="accordion-body">
                                    <div class="row pb-2">             
                                        <div class="col-md-6">
                                            Base Price                    
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            ₹{{ $departures['PricingDetail'][0]['Price']['BasePrice'] }}
                                        </div>
                                    </div>
                                    <div class="row border-top pt-2">
                                        <div class="col-md-6">
                                            Taxes                    
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            ₹{{ $departures['PricingDetail'][0]['Price']['Tax'] }}
                                        </div>
                                    </div>
                                    <div class="row border-top pt-2">
                                        <div class="col-md-6">
                                            Other & Service Charges                    
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            ₹{{ $departures['PricingDetail'][0]['Price']['ServiceCharges'] }}
                                        </div>
                                    </div>
                                    <div class="row border-top pt-2">
                                        <div class="col-md-6">
                                            Discount(-)                    
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            ₹{{ $departures['PricingDetail'][0]['Price']['Discount'] }}
                                        </div>
                                    </div>
                                    <div class="row border-top pt-2">
                                        <div class="col-md-6">
                                            TDS(+)                    
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            ₹{{ $departures['PricingDetail'][0]['Price']['TDS'] }}
                                        </div>
                                    </div>
                                    <div class="row border-top pt-2">
                                        <div class="col-md-6">
                                            <b>Total Amount</b>                    
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end">
                                            ₹{{ $departures['PricingDetail'][0]['Fare'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <button onClick="printdiv('printable_div_id');" class="btn btn-primary mt-3 printPage">PRINT</button> 
                </div>
            </div>
        </div>
    </section> 
</main>
@endsection

<!-- <script>
function printdiv(elem) {
    var header_str = '<html><head><title>' + document.title  + '</title></head><body>';
    var footer_str = '</body></html>';
    var new_str = document.getElementById(elem).innerHTML;
    var old_str = document.body.innerHTML;
    document.body.innerHTML = header_str + new_str + footer_str;
    window.print();
    document.body.innerHTML = old_str;
    return false;
}
</script>
 -->
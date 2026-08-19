@extends('layouts.app')
@section('title', 'Order')

@section('content')
<style>
  .contactHeading.test::after {
  display: inline-block;
  content: "";
  border-top: 3px solid black;
  width: 9rem;
  margin: 0 1rem;
  transform: translateY(-1rem);
}
td {
  border-color: inherit;
  border-style: solid !important;
  border-width: 1 !important;
}
tr {
  border-color: inherit;
  border-style: solid;
  border-width: 2px;
}
</style>
<section class=" position-relative">
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
        <li class="breadcrumb-item active" aria-current="page">Order</li>
      </ol>
    </nav>
  </div>
  <section class="section_widget mt-5">
   <div class="container">
     <div class="row">
       <div class="col-md-12">
        <div class="contactHeading test" style="text-align:center;">
          <h2>Order</h2>
          </div>
         <div class="cart-view-area">
           <div class="cart-view-table">
             <form action="">
             
               <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Order Id</th>
                        <th>Order Status</th>
                        <th>Departure</th>
                        <th>Departure Date</th>
                        <th>Price</th>
                        <th>Placed At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($orders as $list)
                      <tr>
                        <td class="order_id_btn"><a href="{{url('order_detail')}}/{{$list->booking_id}}">{{$list->order_id}}</a></td>
                        <td>{{$list->booking_status}}</td>
                        <td>{{$list->destination_title}}</td>
                        <td>{{$list->departure_date}}</td>
                        <td>{{$list->price}}</td>
                        <td>{{$list->created_at}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
             </form>
             <!-- Cart Total view -->
           
		   </div>
         </div>
       </div>
     </div>
   </div>
 </section> 
</main>
@endsection
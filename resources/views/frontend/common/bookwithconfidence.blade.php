 <?php 
        $footer = DB::table('footer_settings')->first();
        ?>
	 <div class="book_with_confidence">
      <h5 class="px-2">Book With Confidence</h5>
      <p class="color_gray"><img src="{{asset('assets/images/icons/thumbs-up.png')}}" alt="" class="px-2">Hassle-free booking and best price guaranteed</p>
      <p class="color_gray"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> 24/7 support available</p>
      <p class="color_gray"><img src="{{asset('assets/images/icons/star.png')}}" alt="" class="px-2"> Hand-picked tours & activities</p>
      <p class="color_gray"><img src="{{asset('assets/images/icons/crosshair.png')}}" alt="" class="px-2"> Free travel insurance</p>

    </div>

    <div class="book_with_confidence">
      <h5 class="px-2">Need Help?</h5>
      <p class="color_gray"> <a class="" aria-current="page" href="tel:{{$footer->phone}}"><img src="{{asset('assets/images/icons/mobile.png')}}" alt="" class="px-2"> {{$footer->phone}}</a></p>
      <p class="color_gray"> <a class="" aria-current="page" href="mailto:{{$footer->email}}"><img src="{{asset('assets/images/icons/mailbox.png')}}" alt="" class="px-2">  {{$footer->email}}</a></p>
      <p class="color_gray">  <a class="" aria-current="page" href="https://api.whatsapp.com/send?phone={{$footer->mobile}}"><img src="{{asset('assets/images/icons/chat.png')}}" alt="" class="px-2"> +{{$footer->mobile}}</a></p>
    </div>
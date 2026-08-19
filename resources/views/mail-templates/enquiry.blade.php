<p>
<b>Name:</b> {{ $name }}
</p>
<p>
<b>Mobile:</b> {{ $mob_no }}
</p>
<p>
<b>Email:</b> {{ $email }}
</p>
@if(isset($comp_name))
<p>
<b>Company Name:</b> {{ $comp_name }}
</p>
@endif
@if(isset($departure_title))
<p>
<b>Departure title:</b> {{ $departure_title }}
</p>
@endif
@if(isset($destinations))
<p>
<b>Destination:</b> {{ $destinations }}
</p>
@endif
@if(isset($travel_date))
<p>
<b>Date Of Travel:</b> {{ $travel_date }}
</p>
@endif
@if(isset($no_of_traveler))
<p>
<b>No Of Travelers:</b> {{ $no_of_traveler }}
</p>
@endif
@if(isset($country))
<p>
<b><i>Country:</i></b> {{ $country }}
</p>
@endif
@if(isset($origin))
<p>
<b><i>Origin:</i></b> {{ $origin }}
</p>
@endif
@if(isset($region))
<p>
<b><i>Region:</i></b> {{ $region }}
</p>
@endif
@if(isset($city))
<p>
<b><i>City:</i></b> {{ $city }}
</p>
@endif
<p>
<b>IP Details:</b> {{ $ip }}
</p>
@if(isset($form_title))
<p>
<b>Form Title:</b> {{ $form_title }}
</p>
@endif
<p>
<b>Refer:</b> {{ $url }}
</p>

@if(isset($bnpl))
<p>
<b>Buy Now Pay Later:</b> {{ $bnpl }}
</p>
@endif

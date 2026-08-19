 @foreach($activities as $activity)
<div class="{{$activity->colMd ?? 'col-md-3'}} ">

<a href="{{route('frontend.activity_detail',$activity->slug_url)}}">
<div class="card mb-4">
  <img src="{{$activity->image}}" alt="{{$activity->activity_name}}" class="card-img-top" alt="...">
  <div class="card-body py-2 px-3">
     <h6>{{$activity->activity_name}}</h6>
        <p> {{$activity->total_departure}} Tours in {{$activity->total_destination}} Destinations</p>
  </div>
</div>
</a>
</div>
 @endforeach
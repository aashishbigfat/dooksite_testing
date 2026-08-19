<div class="containerGridWrapper">
  @foreach($experiences as $key => $experience)
  <a href="{{url('/')}}/{{$experience->slug_url}}" class="thingstodo-picture">
    <div class="wrapperImageContainer">
      <img src="{{ $experience->image }}" alt="{{$experience->experience_name}}" />
      <p> {{ $experience->experience_name }}</p>
    </div>
  </a>
  @endforeach
</div>
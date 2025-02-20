<div class="col-md-3 mt-3 attr_img" style="position: relative;">
    <a href="{{route('frontend.poi_details',['slug'=>$pointOfInterest->poi_url, 'id'=>$pointOfInterest->poiId])}}" target="_blank">
        <img src="{{ $pointOfInterest['image'] ?? url('images/poi-no-image.jpg') }}"
            alt="{{ $pointOfInterest['poi_name'] }}" style="width:100%;">
        <div class="text-block px-2 py-2">
            <h6 class="m-0 p-0">{{ $pointOfInterest['poi_name'] }}</h6>
            <p class="p-0 m-0">Duration: {{ $pointOfInterest['duration'] ?? 'N/A' }}</p>
        </div>
    </a>
</div>
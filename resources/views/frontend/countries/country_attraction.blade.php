@extends('frontend.layouts.master')
@push('title') {{$country_poi_detail->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$country_poi_detail->meta_keywords}}">
<meta name="description" content="{{$country_poi_detail->meta_description}}">@endpush 

@section('content')

 <div class="container mb-4">
      <div class="row">
          <div class="col-md-12">
              <p class="color_gray"><a href="/" class="text-danger">Home</a> /<a href="/" class="text-danger">Countries </a> / {{$country_poi_detail->countryName}}</p>
          </div>
          <div class="col-md-12 mt-3 mb-4 attract_desc">
        
            <h6>{{$country_poi_detail->country_attraction_slug_url}}</h6>
            <p>{!! $country_poi_detail->attraction_description !!}</p>
              <hr>

            <h5> {{$country_poi_detail->attraction_heading}}</h5>
           <div class="row" id="poiContainer">
              @foreach($poi_array as $key => $poiArray)
                <div class="col-md-3 attract_card mb-4 mt-4 poiItem">
                  <img src="{{$poiArray->image}}" alt="" class="image">
                  <div class="overlay">
                    <div class="text">
                      <h5>{{$poiArray->poi_name}}</h5>
                      <p>{{$poiArray->description}}</p>
                    </div>
                  </div>               
                </div>
              @endforeach
              @foreach($existing_pois as $key => $existing_poi)
                <div class="col-md-3 attract_card mb-4 mt-4 poiItem">
                  <img src="{{$existing_poi->image}}" alt="" class="image">
                  <div class="overlay">
                    <div class="text">
                      <h5>{{$existing_poi->poi_name}}</h5>
                      <p>{{$existing_poi->description}}</p>
                    </div>
                  </div>               
                </div>
              @endforeach
            </div>

            <div class="col-md-12 mt-4 d-flex">
              <ul style="list-style-type: none;" class="p-0 d-flex justify-content-center" id="pagination">
                <li><a href="javascript:void(0)" class="border p-2 text-dark rounded text-white bg-danger" id="prev">&lt;</a></li>
                <li><a href="javascript:void(0)" class="border p-2 text-dark rounded mx-2 text-white bg-danger" id="next">&gt;</a></li>
              </ul>
            </div>
          </div>
          <hr>
          <div class="col-md-12 mt-4">
            <h5>Things to Do in {{$country_poi_detail->countryName}}</h5>
            <p>Do what makes you happy</p>
             
            <div class="containerGridWrapper">
              @foreach($experience_row as $key => $experience_row)
              <a href="{{url('/')}}/{{$experience_row->slug}}" class="thingstodo-picture">
                <div class="wrapperImageContainer">
                  <img src="{{ $experience_row->image }}" />
                  <p> {{ $experience_row->name }}</p>
                </div>
              </a>
              @endforeach
            </div>
            

            
          </div>
         
       </div>
    </div>
   @include('frontend.common.testimonial')
<script>
  const itemsPerPage = 8; 
  let currentPage = 1;
  const poiItems = document.querySelectorAll('.poiItem');
  const totalItems = poiItems.length;
  const totalPages = Math.ceil(totalItems / itemsPerPage); 
  function showPage(page) {
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    poiItems.forEach(item => item.style.display = 'none');
    for (let i = startIndex; i < endIndex; i++) {
      if (poiItems[i]) {
        poiItems[i].style.display = 'block';
      }
    }
    const paginationLinks = document.querySelectorAll('#pagination a');
    paginationLinks.forEach(link => {
      link.classList.remove('active');
    });
    paginationLinks[page].classList.add('active');
    document.getElementById('prev').classList.toggle('disabled', currentPage === 1);
    document.getElementById('next').classList.toggle('disabled', currentPage === totalPages);
  }
  function createPagination() {
    const paginationContainer = document.getElementById('pagination');
    const pageLinks = paginationContainer.querySelectorAll('.page-link');
    pageLinks.forEach(link => link.remove());
    for (let i = 1; i <= totalPages; i++) {
      const pageLink = document.createElement('li');
      pageLink.innerHTML = `<a href="javascript:void(0)" class="border p-2 text-dark rounded page-link" data-page="${i}">${i}</a>`;
      paginationContainer.insertBefore(pageLink, document.getElementById('next'));
    }
  }
  showPage(1);
  document.getElementById('prev').addEventListener('click', () => {
    if (currentPage > 1) {
      currentPage--;
      showPage(currentPage);
    }
  });
  document.getElementById('next').addEventListener('click', () => {
    if (currentPage < totalPages) {
      currentPage++;
      showPage(currentPage);
    }
  });

  document.querySelectorAll('#pagination .page-link').forEach((link) => {
    link.addEventListener('click', (event) => {
      event.preventDefault();
      currentPage = parseInt(link.dataset.page); 
      showPage(currentPage);
    });
  });
  createPagination();

</script>
@endsection

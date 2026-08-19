
   @foreach($all_pois  as $key => $paginated_pois )
    <div class="masonry-card">
        <img src="{{$paginated_pois ->image}}" alt="{{$paginated_pois->poi_name}}" class="card-image">
        <div class="masonry-content">
            <h3 class="masonry-title">{{$paginated_pois->poi_name}}</h3>
            <p class="masonry-description">{{$paginated_pois->description}}</p>
        </div>
    </div>
    @endforeach
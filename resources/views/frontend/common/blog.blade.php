@foreach($postData as $key => $posts)
    <div class="col-md-4 mb-3">
        <div class="card">
            <img src="{{$posts->image}}" class="card-img-top" alt="...">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="blog_date text-center">
                            <h4>{{date('d M',strtotime($posts->published_date))}}</h4>
                        </div>
                    </div>
                </div>
                 <div class="cat-links text-danger">
                  <ul class="list-inline mb-1 ml-0 ellipsis">
                    @foreach($posts->related_categories as $key => $category)
                        @if($key < 5)
                        <li class="list-inline-item">
                          <a href="{{url('blog/category')}}/{{$category->slug}}/" class="text-danger" target="_blank"><em>{{$category->cat_name}}</em></a>
                        </li>
                        @endif
                    @endforeach
                  </ul>
                </div>
                <!-- <p>Travel <img src="{{asset('assets/images/icons/Rectangle19436.png')}}">  Admin <img src="{{asset('assets/images/icons/Rectangle19436.png')}}"> Coments (8)</p> -->
                <h5 class="card-title"><a href="{{url('blog')}}/{{$posts->slug}}/" target="_blank" class="text-dark">{{ Str::limit($posts->title, 60, '...') }}</a></h5>
                <p class="card-text"> {{ Str::limit($posts->short_description, 100, '...') }}</p>
                <a href="{{url('blog')}}/{{$posts->slug}}/" target="_blank" class="btn btn-danger">Read More</a>
            </div>
        </div>
    </div>
@endforeach
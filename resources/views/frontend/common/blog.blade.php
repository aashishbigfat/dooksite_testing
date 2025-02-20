@foreach ($posts['posts'] as $posts)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{$posts['image']}}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="blog_date text-center">
                                    <h4>{{date('d M',strtotime($posts['published_date']))}}</h4>
                                </div>
                            </div>
                        </div>
                        <p>Travel <img src="{{asset('assets/images/icons/Rectangle19436.png')}}">  Admin <img src="{{asset('assets/images/icons/Rectangle19436.png')}}"> Coments (8)</p>
                        <h6 class="card-title">{{ Str::limit($posts['title'], 60, '...') }}</h6>
                        <p class="card-text"> {{ Str::limit($posts['short_description'], 100, '...') }}</p>
                        <a href="{{url('blog')}}/{{$posts['slug']}}/" target="_blank" class="btn btn-danger">Read More</a>
                    </div>
                </div>
            </div>
    @endforeach
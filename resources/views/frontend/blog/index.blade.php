@extends('frontend.layouts.master')
@push('title') Dook International Blog - Travel Blogs, Travel Stories, Travel Destinations @endpush
@push('meta_tag')
<meta name="keywords" content="Dook Blog, Dook International Blog, Dook Travels Blog, Travel Blog, Travel Articles, Travel Stories, Travel Tips, Travel News, Travel Updates">
<meta name="description" content="Follow Dook International Blog for Worldwide Travel Destinations & Attractions, Travel Stories, Travel Trends, Travel Tips and Guide, Travel Experiences!">@endpush
@section('content')
<style>
@media screen and (max-width: 600px) {
  .blog_date h4 {
    position: absolute !important;
    left: 7px !important;
    z-index: 1000;
    top: 8px !important;
    margin-top: 0 !important;
    font-size: 12px;
    max-width: 30px;
    padding: 0px !important;
  }
}
.blog__card___area {
  height: 100%;
}
.card-body h5 {
  font-size: 16px;
}
</style> 
<!-- home section -->
 <section class="breadcrumb-section">
      <div class="container">
        <div class="breadcrumb-nav animate-fade-up">
          <div class="breadcrumb-item">
            <a href="/"><i class="fas fa-home"></i>Home</a>
          </div>
          <span class="breadcrumb-separator">/</span>
          <div class="breadcrumb-item">
            <span class="breadcrumb-current">Blog</span>
          </div>
        </div>
      </div>
    </section>


<div class="container mb-5">
    <div class="row mt-4 mb-5">
        <div class="col-md-12">
            <!-- <p class="color_gray"><a href="/" class="text-danger">Home</a> / Blog</p> -->
                 <ul class="nav nav-tabs shadow-sm bg-white rounded tab mb-4"> 
                    <li class="active"><button class="tablinks active" onclick="openCity(event, 'Latest')" id="defaultOpen">Latest Blog</button></li>
                    <li><button class="tablinks" onclick="openCity(event, 'Recent')">Recent Blog</button></li>
                </ul>
        </div>
        <div class="col-md-9">
			  <div class="tabcontent" id="Latest">   
                <div class="row" id="resetPostData"> 
                       @include('frontend.common/blog',['postData'=>$posts->posts])
                    </div>
                    @if(request()->route('cat_url') == "")
                    <div class="row" id="blogCountDivRemove">
                      <div class="col-12 text-center position-relative mt-4">
                        <button  onclick="loadMoreBlog()" class="load_more_btn"><i class="fas fa-plus me-2"></i> View more blog</button>
                        <p class="progressLine"></p>
                        <p class="progressLineFigure" id="paginationWidth" style="width:20%"></p>
                      </div>
                    </div>
                    @endif
                </div>
			<div id="Recent" class="tabcontent">
                  <h3>Recent Blog</h3>
			    <div class="row">
                    @include('frontend.common.recent_blog')
                </div>
			</div>    
       </div>
       <div class="col-md-3 mt-2">
        <div class="shadow p-3 mb-3 bg-white rounded">
          <h4 class="px-2">Search Posts</h4>
           <div class="input-group">
            <input type="text" class="form-control" id="post_keyword" placeholder="Enter keyword..">
            <span class="input-group-text" id="PostSerchSubmit" style="border: 1px solid #ddcece;"><i class="fa fa-search text-danger"></i></span>
            <label class="spanColor" id="post_keyword_error"></label>
        </div>

        </div>
        <div class="shadow p-3 mb-3 bg-white rounded">
          <h4 class="px-2">Categories</h4>
               <select id="categoryList" class="form-control">
                <option value="">Select Category</option>
                @foreach($categories as $key => $category)
                    <option value="{{ $category->slug }}" @if($category->slug == request()->route('frontend.cat_url')) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
           <div class="loaderBg" style="display:none;">Loading...</div>
        </div>
        @include('frontend.common.bookwithconfidence')
       </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const categoryList = document.getElementById('categoryList');

    if (categoryList) {
        categoryList.addEventListener('change', function () {
            const categorySlug = this.value;

            if (categorySlug !== "") {
                const loader = document.querySelector('.loaderBg');
                if (loader) loader.style.display = 'block';

                const baseUrl = "{{ url('/') }}";
                window.location.href = baseUrl + "/blog/category/" + categorySlug;
            }
        });
    }
});
</script>

<script type="text/javascript">
var currentPage = 1;
var total = parseInt("{{ $total ?? 0 }}");
var itemOnPage = parseInt("{{ $item_per_page ?? 18 }}");

$(document).ready(function () {

    updatePaginationWidth();

    function updatePaginationWidth() {
        if (total > 0) {
            var width = (itemOnPage / total) * 100;
            if (width > 100) width = 100;
            $("#paginationWidth").css("width", width + "%");
        }
    }

    function searchBlog() {
        var post_keyword = $.trim($("#post_keyword").val());

        if (post_keyword === "") {
            $("#post_keyword_error").html("This field is required!");
            $("#post_keyword").focus();
            return false;
        }

        $("#post_keyword_error").html("");
        $(".loaderBg").show();

        $.ajax({
            url: "{{ route('frontend.blog') }}",
            method: "GET",
            dataType: "json",
            data: {
                post_keyword: post_keyword
            },
            success: function (data) {
                $(".loaderBg").hide();

                $("#resetPostData").fadeIn().html(data.view);
                $("#blogCountDivRemove").hide();
            },
            error: function (xhr, textStatus, errorThrown) {
                $(".loaderBg").hide();
                console.log("Search AJAX Error:", xhr.status, textStatus, errorThrown);
                console.log(xhr.responseText);
            }
        });
    }

    $("#post_keyword").on("keypress", function (event) {
        if ((event.keyCode || event.which) === 13) {
            event.preventDefault();
            searchBlog();
        }
    });

    $("#PostSerchSubmit").on("click", function (e) {
        e.preventDefault();
        searchBlog();
    });

    window.loadMoreBlog = function () {
        $(".loaderBg").show();
        currentPage++;

        $.ajax({
            url: "{{ route('frontend.blog') }}",
            method: "GET",
            dataType: "json",
            data: {
                page: currentPage
            },
            success: function (data) {
                $(".loaderBg").hide();

                var resultprPage = parseInt(data.postCount || 0);

                if (data.view) {
                    $("#resetPostData").fadeIn().append(data.view);
                }

                itemOnPage = itemOnPage + resultprPage;
                updatePaginationWidth();

                if (total <= itemOnPage || resultprPage === 0) {
                    $("#blogCountDivRemove").hide();
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $(".loaderBg").hide();
                currentPage--;

                console.log("Load More AJAX Error:", xhr.status, textStatus, errorThrown);
                console.log(xhr.responseText);
            }
        });
    };
});
</script>

<script>
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    var city = document.getElementById(cityName);
    if (city) city.style.display = "block";

    if (evt && evt.currentTarget) {
        evt.currentTarget.className += " active";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    var defaultOpen = document.getElementById("defaultOpen");
    if (defaultOpen) defaultOpen.click();
});
</script>

@endsection
@extends('frontend.layouts.master')
@push('title') {{$country_header->meta_title}}@endpush
@push('meta_tag')<meta name="keywords" content="{{$country_header->meta_keywords}}">
<meta name="description" content="{{$country_header->meta_description}}">@endpush 

@section('content')
    <!-- home section -->
   <div class="container">
    <div class="row mt-4">
        <div class="col-md-12">
            <p class="color_gray"><a href="/" class="text-danger">Home</a> / Countries</p>
        </div>
        <div id="countries-list">
            @include('frontend.countries.countries_card', ['countries' => $countries])
        </div>

        <!-- Pagination Links -->
        <div class="col-md-12 mt-4 mb-4">
            <ul style="list-style-type: none;" class="p-0 d-flex pagination" id="pagination-links">
            </ul>
        </div>
    </div>
</div>

    <!-- testimonial -->
  @include('frontend.common.testimonial')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var totalPages = {{$countries->lastPage()}};
        var currentPage = {{$countries->currentPage()}};
        var maxPagesToShow = 3;        
        function updatePagination() {
            var startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
            var endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
            var paginationHtml = '';
            if (currentPage > 1) {
                paginationHtml += '<li><a href="javascript:void(0)" class="border p-2 text-white bg-danger rounded" data-page="' + (currentPage - 1) + '"> < </a></li>';
            }
            for (var i = startPage; i <= endPage; i++) {
                paginationHtml += '<li><a href="javascript:void(0)" class="border p-2 text-dark rounded ' + (i === currentPage ? 'active' : '') + '" data-page="' + i + '">' + i + '</a></li>';
            }
            if (currentPage < totalPages) {
                paginationHtml += '<li><a href="javascript:void(0)" class="border p-2 text-white bg-danger rounded" data-page="' + (currentPage + 1) + '"> > </a></li>';
            }

            $('#pagination-links').html(paginationHtml);
        }
        $('#pagination-links').on('click', 'a', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            $.ajax({
                url: "{{ route('frontend.countries.paginate') }}",
                data: { page: page },
                type: 'GET',
                success: function(response) {
                    $('#countries-list').html(response);
                    currentPage = page;
                    updatePagination();
                }
            });
        });
        updatePagination();
    });
</script>
@endsection

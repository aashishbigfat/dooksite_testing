<!-- Mobile Filter Toggle -->
<button class="mobile-filter-toggle" onclick="toggleMobileFilter()">
    <i class="fas fa-filter"></i> Filter Options
</button>

<!-- Filter Overlay -->
<div class="filter-overlay" onclick="closeMobileFilter()"></div>
<!-- Filter Sidebar -->
<div class="filter-sidebar" id="filterSidebar">
    <button class="close-btn" onclick="closeMobileFilter()" style="display: none">
        <i class="fas fa-times"></i>
    </button>

    <!-- Price Filter -->
    <div class="filter-section">
        <div class="filter-header" onclick="toggleFilter('price')">
            <h3>Filter by Price</h3>
            <i class="fas fa-chevron-up"></i>
        </div>
        <div class="filter-content" id="price">
            <input type="text" class="js-range-slider" name="my_range" value="" data-skin="round" data-type="double"
                data-min="{{ $minPrice ?? '0' }}" data-max="{{ $maxPrice ?? $maxPrice1 }}" data-grid="false" />
            <div class="row">
                <div class="col-md-6">
                    <div class="from_flight px-2 bg-light">
                        <label for="minPrice" class="form-label m-0">Min Price </label>
                        <input id="minPrice" type="number" maxlength="4" value="{{ $minPrice ?? '0' }}"
                            class="from-control w-100 bg-transparent from" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="from_flight px-2 bg-light">
                        <label for="maxPrice" class="form-label m-0">Max Price </label>
                        <input id="maxPrice" type="number" maxlength="4" value="{{ $maxPrice ?? '' }}"
                            class="from-control w-100 bg-transparent to" />
                    </div>
                </div>
                <div class="filter-buttons">
                    <button class=" btn-clear-filter btn-clear">Clear</button>

                    <button class="btn-apply-price-filter btn-apply">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Flights Filter -->
    <div class="filter-section">
        <div class="filter-header" onclick="toggleFilter('flights')">
            <h3>Flights</h3>
            <i class="fas fa-chevron-up"></i>
        </div>
        <div class="filter-content" id="flights">
            <div class="filter-options">
                <div class="filter-option">
                    <input type="checkbox" class="form-check-input flight-filter" id="with_flight" name="flight_filter"
                        value="with_flight" {{ request()->get('flight_filter') == 'with_flight' ? 'checked' : '' }}>
                    <label for="with-flight">With Flight</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" class="form-check-input flight-filter" id="without_flight"
                        name="flight_filter" value="without_flight" {{ request()->get('flight_filter') ==
                    'without_flight' ? 'checked' : '' }}>
                    <label for="without-flight">Without Flight</label>
                </div>
                <div class="filter-option">
                    <input type="checkbox" class="form-check-input flight-filter" id="both_flight" name="both_flight"
                        value="both_flight" {{ request()->get('both_flight') ? 'checked' : '' }}>
                    <label for="both">Both</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Destination Filter -->
    <div class="filter-section">
        <div class="filter-header" onclick="toggleFilter('destinations')">
            <h3>Destination</h3>
            <i class="fas fa-chevron-up"></i>
        </div>
        <div class="filter-content" id="destinations"
            style="overflow-y: scroll;height: 200px;scrollbar-width: thin;scrollbar-color: #dc3545 #f1f1f1;">
            <div class="filter-options">
                @foreach ($destinations as $destinations)
                <div class="filter-option">
                    <input type="checkbox" class="destinations-filter" id="{{ $destinations->id }}"
                        name="destinations[]" value="{{ $destinations->dest_name }}" {{
                        in_array($destinations->dest_name, $selectedDestinations) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $destinations->id }}">{{ $destinations->dest_name }}</label>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


{{-- price Filtering --}}
<script>
    $(document).ready(function () {
        $(".js-range-slider").ionRangeSlider({
            type: "double", // Enables dual thumb (Min and Max)
            skin: "round",
            min: {{ $minPrice ?? 0 }},
            max: {{ $maxPrice ?? $maxPrice1 }},
            from: {{ $minPrice ?? 0 }},
            to: {{ $maxPrice ?? $maxPrice1 }},
            grid: true,
            onFinish: function (data) {
                $("#minPrice").val(data.from);
                $("#maxPrice").val(data.to);
            }
        });

        // Apply Price Filter
        $(".btn-apply-price-filter").on("click", function () {
            let minPrice = $("#minPrice").val();
            let maxPrice = $("#maxPrice").val();
            let currentUrl = window.location.href.split('?')[0];
            window.location.href = currentUrl + "?min_price=" + minPrice + "&max_price=" + maxPrice;
        });

        // Clear Filter
        $(".btn-clear-filter").on("click", function () {
            let currentUrl = window.location.href.split('?')[0];
            window.location.href = currentUrl;
        });
    });
</script>

{{-- flight filtering --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".flight-filter").forEach(function(checkbox) {
            checkbox.addEventListener("change", function() {
                let url = new URL(window.location.href);
                // Uncheck other checkboxes when one is selected
                document.querySelectorAll(".flight-filter").forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
                // Set the selected filter in the URL
                if (this.checked) {
                    url.searchParams.set("flight_filter", this.value);
                } else {
                    url.searchParams.delete("flight_filter");
                }
                window.location.href = url.toString();
            });
        });
    });
</script>

{{-- Countries filter --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".destinations-filter").forEach(function(checkbox) {
            checkbox.addEventListener("change", function() {
                let selectedDestinations = [];
                document.querySelectorAll(".destinations-filter:checked").forEach(function(
                    checkedBox) {
                    selectedDestinations.push(checkedBox.value);
                });
                let url = new URL(window.location.href);
                if (selectedDestinations.length > 0) {
                    url.searchParams.set("destinations", selectedDestinations.join(
                        ","));
                } else {
                    url.searchParams.delete("destinations");
                }
                window.location.href = url.toString();
            });
        });
    });
</script>
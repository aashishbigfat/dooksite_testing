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
      <input type="text" class="js-range-slider" name="my_range" value="0" data-skin="round" data-type="double"
        data-min="{{ request()->get('min_price', 0) }}" data-max="{{ request()->get('max_price', $maxPrice1) }}"
        data-grid="false" />
      <div class="row">
        <div class="col-md-6">
          <div class="from_flight px-2 bg-light">
            <label for="minPrice" class="form-label m-0">Min Price </label>
            <input id="minPrice" type="number" maxlength="4" value="{{ request()->get('min_price', 0) }}"
              class="form-control w-100 bg-transparent from" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="from_flight px-2 bg-light">
            <label for="maxPrice" class="form-label m-0">Max Price </label>
            <input id="maxPrice" type="number" maxlength="4" value="{{ request()->get('max_price', $maxPrice1) }}"
              class="form-control w-100 bg-transparent to" />
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
          <input type="checkbox" class="form-check-input flight-filter" id="without_flight" name="flight_filter"
            value="without_flight" {{ request()->get('flight_filter') == 'without_flight' ? 'checked' : '' }}>
          <label for="without-flight">Without Flight</label>
        </div>
        <div class="filter-option">
          <input type="checkbox" class="form-check-input flight-filter" id="both_flight" name="flight_filter"
            value="both" {{ request()->get('flight_filter') == 'both' ? 'checked' : '' }}>
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
    <div class="filter-content" id="destinations">
      <div class="filter-options">
        @foreach ($destinations as $destination)
        <div class="filter-option">
          <input type="checkbox" class="destinations-filter" name="destinations[]" value="{{ $destination }}" {{
            in_array($destination, $selectedDestinations) ? 'checked' : '' }}>
          <label class="form-check-label" for="dest_{{ $destination }}">{{ $destination }}</label>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
        const $minPriceInput = $("#minPrice");
        const $maxPriceInput = $("#maxPrice");

        const priceSlider = $(".js-range-slider").ionRangeSlider({
            type: "double",
            skin: "round",
            min: {{ $minPrice }},
            max: {{ $maxPrice1 }},
            from: {{ request()->get('min_price', $minPrice) }},
            to: {{ request()->get('max_price', $maxPrice1) }},
            grid: true,
            onFinish: function(data) {
                $minPriceInput.val(data.from);
                $maxPriceInput.val(data.to);
            }
        }).data("ionRangeSlider");

        document.querySelectorAll('input[name="destinations[]"]').forEach(function(checkbox) {
            checkbox.addEventListener("change", function() {
                const url = new URL(window.location.href);
                const selectedDestinations = Array.from(document.querySelectorAll('input[name="destinations[]"]:checked'))
                    .map(cb => cb.value);
                if (selectedDestinations.length > 0) {
                    url.searchParams.set("destinations", selectedDestinations.join(","));
                } else {
                    url.searchParams.delete("destinations");
                }
                url.searchParams.delete("page");
                window.location.href = url.toString();
            });
        });

        document.querySelectorAll(".flight-filter").forEach(function(checkbox) {
            checkbox.addEventListener("change", function() {
                const url = new URL(window.location.href);
                if (this.checked) {
                    url.searchParams.set("flight_filter", this.value);
                } else {
                    url.searchParams.delete("flight_filter");
                }
                url.searchParams.delete("page");
                window.location.href = url.toString();
            });
        });

        $(".btn-apply-price-filter").on("click", function(e) {
            e.preventDefault();
            const url = new URL(window.location.href);
            url.searchParams.set("min_price", $minPriceInput.val());
            url.searchParams.set("max_price", $maxPriceInput.val());
            const selectedDestinations = Array.from(document.querySelectorAll('input[name="destinations[]"]:checked'))
                .map(cb => cb.value);
            if (selectedDestinations.length > 0) {
                url.searchParams.set("destinations", selectedDestinations.join(","));
            }
            const flightFilter = document.querySelector("input[name='flight_filter']:checked");
            if (flightFilter) {
                url.searchParams.set("flight_filter", flightFilter.value);
            }
            url.searchParams.delete("page");
            window.location.href = url.toString();
        });

        $(".btn-clear-filter").on("click", function(e) {
            e.preventDefault();
            document.querySelectorAll('input[name="destinations[]"]').forEach(cb => cb.checked = false);
            document.querySelectorAll(".flight-filter").forEach(cb => cb.checked = false);
            window.location.href = window.location.pathname;
        });
    });
</script>
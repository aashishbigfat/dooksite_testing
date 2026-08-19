<!-- Option 1: Bootstrap Bundle with Popper -->
<?php 
$url = $_SERVER['SERVER_NAME'];
?>
<script>
  var DateFormat = 'd M y';
  var site_url = "<?=  "https://$url/book/" ?>";
</script>
<!-- Bootstrap -->
<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<!-- Plugins -->
<script defer src="{{asset('assets/js/select2.min.js')}}"></script>
<script defer src="{{asset('assets/js/owl.carousel.min.js')}}"></script>
<script   src="{{asset('assets/js/ion.rangeSlider.min.js')}}"></script>

<!-- Local scripts LAST -->
<script   src="{{asset('assets/jquery.ui.js')}}"></script>
<script  src="{{asset('assets/flight/js/flight.js')}}"></script>
<script   src="{{asset('assets/custom.js')}}"></script>
@stack('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("travelDate");
    if (input) {
        input.addEventListener("click", function (e) {
            if (typeof this.showPicker === "function") {
                this.showPicker(); // ✅ For Chrome/Edge
            } else {
                // For Firefox and Safari — blur and refocus workaround
                this.blur();
                setTimeout(() => this.focus(), 50);
            }
        });
    }
});
</script>
<script>

  var $range = $(".js-range-slider"),
    $from = $(".from"),
    $to = $(".to"),
    range,
    min = $range.data("min"),
    max = $range.data("max"),
    from,
    to;

  var updateValues = function () {
    $from.prop("value", from);
    $to.prop("value", to);
  };

  $range.ionRangeSlider({
    onChange: function (data) {
      from = data.from;
      to = data.to;
      updateValues();
    },
  });

  range = $range.data("ionRangeSlider");
  var updateRange = function () {
    range.update({
      from: from,
      to: to,
    });
  };

  $from.on("input", function () {
    from = +$(this).prop("value");
    if (from < min) {
      from = min;
    }
    if (from > to) {
      from = to;
    }
    updateValues();
    updateRange();
  });

  $to.on("input", function () {
    to = +$(this).prop("value");
    if (to > max) {
      to = max;
    }
    if (to < from) {
      to = from;
    }
    updateValues();
    updateRange();
  });
   document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById("commonInquiryForm");
    const submitButton = form.querySelector('button[type="submit"]');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const statusMessage = document.getElementById('statusMessage');
    
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        
        // Start loading state immediately
        startLoadingState();
        
         const formData = new FormData(form);
        
        // Use fetch API for better performance than jQuery
        fetch("{{ route('frontend.common_inquiry_store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            // Success
            showSuccess();
            setTimeout(() => {
                window.location.href = "{{ route('frontend.thank-you') }}";
            }, 1500);
        })
        .catch(error => {
            // Error
            showError();
            console.error('Error:', error);
        });
    });
    
   function startLoadingState() {
    submitButton.disabled = true;
    submitButton.innerHTML = `
        Submitting...
        <span class="spinner-border spinner-border-sm ms-2"></span>
    `;
}
    
   function showSuccess() {
    submitButton.innerHTML = "Submitted ✅";
}

function showError() {
    submitButton.disabled = false;
    submitButton.innerHTML = "Try Again";
}
    
    function showStatusMessage(message, type) {
        statusMessage.textContent = message;
        statusMessage.className = `status-message show ${type}`;
    }
});
  // Function to prevent space key in all fields
  function preventSpaceKey(event) {
    if (event.key === " " || event.code === "Space") {
      event.preventDefault();  // Prevent the default space key behavior
    }
  }

  // Validate name field (only alphabets and spaces allowed)
  document.getElementById("name").addEventListener("input", function (e) {
    const namePattern = /^[A-Za-z ]+$/;
    if (!namePattern.test(e.target.value)) {
      e.target.setCustomValidity("Only alphabets are allowed in name.");
    } else {
      e.target.setCustomValidity("");
    }
  });

  // Prevent space in name field
  document.getElementById("name").addEventListener("keydown", preventSpaceKey);

  // Validate mobile field (exactly 10 digits allowed)
  document.getElementById("mobile").addEventListener("input", function (e) {
    const mobilePattern = /^[0-9]{10}$/;

    if (!mobilePattern.test(e.target.value)) {
      e.target.setCustomValidity("Please enter a valid 10-digit mobile number.");
    } else {
      e.target.setCustomValidity("");
    }
  });

  // Prevent space in mobile field
  document.getElementById("mobile").addEventListener("keydown", preventSpaceKey);

  // Date field - prevent selecting past dates and open calendar on click
  document.getElementById("date").addEventListener("click", function (e) {
    e.target.setCustomValidity("");  // Reset any previous custom validation
  });

  // Set the minimum date to today's date (disable past dates)
  document.getElementById("date").setAttribute("min", new Date().toISOString().split("T")[0]);

  // Prevent space in date field
  document.getElementById("date").addEventListener("keydown", preventSpaceKey);

  // Validate number of travelers field (only allow up to 3 digits)
  document.getElementById("no_of_travelers").addEventListener("input", function (e) {
    const travelerPattern = /^[0-9]{0,3}$/;  // Allow up to 3 digits
    if (!travelerPattern.test(e.target.value)) {
      e.target.setCustomValidity("Please enter a number between 0 and 999.");
    } else {
      e.target.setCustomValidity("");
    }
  });

  // Prevent space in number of travelers field
  document.getElementById("no_of_travelers").addEventListener("keydown", preventSpaceKey);

  // Ensure all fields are mandatory
  document.querySelectorAll('input[required]').forEach(function (field) {
    field.addEventListener('input', function () {
      if (field.checkValidity()) {
        field.setCustomValidity("");  // Clear any previous custom validity messages
      } else {
        field.setCustomValidity("This field is required.");
      }
    });
  });

  let userAgent = navigator.userAgent;
  let browserName;

  if (userAgent.match(/chrome|chromium|crios/i)) {
    browserName = "chrome";
  } else if (userAgent.match(/firefox|fxios/i)) {
    browserName = "firefox";
  } else if (userAgent.match(/safari/i)) {
    browserName = "safari";
  } else if (userAgent.match(/opr\//i)) {
    browserName = "opera";
  } else if (userAgent.match(/edg/i)) {
    browserName = "edge";
  } else {
    browserName = "";
  }
  $("#browserNameM").val(browserName);
  $("#browserName").val(browserName);

</script>
<script>
      // Initialize AOS (Animate On Scroll)
      AOS.init({
        duration: 400,
        easing: "ease-in-out",
        once: true,
        mirror: false,
        offset: 0,
        disable: function () {
          // Disable AOS on small screens for better performance
          return window.innerWidth < 768;
        },
      });

      // Custom scroll animations for elements that don't use AOS
      function initCustomScrollAnimations() {
        const observerOptions = {
          threshold: 0.1,
          rootMargin: "0px 0px -50px 0px",
        };

        const observer = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("animated");
            }
          });
        }, observerOptions);

        // Add custom animations to elements that need them
        document.querySelectorAll(".animate-on-scroll").forEach((el) => {
          observer.observe(el);
        });
      }

      // Enhanced navigation functionality
      document.addEventListener("DOMContentLoaded", function () {
        // Initialize custom scroll animations
        initCustomScrollAnimations();

        // Refresh AOS on window resize
        let resizeTimer;
        window.addEventListener("resize", function () {
          clearTimeout(resizeTimer);
          resizeTimer = setTimeout(function () {
            AOS.refresh();
          }, 250);
        });

        // Mobile navigation functionality
        const navbarCollapse = document.querySelector(".navbar-collapse");
        const dropdownToggles = document.querySelectorAll(
          ".dropdown-toggle-mobile"
        );

        // Handle mobile dropdown toggles
        dropdownToggles.forEach((toggle) => {
          toggle.addEventListener("click", function (e) {
            e.preventDefault();

            // Close other open dropdowns
            dropdownToggles.forEach((otherToggle) => {
              if (
                otherToggle !== toggle &&
                !otherToggle.classList.contains("collapsed")
              ) {
                otherToggle.classList.add("collapsed");
                const otherTarget = document.querySelector(
                  otherToggle.getAttribute("href")
                );
                if (otherTarget) {
                  const bsCollapse = new bootstrap.Collapse(otherTarget, {
                    hide: true,
                  });
                }
              }
            });
          });
        });

        // Enhanced accordion functionality for mobile dropdowns (Places & Experience)
        function setupMobileDropdown(menuId, accordionId) {
          const menu = document.getElementById(menuId);
          if (!menu) return;

          const scrollHint = menu.querySelector(".scroll-hint");

          // Hide scroll hint when user scrolls
          menu.addEventListener("scroll", function () {
            if (scrollHint && this.scrollTop > 30) {
              scrollHint.style.opacity = "0.7";
              scrollHint.style.transform = "scale(0.95)";
              scrollHint.style.transition = "all 0.3s ease";
            } else if (scrollHint && this.scrollTop <= 30) {
              scrollHint.style.opacity = "1";
              scrollHint.style.transform = "scale(1)";
            }
          });

          // Auto-scroll when accordion sections expand
          const accordionButtons = menu.querySelectorAll(".accordion-button");
          accordionButtons.forEach((button) => {
            button.addEventListener("click", function () {
              const target = document.querySelector(
                this.getAttribute("data-bs-target")
              );

              // Wait for accordion animation to complete
              setTimeout(() => {
                if (target && target.classList.contains("show")) {
                  // Scroll the expanded section into view
                  const buttonRect = this.getBoundingClientRect();
                  const menuRect = menu.getBoundingClientRect();
                  const relativeTop =
                    buttonRect.top - menuRect.top + menu.scrollTop;

                  menu.scrollTo({
                    top: relativeTop - 20,
                    behavior: "smooth",
                  });
                }
              }, 350);
            });
          });

          // Add hover effects for links
          const dropdownLinks = menu.querySelectorAll(".dropdown-links a");
          dropdownLinks.forEach((link) => {
            link.addEventListener("mouseenter", function () {
              this.style.backgroundColor = "rgba(220, 53, 69, 0.1)";
              this.style.borderColor = "#dc3545";
              this.style.transform = "translateX(5px)";
              this.style.color = "#dc3545";
            });

            link.addEventListener("mouseleave", function () {
              this.style.backgroundColor = "white";
              this.style.borderColor = "#e9ecef";
              this.style.transform = "translateX(0)";
              this.style.color = "#495057";
            });
          });

          // Add hover effects for view-all links
          const viewAllLinks = menu.querySelectorAll(".view-all-link");
          viewAllLinks.forEach((link) => {
            link.addEventListener("mouseenter", function () {
              this.style.backgroundColor = "#dc3545";
              this.style.color = "white";
              this.style.transform = "translateX(5px)";
            });

            link.addEventListener("mouseleave", function () {
              this.style.backgroundColor = "transparent";
              this.style.color = "#dc3545";
              this.style.transform = "translateX(0)";
            });
          });

          // Show scroll hint when menu is opened
          menu.addEventListener("shown.bs.collapse", function () {
            if (scrollHint) {
              scrollHint.style.opacity = "1";
              scrollHint.style.transform = "scale(1)";
            }
          });
        }

        // Initialize both Places and Experience mobile dropdowns
        setupMobileDropdown("placesMenu", "placesAccordion");
        setupMobileDropdown("experienceMenu", "experienceAccordion");

        // Initialize search tab functionality
        const searchTabButtons = document.querySelectorAll(".search-tab-btn");
        const searchTabContents = document.querySelectorAll(
          ".search-tab-content"
        );

        searchTabButtons.forEach((button) => {
          button.addEventListener("click", () => {
            const targetTab = button.getAttribute("data-tab");

            // Remove active class from all buttons and contents
            searchTabButtons.forEach((btn) => btn.classList.remove("active"));
            searchTabContents.forEach((content) =>
              content.classList.remove("active")
            );

            // Add active class to clicked button and corresponding content
            button.classList.add("active");
            document.getElementById(targetTab).classList.add("active");
          });
        });

        // Initialize trip type selector
        const tripTypeLabels = document.querySelectorAll(".trip-type");
        tripTypeLabels.forEach((label) => {
          label.addEventListener("click", () => {
            tripTypeLabels.forEach((lbl) => lbl.classList.remove("active"));
            label.classList.add("active");
          });
        });

        // Swap airports functionality
        const swapButton = document.querySelector(".swap-button");
        if (swapButton) {
          swapButton.addEventListener("click", () => {
            const fromCity = document.querySelector(
              ".flight-input-group:first-child .city-name"
            );
            const fromAirport = document.querySelector(
              ".flight-input-group:first-child .airport-code"
            );
            const toCity = document.querySelector(
              ".flight-input-group:nth-child(3) .city-name"
            );
            const toAirport = document.querySelector(
              ".flight-input-group:nth-child(3) .airport-code"
            );

            if (fromCity && toCity && fromAirport && toAirport) {
              // Swap city names
              const tempCity = fromCity.textContent;
              fromCity.textContent = toCity.textContent;
              toCity.textContent = tempCity;

              // Swap airport codes
              const tempAirport = fromAirport.textContent;
              fromAirport.textContent = toAirport.textContent;
              toAirport.textContent = tempAirport;
            }
          });
        }


        // Add parallax effect to hero section
        const heroSection = document.querySelector(".hero-section");
        if (heroSection) {
          window.addEventListener("scroll", () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            heroSection.style.transform = `translateY(${rate}px)`;
          });
        }

        // Smooth scrolling for anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach((link) => {
          link.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            if (href !== "#" && href.length > 1) {
              e.preventDefault();
              console.log("Navigate to:", href);
            }
          });
        });

        // Add intersection observer for progressive image loading
        const imageObserver = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              const img = entry.target;
              if (img.dataset.src) {
                img.src = img.dataset.src;
                img.classList.add("loaded");
                imageObserver.unobserve(img);
              }
            }
          });
        });

        // Observe all images for lazy loading
        document.querySelectorAll("img[data-src]").forEach((img) => {
          imageObserver.observe(img);
        });

        // Add scroll progress indicator
        const scrollProgress = document.createElement("div");
        scrollProgress.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, #dc3545, #ff6b35);
        z-index: 9999;
        transition: width 0.3s ease;
    `;
        document.body.appendChild(scrollProgress);

        window.addEventListener("scroll", () => {
          const scrollTop =
            window.pageYOffset || document.documentElement.scrollTop;
          const scrollHeight =
            document.documentElement.scrollHeight - window.innerHeight;
          const progress = (scrollTop / scrollHeight) * 100;
          scrollProgress.style.width = progress + "%";
        });
      });
    </script>
</body>

</html>
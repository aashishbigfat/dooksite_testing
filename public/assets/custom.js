jQuery(document).ready(function () {
    jQuery("#carousel").owlCarousel({
        autoplay: false,
        rewind: false,
        margin: 20,
        loop: false,
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: true,
        navText: [
            // Previous Button with aria-label
            '<button type="button" aria-label="Previous slide" class="owl-prev-btn">' +
                '<i class="fas fa-chevron-left"></i></button>',

            // Next Button with aria-label
            '<button type="button" aria-label="Next slide" class="owl-next-btn">' +
                '<i class="fas fa-chevron-right"></i></button>',
        ],
        responsive: {
            0: {
                items: 1,
                nav: false,
            },
            600: {
                items: 2,
            },
            1024: {
                items: 4,
            },
            1366: {
                items: 4,
            },
        },
        onInitialized: addCarouselAccessibility,
        onChanged: addCarouselAccessibility,
    });

    // Add ARIA labels to dots for accessibility
    function addCarouselAccessibility() {
        jQuery("#carousel .owl-dot").each(function (index) {
            jQuery(this)
                .attr("aria-label", "Go to slide " + (index + 1))
                .attr("role", "button");
        });
    }
});
jQuery(document).ready(function () {
    // Initialize Owl Carousel
    jQuery("#bestselling").owlCarousel({
        autoplay: false,
        rewind: false,
        margin: 20,
        loop: false,
        responsiveClass: true,
        autoHeight: true,
        autoplayTimeout: 7000,
        smartSpeed: 800,
        nav: true,
        navText: [
            '<i class="fas fa-chevron-left"></i>',
            '<i class="fas fa-chevron-right"></i>',
        ],
        responsive: {
            0: {
                items: 1,
                nav: false,
            },
            600: {
                items: 2,
            },
            1024: {
                items: 4,
            },
            1366: {
                items: 4,
            },
        },
        onInitialized: addAccessibility,
        onChanged: addAccessibility,
    });

    // Function to add aria-labels for accessibility
    function addAccessibility(event) {
        jQuery("#bestselling .owl-dot").each(function (index) {
            jQuery(this)
                .attr("aria-label", "Go to slide " + (index + 1))
                .attr("role", "button");
        });
    }
});
jQuery("#special").owlCarousel({
    autoplay: false,
    rewind: false /* use rewind if you don't want loop */,
    margin: 20,
    loop: false,
    /*
  animateOut: 'fadeOut',
  animateIn: 'fadeIn',
  */
    responsiveClass: true,
    autoHeight: true,
    autoplayTimeout: 7000,
    smartSpeed: 800,
    nav: true,
    navText: [
        '<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>',
        '<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>',
    ],
    responsive: {
        0: {
            items: 2,
            nav: false,
        },

        600: {
            items: 2,
        },

        1024: {
            items: 4,
        },

        1366: {
            items: 4,
        },
    },
});
jQuery("#destination").owlCarousel({
    autoplay: false,
    rewind: false /* use rewind if you don't want loop */,
    margin: 20,
    loop: false,
    /*
  animateOut: 'fadeOut',
  animateIn: 'fadeIn',
  */
    responsiveClass: true,
    autoHeight: true,
    autoplayTimeout: 7000,
    smartSpeed: 800,
    nav: true,
    navText: [
        '<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>',
        '<svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>',
    ],
    responsive: {
        0: {
            items: 2,
            nav: false,
        },

        600: {
            items: 4,
        },

        1024: {
            items: 6,
        },

        1366: {
            items: 6,
        },
    },
});
jQuery(document).ready(function () {
    if (jQuery("#blog").length) {
        jQuery("#blog").owlCarousel({
            autoplay: false,
            rewind: false,
            margin: 20,
            loop: false,
            responsiveClass: true,
            autoHeight: true,
            autoplayTimeout: 7000,
            smartSpeed: 800,
            nav: true,
            navText: [
                '<button class="owl-prev-btn" aria-label="Previous Slide"><svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg></button>',
                '<button class="owl-next-btn" aria-label="Next Slide"><svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg></button>',
            ],
            responsive: {
                0: { items: 2, nav: false },
                600: { items: 3 },
                1024: { items: 3 },
                1366: { items: 3 },
            },
            onInitialized: addBlogAccessibility,
            onChanged: addBlogAccessibility,
        });

        function addBlogAccessibility() {
            jQuery("#blog .owl-dot").each(function (index) {
                jQuery(this)
                    .attr("aria-label", "Go to slide " + (index + 1))
                    .attr("role", "button");
            });

            jQuery("#blog .owl-prev").attr("aria-label", "Previous slide");
            jQuery("#blog .owl-next").attr("aria-label", "Next slide");
        }
    }
});

// Enquire Now Modal Functions
function openEnquiryModal() {
    const modal = document.getElementById("enquiryModal");
    modal.style.display = "flex";
    setTimeout(() => {
        modal.classList.add("show");
    }, 10);

    // Prevent body scroll
    document.body.style.overflow = "hidden";
}

function closeEnquiryModal() {
    const modal = document.getElementById("enquiryModal");
    modal.classList.remove("show");
    setTimeout(() => {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }, 300);
}

// Close modal when clicking outside
document.getElementById("enquiryModal").addEventListener("click", function (e) {
    if (e.target === this) {
        closeEnquiryModal();
    }
});

// Close modal with Escape key
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        closeEnquiryModal();
    }
});

// // Handle form submission
// document
//   .getElementById("enquiryForm")
//   .addEventListener("submit", function (e) {
//     e.preventDefault();

//     // Get form data
//     const formData = new FormData(this);
//     const data = {};
//     for (let [key, value] of formData.entries()) {
//       data[key] = value;
//     }

//     // Here you can add your form submission logic
//     console.log("Form submitted with data:", data);

//     // Show success message
//     alert("Thank you for your enquiry! We will get back to you soon.");

//     // Reset form and close modal
//     this.reset();
//     closeEnquiryModal();
//   });

// Set minimum date to today
document.getElementById("date").min = new Date().toISOString().split("T")[0];

// Enhanced navigation functionality
document.addEventListener("DOMContentLoaded", function () {
    // Desktop hover functionality for dropdowns
    if (window.innerWidth >= 992) {
        const dropdownItems = document.querySelectorAll(".nav-item.dropdown");

        dropdownItems.forEach((item) => {
            const dropdownMenu = item.querySelector(".dropdown-menu");
            let hoverTimer;

            // Show dropdown on hover
            item.addEventListener("mouseenter", function () {
                clearTimeout(hoverTimer);
                if (dropdownMenu) {
                    dropdownMenu.style.display = "block";
                    setTimeout(() => {
                        dropdownMenu.style.opacity = "1";
                        dropdownMenu.style.visibility = "visible";
                        dropdownMenu.style.transform =
                            "translateX(-50%) translateY(0)";
                    }, 10);
                }
            });

            // Hide dropdown on mouse leave with slight delay
            item.addEventListener("mouseleave", function () {
                hoverTimer = setTimeout(() => {
                    if (dropdownMenu) {
                        dropdownMenu.style.opacity = "0";
                        dropdownMenu.style.visibility = "hidden";
                        dropdownMenu.style.transform =
                            "translateX(-50%) translateY(-10px)";
                        setTimeout(() => {
                            dropdownMenu.style.display = "none";
                        }, 300);
                    }
                }, 100);
            });
        });
    }

    // Mobile navigation functionality
    const navbarCollapse = document.querySelector(".navbar-collapse");
    const dropdownToggles = document.querySelectorAll(
        ".dropdown-toggle-mobile",
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
                        otherToggle.getAttribute("href"),
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

    // Close mobile menu when clicking on regular nav links
    const regularNavLinks = document.querySelectorAll(
        ".navbar-nav .nav-link:not(.dropdown-toggle-mobile)",
    );
    regularNavLinks.forEach((link) => {
        link.addEventListener("click", () => {
            if (
                window.innerWidth < 992 &&
                navbarCollapse.classList.contains("show")
            ) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                bsCollapse.hide();
            }
        });
    });

    // Close mobile menu when clicking on dropdown links
    const dropdownLinks = document.querySelectorAll(".mobile-dropdown a");
    dropdownLinks.forEach((link) => {
        link.addEventListener("click", () => {
            if (
                window.innerWidth < 992 &&
                navbarCollapse.classList.contains("show")
            ) {
                setTimeout(() => {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                    bsCollapse.hide();
                }, 150);
            }
        });
    });

    // Handle window resize
    window.addEventListener("resize", function () {
        // Close mobile menu if window is resized to desktop
        if (
            window.innerWidth >= 992 &&
            navbarCollapse.classList.contains("show")
        ) {
            const bsCollapse = new bootstrap.Collapse(navbarCollapse);
            bsCollapse.hide();
        }
    });

    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            const href = this.getAttribute("href");
            if (href !== "#" && href.length > 1) {
                e.preventDefault();
                // Add smooth scrolling logic here if needed
                console.log("Navigate to:", href);
            }
        });
    });

    // Initialize animations
    animateOnScroll();
});
// Animation on scroll
function animateOnScroll() {
    const elements = document.querySelectorAll(
        ".animate-on-scroll, .animate-left, .animate-right",
    );

    elements.forEach((element) => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;

        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add("animate");
        }
    });
}

// Initialize animations on page load
window.addEventListener("scroll", animateOnScroll);
window.addEventListener("load", animateOnScroll);

// Tab switching functionality
document.querySelectorAll(".tab-button").forEach((button) => {
    button.addEventListener("click", function () {
        document
            .querySelectorAll(".tab-button")
            .forEach((btn) => btn.classList.remove("active"));
        this.classList.add("active");
    });
});

// Trip type switching
document.querySelectorAll(".trip-type-btn").forEach((link) => {
    link.addEventListener("click", function (e) {
        e.preventDefault();
        document
            .querySelectorAll(".trip-type-btn")
            .forEach((btn) => btn.classList.remove("active"));
        this.classList.add("active");
    });
});

// Filter toggle functions
function toggleFilter(filterId) {
    const filterContent = document.getElementById(filterId);
    const filterHeader = filterContent.previousElementSibling;
    const icon = filterHeader.querySelector("i");

    if (filterContent.classList.contains("collapsed")) {
        filterContent.classList.remove("collapsed");
        icon.style.transform = "rotate(0deg)";
    } else {
        filterContent.classList.add("collapsed");
        icon.style.transform = "rotate(180deg)";
    }
}

// Mobile filter functions
function toggleMobileFilter() {
    const filterSidebar = document.getElementById("filterSidebar");
    const filterOverlay = document.querySelector(".filter-overlay");
    const closeBtn = filterSidebar.querySelector(".close-btn");

    if (window.innerWidth <= 768) {
        filterSidebar.classList.add("show");
        filterOverlay.classList.add("show");
        closeBtn.style.display = "block";
        document.body.style.overflow = "hidden";
    }
}

function closeMobileFilter() {
    const filterSidebar = document.getElementById("filterSidebar");
    const filterOverlay = document.querySelector(".filter-overlay");
    const closeBtn = filterSidebar.querySelector(".close-btn");

    filterSidebar.classList.remove("show");
    filterOverlay.classList.remove("show");
    closeBtn.style.display = "none";
    document.body.style.overflow = "auto";
}
// View button functionality
document.querySelectorAll(".view-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
        const originalText = this.textContent;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        this.disabled = true;

        setTimeout(() => {
            this.textContent = originalText;
            this.disabled = false;
            // alert("Opening tour details...");
        }, 1500);
    });
});
const track = document.getElementById("slideTrack");
const container = document.getElementById("sliderContainer");

// Duplicate slides for smooth infinite effect
track.innerHTML += track.innerHTML;

// Pause on hover
container.addEventListener("mouseenter", () => {
    track.style.animationPlayState = "paused";
});

container.addEventListener("mouseleave", () => {
    track.style.animationPlayState = "running";
});

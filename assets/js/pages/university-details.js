$(document).ready(function() {
    var sidebarShop = $(".sidebar-shop"),
        sidebarToggler = $(".shop-sidebar-toggler"),
        shopOverlay = $(".shop-content-overlay");
    // show sidebar
    sidebarToggler.on("click", function() {
        sidebarShop.toggleClass("show");
        var header = $(".content-header");
        var heightofheader = header[0].offsetHeight - 80;
        sidebarShop.css({ top: heightofheader });
        shopOverlay.toggleClass("show");
    });

    // remove sidebar
    $(".shop-content-overlay, .sidebar-close-icon").on("click", function() {
        sidebarShop.removeClass("show");
        sidebarShop.css({ top: "0rem" });
        shopOverlay.removeClass("show");
    });
    $("details").on("click", function() {
        sidebarShop.removeClass("show");
        shopOverlay.removeClass("show");
    })
    $(window).on("resize", function() {
        if ($(window).width() <= 991) {
            $(".sidebar-shop").removeClass("show");
            $(".shop-content-overlay").removeClass("show");
        } else {
            $(".sidebar-shop").addClass("show");
        }
    });
    $("#confirm-text").on("click", function() {
        Swal.fire({
            title: "Are you sure?",
            text: "You want to enroll this Course",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#0084ff",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function(t) {
            t.value && Swal.fire({
                type: "success",
                title: "Enrolled",
                text: "Your have been Successfully Enrolled",
                confirmButtonClass: "btn btn-success"
            });
        });
    });
    var mySwiper14 = new Swiper('.swiper-responsive-breakpoints', {
        slidesPerView: 5,
        spaceBetween: 55,
        // init: false,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            1600: {
                slidesPerView: 4,
                spaceBetween: 55,
            },
            1300: {
                slidesPerView: 3,
                spaceBetween: 55,
            },
            900: {
                slidesPerView: 2,
                spaceBetween: 55,
            },
            768: {
                slidesPerView: 1,
                spaceBetween: 55,
            }
        }
    });

    const $app = $('.app');
    const $img = $('.app__img');
    const $pageNav1 = $('.pages__item--1');
    const $pageNav2 = $('.pages__item--2');
    let animation = true;
    let curSlide = 1;
    let scrolledUp, nextSlide;

    let pagination = function(slide, target) {
        animation = true;
        if (target === undefined) {
            nextSlide = scrolledUp ? slide - 1 : slide + 1;
        } else {
            nextSlide = target;
        }

        $('.pages__item--' + nextSlide).addClass('page__item-active');
        $('.pages__item--' + slide).removeClass('page__item-active');

        $app.toggleClass('active');
        setTimeout(function() {
            animation = false;
        }, 3000);
    };

    let navigateDown = function() {
        if (curSlide > 1) return;
        scrolledUp = false;
        pagination(curSlide);
        curSlide++;
    };

    let navigateUp = function() {
        if (curSlide === 1) return;
        scrolledUp = true;
        pagination(curSlide);
        curSlide--;
    };

    setTimeout(function() {
        $app.addClass('initial');
    }, 1500);

    setTimeout(function() {
        animation = false;
    }, 4500);

    $(document).on('mousewheel DOMMouseScroll', function(e) {
        var delta = e.originalEvent.wheelDelta;
        if (animation) return;
        if (delta > 0 || e.originalEvent.detail < 0) {
            navigateUp();
        } else {
            navigateDown();
        }
    });

    $(document).on("click", ".pages__item:not(.page__item-active)", function() {
        if (animation) return;
        let target = +$(this).attr('data-target');
        pagination(curSlide, target);
        curSlide = target;
    });

    var zindex = 10;

    $("div.card-course").click(function(e) {
        e.preventDefault();

        var isShowing = false;

        if ($(this).hasClass("show")) {
            isShowing = true
        }

        if ($("div.cards").hasClass("showing")) {
            // a card is already in view
            $("div.card-course.show")
                .removeClass("show");

            if (isShowing) {
                // this card was showing - reset the grid
                $("div.cards")
                    .removeClass("showing");
            } else {
                // this card isn't showing - get in with it
                $(this)
                    .css({ zIndex: zindex })
                    .addClass("show");

            }

            zindex++;

        } else {
            // no cards in view
            $("div.cards")
                .addClass("showing");
            $(this)
                .css({ zIndex: 1 })
                .addClass("show");
        }

    });


    $("[data-toggle=popover]").popover({
        html: true,
        trigger: 'hover',
        content: function() {
            var content = $(this).attr("data-popover-content");
            return $(content).children(".popover-body").html();
        }
    });

});
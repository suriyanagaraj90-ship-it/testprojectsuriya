//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

$(".next").click(function() {
    if (animating) return false;
    animating = true;

    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //activate next step on progressbar using the index of next_fs
    $("#progressbar-register li").eq($("fieldset").index(next_fs)).addClass("active");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate({ opacity: 0 }, {
        step: function(now, mx) {
            //as the opacity of current_fs reduces to 0 - stored in "now"
            //1. scale current_fs down to 80%
            scale = 1 - (1 - now) * 0.2;
            //2. bring next_fs from the right(50%)
            left = (now * 50) + "%";
            //3. increase opacity of next_fs to 1 as it moves in
            opacity = 1 - now;
            current_fs.css({
                'transform': 'scale(' + scale + ')',
                'position': 'absolute'
            });
            next_fs.css({ 'left': left, 'opacity': opacity });
        },
        duration: 800,
        complete: function() {
            current_fs.hide();
            animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInCubic'
    });
});

$(".previous").click(function() {
    if (animating) return false;
    animating = true;

    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    //de-activate current step on progressbar
    $("#progressbar-register li").eq($("fieldset").index(current_fs)).removeClass("active");

    //show the previous fieldset
    previous_fs.show();
    //hide the current fieldset with style
    current_fs.animate({ opacity: 0 }, {
        step: function(now, mx) {
            //as the opacity of current_fs reduces to 0 - stored in "now"
            //1. scale previous_fs from 80% to 100%
            scale = 0.8 + (1 - now) * 0.2;
            //2. take current_fs to the right(50%) - from 0%
            left = ((1 - now) * 50) + "%";
            //3. increase opacity of previous_fs to 1 as it moves in
            opacity = 1 - now;
            current_fs.css({ 'left': left });
            previous_fs.css({ 'transform': 'scale(' + scale + ')', 'opacity': opacity });
        },
        duration: 800,
        complete: function() {
            current_fs.hide();
            animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
    });
});
$(".submit").click(function() {
    return false;
})
jQuery(document).ready(function($) {

    startSlider($('#slider'), 30); // Slide container ID, SlideShow interval 

    function startSlider(obj, timer) {

        var obj, timer;
        var id = "#" + obj.attr("id");
        var slideCount = obj.find('ul li').length;
        slideWidth = obj.attr("data-width");
        var sliderUlWidth = (slideCount + 1) * slideWidth;
        var time = 2;
        var $bar,

            isPause,
            tick,
            percentTime;
        isPause = false; //false for auto slideshow

        $bar = obj.find('.progress .bar');

        function startProgressbar() {
            resetProgressbar();
            percentTime = 0;
            tick = setInterval(interval, timer);
        }

        function interval() {
            if (isPause === false) {
                percentTime += 1 / (time + 0.1);
                $bar.css({
                    width: percentTime + "%"
                });
                if (percentTime >= 100) {
                    moveRight();
                    startProgressbar();
                }
            }
        }

        function resetProgressbar() {
            $bar.css({
                width: 0 + '%'
            });
            clearTimeout(tick);
        }

        function startslide() {

            $(id + ' ul li:last-child').prependTo(id + ' ul');
            obj.find('ul').css({
                width: sliderUlWidth + 'vw',
                marginLeft: -slideWidth + 'vw'
            });

            obj.find('ul li:last-child').appendTo(obj.attr('id') + ' ul');

        }

        if (slideCount > 1) {
            startslide();
            startProgressbar();
        } else { // hade navigation buttons for 1 slide only
            $(id + ' button.control_prev').hide();
            $(id + ' button.control_next').hide();
        }




        function moveLeft() {
            $(id + ' ul').css({
                transition: "1s",
                transform: "translateX(" + slideWidth + "vw)"
            });

            setTimeout(function() {

                $(id + ' ul li:last-child').prependTo(id + ' ul');
                $(id + ' ul').css({
                    transition: "none",
                    transform: "translateX(" + 0 + "vw)"
                });

                $('li.actslide').prev().addClass('actslide').next().removeClass('actslide');
            }, 1000);

        }

        function moveRight2() { // fix for only 2 slades
            $(id + ' ul li:first-child').appendTo(id + ' ul');


            $(id + ' ul').css({
                transition: "none",
                transform: "translateX(100vw)"
            }).delay();

            setTimeout(function() {

                $(id + ' ul').css({
                    transition: "1s",
                    transform: "translateX(0vw)"
                });


            }, 100, setTimeout(function() {


                $(id + ' ul').css({
                    transition: "none",
                    transform: "translateX(0vw)"
                });
                $('li.actslide').next().addClass('actslide').prev().removeClass('actslide');

            }, 1000));




        }

        function moveRight() {
            if (slideCount > 2) {
                $(id + ' ul').css({
                    transition: "1s",
                    transform: "translateX(" + (-1) * slideWidth + "vw)"
                });

                setTimeout(function() {

                    $(id + ' ul li:first-child').appendTo(id + ' ul');
                    $(id + ' ul').css({
                        transition: "none",
                        transform: "translateX(" + 0 + "vw)"
                    });

                    $('li.actslide').next().addClass('actslide').prev().removeClass('actslide');
                }, 1000);
            } else {
                moveRight2();
            }
        }

        $(id + ' button.control_prev').click(function() {
            moveLeft();
            startProgressbar();
        });

        $(id + ' button.control_next').click(function() {

            moveRight();

            startProgressbar();
        });

        $(id + ' .progress').click(function() {
            if (isPause === false) {
                isPause = true;
            } else {
                isPause = false;
            }
        });
    };
});
$(function () {
    var left = $('.left');
    var right = $('.right');
    var bg = $('.bgDiv');
    var leftNav = $('.leftNav');
    var rightNav = $('.rightNav');
    showNav(left, leftNav, "left");
    showNav(right,rightNav, "right");
    function showNav(btn, navDiv, direction) {
        btn.on('click', function () {
            bg.css({
                display: "block",
                transition: "opacity .5s"
            });
            if (direction == "left") {
                navDiv.css({
                    left: "0px",
                    transition: "left 1s"
                });
            }else if (direction == "right") {
                navDiv.css({
                    right:"0px",
                    transition:"right 1s"
                })
            };

        });
    }
    $('span').each(function () {
        var dom = $(this);
        dom.on('click', function () {
            hideNav();
            alert(dom.text())
        });
    });
    bg.on('click', function () {
        hideNav();
    });
    function hideNav() {
        leftNav.css({
            left: "-50%",
            transition: "left .5s"
        });
        rightNav.css({
            right: "-50%",
            transition:"right .5s"
        })   
        bg.css({
            display: "none",
            transition: "display 1s"
        });
    }



});
$(function () {
    var left = $('.left');
    var bg = $('.bgDiv');
    var leftNav = $('.leftNav');
    showNav(left, leftNav, "left");
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
            }
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
        bg.css({
            display: "none",
            transition: "display 1s"
        });
    }



});
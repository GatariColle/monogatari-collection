(function() {
    let horizontalScrollables = document.querySelectorAll('.horizontal-scrollable')
    function scrollHorizontally(e) {
        e = window.event || e;
        let delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
        e.currentTarget.scrollLeft -= (delta * 40); // Multiplied by 40
        e.preventDefault();
    }

    for (let item of horizontalScrollables) {
        if (item.addEventListener) {
            // IE9, Chrome, Safari, Opera
            item.addEventListener('mousewheel', scrollHorizontally, false);
            // Firefox
            item.addEventListener('DOMMouseScroll', scrollHorizontally, false);
        } else {
            // IE 6/7/8
            item.attachEvent('onmousewheel', scrollHorizontally);
        }
    }
})();

(function () {
    let drawerToggler = document.querySelector('.drawer-toggler');
    function toggleDrawer(e) {
        let drawer = document.querySelector('.drawer');
        let wrapper = document.querySelector('.wrapper');

        drawer.classList.toggle('drawer-expanded');
        wrapper.classList.toggle('wrapper-shrinked');
        e.preventDefault();
    }

    drawerToggler.addEventListener('click', toggleDrawer, false);
})();
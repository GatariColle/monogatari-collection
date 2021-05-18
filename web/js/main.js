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

    if (!drawerToggler)
        return;

    function toggleDrawer(e) {
        let drawer = document.querySelector('.drawer');
        let wrapper = document.querySelector('.wrapper');

        drawer.classList.toggle('drawer-expanded');
        wrapper.classList.toggle('wrapper-shrinked');
        e.preventDefault();
    }

    drawerToggler.addEventListener('click', toggleDrawer, false)
})();

(function () {
    let applyFiltersButton = document.getElementById("apply-filters-button")

    if (!applyFiltersButton)
        return;

    function updateFormAction() {
        let genreTags = document.querySelectorAll('input[name=genre-checkbox]:checked')
        let genres = [];
        genreTags.forEach(ch => genres.push(ch.defaultValue))

        let form = document.getElementById('search-filters-form')

        let baseUrl = form.action.split('?')[0]

        genres.length === 0
            ? form.action = baseUrl
            : form.action = `${baseUrl}?genres=${genres.toString()}`

        console.log(form.action)
    }

    applyFiltersButton.addEventListener('click', updateFormAction, false)
})();


function renderMessage(message) {
    let form = document.querySelector('form')

    if (!form)
        return

    if (!form.querySelector('#message')) {
        let html = `<div id="message">${message}</div>`
        form.insertAdjacentHTML('beforeend', html)
    }
}


(function () {
    let userRegisterButton = document.getElementById("user-register-button");

    if (!userRegisterButton)
        return;

    function checkPasswordsMatching(e) {
        let form = document.querySelector('form')

        if (form.action.endsWith("/register")) {
            if (form.querySelector('#password').value !== form.querySelector('#password-confirmation').value) {
                renderMessage('Пароли не совпадают')
                e.preventDefault()
            }
        }
    }

    userRegisterButton.addEventListener('click', checkPasswordsMatching)
})();
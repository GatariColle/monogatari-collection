function renderMessage(message) {
    let form = document.querySelector('form')

    if (!form)
        return

    let messageDiv = form.querySelector('#message');

    if (!messageDiv) {
        let html = `<div id="message">${message}</div>`
        form.insertAdjacentHTML('beforeend', html)
    } else {
        messageDiv.textContent = message;
    }
}

function submitForm(formId) {
    document.getElementById(formId).submit();
}

(function () {
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

(function () {
    let newPostButton = document.getElementById('newPostButton')

    if (!newPostButton)
        return

    newPostButton.addEventListener('click', insertForm);
})();

(function () {
    let showPaymentFormButton = document.getElementById('show-payment-form-button');
    if (!showPaymentFormButton)
        return

    let insertPaymentForm = () => {
        let paymentForm = '<div class="card p-1">\n' +
            '            <h1>Оплата</h1>\n' +
            '            <form action="/subscribe" method="post" style="width: 30%;">\n' +
            '                <div class="form-group">\n' +
            '                    <label for="card-no">Номер карты</label>\n' +
            '                    <input type="text" name="card-no" id="card-no" placeholder="Номер банковской карты" required>\n' +
            '                    <p id="card-no-msg"></p>\n' +
            '                </div>\n' +
            '\n' +
            '                <div class="form-group">\n' +
            '                    <label for="cvv-cvc">CVV/CVC</label>\n' +
            '                    <input type="number" name="cvv-cvc" id="cvv-cvc" placeholder="CVV/CVC с обратной стороны карты" required min="0">\n' +
            '                    <p id="cvv-cvc-msg"></p>\n' +
            '                </div>\n' +
            '            <button type="submit" class="btn btn-primary">Оплатить</button>\n' +
            '            </form>\n' +
            '        </div>'

        document.querySelector('.container > .card').insertAdjacentHTML('afterend', paymentForm);
        document.querySelector('.container > .card').remove();

        let paymentFormNode = document.querySelector("form[action='/subscribe']")

        let cardNoInput = paymentFormNode.querySelector('#card-no')

        cardNoInput.addEventListener('blur', (e) => {
            let cardNo = cardNoInput.value
            if (validateCardNo(cardNo))
                document.querySelector("p#card-no-msg").textContent = 'Отлично!'
            else
                document.querySelector("p#card-no-msg").textContent = 'Некорректный номер карты'
        })
    }

    showPaymentFormButton.addEventListener('click', insertPaymentForm)
})();

let setTheme = (theme) => {

    localStorage.setItem('theme', theme)

    let body = document.querySelector('body')
    body.classList.remove('light')
    body.classList.remove('dark')
    body.classList.add(theme)

    let themeSwitcherContainer = document.getElementById('theme-switcher-container')

    if (!themeSwitcherContainer)
        return

    let html = ''
    switch (theme) {
        case 'dark':
            html = '<svg width="24" height="24" viewBox="0 0 219.786 219.786" xmlns="http://www.w3.org/2000/svg"><path d="M109.881,183.46c-4.142,0-7.5,3.358-7.5,7.5v21.324c0,4.142,3.358,7.5,7.5,7.5c4.143,0,7.5-3.358,7.5-7.5V190.96 C117.381,186.817,114.023,183.46,109.881,183.46z"/><path d="M109.881,36.329c4.143,0,7.5-3.358,7.5-7.5V7.503c0-4.142-3.357-7.5-7.5-7.5c-4.142,0-7.5,3.358-7.5,7.5v21.326 C102.381,32.971,105.739,36.329,109.881,36.329z"/><path d="M47.269,161.909l-15.084,15.076c-2.93,2.928-2.931,7.677-0.003,10.606c1.465,1.465,3.385,2.198,5.305,2.198 c1.919,0,3.837-0.732,5.302-2.195l15.084-15.076c2.93-2.928,2.931-7.677,0.003-10.606 C54.946,158.982,50.198,158.982,47.269,161.909z"/><path d="M167.208,60.067c1.919,0,3.838-0.732,5.303-2.196l15.082-15.076c2.929-2.929,2.93-7.677,0.002-10.607 c-2.929-2.93-7.677-2.931-10.607-0.001l-15.082,15.076c-2.929,2.928-2.93,7.677-0.002,10.606 C163.368,59.335,165.288,60.067,167.208,60.067z"/><path d="M36.324,109.895c0-4.142-3.358-7.5-7.5-7.5H7.5c-4.142,0-7.5,3.358-7.5,7.5c0,4.142,3.358,7.5,7.5,7.5h21.324 C32.966,117.395,36.324,114.037,36.324,109.895z"/><path d="M212.286,102.395h-21.334c-4.143,0-7.5,3.358-7.5,7.5c0,4.142,3.357,7.5,7.5,7.5h21.334c4.143,0,7.5-3.358,7.5-7.5 C219.786,105.754,216.429,102.395,212.286,102.395z"/><path d="M47.267,57.871c1.464,1.464,3.384,2.196,5.303,2.196c1.919,0,3.839-0.732,5.303-2.196c2.929-2.929,2.929-7.678,0-10.607 L42.797,32.188c-2.929-2.929-7.678-2.929-10.606,0c-2.929,2.929-2.929,7.678,0,10.606L47.267,57.871z"/><path d="M172.52,161.911c-2.929-2.929-7.678-2.93-10.607-0.001c-2.93,2.929-2.93,7.678-0.001,10.606l15.074,15.076 c1.465,1.465,3.384,2.197,5.304,2.197c1.919,0,3.839-0.732,5.303-2.196c2.93-2.929,2.93-7.678,0.001-10.606L172.52,161.911z"/><path d="M109.889,51.518c-32.187,0-58.373,26.188-58.373,58.377c0,32.188,26.186,58.375,58.373,58.375 c32.19,0,58.378-26.187,58.378-58.375C168.267,77.706,142.078,51.518,109.889,51.518z M109.889,153.27 c-23.916,0-43.373-19.458-43.373-43.375c0-23.918,19.457-43.377,43.373-43.377c23.919,0,43.378,19.459,43.378,43.377 C153.267,133.812,133.808,153.27,109.889,153.27z"/></svg>' +
                '<span><a href="#" id="theme-toggler" onclick="toggleTheme()">Светлая тема</a></span>'
            break
        default:
            html = '<svg height="24" width="24" viewBox="0 0 512.001 512.001" xmlns="http://www.w3.org/2000/svg"><path d="m406 151.001c8.284 0 15-6.716 15-15 0-24.813 20.187-45 45-45 8.284 0 15-6.716 15-15s-6.716-15-15-15c-24.393 0-45-21.065-45-46 0-8.284-6.716-15-15-15s-15 6.716-15 15c0 24.935-20.607 46-45 46-8.284 0-15 6.716-15 15s6.716 15 15 15c24.813 0 45 20.187 45 45 0 8.284 6.716 15 15 15zm-15.253-75.15c5.784-4.41 10.865-9.568 15.253-15.479 4.387 5.91 9.468 11.069 15.253 15.479-5.781 4.312-10.922 9.437-15.253 15.203-4.331-5.767-9.472-10.891-15.253-15.203z"/><path d="m301 106.001c0-8.284-6.716-15-15-15s-15 6.716-15 15c0 41.355-33.645 75-75 75-8.284 0-15 6.716-15 15s6.716 15 15 15c41.355 0 75 33.645 75 75 0 8.284 6.716 15 15 15s15-6.716 15-15c0-41.355 33.645-75 75-75 8.284 0 15-6.716 15-15s-6.716-15-15-15c-41.355 0-75-33.645-75-75zm-15 125.972c-8.871-14.722-21.25-27.101-35.971-35.972 14.722-8.871 27.1-21.25 35.971-35.972 8.871 14.722 21.25 27.101 35.971 35.972-14.721 8.871-27.1 21.249-35.971 35.972z"/><path d="m256 512.001c128.638 0 238.83-96.522 255.862-221.298.946-6.93-3.022-13.593-9.566-16.063-6.542-2.469-13.924-.09-17.793 5.737-33.016 49.73-91.835 80.624-153.503 80.624-99.252 0-180-80.748-180-180 0-61.668 30.893-120.487 80.624-153.503 5.826-3.868 8.207-11.25 5.737-17.793-2.469-6.543-9.131-10.511-16.063-9.566-124.939 17.055-221.298 127.397-221.298 255.862 0 140.959 115.05 256 256 256zm-87.774-466.347c-29.922 37.658-47.226 85.737-47.226 135.347 0 115.794 94.206 210 210 210 49.61 0 97.688-17.304 135.347-47.226-34.932 81.747-117.091 138.226-210.347 138.226-124.617 0-226-101.383-226-226 0-93.256 56.479-175.415 138.226-210.347z"/></svg>' +
                '<span><a href="#" id="theme-toggler" onclick="toggleTheme()">Тёмная тема</a></span>'

    }
    while (themeSwitcherContainer.firstChild) {
        themeSwitcherContainer.removeChild(themeSwitcherContainer.firstChild)
    }
    themeSwitcherContainer.insertAdjacentHTML('beforeend', html)
}

let toggleTheme = () => {
    let theme = localStorage.getItem('theme')
    if (theme === 'light') {
        setTheme('dark')
    } else {
        setTheme('light')
    }
};

(function () {
    let theme = localStorage.getItem('theme') ?? 'light'

    setTheme(theme)
})();

let validateCardNo = function (no) {
    return (no && checkLuhn(no) &&
        no.length === 16 && (parseInt(no[0]) === 4 || parseInt(no[0]) === 5 && no[1] >= 1 && no[1] <= 5 ||
            (no.indexOf("6011") === 0 || no.indexOf("65") === 0)) ||
        no.length === 15 && (no.indexOf("34") === 0 || no.indexOf("37") === 0) ||
        no.length === 13 && parseInt(no[0]) === 4)
}
let checkLuhn = function (cardNo) {
    let s = 0;
    let doubleDigit = false;
    for (let i = cardNo.length - 1; i >= 0; i--) {
        let digit = +cardNo[i];
        if (doubleDigit) {
            digit *= 2;
            if (digit > 9)
                digit -= 9;
        }
        s += digit;
        doubleDigit = !doubleDigit;
    }
    return parseInt(s) % 10 === 0;
}


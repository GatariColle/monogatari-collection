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

    let insertForm = () => {
        newPostButton.insertAdjacentHTML('afterend',
            '<form action="/newpost?thread_id={{ title.title_id }}" method="post"> \
                <div class="form-group"> \
                                <textarea name="newpost-message" class="newpost-message" cols="30" rows="10" \
                                          placeholder="Текст поста" maxLength="500" required></textarea> \
                </div> \
                <button type="submit" class="btn btn-primary ml-auto">Опубликовать</button> \
                </form>'
        )
        newPostButton.remove()
    }
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

let toggleTheme = () => {
    let theme = localStorage.getItem('theme')
    let themeSwitcherContainer = document.getElementById('theme-switcher-container')
    let body = document.querySelector('body')
    let text = ''
    if (theme === 'light') {
        body.classList.remove('light')
        body.classList.add('dark')
        theme = 'dark'
        text = 'Светлая тема'
    } else {
        body.classList.remove('dark')
        body.classList.add('light')
        theme = 'light'
        text = 'Тёмная тема'
    }
    localStorage.setItem('theme', theme)
    themeSwitcherContainer.querySelector('p').textContent = text
};

(function () {
    let theme = localStorage.getItem('theme')

    if (!theme)
        localStorage.setItem('theme', 'light')

    document.querySelector('body').classList.add(theme)

    let themeSwitcherContainer = document.getElementById('theme-switcher-container')

    if (!themeSwitcherContainer)
        return

    let html = ''
    switch (theme) {
        case 'dark':
            html = '<p>Светлая тема</p>'
            break
        default:
            html = '<p>Тёмная тема</p>'

    }
    themeSwitcherContainer.insertAdjacentHTML('beforeend', html)
    themeSwitcherContainer.querySelector('p').addEventListener('click', toggleTheme)
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


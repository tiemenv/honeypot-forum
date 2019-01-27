function init() {
    // Generate li foreach fieldset
    for (let i = 0; i < count; i++) {
        var ul = document.querySelector('ul.items'),
            li = document.createElement("li");

        ul.appendChild(li);
    }
    // Add class active on first li
    ul.firstChild.classList.add('active');
}

function next(target) {
    let input = target.previousElementSibling;

    // Check if input is empty
    if (input.value === '') {
        body.classList.add('error');
    } else {
        body.classList.add('error');
        body.classList.remove('error');
        if (input.id === 'password') {
            document.querySelector("form").submit();
        } else {
            let enable = document.querySelector('form fieldset.enable'),
                nextEnable = enable.nextElementSibling;
            enable.classList.remove('enable');
            enable.classList.add('disable');
            nextEnable.classList.add('enable');
            nextEnable.childNodes[3].focus();
        }

        // Switch active class on left list
        let active = document.querySelector('ul.items li.active'),
            nextActive = active.nextElementSibling;
        active.classList.remove('active');
        nextActive.classList.add('active');
    }
}

function keyDown(event) {
    let key = event.keyCode,
        target = document.querySelector('fieldset.enable .button');
    if (key == 13 || key == 9) next(target);
}

let body = document.querySelector('body'),
    form = document.querySelector('form'),
    count = form.querySelectorAll('fieldset').length;

window.onload = init;
document.body.onmouseup = function (event) {
    let target = event.target || event.toElement;
    if (target.classList.contains("button")) next(target);
};
document.addEventListener("keydown", keyDown, false);
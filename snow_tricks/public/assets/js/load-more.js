let script = document.getElementById("load-more-script");

let pageTotal = script.getAttribute("data-page-total");
let href = script.getAttribute("data-page-href");
let containerId = script.getAttribute("data-container");

let btn = document.querySelector('#load-more');
let url = new URL(btn.href);
let index = url.searchParams.get('page') + 1;

btn.addEventListener('click', onClickBtn);

function onClickBtn(event) {
    event.preventDefault();

    fetch(this.getAttribute("href"), {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "text/html",
        }
    }).then(function (response) {
        return response.text();
    }).then(function (data) {
        appendData(data);
    }).catch(function (err) {
        console.log(err);
    });
}

function appendData(data) {

    var container = document.getElementById(containerId);

    index++;
    btn.href = '/' + href + '/' + (index + 1);
    container.innerHTML += data;

    if (index >= pageTotal) {
        btn.remove();
    }
}
let pageTotal = document.getElementById("load-more-script").getAttribute("data-page-total");
let href = document.getElementById("load-more-script").getAttribute("data-page-href");
let btn = document.querySelector('#load-more');
let url = new URL(btn.href);
let index = url.searchParams.get('page') + 1;

btn.addEventListener('click', onClickBtn);

function onClickBtn(event) {
    event.preventDefault();

    // On envoie une requête Ajax vers le href du lien avec la méthode GET
    fetch(this.getAttribute("href"), {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "text/html"
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

    var container = document.getElementById('comments-container');

    index++;
    btn.href = '/' + href + '/' + (index + 1);
    container.innerHTML += data;

    if (index >= pageTotal) {
        btn.remove();
    }
}
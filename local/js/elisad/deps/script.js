document.addEventListener('DOMContentLoaded', () => {
    let result1 = sum(3, 4);
    let result2 = multiplication(3, 4);

    let txt = '3 + 4 = ' + result1;
    txt += '<br>';
    txt += '3 * 4 = ' + result2;
    let html = '<h1 class="hello-world">' + txt + '</h1>';
    document.querySelector('#application').innerHTML = html;
});
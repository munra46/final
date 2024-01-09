function change(button) {
    var form = button.nextElementSibling;
    form.classList.toggle('hidden');
    if (form.classList.contains('hidden')) {
        button.textContent = '表示';
    } else {
        button.textContent = '非表示';
    }
}
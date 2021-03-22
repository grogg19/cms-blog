/**
 *  При событии onsubmit у формы formForValidation, создаем ajax запрос в form_action.php
 *  и методом PostController передаем в него данные объекта FormData(formForValidation).
 *  Данные получаем в формате JSON, результат выводим в DIV с идентификатором messageField.
 */
formForValidation.onsubmit = async (e) => {
    e.preventDefault();

    let response = await fetch('/homework5/ajax/form_action.php', {
        method: 'POST',
        body: new FormData(formForValidation)
    });

    let result = await response.json();

    if (result.message !== true) {
        if (result.code == 1) {
            messageField.className = "green";
        } else if (result.code == 2) {
            messageField.className = "red";
        }
        messageField.innerHTML = result.message;
    }
};
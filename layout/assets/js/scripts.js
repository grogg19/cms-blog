$.fancybox.defaults.parentEl = 'footer';

$('#datetimepicker').datetimepicker({
    uiLibrary: 'bootstrap4',
    modal: true,
    footer: true
});

/**
 *  При событии onsubmit у формы formForValidation, создаем ajax запрос в form_action.php
 *  и методом PostController передаем в него данные объекта FormData(formForValidation).
 *  Данные получаем в формате JSON, результат выводим в DIV с идентификатором messageField.
 */

$(document).on('ajaxSuccess', function(e){
    listenButtonClick();
});

function listenButtonClick() {
    const buttons = document.querySelectorAll('[type=submit]');
    const quill = document.querySelector('#form_editor');

    if(buttons) {
        buttons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();

                const form = document.querySelector('#'+button.getAttribute('data-form'));
                const formData = new FormData(form);

                if (quill) {
                    formData.append("content", quill.firstChild.innerHTML);
                }

                let response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                let result = await response.json();

                if(result.url) {
                    location.href = result.url;
                }

                document.querySelectorAll('.form-element').forEach(item => {
                    item.removeAttribute('tooltip');
                });

                if (result.error && Object.keys(result.error).length > 0) {
                    for (let key in result.error) {
                        document.querySelector('.div-' + result.error[key].field).setAttribute('tooltip', result.error[key].errorMessage);
                    }
                    jump(Object.keys(result.error).shift());
                }

                if(result.toast) {
                    await getToast(result.toast.typeToast, result.toast.dataToast);
                }
                //console.log(result);
            });
        });
    }
    const inputs = document.querySelectorAll('[type=text], [type=password], [type=email]');

    inputs.forEach(item => {
        item.addEventListener('focus', async (e) => {
            document.querySelector('.div-' + item.name).setAttribute('tooltip', '');
        });
    });
}

function slugify(s, opt) {
    s = String(s);
    opt = Object(opt);

    var defaults = {
        'delimiter': '-',
        'limit': undefined,
        'lowercase': true,
        'replacements': {},
        'transliterate': (typeof(XRegExp) === 'undefined') ? true : false
    };

    // Merge options
    for (var k in defaults) {
        if (!opt.hasOwnProperty(k)) {
            opt[k] = defaults[k];
        }
    }

    var char_map = {
        // Latin
        'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE', 'Ç': 'C',
        'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I',
        'Ð': 'D', 'Ñ': 'N', 'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', 'Ő': 'O',
        'Ø': 'O', 'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ű': 'U', 'Ý': 'Y', 'Þ': 'TH',
        'ß': 'ss',
        'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae', 'ç': 'c',
        'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i',
        'ð': 'd', 'ñ': 'n', 'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ő': 'o',
        'ø': 'o', 'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ű': 'u', 'ý': 'y', 'þ': 'th',
        'ÿ': 'y',

        // Latin symbols
        '©': '(c)',

        // Greek
        'Α': 'A', 'Β': 'B', 'Γ': 'G', 'Δ': 'D', 'Ε': 'E', 'Ζ': 'Z', 'Η': 'H', 'Θ': '8',
        'Ι': 'I', 'Κ': 'K', 'Λ': 'L', 'Μ': 'M', 'Ν': 'N', 'Ξ': '3', 'Ο': 'O', 'Π': 'P',
        'Ρ': 'R', 'Σ': 'S', 'Τ': 'T', 'Υ': 'Y', 'Φ': 'F', 'Χ': 'X', 'Ψ': 'PS', 'Ω': 'W',
        'Ά': 'A', 'Έ': 'E', 'Ί': 'I', 'Ό': 'O', 'Ύ': 'Y', 'Ή': 'H', 'Ώ': 'W', 'Ϊ': 'I',
        'Ϋ': 'Y',
        'α': 'a', 'β': 'b', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'h', 'θ': '8',
        'ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': '3', 'ο': 'o', 'π': 'p',
        'ρ': 'r', 'σ': 's', 'τ': 't', 'υ': 'y', 'φ': 'f', 'χ': 'x', 'ψ': 'ps', 'ω': 'w',
        'ά': 'a', 'έ': 'e', 'ί': 'i', 'ό': 'o', 'ύ': 'y', 'ή': 'h', 'ώ': 'w', 'ς': 's',
        'ϊ': 'i', 'ΰ': 'y', 'ϋ': 'y', 'ΐ': 'i',

        // Turkish
        'Ş': 'S', 'İ': 'I', 'Ç': 'C', 'Ü': 'U', 'Ö': 'O', 'Ğ': 'G',
        'ş': 's', 'ı': 'i', 'ç': 'c', 'ü': 'u', 'ö': 'o', 'ğ': 'g',

        // Russian
        'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ё': 'Yo', 'Ж': 'Zh',
        'З': 'Z', 'И': 'I', 'Й': 'J', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O',
        'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C',
        'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Sh', 'Ъ': '', 'Ы': 'Y', 'Ь': '', 'Э': 'E', 'Ю': 'Yu',
        'Я': 'Ya',
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh',
        'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
        'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
        'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu',
        'я': 'ya',

        // Ukrainian
        'Є': 'Ye', 'І': 'I', 'Ї': 'Yi', 'Ґ': 'G',
        'є': 'ye', 'і': 'i', 'ї': 'yi', 'ґ': 'g',

        // Czech
        'Č': 'C', 'Ď': 'D', 'Ě': 'E', 'Ň': 'N', 'Ř': 'R', 'Š': 'S', 'Ť': 'T', 'Ů': 'U',
        'Ž': 'Z',
        'č': 'c', 'ď': 'd', 'ě': 'e', 'ň': 'n', 'ř': 'r', 'š': 's', 'ť': 't', 'ů': 'u',
        'ž': 'z',

        // Polish
        'Ą': 'A', 'Ć': 'C', 'Ę': 'e', 'Ł': 'L', 'Ń': 'N', 'Ó': 'o', 'Ś': 'S', 'Ź': 'Z',
        'Ż': 'Z',
        'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z',
        'ż': 'z',

        // Latvian
        'Ā': 'A', 'Č': 'C', 'Ē': 'E', 'Ģ': 'G', 'Ī': 'i', 'Ķ': 'k', 'Ļ': 'L', 'Ņ': 'N',
        'Š': 'S', 'Ū': 'u', 'Ž': 'Z',
        'ā': 'a', 'č': 'c', 'ē': 'e', 'ģ': 'g', 'ī': 'i', 'ķ': 'k', 'ļ': 'l', 'ņ': 'n',
        'š': 's', 'ū': 'u', 'ž': 'z'
    };

    // Make custom replacements
    for (var k in opt.replacements) {
        s = s.replace(RegExp(k, 'g'), opt.replacements[k]);
    }

    // Transliterate characters to ASCII
    if (opt.transliterate) {
        for (var k in char_map) {
            s = s.replace(RegExp(k, 'g'), char_map[k]);
        }
    }

    // Replace non-alphanumeric characters with our delimiter
    var alnum = (typeof(XRegExp) === 'undefined') ? RegExp('[^a-z0-9]+', 'ig') : XRegExp('[^\\p{L}\\p{N}]+', 'ig');
    s = s.replace(alnum, opt.delimiter);

    // Remove duplicate delimiters
    s = s.replace(RegExp('[' + opt.delimiter + ']{2,}', 'g'), opt.delimiter);

    // Truncate slug to max. characters
    s = s.substring(0, opt.limit);

    // Remove delimiter from ends
    s = s.replace(RegExp('(^' + opt.delimiter + '|' + opt.delimiter + '$)', 'g'), '');

    return opt.lowercase ? s.toLowerCase() : s;
}

const sourceSlugify = document.querySelector('form .data-source-slugify');
const targetSlugify = document.querySelector('form .data-target-slugify');

if(sourceSlugify) {
    sourceSlugify.oninput = async (e) => {
        e.preventDefault();
        targetSlugify.value = '/' + slugify(sourceSlugify.value);
    }
}

listenButtonClick();

/***AVATAR SCRIPT***/
$(document).ready(function() {

    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').replaceWith('<img class="profile-pic" src="'+ e.target.result +'">');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".file-upload").on('change', function(){
        readURL(this);
    });

    $(".upload-button").on('click', function() {
        $(".file-upload").click();
    });
});
/***AVATAR SCRIPT***/

const elementSendUrl = document.querySelector('[data-send-url]');
const elementsForSend = document.querySelectorAll('[data-for-send]');

if(elementsForSend) {
    elementsForSend.forEach(element => {
        $(element).change(async (e) => {
            e.preventDefault();

            const formData = new FormData();
            if(document.querySelector('[name = _token]')) {
                formData.append("_token", document.querySelector('[name = _token]').value);
            }
            if (element.getAttribute('data-for-send')) {
                formData.append("user", element.getAttribute('data-for-send'));
                if(element.getAttribute('type') === 'checkbox') {
                    formData.append(element.getAttribute('data-field'), element.checked);
                } else {
                    formData.append(element.getAttribute('data-field'), element.value);
                }
            }

            let response = await fetch(elementSendUrl.getAttribute('data-send-url') + element.getAttribute('data-method'), {
                method: 'POST',
                body: formData
            });

            let result = await response.json();

            if(result.toast) {
                await getToast(result.toast.typeToast,  result.toast.dataToast);
            }
        });
    });
}

const actionButtons = document.querySelectorAll('[data-type = action]');
const requestButtons = document.querySelectorAll('[data-type = request]');

if(actionButtons) {
    actionButtons.forEach(element => {
        $(element).click(async (e) => {
            e.preventDefault();
            let form = document.querySelector('#form_edit_static_pages');
            form.action = element.getAttribute('data-action');
            form.method = 'POST';
            let pageName = document.createElement('input');
            pageName.name = 'pageName';
            pageName.type = 'hidden';
            pageName.value = element.getAttribute('data-value');
            form.appendChild(pageName);
            form.submit();
        });
    });
}

if (requestButtons) {
    requestButtons.forEach(element => {
        $(element).click(async (e) => {
            e.preventDefault();
            let formData = new FormData();
            formData.append("_token", document.querySelector('[name = _token]').value);
            formData.append(element.getAttribute('data-field'), element.getAttribute('data-value'));

            let response = await fetch(element.getAttribute('href'), {
                method: 'POST',
                body: formData
            });

            let result = await response.json();

            if(result.url) {
                location.href = result.url;
            }

            if(result.comment) {
                await commentApproved(result.comment);
            }

            if(result.toast) {
                await getToast(result.toast.typeToast,  result.toast.dataToast);
            }

            if (result.error && Object.keys(result.error).length > 0) {
                // for (let key in result.error) {
                //     document.querySelector(result.error[key].field).setAttribute('tooltip', result.error[key].errorMessage);
                // }

                await getToast('warning',  result.error);

            }
        });
    });
}

let getToast = async (type, data) => {

    let formData = new FormData();
    formData.append("_token", document.querySelector('[name = _token]').value);
    formData.append('typeToast', type);
    formData.append('dataToast', data.message);

    let response = await fetch('/toasts/index', {
        method: 'POST',
        body: formData
    });

    let result = await response.text();

    if(result !== '' && result !== 'undefined') {

        await executeToast(result);

    }
}
const executeToast = async (result) => {

    document.querySelector('#messageToast').innerHTML = result;

    let toastTimeout;
    let toast = document.querySelector(".toast");

    toast.classList.add("toast--active");
    toastTimeout = setTimeout(() => {
        toast.classList.remove("toast--active");
    }, 3500);

    toast.addEventListener("click", () => {
        toast.classList.remove("toast--active");
        clearTimeout(toastTimeout);
    });
}

const jump = async (h) => {
    let element = document.querySelector('.div-' + h);
    window.scroll({
        top: element.offsetTop,
        left: 0,
        behavior: "smooth"
    });
};

// const addComment = async (comment) => {
//     let commentBlock = document.querySelector('.comments-block');
//     if(commentBlock) {
//         commentBlock.appendChild(comment);
//     }
// };

const commentButtons = document.querySelectorAll('.btn-comment-content');
//const commentsContent = document.querySelectorAll('.comment-content');

if(commentButtons) {
    commentButtons.forEach(element => {
        $(element).click(async (e) => {
            e.preventDefault();
            let targetCommentId = element.getAttribute('data-id');
            let targetComment = document.querySelector('div[data-content-id = "'+ targetCommentId + '"]');
            targetComment.classList.toggle('comment-hidden');

        });
    });
}

const loadMorePostsButton = async () => {

    let postButton = document.querySelector('.btn-more-posts');
    let page = postButton.getAttribute('data-value');
    page++;
    postButton.setAttribute('data-value', page);

    let formData = new FormData();
    formData.append('page', page);

    let response = await fetch('/morePosts', {
        method: 'POST',
        body: formData
    });

    let result = await response.text();

    if(result.length > 1) {
        document.querySelector('#list-posts').insertAdjacentHTML('beforeend', result);
    } else {
        postButton.textContent = "Постов больше нет";
        postButton.classList.add('dx-btn-loaded');
    }
}

const subscribeElement = document.querySelectorAll('#subscribe_form button, #subscribe_switch');
const subscribeBlock = document.querySelector('.subscribe-block');

if(subscribeElement) {
    subscribeElement.forEach(element => {
        if(element.getAttribute('type') === 'checkbox') {
            $(element).change( async (e) => {
                await getSubscribeResponse(element);
            });
        }
        if(element.getAttribute('type') === 'button') {
            $(element).click( async (e) => {
                await getSubscribeResponse(element);
            });
        }
    })
}

const getSubscribeResponse = async (element) => {

        const form = document.querySelector('#subscribe_form');
        const formData = new FormData(form);

        if(element.getAttribute('type') === 'checkbox') {
            formData.append('emailSubscribe', element.getAttribute('data-email'));
            formData.append('switch', element.checked);
        }

        let response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });

        let result = await response.json();

        if(result.url) {
            location.href = result.url;
        }

        if(result.toast) {
            await getToast(result.toast.typeToast,  result.toast.dataToast);
        }

        if(result.toast.typeToast === 'success') {

            if(subscribeBlock) {
                setTimeout(function () {
                    subscribeBlock.remove();
                }, 1000)
            }

        }

        if (result.error && Object.keys(result.error).length > 0) {
            await getToast('warning',  result.error);
        }
}

const checkToast = async () => {

    let response = await fetch('/checkToast', {
        method: 'POST',

    });

    let result = await response.text();

    if(result !== '' && result !== 'undefined' && result !== null) {
        await executeToast(result);
    }
}

// Чекаем тосты
checkToast().then();

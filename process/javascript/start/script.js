var step = 1;
const lighten = '#02f9e1';

function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

async function pass() {
    $('.container').removeClass('show');
    $('.pass').css('opacity', '0');

    await sleep(500);

    $('.container').remove()

    $('body').css("background-color", '#141414');

    await sleep(500);

    $('.button').css('display', 'block');
    $('.title-container').css('display', 'block');

    await sleep(500);
    
    $('.button').css("opacity", 1);
    $('.title').addClass('show');
}

function scrollToBottom() {
    document.querySelector('.tchat').scrollTo({
        top: document.querySelector('.tchat').scrollHeight,
        behavior: 'smooth'
    });
}

document.addEventListener("keypress", function (event) {
    // Vérifier si le code de touche est 13 (Entrée)
    if (event.key === "Enter" && $(".submit").prop("disabled") == false) {
        $(".submit").trigger("click");
    }
});

window.addEventListener('DOMContentLoaded', async function () {
    start();
});

async function start() {
    const status_badge = document.querySelector('.connexion_badge');

    await sleep(500);
    $('.title').addClass('show');
    await sleep(700);
    $('.subtitle').addClass('show');
    await sleep(700);
    $('.sub-subtitle').addClass('show');
    await sleep(2000);
    $('.title').removeClass('show');
    await sleep(700);
    $('.subtitle').removeClass('show');
    await sleep(700);
    $('.sub-subtitle').removeClass('show');
    await sleep(500);
    $('.title-container').addClass('display-off');
    await sleep(500);
    $('.container').addClass('display-flex');
    $('.container').addClass('show');
    $('.pass').css('opacity', '1');


    await sleep(1200);

    status_badge.style.backgroundColor = 'green';

    await sleep(1500);

    $('.tchat').append(`
    <div class='message other-message'>
        <p>Bonjour ! Cette discussion est une version d'essai et je ne suis pas réel... (╥_╥)</p>
    </div>\n
    `);
    scrollToBottom();

    await sleep(1500);

    $('.tchat').append(`
    <div class='message other-message'>
        <p>Je me sens seul, envoie-moi un message ! (＾▽＾)\nPour ça, il te suffit d'écrire le message dans la barre en dessous et d'appuyer sur le bouton "envoyer".</p>
    </div>\n
    `);
    scrollToBottom();
    $('.message_input').attr("placeholder", "Écris ton message ici");
    $('.message_input').prop("disabled", false);
    $('.message_input').focus();
    $('.submit').prop("disabled", false);
    $('.message_input').addClass('shake-animation');
    $('.message_input').css('border', '3px solid ' + lighten);

    await sleep(2000);

    $('.message_input').removeClass('shake-animation');
    $('.message_input').css('border', '1px solid #9b9b9b');
    $('.submit').addClass('shake-animation');
    $('.submit').css('border', '3px solid ' + lighten);

    await sleep(2000);

    $('.submit').removeClass('shake-animation');
    $('.submit').css('border', '1px solid #9b9b9b');
}

async function Message() {
    let MessageContent = $('.message_input').val();
    MessageContent = MessageContent.trim();
    if (MessageContent.length == 0) {
        $('.message_input').addClass('shake-animation');
        $('.message_input').css('border', '3px solid red');

        await sleep(2000);

        $('.message_input').removeClass('shake-animation');
        $('.message_input').css('border', '1px solid #9b9b9b');
    }
    if (step == 1) {

        $('.message_input').val("");
        $('.message_input').prop("disabled", true);
        $('.message_input').attr("placeholder", "Indisponible");
        $('.submit').prop("disabled", true);

        $('.tchat').append(`
        <div class='message user-message'>
            <button class='delete_button' onclick='DeleteMessage()'>Delete</button>
            <p>${MessageContent}</p>
        </div>\n
        `);
        scrollToBottom();

        await sleep(700);

        $('.tchat').append(`
        <div class='message other-message'>
            <p>Tout fonctionne !! Parfait ! Pour envoyer un message tu peux également appuyer sur ta touche ENTREE !</p>
        </div>\n
        `);
        scrollToBottom();

        await sleep(1000)

        $('.tchat').append(`
        <div class='message other-message'>
            <p>Maintenant supprime ton message en cliquant sur la petite corbeille rouge en bas et en cliquant ensuite sur le bouton "Delete" associé à ton message !</p>
        </div>\n
        `);
        scrollToBottom();
        $('.delete').prop("disabled", false);
        $('.delete').addClass('shake-animation');
        $('.delete').css('border', '3px solid ' + lighten);

        await sleep(2000);

        $('.delete').removeClass('shake-animation');
        $('.delete').css('border', '1px solid #dc3545');
    } else {
        $('.message_input').val("");
        if (MessageContent == "/color") {
            const colorPickerElement = document.createElement('input');
            colorPickerElement.type = 'color';
            colorPickerElement.id = 'colorPicker';
            colorPickerElement.value = '#141414';

            const messageElement = document.createElement('div');
            messageElement.classList.add('message', 'user-message');
            messageElement.textContent = 'Choisissez la couleur souhaitée : ';
            messageElement.appendChild(colorPickerElement);

            $('.tchat').append(messageElement);
            scrollToBottom();

            $('#colorPicker').addClass('shake-animation');
            $('#colorPicker').css('border', '3px solid ' + lighten);

            await sleep(2000);

            $('#colorPicker').removeClass('shake-animation');
            $('#colorPicker').css('border', 'unset');

            $('#colorPicker').on('change', function (event) {
                const selectedColor = event.target.value;
                ChangeWallpaper(selectedColor);
            });

        } else {
            $('.message_input').addClass('shake-animation');
            $('.message_input').css('border', '3px solid red');

            await sleep(2000);

            $('.message_input').removeClass('shake-animation');
            $('.message_input').css('border', '1px solid #9b9b9b');
        }
    }
    step++;
}

async function ToggleDelete() {

    $(".delete_button").css('display', 'block');
    $('.delete_button').removeClass('shake-animation');
    $(".delete_button").css('border', '3px solid ' + lighten);

    await sleep(2000);

    $('.delete_button').removeClass('shake-animation');
    $('.delete_button').css('border', '1px solid #dc3545');
}

async function DeleteMessage() {
    $('.delete').prop("disabled", true);
    $(".user-message").css('display', 'none');

    await sleep(800);

    $('.tchat').append(`
    <div class='message other-message'>
        <p>Félicitations ! Je ne le vois plus ! Maintenant, testons une commande ! Les commandes te permettent d'effectuer des actions dans le tchat.\nLa commande /color te permet de changer la couleur du tchat ! Essaye !!</p>
    </div>\n
`);

scrollToBottom();

$('.message_input').attr("placeholder", "Écris ton message ici");
$('.message_input').prop("disabled", false);
$('.message_input').focus();
$('.submit').prop("disabled", false);
$('.message_input').addClass('shake-animation');
$('.message_input').css('border', '3px solid ' + lighten);

await sleep(2000);

$('.message_input').removeClass('shake-animation');
$('.message_input').css('border', '1px solid #9b9b9b');
}

function darkenColor(Hex, percent) {
    // Convertir la couleur hexadécimale en valeurs RGB
    var r = parseInt(Hex.substr(1, 2), 16);
    var g = parseInt(Hex.substr(3, 2), 16);
    var b = parseInt(Hex.substr(5, 2), 16);

    // Calculer les nouvelles valeurs RGB plus foncées
    var factor = 1 - (percent / 100);
    r = Math.floor(r * factor);
    g = Math.floor(g * factor);
    b = Math.floor(b * factor);

    // Convertir les valeurs RGB en une nouvelle couleur hexadécimale
    var newColor = "#" +
        ("00" + r.toString(16)).slice(-2) +
        ("00" + g.toString(16)).slice(-2) +
        ("00" + b.toString(16)).slice(-2);

    return newColor;
}

function lightenColor(Hex, percent) {
    // Convertir la couleur hexadécimale en valeurs RGB
    var r = parseInt(Hex.substr(1, 2), 16);
    var g = parseInt(Hex.substr(3, 2), 16);
    var b = parseInt(Hex.substr(5, 2), 16);

    // Calculer les nouvelles valeurs RGB plus claires
    var factor = 1 + (percent / 100);
    r = Math.min(Math.floor(r * factor), 255);
    g = Math.min(Math.floor(g * factor), 255);
    b = Math.min(Math.floor(b * factor), 255);

    // Convertir les valeurs RGB en une nouvelle couleur hexadécimale
    var newColor = "#" +
        ("00" + r.toString(16)).slice(-2) +
        ("00" + g.toString(16)).slice(-2) +
        ("00" + b.toString(16)).slice(-2);

    return newColor;
}

function hexToRgba(hex, opacity) {
    // Supprimer le caractère "#" s'il est présent
    hex = hex.replace("#", "");

    // Extraire les valeurs RGB du code hexadécimal
    const r = parseInt(hex.substr(0, 2), 16);
    const g = parseInt(hex.substr(2, 2), 16);
    const b = parseInt(hex.substr(4, 2), 16);

    // Vérifier et normaliser la valeur alpha fournie (entre 0 et 1)
    const finalOpacity = Math.min(Math.max(opacity, 0), 1);

    // Retourner la couleur RGBA sous forme de chaîne de caractères
    return `rgba(${r}, ${g}, ${b}, ${finalOpacity})`;
}

async function ChangeWallpaper(color) {
    $('#colorPicker').prop("disabled", true);

    const top_bar = document.querySelector('.top_bar');
    const tchat = document.querySelector('.tchat');
    const message_form = document.querySelector('.message_form');
    const body = document.querySelector('body');

    const verydarkenHex = darkenColor(color, 30);
    //var darkenHex = darkenColor(color, 10);
    //var lightenHex = lightenColor(color, 20);

    // On convertit l'entrée hexadécimale en rgba
    const verydarkenRgba = hexToRgba(verydarkenHex, 1);
    //const darkenRgba = hexToRgba(darkenHex, 0.7);
    const Rgba = hexToRgba(color, 1);
    //const lightenRgba = hexToRgba(lightenHex, 0.7);

    // On modifie la couleur des éléments
    top_bar.style.backgroundColor = verydarkenRgba;
    message_form.style.backgroundColor = verydarkenRgba;
    tchat.style.backgroundColor = Rgba;
    $('.container').css('border-color', verydarkenRgba);

    await sleep(200);

    body.style.backgroundColor = Rgba;
    
    await sleep(900);

    $('.tchat').append(`
        <div class='message other-message'>
            <p>C'est joli ! Bon choix !\nJe pense que je t'ai montré le principal !\nÀ bientôt j'espère (*^▽^*)</p>
        </div>\n
    `);
    scrollToBottom();

    await sleep(1500);

    end();
}

async function end() {

    const messages = document.querySelectorAll('.message');
    let index = 0;

    async function hideMessages() {
        if (index < messages.length) {
            await sleep(400);
            messages[index].style.opacity = 0;
            index++;
            hideMessages(); // Appel récursif pour masquer le prochain élément après le délai
        }
    }
    
    hideMessages();
    await sleep(3500);

    $('.container').removeClass('show');

    await sleep(500);

    $('.container').remove();
    $('body').css("background-color", '#141414');

    await sleep(500);

    $('.button').css('display', 'block');
    $('.title-container').css('display', 'block');

    await sleep(500);
    
    $('.button').css("opacity", 1);
    $('.title').addClass('show');
}
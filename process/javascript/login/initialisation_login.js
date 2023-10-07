function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

async function red_all_register() {
    $('.register').css('border', '2px solid red');
    $('.register').addClass('shake-animation');
    await sleep(1000);
    $('.register').removeClass('shake-animation');
    $('.register').css('border', '2px solid #bb9457');
}

async function red_all_login() {
    $('.login').css('border', '2px solid red');
    $('.login').addClass('shake-animation');
    await sleep(1000);
    $('.login').removeClass('shake-animation');
    $('.login').css('border', '2px solid #bb9457');
}

async function red_username() {
    $('.username').addClass('shake-animation');
    $('.username').css('border', '1px solid red');
    await sleep(1000);
    $('.username').removeClass('shake-animation');
    $('.username').css('border', '1px solid #bb9457');
}

async function red_password() {
    $('.password').addClass('shake-animation');
    $('.password').css('border', '1px solid red');
    await sleep(1000);
    $('.password').removeClass('shake-animation');
    $('.password').css('border', '1px solid #bb9457');
}
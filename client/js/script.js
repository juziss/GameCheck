$(document).ready(function() {
    $('.tela-cadastro').hide();

    $('#btn-cadastrar').on('click', function() {
        $('.tela-cadastro').fadeIn();
        $('.logo, h1, .linha, .redefinir-senha, .input-email, .input-senha, .entrar-botao, .cadastro').hide();
    });

    $('#btn-login').on('click', function() {
        $('.tela-cadastro').hide();
        $('.logo, h1, .linha, .redefinir-senha, .input-email, .input-senha, .entrar-botao, .cadastro').show();
    });
});

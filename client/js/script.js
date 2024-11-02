// Transição entre telas Login e Cadastro

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

// Validação de campos Login e Cadastro

$(document).ready(function() {
    
    $('form[action="#"]').eq(0).submit(function(event) {
        let email = $('#email').val();
        let senha = $('#senha').val();
        let isValid = true;

        if (!validateEmail(email)) {
            alert("Por favor, insira um e-mail válido.");
            isValid = false;
        }

        if (senha.length < 8) {
            alert("A senha deve ter no mínimo 8 caracteres.");
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    $('form[action="#"]').eq(1).submit(function(event) {
        let nome = $('#nome').val();
        let emailCadastro = $('#email-cadastro').val();
        let senhaCadastro = $('#senha-cadastro').val();
        let confirmacao = $('#confirmação').val();
        let isValid = true;

        if (nome.trim() === "") {
            alert("Por favor, insira seu nome completo.");
            isValid = false;
        }

        if (!validateEmail(emailCadastro)) {
            alert("Por favor, insira um e-mail válido.");
            isValid = false;
        }

        if (senhaCadastro.length < 8) {
            alert("A senha deve ter no mínimo 8 caracteres.");
            isValid = false;
        }

        if (senhaCadastro !== confirmacao) {
            alert("As senhas não coincidem.");
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    function validateEmail(email) {
        let re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
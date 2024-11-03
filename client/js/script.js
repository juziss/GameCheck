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

// classificação

document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.getAttribute('data-value');

        // Remove a seleção de todas as estrelas
        document.querySelectorAll('.star').forEach(s => s.classList.remove('selected'));

        // Adiciona a classe 'selected' a partir da estrela clicada (da esquerda para a direita)
        for (let i = 0; i < rating; i++) {
            document.querySelectorAll('.star')[i].classList.add('selected');
        }

        console.log(`Avaliação escolhida: ${rating} estrelas`);
    });
});

// avaliacoes

const comentarioInput = document.getElementById('comentario-input');
const enviarComentario = document.getElementById('enviar-comentario');
const listaComentarios = document.getElementById('lista-comentarios');
const contadorCaracteres = document.getElementById('contador-caracteres');

let usuarioNome = "Nome Usuário"; // Defina o nome do usuário
let usuarioFoto = "../public/user-profile.jpg"; // Caminho para a foto do perfil

comentarioInput.addEventListener('input', () => {
    const totalCaracteres = comentarioInput.value.length;
    contadorCaracteres.textContent = `${totalCaracteres}/250 caracteres`;
});

enviarComentario.addEventListener('click', () => {
    const comentarioTexto = comentarioInput.value.trim();
    if (comentarioTexto) {
        const li = document.createElement('li');
        li.innerHTML = `
            <div class="comentario">
                <img src="${usuarioFoto}" alt="Foto de Perfil" class="perfil-comentario" />
                <div>
                    <strong>${usuarioNome}</strong>
                    <p>${comentarioTexto}</p>
                </div>
            </div>
        `;
        listaComentarios.appendChild(li);
        comentarioInput.value = '';
        contadorCaracteres.textContent = `0/250 caracteres`;
    }
});

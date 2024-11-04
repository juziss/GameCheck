<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./assets/js/script.js"></script>
    <title>GameCheck</title>
</head>

<body>
    <main>
        <section class="esquerda">
            <div class="logo">
                <img src="./assets/img/logo-gamecheck-branco.png" alt="Logo Gamecheck" />
            </div>
            <h1>Acesse sua conta</h1>
            <hr class="linha" />
            <form action="login.php" method="POST">
                <div class="campos">
                    <div class="input-email">
                        <input type="text" id="email" name="email" placeholder="Digite o E-mail" />
                    </div>
                    <div class="input-senha">
                        <input type="password" id="senha" name="senha" placeholder="Digite a Senha" />
                    </div>
                </div>
                <div class="redefinir-senha">
                    <a href="#">Esqueci minha senha</a>
                </div>
                <button type="submit" class="entrar-botao">Entrar</button>
                <div class="cadastro">
                    <p>
                        Não tem uma conta?
                        <button type="button" class="cadastro-botao" id="btn-cadastrar">Cadastre-se</button>
                    </p>
                </div>
            </form>

            <div class="tela-cadastro">
                <h2 class="titulo-cad">Cadastre-se Gratuitamente</h2>
                <form action="cadastrar.php" method="POST">
                    <div class="campos">
                        <div class="input-nome">
                            <input type="text" id="nome" name="nome" placeholder="Seu nome completo" />
                        </div>
                        <div class="input-email-cad">
                            <input type="email" id="email-cadastro" name="email" placeholder="E-mail" />
                        </div>
                        <div class="input-senha-cad">
                            <input type="password" id="senha-cadastro" name="senha"
                                placeholder="Deve ter no mínimo 8 caracteres" />
                        </div>
                        <div class="input-confirmacao">
                            <input type="password" id="confirmacao" name="confirmacao"
                                placeholder="Deve ter no mínimo 8 caracteres" />
                        </div>
                    </div>
                    <p>Ao se cadastrar, você aceita nossos <a href="#">termos de uso</a> e a nossa <a href="#">política
                            de privacidade</a>.</p>
                    <button type="submit" class="cadastrar-botao">Cadastrar</button>
                    <div class="login">
                        <p>
                            Já tem uma conta?
                            <button type="button" class="cadastro-botao" id="btn-login">Login</button>
                        </p>
                    </div>
                </form>
            </div>
        </section>
        <section class="direita">
            <div class="imagem-direita"></div>
        </section>
    </main>
</body>

</html>
<?php

require_once 'busca.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./assets/css/home-style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="./assets/img/favicon.png" />
  <title>GameCheck</title>
</head>

<body>
  <header>
    <nav class="navbar">
      <div class="logo">
        <img src="./assets/img/favicon.png" />
        <h2>GAMECHECK</h2>
      </div>
      <div class="links">
        <a href="#">Jogos</a>
        <a href="#">Favoritos</a>
        <a href="#">Minhas Listas</a>
      </div>
      <div class="dropdown">
        <a href="#">Nome Usuário
          <img src="./assets/img/user-profile.jpg" alt="Foto de Perfil" class="profile-pic" id="profile-pic" />
        </a>
        <div class="menu">
          <a href="#"> Perfil </a>
          <a href="#"> Configurações </a>
          <a href="#"> Sair </a>
        </div>
      </div>
    </nav>
  </header>
  <main class="home">
    <div class="container">
      <section class="filtros">
        <div class="filtros-container">
          <label for="tempo">Tempo</label>
          <select name="tempo" id="tempo" class="select-field">
            <option value="antigo">Mais antigo</option>
            <option value="recente">Mais recente</option>
          </select>
          <label for="avaliacao">Avaliação</label>
          <select name="avaliacao" id="avaliacao" class="select-field">
            <option value="melhor">Melhores</option>
            <option value="pior">Piores</option>
          </select>
          <div class="search-container">
            <form action="jogos.php" method="GET">
                <input type="text" name="query" class="search-field" placeholder="Pesquisar jogos...">
            </form>
        </div>
        </div>
    </div>
    </section>
    </div>
    <section class="classificacao">
      <div class="semana">
        <p>Preferidos da semana <a href="#">Ver mais+</a></p>
        <hr class="divisoria">
        <div class="cards-container">
          <div class="card">
            <div class="card-content">
              <img src="./assets/img/jogo-1.jpg" alt="Jogo 1" class="card-image" />
              <p class="game-title">Jogo 1</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="./assets/img/jogo-2.jpg" alt="Jogo 2" class="card-image" />
              <p class="game-title">Jogo 2</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="assets/img/jogo-3.jpg" alt="Jogo 3" class="card-image" />
              <p class="game-title">Jogo 3</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="assets/img/jogo-4.jpg" alt="Jogo 4" class="card-image" />
              <p class="game-title">Jogo 4</p>
            </div>
          </div>
        </div>
      </div>
      <div class="semana">
        <p>Odiados da semana <a href="">Ver mais+</a></p>
        <hr class="divisoria">
        <div class="cards-container">
          <div class="card">
            <div class="card-content">
              <img src="assets/img/jogo-5.jpg" alt="Jogo 5" class="card-image" />
              <p class="game-title">Jogo 5</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="./assets/img/jogo-6.jpg" alt="Jogo 6" class="card-image" />
              <p class="game-title">Jogo 6</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="assets/img/jogo-7.jpg" alt="Jogo 7" class="card-image" />
              <p class="game-title">Jogo 7</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="./assets/img/jogo-8.jpg" alt="Jogo 8" class="card-image" />
              <p class="game-title">Jogo 8</p>
            </div>
          </div>
        </div>
      </div>
      <div class="comunidade">
        <p>Listas da comunidade <a href="">Ver mais+</a></p>
        <hr class="divisoria">
        <div class="cards-container">
          <div class="card">
            <div class="card-content">
              <img src="./assets/img/lista-1.jpg" alt="Lista 1" class="card-image" />
              <p class="game-title">Lista 1</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="./assets/img/lista-2.jpg" alt="Lista 2" class="card-image" />
              <p class="game-title">Lista 2</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="./assets/img/jogo-3.jpg" alt="Lista 3" class="card-image" />
              <p class="game-title">Lista 3</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="./assets/img/lista-4.jpg" alt="Lista 4" class="card-image" />
              <p class="game-title">Lista 4</p>
            </div>
          </div>
        </div>
      </div>
    </section>
</section>
    </div>
  </main>
  <footer>
    <ul>
      <li><a href="">Sobre</a></li>
      <li><a href="">Ajuda</a></li>
      <li><a href="">Contato</a></li>
    </ul>
    <p>
      Todos os direitos reservados © 2024 GAMECHECK. A avaliação dos jogos é
      uma expressão da comunidade.
    </p>
  </footer>
  <script src="./assets/js/script.js"></script>

</body>

</html>


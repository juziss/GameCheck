<?php
session_start();

require_once '../models/busca.php';
require_once '../models/db.php';
if (!isset($_SESSION['id_usuario'])) {
  header('Location: login.php'); // Redireciona para a página de login se não estiver logado
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/css/home-style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Jersey+10&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon.png" />
  <title>GameCheck</title>
</head>

<body>
  <header>
    <nav class="navbar">
      <div class="logo">
        <img src="../assets/img/favicon.png" />
        <h2>GAMECHECK</h2>
      </div>
      <div class="links">
        <a href="#">Jogos</a>
        <a href="#">Favoritos</a>
        <a href="#">Minhas Listas</a>
      </div>
      <div class="dropdown">
        <a>
        <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : "Visitante"; ?>
          <img src="../assets/img/user-profile.jpg" alt="Foto de Perfil" class="profile-pic" id="profile-pic" />
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
        <p>Preferidos da semana <a href="#">Ver mais+</a></p><?php
// Consultar os piores jogos da view (limitando a 4 resultados)
$query = "SELECT id_jogo, nome_jogo, media_nota FROM melhores_avaliados ORDER BY media_nota DESC LIMIT 4";
$result = mysqli_query($conn, $query);

// Verificar se há resultados
if (mysqli_num_rows($result) > 0) {
    echo '<div class="cards-container">';
    while ($row = mysqli_fetch_assoc($result)) {
        $id_jogo = $row['id_jogo'];
        $nome_jogo = $row['nome_jogo'];
        $media_nota = $row['media_nota'];

        // Exibir os jogos na estrutura de cartões
        echo '<div class="card">';
        echo '<div class="card-content">';
        
        // Supondo que você tem as imagens dos jogos armazenadas na pasta 'assets/img/'
        // A imagem do jogo pode ser armazenada no banco, ou você pode usar uma imagem genérica por enquanto
           
        echo '<img src="assets/img/jogo-'.$id_jogo.'.jpg" alt="'.$nome_jogo.'" class="card-image" />';

        
        // Exibir o título do jogo
        echo '<p class="game-title">' . htmlspecialchars($nome_jogo) . '</p>';
        
        // Exibir estrelas
        $estrelas = round($media_nota);  // arredondando para o número de estrelas
        for ($i = 1; $i <= 5; $i++) {
            echo $i <= $estrelas ? '★' : '☆'; // Estrela cheia ou vazia
        }

        echo '</div>';  // Fecha card-content
        echo '</div>';  // Fecha card
    }
    echo '</div>';  // Fecha cards-container
} else {
    echo "<p>Não há jogos avaliados.</p>";
}
?>
      <div class="semana">
        <p>Odiados da semana <a href="">Ver mais+</a></p>

<?php
// Consultar os piores jogos da view (limitando a 4 resultados)
$query = "SELECT id_jogo, nome_jogo, media_nota FROM piores_avaliados ORDER BY media_nota ASC LIMIT 4";
$result = mysqli_query($conn, $query);

// Verificar se há resultados
if (mysqli_num_rows($result) > 0) {
    echo '<div class="cards-container">';
    while ($row = mysqli_fetch_assoc($result)) {
        $id_jogo = $row['id_jogo'];
        $nome_jogo = $row['nome_jogo'];
        $media_nota = $row['media_nota'];

        // Exibir os jogos na estrutura de cartões
        echo '<div class="card">';
        echo '<div class="card-content">';
        
        // Supondo que você tem as imagens dos jogos armazenadas na pasta 'assets/img/'
        // A imagem do jogo pode ser armazenada no banco, ou você pode usar uma imagem genérica por enquanto
        echo '<img src="assets/img/jogo-'.$id_jogo.'.jpg" alt="'.$nome_jogo.'" class="card-image" />';
        
        // Exibir o título do jogo
        echo '<p class="game-title">' . htmlspecialchars($nome_jogo) . '</p>';
        
        // Exibir estrelas
        $estrelas = round($media_nota);  // arredondando para o número de estrelas
        for ($i = 1; $i <= 5; $i++) {
            echo $i <= $estrelas ? '★' : '☆'; // Estrela cheia ou vazia
        }

        echo '</div>';  // Fecha card-content
        echo '</div>';  // Fecha card
    }
    echo '</div>';  // Fecha cards-container
} else {
    echo "<p>Não há jogos avaliados.</p>";
}
?>
      <div class="comunidade">
        <p>Listas da comunidade <a href="">Ver mais+</a></p>
        <hr class="divisoria">
        <div class="cards-container">
          <div class="card">
            <div class="card-content">
              <img src="../assets/img/lista-1.jpg" alt="Lista 1" class="card-image" />
              <p class="game-title">Lista 1</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="../assets/img/lista-2.jpg" alt="Lista 2" class="card-image" />
              <p class="game-title">Lista 2</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="../assets/img/jogo-3.jpg" alt="Lista 3" class="card-image" />
              <p class="game-title">Lista 3</p>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <img src="../assets/img/lista-4.jpg" alt="Lista 4" class="card-image" />
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
  <script src="../assets/js/script.js"></script>

</body>

</html>


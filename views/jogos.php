<?php
require_once 'db.php';

$resultadosBusca = [];
$query = '';

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    $stmt = $conn->prepare("SELECT nome_jogo FROM jogo WHERE nome_jogo LIKE ?");
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $resultadosBusca[] = $row;
    }

    $stmt->close();
}
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
    <title>Resultados da Pesquisa</title>
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
    <main>
        <h1>Resultados da Pesquisa</h1>
        <div class="container">
        <?php if (!empty($resultadosBusca)): ?>
    <ul>
        <?php foreach ($resultadosBusca as $jogo): ?>
            <li>
                <strong><a href= "#"><?php echo htmlspecialchars($jogo['nome_jogo']); ?></a></strong><br>
                <img src="<?php echo htmlspecialchars($jogo['capa_jogo']); ?>" alt="Capa de <?php echo htmlspecialchars($jogo['nome_jogo']); ?>" style="width: 100px; height: auto;">
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Nenhum jogo encontrado.</p>
<?php endif; ?>

        </div>
    </main>
</body>

</html>

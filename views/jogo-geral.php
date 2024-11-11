<?php
session_start();  // Inicia a sessão
require_once '../models/db.php';
require_once '../models/busca.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Redireciona para a página de login se não estiver logado
    exit;
}
if (!isset($_SESSION['nome_usuario'])) {
    // Faz a consulta para obter o nome do usuário com base no id_usuario da sessão
    $id_usuario = $_SESSION['id_usuario'];
    $query = "SELECT nome_usuario FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($nome_usuario);

    if ($stmt->fetch()) {
        // Armazena o nome do usuário na sessão
        $_SESSION['nome_usuario'] = $nome_usuario;
    }
    $stmt->close();
}

// Obtém o ID do jogo pela URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID do jogo inválido");
}

// Consulta os detalhes do jogo
$sql = "SELECT id_jogo, nome_jogo, desenvolvedora_jogo, ano_lancamento, capa_jogo, desc_jogo, media_nota FROM Jogo WHERE id_jogo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Jogo não encontrado");
}

$jogo = $result->fetch_assoc();

// Função para obter os gêneros do jogo
function getGenerosDoJogo($jogo_id, $conn) {
    $sql = "SELECT g.nome_genero 
    FROM Genero g 
    INNER JOIN Jogo_Genero jg ON g.id_genero = jg.id_genero 
    WHERE jg.id_jogo = ?";
$stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $jogo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $generos = [];
    while ($row = $result->fetch_assoc()) {
        $generos[] = $row['nome_genero'];
    }
    
    return $generos;
}

$capa_jogo = $jogo['capa_jogo'];

// Obtém os gêneros do jogo com base no ID
$generos = getGenerosDoJogo($id, $conn);

$sql_comentarios = "SELECT u.nome_usuario, a.nota, a.avaliacao 
                    FROM Avaliacao a
                    INNER JOIN Usuario u ON a.id_usuario = u.id_usuario
                    WHERE a.id_jogo = ?";
$stmt_comentarios = $conn->prepare($sql_comentarios);
$stmt_comentarios->bind_param("i", $id);
$stmt_comentarios->execute();
$result_comentarios = $stmt_comentarios->get_result();
$comentarios = [];

while ($row = $result_comentarios->fetch_assoc()) {
    $comentarios[] = $row;
}

$media_nota = $jogo['media_nota'];

$id_usuario = $_SESSION['id_usuario'];
$id_jogo = 1;

// Consulta para obter as listas do usuário
$query = "SELECT id_lista, nome_lista FROM lista WHERE id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$listas = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['lista_selecionada']) && $_POST['lista_selecionada'] !== 'nova_lista') {
        // Usuário selecionou uma lista existente
        $id_lista = $_POST['lista_selecionada'];
    } else if (isset($_POST['nome_nova_lista'])) {
        // Usuário optou por criar uma nova lista
        $nome_nova_lista = $_POST['nome_nova_lista'];

        // Adiciona a nova lista ao banco de dados
        $query = "INSERT INTO lista (nome_lista, id_usuario) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nome_nova_lista, $_SESSION['id_usuario']);
        $stmt->execute();

        // Obtém o ID da nova lista
        $id_lista = $stmt->insert_id;
        $stmt->close();
    }

    // Agora que temos o ID da lista (seja ela nova ou existente), você pode adicionar o jogo à lista
    $id_jogo = $_GET['id']; // Supondo que o ID do jogo esteja na URL

    $query = "INSERT INTO lista_jogo (id_lista, id_jogo) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_lista, $id_jogo);
    $stmt->execute();
    $stmt->close();

    header('Location: jogo.php?id=' . $id_jogo);  // Redireciona para a página do jogo
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../assets/css/jogo-geral.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.png" />
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
                <img src="../assets/img/favicon.png" alt="GameCheck Logo" />
                <h2>GAMECHECK</h2>
            </div>
            <div class="links">
                <a href="#">Jogos</a>
                <a href="#">Favoritos</a>
                <a href="#">Minhas Listas</a>
            </div>
            <div class="dropdown">
                <a href="#"><?php echo isset($_SESSION['nome_usuario']) ? htmlspecialchars($_SESSION['nome_usuario']) : "Visitante"; ?>
                    <img src="../assets/img/user-profile.jpg" alt="Foto de Perfil" class="profile-pic"
                        id="profile-pic" />
                </a>
                <div class="menu">
                    <a href="#">Perfil</a>
                    <a href="#">Configurações</a>
                    <a href="#">Sair</a>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <section class="jogo">
        <main>
        <section class="jogo">
            <div class="cards-container">
                <div class="card">
                    <div class="card-content">
                    <?php if (!empty($jogo['capa_jogo']) && filter_var($jogo['capa_jogo'], FILTER_VALIDATE_URL)): ?>
                        <img src="../assets/img/no-image.jpg" alt="Imagem não disponível" class="card-image" />
                        <img src="<?php echo htmlspecialchars($capa_jogo); ?>" alt="<?php echo htmlspecialchars($jogo['nome_jogo']); ?>" class="card-image" />
                        <?php else: ?>
                        <?php
                            $caminhoImagem = "../capas_jogos/" . htmlspecialchars($jogo['capa_jogo']) . ".jpg";
                        ?>
                        <img src="<?php echo $caminhoImagem; ?>" alt="Capa do Jogo" style="height: 100%; width: 100%; object-fit: cover;">
                        <?php endif; ?>

                    </div>
                </div>
                
                <div class="info-area">
                    <div class="info-container">
                        <h1 class="titulo-jogo"><?php echo htmlspecialchars($jogo['nome_jogo']); ?></h1>
                        <div class="info-header">
                            <p class="ano"><?php echo htmlspecialchars($jogo['ano_lancamento']); ?></p>
                            <p class="dev">Desenvolvido por <a href="#"><?php echo htmlspecialchars($jogo['desenvolvedora_jogo']); ?></a></p>
                        </div>
                        <div class="descricao">
                        <p><?php echo htmlspecialchars($jogo['desc_jogo']);?></p>
                        </div>
                    </div>
                    
                    <div class="avaliacao-site">
                        <h2 class="titulo-ava">Avaliação do Jogo</h2>
                        <div class="ava-usuario">
                            <p>Sua Opinião:</p>
                            <span class="star" data-value="1">&#9733;</span>
                            <span class="star" data-value="2">&#9733;</span>
                            <span class="star" data-value="3">&#9733;</span>
                            <span class="star" data-value="4">&#9733;</span>
                            <span class="star" data-value="5">&#9733;</span>
                        </div>
                        <div class="ava-comunidade">
                            <p>Comunidade:</p>
                            <?php
                            // Exibe as estrelas com base na média
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $media_nota) {
                                    echo '<span class="star selected">&#9733;</span>';
                                } else {
                                    echo '<span class="star">&#9734;</span>';
                                }
                            }
                            ?>
                            <br><br>
                        </div>
                        <div class="btn-lista">
                        <form method="post" action="adicionar_lista.php">
                            <select name="lista_selecionada" id="btn-lista" onchange="mostrarCampoNovaLista(this)">
                                <option value="" disabled selected>Adicionar à uma lista</option>
                                <option value="nova_lista">Nova lista +</option>
                                <?php while ($row = $result->fetch_assoc()) : ?>
                                <option value="<?php echo $row['id_lista']; ?>"><?php echo htmlspecialchars($row['nome_lista']); ?></option>
                            <?php endwhile; ?>
                            </select>
                            
                            <!-- Campo oculto para nova lista -->
                            <div id="campo-nova-lista" style="display:none;">
                                <input type="text" name="nome_nova_lista" placeholder="Digite o nome da nova lista">
                            </div>
                        </form>

                        </form>
                        </div> 
                        <br><br>
                    </div>
                        <div class="genero">
                        <div class="genero-cards">
                            <?php foreach ($generos as $genero): ?>
                                <div class="card-genero"><?php echo htmlspecialchars($genero); ?></div>
                            <?php endforeach; ?>
                        </div>
                        </div>            
                </div>
            </div>
            
            <section class="comentarios">
            <h2 class="coment-titulo">Deixe uma Review</h2>
        <textarea id="comentario-input" maxlength="250" placeholder="O que você achou do game?"></textarea>
        <p id="contador-caracteres">0/250 caracteres</p>
        <button id="enviar-comentario">Postar Comentário</button>

        <div id="comentarios-lista">
            <h3>Comentários:</h3>
            <ul id="lista-comentarios">
            <?php foreach ($comentarios as $comentario): ?>
            <div class="comentario">
            <img src="../assets/img/user-profile.jpg" alt="Foto de Perfil" class="comentario-foto" />
            <div class="comentario-conteudo">
                <p class="comentario-nome">Review por <strong class="realce"><?php echo htmlspecialchars($comentario['nome_usuario']); ?></strong></p>
                <div class="comentario-estrelas">
                    <?php
                    // Exibe as estrelas com base na nota
                    for ($i = 1; $i <= 5; $i++) {
                        echo ($i <= $comentario['nota']) ? '<span class="estrela">&#9733;</span>' : '<span class="estrela">&#9734;</span>';
                    }
                    ?>
                </div>
                <button class="comentario-coracao">&#10084;</button>
                <p class="comentario-texto"><?php echo htmlspecialchars($comentario['avaliacao']); ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </ul>
</div>

            </div>
            </ul>
        </div>
    </section>
            </section>
        </section>
    </main>
    <footer>
        <ul>
            <li><a href="#">Sobre</a></li>
            <li><a href="#">Ajuda</a></li>
            <li><a href="#">Contato</a></li>
        </ul>
        <p>
            Todos os direitos reservados © 2024 GAMECHECK. A avaliação dos jogos é uma expressão da comunidade.
        </p>
    </footer>
    <script >document.addEventListener("DOMContentLoaded", function() {
    // Elements
    const stars = document.querySelectorAll('.ava-usuario .star');
    const comentarioInput = document.getElementById('comentario-input');
    const contadorCaracteres = document.getElementById('contador-caracteres');
    const enviarComentario = document.getElementById('enviar-comentario');
    const listaComentarios = document.getElementById('lista-comentarios');

    // Get game ID from URL (if using dynamic loading)
    const jogoId = new URLSearchParams(window.location.search).get('id');
    
    // Star rating system
    let selectedRating = 0;
    stars.forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = this.dataset.value;
            stars.forEach(s => s.classList.remove('selected'));
            for (let i = 0; i < selectedRating; i++) {
                stars[i].classList.add('selected');
            }
        });

        star.addEventListener('mouseover', function() {
            const rating = this.dataset.value;
            stars.forEach((s, index) => {
                s.classList.toggle('selected', index < rating);
            });
        });

        star.addEventListener('mouseout', function() {
            stars.forEach((s, index) => {
                s.classList.toggle('selected', index < selectedRating);
            });
        });
    });

    // Character counter
    comentarioInput.addEventListener('input', function() {
        const caracteresRestantes = this.value.length;
        contadorCaracteres.textContent = `${caracteresRestantes}/250 caracteres`;
    });

    // Submit review
   enviarComentario.addEventListener('click', async function() {
    const comentario = comentarioInput.value.trim();

    if (selectedRating === 0) {
        alert("Por favor, dê uma nota de 1 a 5 estrelas.");
        return;
    }

    if (!comentario) {
        alert("Por favor, escreva sua avaliação sobre o jogo.");
        return;
    }

    try {
        // Envia os dados ao PHP
        const formData = new FormData();
        formData.append('nota', selectedRating);
        formData.append('avaliacao', comentario);
        formData.append('id_jogo', jogoId);
        formData.append('id_usuario', <?php echo $_SESSION['id_usuario']; ?>);  // Passando o id_usuario logado


        const response = await fetch('../controllers/salvar_avaliacao.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            console.error("Erro na requisição:", response.statusText);
            return;
        }

        const data = await response.text(); // Recebe a resposta como texto

        if (data.includes) {
            // Criar novo comentário na interface
            const novoComentario = `
                <div class="comentario">
                    <img src="../assets/img/user-profile.jpg" alt="Foto de Perfil" class="comentario-foto" />
                    <div class="comentario-conteudo">
                        <p class="comentario-nome">Review por <strong class="realce">${document.querySelector('.dropdown a').textContent.trim()}</strong></p>
                        <div class="comentario-estrelas">
                            ${Array(parseInt(selectedRating)).fill('<span class="estrela">&#9733;</span>').join('')}
                        </div>
                        <button class="comentario-coracao">&#10084;</button>
                        <p class="comentario-texto">${comentario}</p>
                    </div>
                </div>
            `;
            listaComentarios.innerHTML = novoComentario + listaComentarios.innerHTML;
            
            // Limpar formulário
            comentarioInput.value = '';
            contadorCaracteres.textContent = '0/250 caracteres';
            selectedRating = 0;
            stars.forEach(s => s.classList.remove('selected'));
            
        } else {
            alert("Erro: " + data.error);
        }
    } catch (error) 
    {
        alert("Erro ao enviar avaliação: " + error.message);
    }
});

    // Like button functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('comentario-coracao')) {
            e.target.classList.toggle('liked');
        }
    });

    // Inicializar média da comunidade (exemplo)
    function atualizarMediaComunidade(media = 4) {
        const starsComm = document.querySelectorAll('.ava-comunidade .star');
        starsComm.forEach((star, index) => {
            star.classList.toggle('selected', index < media);
        });
    }

    // Carregar dados iniciais
    atualizarMediaComunidade();

    function mostrarCampoNovaLista(selectElement) {
    var campoNovaLista = document.getElementById('campo-nova-lista');
    if (selectElement.value === 'nova_lista') {
        campoNovaLista.style.display = 'block';  // Exibe o campo
    } else {
        campoNovaLista.style.display = 'none';  // Oculta o campo
    }
}
});
</script>
</body>
</html>


<?php
// Database connection (mantido)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gamecheck";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Get game ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID do jogo inválido");
}

// Fetch game details
$sql = "SELECT id_jogo, nome_jogo, desenvolvedora_jogo, ano_lancamento, capa_jogo 
        FROM Jogo 
        WHERE id_jogo = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Jogo não encontrado");
}

$jogo = $result->fetch_assoc();

// Check if capa_jogo is a URL or a BLOB
$capa_jogo = $jogo['capa_jogo'];
$is_url = filter_var($capa_jogo, FILTER_VALIDATE_URL);

// If it is a BLOB, convert to base64
$capa_base64 = '';
if (!$is_url && $capa_jogo) {
    $capa_base64 = base64_encode($capa_jogo);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/jogo-geral.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon.png" />
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
                <a href="#">Nome Usuário
                    <img src="./assets/img/user-profile.jpg" alt="Foto de Perfil" class="profile-pic"
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
                        <!-- Display the image if it's a valid URL -->
                        <img src="<?php echo htmlspecialchars($jogo['capa_jogo']); ?>" alt="<?php echo htmlspecialchars($jogo['nome_jogo']); ?>" class="card-image" />
                    <?php else: ?>
                        <!-- Display a default image if no valid URL is found -->
                        <img src="./assets/img/no-image.jpg" alt="Imagem não disponível" class="card-image" />
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
                            <span class="star" data-value="1">&#9733;</span>
                            <span class="star" data-value="2">&#9733;</span>
                            <span class="star" data-value="3">&#9733;</span>
                            <span class="star" data-value="4">&#9733;</span>
                            <span class="star" data-value="5">&#9733;</span>
                        </div>
                    </div>
                    
                    <div class="genero">
                        <div class="genero-cards">
                            <div class="card-genero">Ação</div>
                            <div class="card-genero">Sobrevivência</div>
                            <div class="card-genero">RPG</div>
                            <div class="card-genero">Terror</div>
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
            <ul id="lista-comentarios"><div class="comentario">
                <img src="./assets/img/user-profile.jpg" alt="Foto de Perfil" class="comentario-foto" />
                <div class="comentario-conteudo">
                    <p class="comentario-nome">Review por <strong class="realce">Nome Usuário</strong></p>
                    <div class="comentario-estrelas">
                        <span class="estrela">&#9733;</span>
                        <span class="estrela">&#9733;</span>
                        <span class="estrela">&#9733;</span>
                        <span class="estrela">&#9733;</span>
                        <span class="estrela">&#9733;</span>
                    </div>
                    <button class="comentario-coracao">&#10084;</button>
                    <p class="comentario-texto">Eu adorei o jogo, muito imersivo ao mesmo tempo que é muito difícil sobreviver. Entretanto, acredito que é isso que faz a magia do jogo, deixa tão realista, adoro jogar com amigos no novo modo online! (●'◡'●)</p>
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
            // Se estiver usando PHP backend
            const formData = new FormData();
            formData.append('nota', selectedRating);
            formData.append('avaliacao', comentario);
            formData.append('id_jogo', jogoId);

            const response = await fetch('salvar_avaliacao.php', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                // Criar novo comentário na interface
                const novoComentario = `
                    <div class="comentario">
                        <img src="./assets/img/user-profile.jpg" alt="Foto de Perfil" class="comentario-foto" />
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
                
                alert("Avaliação enviada com sucesso!");
            } else {
                throw new Error('Erro ao salvar avaliação');
            }
        } catch (error) {
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
});</script>
</body>
</html>

# Sistema de Avaliação de Jogos - GAMECHECK

Este repositório contém o projeto **GAMECHECK**, um sistema desenvolvido para a disciplina de Banco de Dados II, na Universidade Estadual de Montes Claros. GAMECHECK é uma plataforma interativa que permite aos usuários avaliar jogos, criar listas personalizadas e interagir com uma comunidade apaixonada por games.

---

## 📜 **Sumário**
- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Funcionalidades](#funcionalidades)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Como Rodar o Projeto](#como-rodar-o-projeto)
- [Contribuições](#contribuições)
- [Licença](#licença)

---

## 🕹️ **Sobre o Projeto**

O GAMECHECK é uma plataforma projetada para oferecer aos jogadores:
- Avaliações e comentários sobre jogos.
- Criação e compartilhamento de listas personalizadas.
- Busca detalhada com filtros avançados (gênero, plataforma, desenvolvedor e ano de lançamento).
- Experiência de comunidade, com preferências e destaques semanais.

---

## 💻 **Tecnologias Utilizadas**
- **Frontend:** HTML, CSS, JavaScript.
- **Backend:** PHP.
- **Banco de Dados:** MySQL.
- **Ferramentas de Desenvolvimento:** XAMPP, phpMyAdmin, GitHub.

---

## 🚀 **Funcionalidades**

### Gerenciamento de Usuários
- Cadastro, login e edição de perfil.
- Foto de perfil e biografia personalizáveis.

### Avaliações e Comentários
- Avaliação com estrelas (1 a 5).
- Criação de reviews com limite de 250 caracteres.
- Visualização e exclusão de comentários.

### Listas Personalizadas
- Em futuras implementações...

### Busca e Catálogo de Jogos
- Filtros avançados por gênero, plataforma, desenvolvedor e ano.
- Visualização detalhada de informações do jogo, como descrição e média de avaliação.

### Preferências da Comunidade
- Jogos mais amados e mais odiados da semana.
- Listas populares criadas por outros usuários.

---

## 📂 **Estrutura do Projeto**
```plaintext
├── client/
│   ├── index.html         # Página inicial
│   ├── css/
│   │   └── style.css      # Estilos do projeto
│   ├── js/
│       └── script.js      # Scripts do frontend
├── backend/
│   ├── index.php          # Controle de rotas
│   ├── controllers/       # Regras de negócio
│   ├── models/            # Interações com o banco de dados
│   └── views/             # Interfaces dinâmicas
├── sql/
│   └── gamecheck.sql      # Script de criação do banco de dados
└── README.md              # Documentação do projeto
```

---

## ⚙️ **Como Rodar o Projeto**

### Pré-requisitos
- XAMPP (ou outro servidor Apache com suporte a PHP e MySQL).
- Navegador atualizado.

### Passos
1. Clone o repositório:
   ```bash
   git clone https://github.com/seu_usuario/gamecheck.git
   ```
2. Importe o banco de dados:
   - Abra o phpMyAdmin.
   - Crie um banco chamado `gamecheck`.
   - Importe o arquivo `sql/gamecheck.sql`.

3. Configure o servidor:
   - Mova os arquivos do projeto para a pasta `htdocs` do XAMPP.
   - Inicie os serviços Apache e MySQL.

4. Acesse o sistema:
   - Abra o navegador e vá para `http://localhost/gamecheck`.

---

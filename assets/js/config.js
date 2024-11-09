document.addEventListener('DOMContentLoaded', function() {
    const profilePicture = document.getElementById('profile-picture');
    const profileImage = document.getElementById('profile-image');
    const editUsername = document.getElementById('edit-username');
    const displayUsername = document.getElementById('display-username');
    const settingsForm = document.getElementById('settings-form');
    const modal = document.getElementById('confirmation-modal');
    const closeModal = document.getElementById('close-modal');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');

    // Alterar foto de perfil
    profilePicture.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImage.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Editar nome de usuário
    editUsername.addEventListener('click', function() {
        const newUsername = prompt('Digite o novo nome de usuário:', displayUsername.textContent);
        if (newUsername !== null && newUsername.trim() !== '') {
            displayUsername.textContent = newUsername.trim();
        }
    });

    // Função para mostrar alerta
    function showAlert(message, type) {
        const alertElement = document.createElement('div');
        alertElement.className = `alert alert-${type}`;
        alertElement.textContent = message;
        settingsForm.insertBefore(alertElement, settingsForm.firstChild);
        setTimeout(() => alertElement.remove(), 5000);
    }

    // Validar senha
    function validatePassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (password.length < 8) {
            showAlert('A senha deve ter pelo menos 8 caracteres.', 'error');
            return false;
        }

        if (password !== confirmPassword) {
            showAlert('As senhas não coincidem.', 'error');
            return false;
        }

        return true;
    }

    // Salvar alterações e mostrar modal de confirmação
    settingsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (validatePassword()) {
            // Aqui você adicionaria a lógica para salvar as alterações no servidor
            modal.style.display = 'block';
        }
    });

    // Fechar modal
    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Fechar modal ao clicar fora dele
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
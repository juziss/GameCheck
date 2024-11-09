document.addEventListener('DOMContentLoaded', function() {
    const editBioButton = document.getElementById('edit-bio-button');
    const userBio = document.getElementById('user-bio');

    editBioButton.addEventListener('click', function() {
        const currentBio = userBio.textContent;
        const newBio = prompt('Edite sua bio:', currentBio);
        
        if (newBio !== null && newBio.trim() !== '') {
            userBio.textContent = newBio.trim();
        }
    });

    console.log('Página de perfil carregada');
});
// Seleciona o ícone do perfil e o menu suspenso
const profileIcon = document.getElementById('profile-icon');
const profileContainer = document.getElementById('profile-container');
const dropdownMenu = profileContainer.querySelector('.dropdown-menu');

// Adiciona um evento de clique ao ícone do perfil
profileIcon.addEventListener('click', function(event) {
    // Previne o clique de se propagar
    event.stopPropagation();
    // Alterna a visibilidade do menu suspenso
    dropdownMenu.classList.toggle('show');
});

// Fecha o menu suspenso se o clique for fora do perfil
document.addEventListener('click', function(event) {
    if (!profileContainer.contains(event.target)) {
        dropdownMenu.classList.remove('show');
    }
});
const profileIcon = document.getElementById('profile-icon');
const dropdownMenu = document.querySelector('.dropdown-menu');

profileIcon.addEventListener('click', function(event) {
    event.stopPropagation(); // Previne o clique de se propagar
    console.log('Profile icon clicked');
    dropdownMenu.classList.toggle('show'); // Alterna a visibilidade do menu suspenso
    console.log('Menu visibility after toggle:', dropdownMenu.classList.contains('show'));
});

document.addEventListener('click', function(event) {
    if (!profileIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.remove('show');
    }
});

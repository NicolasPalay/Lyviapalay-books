document.addEventListener('DOMContentLoaded', () => {
    let searchIcon = document.querySelector('.lp-search-icon');
    let searchForm = document.querySelector('.lp-search');
    let searchInput = document.querySelector('.lp-search input');
    let lpSearchActive = document.querySelector('.lp-search-active');

    if (searchIcon && searchForm && searchInput) {
        searchIcon.addEventListener('click', () => {
            searchForm.style.display = 'block';
            searchInput.style.width = '400px';
            searchInput.style.transition = 'width 4s';
            searchForm.classList.add('lp-search-active');
        });

        // Ajouter un écouteur d'événement pour capturer les clics en dehors de l'icône et du formulaire
        document.addEventListener('click', () => {
            if (lpSearchActive) {
                searchForm.style.display = 'none';
                searchInput.style.width = '0';
                searchInput.style.transition = 'width 1s';
                searchForm.classList.remove('lp-search-active');
            }
        });
    }
});
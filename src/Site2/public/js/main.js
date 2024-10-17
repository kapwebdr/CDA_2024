document.addEventListener('DOMContentLoaded', (event) => {
    // Gestion de la barre de recherche
    const searchBar = document.querySelector('.search-bar input');
    const searchButton = document.querySelector('.search-bar button');

    searchButton.addEventListener('click', () => {
        performSearch(searchBar.value);
    });

    searchBar.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            performSearch(searchBar.value);
        }
    });

    function performSearch(query) {
        // Ici, vous pouvez implémenter la logique de recherche
        console.log('Recherche pour:', query);
        // Rediriger vers une page de résultats ou effectuer une recherche AJAX
    }

    // Animation pour les cartes de jeux
    const gameCards = document.querySelectorAll('.game-card');
    gameCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'scale(1.05)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'scale(1)';
        });
    });

    // Gestion de l'ajout au panier
    document.body.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('add-to-cart')) {
            e.preventDefault();
            const gameId = e.target.getAttribute('data-game-id');
            addToCart(gameId, e.target);
        }
    });

    function addToCart(gameId, button) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/cart/add', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    updateCartCount(response.cartCount);
                    showAddToCartAnimation(button);
                }
            }
        };
        xhr.send('game_id=' + gameId);
    }

    function updateCartCount(count) {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = count;
            cartCount.classList.add('updated');
            setTimeout(() => cartCount.classList.remove('updated'), 300);
        }
    }

    function showAddToCartAnimation(button) {
        button.classList.add('added');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Ajouté';
        setTimeout(() => {
            button.classList.remove('added');
            button.innerHTML = originalText;
        }, 2000);

        const cart = document.querySelector('.cart-icon');
        if (cart) {
            cart.classList.add('shake');
            setTimeout(() => cart.classList.remove('shake'), 500);
        }
    }

    // Gestion du menu utilisateur sur mobile
    const userMenuButton = document.querySelector('.btn-user-menu');
    const userDropdown = document.querySelector('.user-dropdown');

    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', function(e) {
            e.preventDefault();
            userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Fermer le menu si on clique en dehors
        document.addEventListener('click', function(e) {
            if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.style.display = 'none';
            }
        });
    }
});

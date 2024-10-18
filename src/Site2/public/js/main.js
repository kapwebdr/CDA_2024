document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour ajouter au panier
    function addToCart(button) {
        const gameId = button.getAttribute('data-game-id');
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

    // Fonction pour mettre à jour le compteur du panier
    function updateCartCount(count) {
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = count;
            cartCount.classList.add('updated');
            setTimeout(() => cartCount.classList.remove('updated'), 300);
        }
    }

    // Fonction pour afficher l'animation d'ajout au panier
    function showAddToCartAnimation(button) {
        button.classList.add('added');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Ajouté';
        setTimeout(() => {
            button.classList.remove('added');
            button.innerHTML = originalText;
        }, 2000);

        // Animation du panier
        const cartIcon = document.querySelector('.cart-icon');
        if (cartIcon) {
            cartIcon.classList.add('shake');
            setTimeout(() => cartIcon.classList.remove('shake'), 500);
        }
    }

    // Fonction pour tronquer le texte
    function truncateText() {
        const descriptions = document.querySelectorAll('.game-description');
        descriptions.forEach(desc => {
            const originalText = desc.textContent;
            const words = originalText.split(' ');
            let truncated = words.slice(0, 30).join(' '); // Limite à 30 mots

            if (words.length > 30) {
                truncated += '...';
            }

            desc.textContent = truncated;
            desc.title = originalText; // Ajoute le texte complet en tooltip
        });
    }

    // Fonction pour égaliser la hauteur des cartes
    function equalizeCardHeights() {
        const cards = document.querySelectorAll('.game-card');
        let maxHeight = 0;

        // Réinitialiser la hauteur
        cards.forEach(card => {
            card.style.height = 'auto';
        });

        // Trouver la hauteur maximale
        cards.forEach(card => {
            const height = card.offsetHeight;
            if (height > maxHeight) {
                maxHeight = height;
            }
        });

        // Appliquer la hauteur maximale à toutes les cartes
        cards.forEach(card => {
            card.style.height = `${maxHeight}px`;
        });
    }

    // Fonction pour initialiser les boutons d'ajout au panier
    function initializeAddToCartButtons() {
        document.querySelectorAll('.add-to-cart').forEach(button => {
            if (!button.hasAttribute('data-listener')) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    addToCart(this);
                });
                button.setAttribute('data-listener', 'true');
            }
        });
    }

    initializeAddToCartButtons();
    truncateText();
    equalizeCardHeights();

    // Réappliquer lors du redimensionnement de la fenêtre
    window.addEventListener('resize', equalizeCardHeights);
});

// Si vous utilisez une navigation en AJAX, appelez initializeAddToCartButtons() après chaque chargement de page

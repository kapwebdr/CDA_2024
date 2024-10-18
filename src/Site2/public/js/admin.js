document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la confirmation de suppression
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const itemName = this.dataset.itemName;
            const deleteUrl = this.href;
            
            if (confirm(`Êtes-vous sûr de vouloir supprimer "${itemName}" ?`)) {
                fetch(deleteUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Suppression réussie
                        showNotification('Élément supprimé avec succès', 'success');
                        // Supprimer la ligne du tableau
                        this.closest('tr').remove();
                    } else {
                        // Erreur lors de la suppression
                        showNotification('Erreur lors de la suppression', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Une erreur est survenue', 'error');
                });
            }
        });
    });

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.className = `notification ${type}`;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Autres fonctionnalités d'administration peuvent être ajoutées ici
});

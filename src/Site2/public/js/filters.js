document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category-select');
    const editorSelect = document.getElementById('editor-select');
    const priceRange = document.getElementById('price-range');
    const priceValue = document.getElementById('price-value');
    const toggleButtons = document.querySelectorAll('.btn-toggle');
    const gamesContainer = document.getElementById('games-container');

    function updateFilters(page = 1) {
        const category = categorySelect.value;
        const editor = editorSelect.value;
        const maxPrice = priceRange.value;
        const isOnline = document.getElementById('online-filter').classList.contains('active');
        const isAvailable = document.getElementById('available-filter').classList.contains('active');
        const isOnPromotion = document.getElementById('promotion-filter').classList.contains('active');

        fetch(`/games?ajax=1&category=${category}&editor=${editor}&maxPrice=${maxPrice}&isOnline=${isOnline}&isAvailable=${isAvailable}&isOnPromotion=${isOnPromotion}&page=${page}`)
            .then(response => response.text())
            .then(html => {
                gamesContainer.innerHTML = html;
                initPagination();
            });
    }

    function initPagination() {
        document.querySelectorAll('.pagination .btn-page').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.dataset.page;
                updateFilters(page);
            });
        });
    }

    [categorySelect, editorSelect].forEach(element => {
        element.addEventListener('change', () => updateFilters());
    });

    priceRange.addEventListener('input', function() {
        priceValue.textContent = this.value;
    });
    priceRange.addEventListener('change', () => updateFilters());

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.classList.toggle('active');
            updateFilters();
        });
    });

    // Initial setup
    priceValue.textContent = priceRange.value;
    updateFilters();
});

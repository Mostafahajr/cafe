document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.category-slider');
    let scrollAmount = 0;
    const step = 200; // Adjust step size as needed

    setInterval(function() {
        scrollAmount += step;
        if (scrollAmount > slider.scrollWidth) {
            scrollAmount = 0;
            slider.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            slider.scrollTo({ left: scrollAmount, behavior: 'smooth' });
        }
    }, 3000);
});
document.addEventListener('DOMContentLoaded', function() {
    const categoryCards = document.querySelectorAll('.category-card');

    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category-id');
            loadProducts(categoryId);
        });
    });

    function loadProducts(categoryId) {
        const productsSection = document.getElementById('products-section');
        productsSection.innerHTML = '<p>Loading...</p>'; // Show loading text while fetching

        fetch(`fetch-products.php?category_id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                let productsHtml = '';
                if (data.length > 0) {
                    data.forEach(product => {
                        productsHtml += `
                            <div class="product-card">
                                <img src="${product.image}" class="img-fluid" alt="${product.name}">
                                <div class="product-name">${product.name}</div>
                                <div class="product-price">${product.price}</div>
                            </div>
                        `;
                    });
                } else {
                    productsHtml = '<p>No products found.</p>';
                }
                productsSection.innerHTML = productsHtml;
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                productsSection.innerHTML = '<p>Error loading products.</p>';
            });
    }
});
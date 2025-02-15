document.addEventListener("DOMContentLoaded", function () {
    if (window.location.hash) {
        history.replaceState(null, null, window.location.pathname);
    }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                window.scrollTo({
                    top: target.offsetTop - 50, 
                    behavior: "smooth"
                });

                history.pushState(null, null, window.location.pathname);
            }
        });
    });
});



document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.category-buttons button');
    const productItems = document.querySelectorAll('.product-item');

    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            productItems.forEach(item => {
                if (item.getAttribute('data-category') === category || category === 'all') {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    const products = {
        1: {
            image: '../images/product1.jpeg',
            title: 'Product 1',
            description: 'Detailed description of Product 1.',
            price: '$29.99'
        },
        2: {
            image: '../images/product2.jpeg',
            title: 'Product 2',
            description: 'Detailed description of Product 2.',
            price: '$39.99'
        },
        3: {
            image: '../images/product3.jpeg',
            title: 'Product 3',
            description: 'Detailed description of Product 2.',
            price: '$40.99'
        },
        4: {
            image: '../images/product4.jpeg',
            title: 'Product 4',
            description: 'Detailed description of Product 2.',
            price: '$30.99'
        },
        5: {
            image: '../images/product5.jpeg',
            title: 'Product 5',
            description: 'Detailed description of Product 2.',
            price: '$29.99'
        },
        6: {
            image: '../images/product6.jpeg',
            title: 'Product 6',
            description: 'Detailed description of Product 2.',
            price: '$29.99'
        }
        
    };

    const product = products[productId];
    if (product) {
        document.getElementById('product-image').src = product.image;
        document.getElementById('product-title').textContent = product.title;
        document.getElementById('product-description').textContent = product.description;
        document.getElementById('product-price').textContent = product.price;
    } else {
        document.querySelector('.product-details').innerHTML = '<p>Product not found.</p>';
    }
});

document.getElementById("delivery-form").addEventListener("submit", function(event) {
    event.preventDefault();
    let selectedWilaya = document.getElementById("wilaya").value;
    
    if (selectedWilaya) {
        alert("Wilaya selectionnée: " + selectedWilaya);
    } else {
        alert("s'il vous plait, selectionnez une Wilaya.");
    }
});


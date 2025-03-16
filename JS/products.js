document.addEventListener("DOMContentLoaded", function () {
    fetch("http://localhost/Wafra/PHP/products.php")
        .then(response => response.json())
        .then(products => {
            const productContainer = document.querySelector(".product-items");
            productContainer.innerHTML = "";

            products.forEach(product => {
                const productHTML = `
                    <div class="product-item">
                        <img src="../${product.image}" alt="${product.name}">
                        <h3>${product.name}</h3>
                        <p>$${product.price}</p>
                        <a href="details_page.html?id=${product.id}" class="cta-button">View Details</a>
                    </div>
                `;
                productContainer.innerHTML += productHTML;
            });
        })
        .catch(error => console.error("Error loading products:", error));
});
document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get("id");

    if (!productId) {
        alert("No product selected!");
        return;
    }

    fetch(`../PHP/products.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                document.getElementById("product-image").src = "../images/" + data.image;
                document.getElementById("product-title").textContent = data.name;
                document.getElementById("product-description").textContent = data.description;
                document.getElementById("product-price").textContent = "Prix: " + data.price + " DA";
            }
        })
        .catch(error => console.error("Error fetching product:", error));
});

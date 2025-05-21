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


document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const API_BASE_URL = "http://localhost/Wafra/PHP";

    async function handleSubmit(url, formData) {
        const response = await fetch(`${API_BASE_URL}/${url}`, {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
            alert(result.message);
            window.location.href = result.redirect; 
        } else {
            alert(result.message);
        }

    }

    // Handle login form submission
    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(loginForm);
        handleSubmit("login.php", formData);
    });

    // Handle register form submission
    registerForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(registerForm);
        handleSubmit("register.php", formData);
    });

    // Toggle between login and register forms
    document.getElementById("show-register").addEventListener("click", function (event) {
        event.preventDefault();
        loginForm.classList.add("hidden");
        registerForm.classList.remove("hidden");
    });

    document.getElementById("show-login").addEventListener("click", function (event) {
        event.preventDefault();
        registerForm.classList.add("hidden");
        loginForm.classList.remove("hidden");
    });
});




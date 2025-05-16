document.addEventListener('DOMContentLoaded', function() {
    // Sistema de carrito
    const cartCount = document.getElementById('cart-count');
    let cartItems = JSON.parse(localStorage.getItem('cart')) || {};
    
    // Actualizar contador del carrito
    function updateCartCount() {
        const totalItems = Object.values(cartItems).reduce((total, qty) => total + qty, 0);
        cartCount.textContent = totalItems || '0';
    }
    updateCartCount();
    
    // Agregar al carrito
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            
            cartItems[productId] = (cartItems[productId] || 0) + 1;
            localStorage.setItem('cart', JSON.stringify(cartItems));
            updateCartCount();
            
            // Feedback visual
            const originalText = this.textContent;
            this.textContent = '✓ Agregado';
            this.classList.add('added-to-cart');
            
            setTimeout(() => {
                this.textContent = originalText;
                this.classList.remove('added-to-cart');
            }, 2000);
        });
    });
    
    // Manejar carrito.html
    if (document.querySelector('.cart-items')) {
        // Actualizar cantidades
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.getAttribute('data-id');
                const newQty = parseInt(this.value);
                
                if (newQty > 0) {
                    cartItems[productId] = newQty;
                } else {
                    delete cartItems[productId];
                }
                
                localStorage.setItem('cart', JSON.stringify(cartItems));
                updateCartCount();
                location.reload();
            });
        });
        
        // Eliminar items
        document.querySelectorAll('.btn-remove').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-id');
                delete cartItems[productId];
                localStorage.setItem('cart', JSON.stringify(cartItems));
                updateCartCount();
                this.closest('tr').remove();
                
                // Actualizar vista si el carrito queda vacío
                if (Object.keys(cartItems).length === 0) {
                    document.querySelector('.cart-items').innerHTML = `
                        <div class="empty-cart">
                            <p>Tu carrito está vacío</p>
                            <a href="productos.php" class="btn">Ver Productos</a>
                        </div>
                    `;
                }
            });
        });
    }
    
    // Validación de formulario de registro
    const registerForm = document.querySelector('form[action="includes/auth.php"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
            }
        });
    }
    
    // Mostrar/ocultar contraseña
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        });
    });
});
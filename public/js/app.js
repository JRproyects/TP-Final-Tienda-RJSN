// Carrito de compras
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
const carritoModalEl = document.getElementById('carritoModal');
const loginModalEl = document.getElementById('loginModal');
const carritoModal = carritoModalEl ? new bootstrap.Modal(carritoModalEl) : null;
const loginModal = loginModalEl ? new bootstrap.Modal(loginModalEl) : null;

// Actualizar carrito en la interfaz
function actualizarCarrito() {
    const carritoContenido = document.getElementById('carritoContenido');
    const cartBadge = document.getElementById('cartBadge');
    const carritoTotal = document.getElementById('carritoTotal');
    const totalItems = document.getElementById('totalItems');
    const btnFinalizarCompra = document.getElementById('btnFinalizarCompra');

    // Calcular totales
    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    const itemsCount = carrito.reduce((sum, item) => sum + item.cantidad, 0);

    // Actualizar badge
    cartBadge.textContent = itemsCount;
    cartBadge.style.display = itemsCount > 0 ? 'flex' : 'none';

    // Actualizar totales
    carritoTotal.textContent = total.toFixed(2);
    totalItems.textContent = itemsCount + ' item' + (itemsCount !== 1 ? 's' : '');

    // Habilitar/deshabilitar botón de finalizar
    btnFinalizarCompra.disabled = itemsCount === 0;

    // Generar contenido del carrito
    if (carrito.length === 0) {
        carritoContenido.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                <h5>Tu carrito está vacío</h5>
                <p class="text-muted">Agrega algunos productos para comenzar</p>
            </div>
        `;
    } else {
        carritoContenido.innerHTML = carrito.map(item => `
            <div class="cart-item">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="mb-1">${item.nombre}</h6>
                        <small class="text-muted">$${item.precio.toFixed(2)} c/u</small>
                    </div>
                    <div class="col-md-4">
                        <div class="quantity-controls">
                            <button class="quantity-btn" onclick="cambiarCantidad(${item.id}, -1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="mx-2 fw-bold">${item.cantidad}</span>
                            <button class="quantity-btn" onclick="cambiarCantidad(${item.id}, 1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <span class="fw-bold text-primary">$${(item.precio * item.cantidad).toFixed(2)}</span>
                        <br>
                        <button class="btn btn-sm btn-outline-danger mt-1" onclick="eliminarDelCarrito(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Guardar en localStorage
    localStorage.setItem('carrito', JSON.stringify(carrito));
}

// Agregar producto al carrito
document.querySelectorAll('.agregar-carrito').forEach(button => {
    button.addEventListener('click', function() {
        const producto = {
            id: parseInt(this.getAttribute('data-id')),
            nombre: this.getAttribute('data-nombre'),
            precio: parseFloat(this.getAttribute('data-precio')),
            stock: parseInt(this.getAttribute('data-stock')),
            cantidad: 1
        };
        
        // Verificar stock
        if (producto.cantidad > producto.stock) {
            mostrarNotificacion('❌ No hay suficiente stock disponible', 'danger');
            return;
        }

        // Verificar si ya está en el carrito
        const itemExistente = carrito.find(item => item.id === producto.id);
        if(itemExistente) {
            if (itemExistente.cantidad + 1 > producto.stock) {
                mostrarNotificacion('❌ No hay suficiente stock disponible', 'danger');
                return;
            }
            itemExistente.cantidad++;
        } else {
            carrito.push(producto);
        }
        
        actualizarCarrito();
        mostrarNotificacion('✅ ' + producto.nombre + ' agregado al carrito', 'success');
    });
});

// Cambiar cantidad de un item
function cambiarCantidad(productId, cambio) {
    const item = carrito.find(item => item.id === productId);
    if (item) {
        const nuevaCantidad = item.cantidad + cambio;
        if (nuevaCantidad <= 0) {
            eliminarDelCarrito(productId);
        } else if (nuevaCantidad > item.stock) {
            mostrarNotificacion('❌ No hay suficiente stock disponible', 'danger');
        } else {
            item.cantidad = nuevaCantidad;
            actualizarCarrito();
        }
    }
}

// Eliminar item del carrito
function eliminarDelCarrito(productId) {
    if (confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
        carrito = carrito.filter(item => item.id !== productId);
        actualizarCarrito();
        mostrarNotificacion('🗑️ Producto eliminado del carrito', 'warning');
    }
}

// Abrir modal del carrito
function abrirCarrito() {
    actualizarCarrito();
    carritoModal.show();
}

// Proceder al pago
function procederPago() {
    if(carrito.length === 0) {
        mostrarNotificacion('🛒 Tu carrito está vacío', 'warning');
        return;
    }
    // Verificar si el usuario está logueado (valor inyectado desde el servidor en la plantilla)
    if (window.isLoggedIn) {
        // Usuario logueado - procesar pago con Mercado Pago
        procesarPagoMercadoPago();
    } else {
        // Usuario no logueado - pedir login
        if (carritoModal) carritoModal.hide();
        setTimeout(() => { if (loginModal) loginModal.show(); }, 500);

        // Mostrar mensaje
        mostrarNotificacion('🔐 Inicia sesión para finalizar tu compra', 'info');
    }
}

// Procesar pago con Mercado Pago
function procesarPagoMercadoPago() {
    // Mostrar loading
    const btnFinalizar = document.getElementById('btnFinalizarCompra');
    let originalText = '';
    if (btnFinalizar) {
        originalText = btnFinalizar.innerHTML;
        btnFinalizar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        btnFinalizar.disabled = true;
    }

    // Preparar datos para enviar
    const formData = new FormData();
    formData.append('action', 'procesar_pago');
    formData.append('carrito', JSON.stringify(carrito));

    // Enviar datos al servidor
    fetch('/Tienda-RJSN/app/controllers/PagoController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta del servidor:', data);
        
        if (data.success && data.init_point) {
            // Redirigir a Mercado Pago
            console.log('Redirigiendo a:', data.init_point);
            window.location.href = data.init_point;
        } else {
            mostrarNotificacion('❌ Error: ' + (data.error || 'No se pudo procesar el pago'), 'danger');
            if (btnFinalizar) {
                btnFinalizar.innerHTML = originalText;
                btnFinalizar.disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('❌ Error al procesar el pago: ' + error.message, 'danger');
        if (btnFinalizar) {
            btnFinalizar.innerHTML = originalText;
            btnFinalizar.disabled = false;
        }
    });
}

// Mostrar notificación
function mostrarNotificacion(mensaje, tipo = 'info') {
    const tipos = {
        'success': 'bg-success text-white',
        'danger': 'bg-danger text-white', 
        'warning': 'bg-warning text-dark',
        'info': 'bg-info text-white'
    };

    const toast = document.createElement('div');
    toast.className = 'position-fixed top-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header ${tipos[tipo]}">
                <i class="fas fa-${tipo === 'success' ? 'check-circle' : tipo === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                <strong class="me-auto">${tipo.charAt(0).toUpperCase() + tipo.slice(1)}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${mensaje}
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

// Inicializar carrito al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarCarrito();
});
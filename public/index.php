<?php 
// Iniciar sesión solo si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar modelos necesarios
require_once '../app/config/database.php';
require_once '../app/models/Producto.php';

$database = new Database();
$db = $database->getConnection();
$producto = new Producto($db);
$productos = $producto->leer();

// Mostrar error de login si existe
$login_error = '';
if(isset($_SESSION['login_error'])) {
    $login_error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .card {
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .product-card {
            height: 100%;
        }
        .product-image {
            height: 200px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
        }
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        /* Carrito Flotante */
        .cart-floating {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        .cart-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #28a745;
            color: white;
            border: none;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s;
        }
        .cart-btn:hover {
            background: #218838;
            transform: scale(1.1);
        }
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Modal del Carrito */
        .cart-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .info-section {
            background: #f8f9fa;
            padding: 60px 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-store"></i> Tienda Online
            </a>
            <div class="navbar-nav ms-auto">
                <?php if(isset($_SESSION['idusuario'])): ?>
                    <span class="navbar-text me-3">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usnombre']); ?>
                    </span>
                    <a class="nav-link btn btn-outline-light btn-sm me-2" href="/Tienda-RJSN/dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Panel
                    </a>
                    <a class="nav-link btn btn-outline-light btn-sm" href="/Tienda-RJSN/app/controllers/AuthController.php?action=logout">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </a>
                <?php else: ?>
                    <a class="nav-link btn btn-outline-light btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Bienvenido a Nuestra Tienda</h1>
            <p class="lead">Los mejores productos al mejor precio. Compra sin registrarte y paga cuando quieras.</p>
            <a href="#productos" class="btn btn-light btn-lg mt-3">
                <i class="fas fa-shopping-bag"></i> Ver Productos
            </a>
        </div>
    </section>

    <!-- Sección de Productos -->
    <section id="productos" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center mb-5">🛍️ Nuestros Productos</h2>
                    <?php if($productos->rowCount() > 0): ?>
                        <div class="row">
                            <?php while($row = $productos->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card product-card">
                                    <div class="product-image">
                                        <?php 
                                        $icons = ['💻', '🖱️', '⌨️', '🖥️', '🎧', '📱'];
                                        $iconIndex = ($row['idproducto'] - 1) % count($icons);
                                        echo $icons[$iconIndex];
                                        ?>
                                    </div>
                                    <div class="card-body">
                                        <span class="badge stock-badge <?php echo $row['procantstock'] > 5 ? 'bg-success' : 'bg-warning'; ?>">
                                            <i class="fas fa-box"></i> <?php echo $row['procantstock']; ?>
                                        </span>
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['pronombre']); ?></h5>
                                        <p class="card-text text-muted small"><?php echo htmlspecialchars($row['prodetalle']); ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="h5 text-primary mb-0">
                                                <i class="fas fa-dollar-sign"></i><?php echo number_format($row['proprecio'], 2); ?>
                                            </span>
                                            <?php if($row['procantstock'] > 0): ?>
                                                <button class="btn btn-primary btn-sm agregar-carrito" 
                                                        data-id="<?php echo $row['idproducto']; ?>"
                                                        data-nombre="<?php echo htmlspecialchars($row['pronombre']); ?>"
                                                        data-precio="<?php echo $row['proprecio']; ?>"
                                                        data-stock="<?php echo $row['procantstock']; ?>">
                                                    <i class="fas fa-cart-plus"></i> Agregar
                                                </button>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><i class="fas fa-times"></i> Sin Stock</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <h5><i class="fas fa-frown"></i> No hay productos disponibles</h5>
                            <p class="text-muted">Próximamente agregaremos nuevos productos.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Información de la Tienda -->
    <section class="info-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center">
                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                    <h4>Envío Gratis</h4>
                    <p class="text-muted">En compras mayores a $5000</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h4>Pago Seguro</h4>
                    <p class="text-muted">Transacciones 100% seguras</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                    <h4>Soporte 24/7</h4>
                    <p class="text-muted">Estamos aquí para ayudarte</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contacto -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3>📞 Contacto</h3>
                    <p><strong>Email:</strong> info@tiendaonline.com</p>
                    <p><strong>Teléfono:</strong> +54 11 1234-5678</p>
                    <p><strong>Dirección:</strong> Av. Siempre Viva 742, Buenos Aires</p>
                </div>
                <div class="col-md-6">
                    <h3>🕒 Horarios de Atención</h3>
                    <p><strong>Lunes a Viernes:</strong> 9:00 - 18:00 hs</p>
                    <p><strong>Sábados:</strong> 9:00 - 13:00 hs</p>
                    <p><strong>Domingos:</strong> Cerrado</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Carrito Flotante -->
    <div class="cart-floating">
        <button class="cart-btn" onclick="abrirCarrito()">
            <i class="fas fa-shopping-cart"></i>
        </button>
        <div class="cart-badge" id="cartBadge">0</div>
    </div>

    <!-- Modal del Carrito -->
    <div class="modal fade" id="carritoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-shopping-cart"></i> Mi Carrito de Compras
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="carritoContenido">
                        <!-- Contenido del carrito se cargará aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Total: $<span id="carritoTotal">0.00</span></h5>
                            <span class="badge bg-secondary" id="totalItems">0 items</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">
                                <i class="fas fa-arrow-left"></i> Seguir Comprando
                            </button>
                            <button type="button" class="btn btn-success flex-fill" onclick="procederPago()" id="btnFinalizarCompra">
                                <i class="fas fa-credit-card"></i> Proceder al Pago
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Login -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🔐 Iniciar Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/Tienda-RJSN/app/controllers/AuthController.php" method="POST">
                    <div class="modal-body">
                        <?php if(!empty($login_error)): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> <?php echo $login_error; ?>
                            </div>
                        <?php endif; ?>
                        <input type="hidden" name="action" value="login">
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="usnombre" required placeholder="Ingresa tu usuario">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="uspass" required placeholder="Ingresa tu contraseña">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Recordarme</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-0">
                <i class="fas fa-store"></i> Tienda Online &copy; <?php echo date('Y'); ?> - Todos los derechos reservados
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variable inyectada por PHP para que el JS sepa si el usuario está logueado
        window.isLoggedIn = <?php echo isset($_SESSION['idusuario']) ? 'true' : 'false'; ?>;
    </script>
    <script src="js/app.js"></script>
</body>
</html>
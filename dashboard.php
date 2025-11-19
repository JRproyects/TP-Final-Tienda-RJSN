<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si el usuario está logueado
if(!isset($_SESSION['idusuario'])) {
    header("Location: /Tienda-RJSN/public/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .welcome-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            border-radius: 10px;
        }
        .role-card {
            transition: transform 0.2s;
        }
        .role-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i> Panel de Control
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usnombre']); ?>
                </span>
                <a class="nav-link btn btn-outline-light btn-sm me-2" href="/Tienda-RJSN/public/index.php">
                    <i class="fas fa-store"></i> Ir a Tienda
                </a>
                <a class="nav-link btn btn-outline-light btn-sm" href="/Tienda-RJSN/app/controllers/AuthController.php?action=logout">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Sección de Bienvenida -->
        <div class="welcome-section text-center">
            <h1><i class="fas fa-user-shield"></i> Vista Privada</h1>
            <p class="lead">Acceso exclusivo para usuarios registrados</p>
        </div>

        <!-- Información del Usuario -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-id-card"></i> Información de tu Cuenta</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-user"></i> Usuario:</strong><br>
                                <?php echo htmlspecialchars($_SESSION['usnombre']); ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-envelope"></i> Email:</strong><br>
                                <?php echo htmlspecialchars($_SESSION['usmail']); ?></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-user-tag"></i> Roles:</strong><br>
                                    <?php 
                                    if(isset($_SESSION['roles']) && is_array($_SESSION['roles'])) {
                                        foreach($_SESSION['roles'] as $rol) {
                                            echo '<span class="badge bg-primary me-1">' . $rol . '</span>';
                                        }
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p><strong><i class="fas fa-calendar"></i> Sesión:</strong><br>
                                <?php echo date('d/m/Y H:i:s'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Funcionalidades por Rol -->
        <div class="row">
            <?php if(isset($_SESSION['roles']) && in_array('cliente', $_SESSION['roles'])): ?>
            <div class="col-md-4 mb-4">
                <div class="card role-card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-shopping-cart"></i> Cliente</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Funciones disponibles para clientes:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Ver historial de compras</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Gestionar perfil</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Ver productos</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Realizar compras</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="/Tienda-RJSN/public/index.php" class="btn btn-primary w-100">
                            <i class="fas fa-store"></i> Ir de Compras
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['roles']) && in_array('deposito', $_SESSION['roles'])): ?>
            <div class="col-md-4 mb-4">
                <div class="card role-card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-warehouse"></i> Depósito</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Funciones disponibles para depósito:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Gestionar inventario</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Controlar stock</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Gestionar entregas</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Ver reportes de stock</li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['roles']) && in_array('administrador', $_SESSION['roles'])): ?>
            <div class="col-md-4 mb-4">
                <div class="card role-card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0"><i class="fas fa-crown"></i> Administrador</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Funciones disponibles para administradores:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Gestionar usuarios</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Ver reportes completos</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Configurar sistema</li>
                            <li class="list-group-item"><i class="fas fa-check text-success"></i> Todas las funciones</li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">
                <i class="fas fa-shield-alt"></i> Área Privada - Tienda Online &copy; <?php echo date('Y'); ?>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

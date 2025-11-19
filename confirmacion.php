<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación - Tienda Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/Tienda-RJSN/public/index.php">
                <i class="fas fa-store"></i> Tienda Online
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (isset($_SESSION['compra_exitosa']) && $_SESSION['compra_exitosa']): ?>
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h3>¡Pago Exitoso!</h3>
                        <p>Tu compra #<?php echo $_SESSION['compra_id'] ?? '000'; ?> ha sido procesada correctamente.</p>
                        <p class="mb-0">
                            <a href="/Tienda-RJSN/public/index.php" class="btn btn-success">
                                <i class="fas fa-shopping-bag"></i> Seguir Comprando
                            </a>
                        </p>
                    </div>
                <?php elseif (isset($_SESSION['compra_pendiente']) && $_SESSION['compra_pendiente']): ?>
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-clock fa-3x mb-3"></i>
                        <h3>Pago Pendiente</h3>
                        <p>Tu pago está siendo procesado. Te notificaremos cuando se complete.</p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h3>Error en el Pago</h3>
                        <p>Ha ocurrido un error al procesar tu pago.</p>
                        <p class="mb-0">
                            <a href="/Tienda-RJSN/public/index.php" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Volver a Intentar
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php 
    // Limpiar variables de sesión
    unset($_SESSION['compra_exitosa']);
    unset($_SESSION['compra_pendiente']);
    unset($_SESSION['compra_error']);
    unset($_SESSION['compra_id']);
    ?>
</body>
</html>
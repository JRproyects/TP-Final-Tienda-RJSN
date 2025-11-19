<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Solo procesar si es POST para pago
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'procesar_pago') {
    
    // Verificar que el usuario esté logueado
    if (!isset($_SESSION['idusuario'])) {
        echo json_encode([
            'success' => false,
            'error' => 'Usuario no autenticado'
        ]);
        exit;
    }

    // Simular procesamiento de pago (sin Mercado Pago por ahora)
    $carrito = json_decode($_POST['carrito'], true);
    $usuario_id = $_SESSION['idusuario'];
    
    if (empty($carrito)) {
        echo json_encode([
            'success' => false,
            'error' => 'Carrito vacío'
        ]);
        exit;
    }

    try {
        // Calcular total
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        // Simular éxito de pago
        $compra_id = uniqid(); // ID temporal
        
        // En un caso real, aquí se integraría con Mercado Pago
        // Por ahora simulamos una URL de pago
        $init_point = "https://www.mercadopago.com.ar/checkout/v1/redirect?pref_id=TEST-" . uniqid();
        
        echo json_encode([
            'success' => true,
            'compra_id' => $compra_id,
            'preference_id' => 'TEST-' . uniqid(),
            'init_point' => $init_point,
            'total' => $total,
            'message' => 'Pago procesado correctamente'
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error al procesar el pago: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Procesar respuesta de pago (GET)
if (isset($_GET['action']) && isset($_GET['compra_id'])) {
    $action = $_GET['action'];
    $compra_id = $_GET['compra_id'];
    
    switch ($action) {
        case 'success':
            $_SESSION['compra_exitosa'] = true;
            $_SESSION['compra_id'] = $compra_id;
            break;
            
        case 'failure':
            $_SESSION['compra_error'] = "El pago fue rechazado";
            break;
            
        case 'pending':
            $_SESSION['compra_pendiente'] = true;
            $_SESSION['compra_id'] = $compra_id;
            break;
    }
    
    // Redirigir a la página de confirmación
    header("Location: /Tienda-RJSN/confirmacion.php");
    exit;
}

// Si se accede directamente sin parámetros válidos
header("Location: /Tienda-RJSN/public/index.php");
exit;
?>
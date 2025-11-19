<?php
require_once '../vendor/autoload.php';
require_once '../models/Compra.php';
require_once '../config/database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailController {
    private $compra;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->compra = new Compra($this->db);
    }

    public function enviarConfirmacionCompra($idcompra) {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tu_email@gmail.com';
            $mail->Password = 'tu_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinatarios
            $mail->setFrom('tienda@online.com', 'Tienda Online');
            $mail->addAddress($_SESSION['usmail'], $_SESSION['usnombre']);

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de Compra #' . $idcompra;
            
            $detalleCompra = $this->compra->obtenerDetalle($idcompra);
            $mail->Body = $this->generarTemplateEmail($detalleCompra);

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function generarTemplateEmail($detalle) {
        return "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .header { background: #007bff; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background: #f8f9fa; padding: 10px; text-align: center; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Confirmación de Compra</h1>
            </div>
            <div class='content'>
                <h3>Gracias por tu compra!</h3>
                <p>Número de compra: <strong>#{$detalle['idcompra']}</strong></p>
                <p>Fecha: {$detalle['fecha']}</p>
                <h4>Productos:</h4>
                <ul>
        " . $this->generarListaProductos($detalle['items']) . "
                </ul>
                <p><strong>Total: $ {$detalle['total']}</strong></p>
            </div>
            <div class='footer'>
                <p>Tienda Online - Todos los derechos reservados</p>
            </div>
        </body>
        </html>
        ";
    }

    private function generarListaProductos($items) {
        $html = '';
        foreach($items as $item) {
            $html .= "<li>{$item['producto']} - Cantidad: {$item['cantidad']} - $ {$item['subtotal']}</li>";
        }
        return $html;
    }
}

// Procesar solicitud
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if($input['action'] == 'enviarConfirmacion') {
        $emailController = new EmailController();
        $emailController->enviarConfirmacionCompra($input['idcompra']);
    }
}
?>
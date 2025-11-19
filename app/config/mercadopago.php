<?php
require_once __DIR__ . '/../../vendor/autoload.php';

class MercadoPagoConfig {
    private $mp;
    
    public function __construct() {
        // Credenciales de prueba - CAMBIAR POR LAS REALES EN PRODUCCIÓN
        MercadoPago\SDK::setAccessToken('TEST-YOUR_ACCESS_TOKEN_HERE');
        
        $this->mp = new MercadoPago\SDK();
    }
    
    public function getInstance() {
        return $this->mp;
    }
    
    // Crear preferencia de pago
    public function createPreference($items, $compra_id, $usuario) {
        $preference = new MercadoPago\Preference();
        
        // Configurar items
        $preference->items = $items;
        
        // URLs de retorno
        $preference->back_urls = array(
            "success" => "http://localhost:8080/Tienda-RJSN/app/controllers/PagoController.php?action=success&compra_id=" . $compra_id,
            "failure" => "http://localhost:8080/Tienda-RJSN/app/controllers/PagoController.php?action=failure&compra_id=" . $compra_id,
            "pending" => "http://localhost:8080/Tienda-RJSN/app/controllers/PagoController.php?action=pending&compra_id=" . $compra_id
        );
        
        $preference->auto_return = "approved";
        $preference->external_reference = $compra_id;
        
        try {
            $preference->save();
            return $preference;
        } catch (Exception $e) {
            error_log("Error MercadoPago: " . $e->getMessage());
            return false;
        }
    }
}
?>
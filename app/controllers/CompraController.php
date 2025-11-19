<?php
session_start();
require_once '../models/Compra.php';
require_once '../models/CompraItem.php';
require_once '../config/database.php';

class CompraController {
    private $compra;
    private $compraItem;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->compra = new Compra($this->db);
        $this->compraItem = new CompraItem($this->db);
    }

    public function crearCompra($items) {
        try {
            $this->db->beginTransaction();

            // Crear compra
            $this->compra->idusuario = $_SESSION['idusuario'];
            $idcompra = $this->compra->crear();

            // Crear items de compra
            foreach($items as $item) {
                $this->compraItem->idcompra = $idcompra;
                $this->compraItem->idproducto = $item['id'];
                $this->compraItem->cicantidad = $item['cantidad'];
                $this->compraItem->crear();
            }

            // Crear estado inicial de compra
            $this->compra->crearEstadoInicial($idcompra);

            $this->db->commit();
            return ['success' => true, 'idcompra' => $idcompra];
        } catch(Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}

// Procesar solicitud
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if($input['action'] == 'crear') {
        $compraController = new CompraController();
        $resultado = $compraController->crearCompra($input['items']);
        echo json_encode($resultado);
    }
}
?>
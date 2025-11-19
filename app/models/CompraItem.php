<?php
class CompraItem {
    private $conn;
    private $table_name = "compraitem";

    public $idcompraitem;
    public $idproducto;
    public $idcompra;
    public $cicantidad;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function agregarItem($compra_id, $producto_id, $cantidad, $precio) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (idcompra, idproducto, cicantidad) 
                  VALUES (:idcompra, :idproducto, :cicantidad)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idcompra", $compra_id);
        $stmt->bindParam(":idproducto", $producto_id);
        $stmt->bindParam(":cicantidad", $cantidad);
        
        return $stmt->execute();
    }

    public function obtenerPorCompra($compra_id) {
        $query = "SELECT ci.*, p.pronombre, p.proprecio 
                  FROM " . $this->table_name . " ci
                  INNER JOIN producto p ON ci.idproducto = p.idproducto
                  WHERE ci.idcompra = :idcompra";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idcompra", $compra_id);
        $stmt->execute();
        
        return $stmt;
    }
}
?>
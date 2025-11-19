<?php
class CompraEstado {
    private $conn;
    private $table_name = "compraestado";

    public $idcompraestado;
    public $idcompra;
    public $idcompraestadotipo;
    public $cefechaini;
    public $cefechafin;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearEstado($compra_id, $estado_tipo) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (idcompra, idcompraestadotipo, cefechaini) 
                  VALUES (:idcompra, :idcompraestadotipo, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idcompra", $compra_id);
        $stmt->bindParam(":idcompraestadotipo", $estado_tipo);
        
        return $stmt->execute();
    }

    public function actualizarEstado($compra_id, $nuevo_estado) {
        // Finalizar estado actual
        $query = "UPDATE " . $this->table_name . " 
                  SET cefechafin = NOW() 
                  WHERE idcompra = :idcompra AND cefechafin IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idcompra", $compra_id);
        $stmt->execute();

        // Crear nuevo estado
        return $this->crearEstado($compra_id, $nuevo_estado);
    }
}
?>
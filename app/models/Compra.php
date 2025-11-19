<?php
class Compra {
    private $conn;
    private $table_name = "compra";

    public $idcompra;
    public $cofecha;
    public $idusuario;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearCompra($usuario_id) {
        $query = "INSERT INTO " . $this->table_name . " (idusuario) VALUES (:idusuario)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idusuario", $usuario_id);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE idcompra = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
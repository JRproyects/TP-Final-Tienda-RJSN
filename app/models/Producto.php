<?php
class Producto {
    private $conn;
    private $table_name = "producto";

    public $idproducto;
    public $pronombre;
    public $prodetalle;
    public $procantstock;
    public $proprecio;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todos los productos
    public function leer() {
        $query = "SELECT idproducto, pronombre, prodetalle, procantstock, proprecio 
                  FROM " . $this->table_name . " 
                  ORDER BY idproducto";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Leer un solo producto
    public function leerUno() {
        $query = "SELECT idproducto, pronombre, prodetalle, procantstock, proprecio 
                  FROM " . $this->table_name . " 
                  WHERE idproducto = ? 
                  LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->idproducto);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->pronombre = $row['pronombre'];
            $this->prodetalle = $row['prodetalle'];
            $this->procantstock = $row['procantstock'];
            $this->proprecio = $row['proprecio'];
            return true;
        }
        return false;
    }
}
?>

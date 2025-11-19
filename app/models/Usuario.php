<?php
class Usuario {
    private $conn;
    private $table_name = "usuario";

    public $idusuario;
    public $usnombre;
    public $uspass;
    public $usmail;
    public $usdeshabilitado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = "SELECT idusuario, usnombre, usmail, uspass FROM " . $this->table_name . " 
                  WHERE usnombre = :usnombre AND usdeshabilitado IS NULL";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usnombre", $this->usnombre);
        $stmt->execute();

        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verificar contraseña encriptada
            if (password_verify($this->uspass, $row['uspass'])) {
                $this->idusuario = $row['idusuario'];
                $this->usnombre = $row['usnombre'];
                $this->usmail = $row['usmail'];
                return true;
            }
        }
        return false;
    }

    public function getRoles() {
        $query = "SELECT r.idrol, r.rodescripcion FROM usuariorol ur
                  INNER JOIN rol r ON ur.idrol = r.idrol
                  WHERE ur.idusuario = :idusuario";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idusuario", $this->idusuario);
        $stmt->execute();
        
        return $stmt;
    }
}
?>
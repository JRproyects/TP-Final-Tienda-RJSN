<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if($conn) {
        echo "✅ CONEXIÓN EXITOSA<br>";
        
        // Verificar si existen los usuarios
        $query = "SELECT * FROM usuario";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        echo "✅ Usuarios en la base de datos:<br>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- " . $row['usnombre'] . " (" . $row['usmail'] . ")<br>";
        }
    }
} catch(PDOException $exception) {
    echo "❌ ERROR: " . $exception->getMessage();
}
?>

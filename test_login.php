<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/config/database.php';
require_once 'app/models/Usuario.php';

echo "<h2>🔍 DESCUBRIR CONTRASEÑA REAL</h2>";

$database = new Database();
$db = $database->getConnection();

// Obtener el hash de la base de datos
$query = "SELECT uspass FROM usuario WHERE usnombre = 'admin_test'";
$stmt = $db->prepare($query);
$stmt->execute();
$hash = $stmt->fetchColumn();

echo "Hash en BD: " . $hash . "<br><br>";

// Probar contraseñas comunes
$common_passwords = ['password', 'admin', '123456', '1234', 'test', 'admin123', 'root', '12345678', 'qwerty', '12345'];

echo "<h3>Probando contraseñas comunes:</h3>";
foreach($common_passwords as $password) {
    $result = password_verify($password, $hash);
    echo "Contraseña: '<strong>$password</strong>' - " . ($result ? "✅ CORRECTA" : "❌ incorrecta") . "<br>";
}

// Probar si es texto plano
echo "<h3>¿Es texto plano?</h3>";
echo "¿Hash = '123456'? " . ($hash === '123456' ? "✅ SÍ" : "❌ NO") . "<br>";
echo "¿Hash = 'password'? " . ($hash === 'password' ? "✅ SÍ" : "❌ NO") . "<br>";
echo "¿Hash = 'admin'? " . ($hash === 'admin' ? "✅ SÍ" : "❌ NO") . "<br>";
?>
<?php
session_start();
echo "<h2>🧪 Test del Sistema</h2>";

echo "<h3>URLs para probar:</h3>";
echo '<a href="/Tienda-RJSN/public/index.php" target="_blank">1. Página Principal</a><br>';
echo '<a href="/Tienda-RJSN/dashboard.php" target="_blank">2. Dashboard (requiere login)</a><br>';

echo "<h3>Estado de Sesión:</h3>";
if (isset($_SESSION['idusuario'])) {
    echo "✅ SESIÓN ACTIVA<br>";
    echo "Usuario: " . $_SESSION['usnombre'] . "<br>";
    echo "Roles: ";
    if (isset($_SESSION['roles'])) {
        foreach($_SESSION['roles'] as $rol) {
            echo $rol . " ";
        }
    }
} else {
    echo "❌ NO HAY SESIÓN ACTIVA";
}
?>
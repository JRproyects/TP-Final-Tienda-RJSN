<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../models/Usuario.php';
require_once '../config/database.php';

// LOGOUT - Desde GET
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: /Tienda-RJSN/public/index.php");
    exit;
}

// LOGIN - Desde POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'login') {
    
    if (isset($_POST['usnombre']) && isset($_POST['uspass'])) {
        $database = new Database();
        $db = $database->getConnection();
        $usuario = new Usuario($db);
        
        $usuario->usnombre = trim($_POST['usnombre']);
        $usuario->uspass = trim($_POST['uspass']);
        
        if ($usuario->login()) {
            // Login exitoso
            $_SESSION['idusuario'] = $usuario->idusuario;
            $_SESSION['usnombre'] = $usuario->usnombre;
            $_SESSION['usmail'] = $usuario->usmail;
            
            // Obtener roles
            $roles = $usuario->getRoles();
            $_SESSION['roles'] = [];
            while ($row = $roles->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['roles'][$row['idrol']] = $row['rodescripcion'];
            }
            
            // Redirigir al dashboard en la raíz
            header("Location: /Tienda-RJSN/dashboard.php");
            exit;
        } else {
            // Login fallido
            $_SESSION['login_error'] = "Usuario o contraseña incorrectos";
            header("Location: /Tienda-RJSN/public/index.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Por favor complete todos los campos";
        header("Location: /Tienda-RJSN/public/index.php");
        exit;
    }
}

// Si se accede directamente sin acción, redirigir al index
header("Location: /Tienda-RJSN/public/index.php");
exit;
?>
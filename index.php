<?php

require_once("controller/routesController.php");
require_once("controller/userController.php");
require_once("controller/loginController.php");
require_once("model/userModel.php");

// Establecer encabezados para permitir CORS
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Headers: Authorization');

// Obtener la ruta solicitada
$rutasArray = explode("/", $_SERVER['REQUEST_URI']);
$endPoint = (array_filter($rutasArray)[2]);

// Verificar la autenticación si la ruta no es 'login'
if ($endPoint != 'login') {
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        $ok = false;
        $identifier = $_SERVER['PHP_AUTH_USER'];
        $key = $_SERVER['PHP_AUTH_PW'];
        
        // Obtener las credenciales de usuarios autenticados
        $users = UserModel::getUseAuth();

        foreach ($users as $u) {
            if ($identifier . ":" . $key == $u["user_identifier"] . ":" . $u["user_key"]) {
                $ok = true;
            }
        }

        if ($ok) {
            // Acceso permitido, manejar las rutas
            $routes = new RoutesController();
            $routes->index();
        } else {
            // Acceso denegado
            $result["mensaje"] = "USTED NO TIENE ACCESO";
            echo json_encode($result, true);
            return false;
        }
    } else {
        // Credenciales de autenticación no proporcionadas
        $result["mensaje"] = "ERROR EN CREDENCIALES 1";
        echo json_encode($result, true);
        return false;
    }
} else {
    // La ruta es 'login', manejar las rutas
    $routes = new RoutesController();
    $routes->index();
}

?>

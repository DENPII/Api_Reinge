<?php

// Importar los controladores y modelos necesarios
require_once("controller/routesController.php");
require_once("controller/userController.php");
require_once("controller/loginController.php");
require_once("model/userModel.php");

// Configuración de CORS
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Headers: Authorization');

// Obtener la ruta del URI
$rutasArray = explode("/", $_SERVER['REQUEST_URI']);
$endPoint = (array_filter($rutasArray)[2]);

// Verificar si el endpoint no es 'login'
if ($endPoint != 'login') {
    // Verificar si las credenciales están proporcionadas
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        $ok = false;
        $identifier = $_SERVER['PHP_AUTH_USER'];
        $key = $_SERVER['PHP_AUTH_PW'];

        // Obtener usuarios autenticados
        $users = UserModel::getUseAuth();

        // Verificar las credenciales del usuario
        foreach ($users as $u) {
            if ($identifier . ":" . $key == $u["user_identifier"] . ":" . $u["user_key"]) {
                $ok = true;
            }
        }

        // Verificar si las credenciales son correctas
        if ($ok) {
            $routes = new RoutesController();
            $routes->index();
        } else {
            $result["mensaje"] = "USTED NO TIENE ACCESO";
            echo json_encode($result, true);
            return false;
        }
    } else {
        // Credenciales no proporcionadas correctamente
        $result["mensaje"] = "ERROR EN CREDENCIALES 1";
        echo json_encode($result, true);
        return false;
    }
} else {
    // Procesar el endpoint 'login'
    $routes = new RoutesController();
    $routes->index();
}
?>

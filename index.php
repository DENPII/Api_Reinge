<?php
require_once ("controller/routesController.php");
require_once ("controller/userController.php");
require_once ("controller/loginController.php");
require_once ("model/userModel.php");
//Instancia
/*$my_object = new Connection();
$my_object->hola;
//Acceder a atributos estaticos
var_export($my_object);
$query = "SELECT * FROM users";
$conectar = Connection::connecction()->prepare($query);
var_dump($conectar);*/
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Headers: Authorization');
$rutasArray = explode("/", $_SERVER['REQUEST_URI']);
$endPoint = (array_filter($rutasArray)[2]);
if($endPoint != 'login'){
    if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
        $ok = false;
        $identifier = $_SERVER['PHP_AUTH_USER'];
        $key = $_SERVER['PHP_AUTH_PW'];
        $users = UserModel::getUseAuth();
        foreach($users as $u){
            if($identifier.":".$key == $u["user_identifier"].":".$u["user_key"]){
                $ok = true;
            }
        }
        if($ok){
            $routes = new RoutesController();
            $routes->index();
        }else{
            $result["mensaje"] = "USTED NO TIENE ACCESO";
            echo json_encode($result, true);
            return false;
        }
    }else{
        $result["mensaje"] = "ERROR EN CREDENCIALES 1";
        echo json_encode($result, true);
        return false;
    }
}else{
    $routes = new RoutesController();
    $routes->index();
}
?>
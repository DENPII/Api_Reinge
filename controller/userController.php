<?php

class UserController {
    private $_method;
    private $_complement;
    private $_data;

    // Constructor que inicializa las propiedades del controlador
    function __construct($method, $complement, $data) {
        $this->_method = $method;
        $this->_complement = $complement == null ? 0 : $complement;
        $this->_data = $data != 0 ? $data : "";
    }

    // Método principal que gestiona las solicitudes según el método HTTP
    public function index() {
        switch ($this->_method) {
            case "GET":
                // Manejar solicitudes GET
                $this->handleGET();
                break;
            case 'POST':
                // Manejar solicitudes POST
                $this->handlePOST();
                break;
            case "UPDATE":
                // Manejar solicitudes UPDATE
                $this->handleUPDATE();
                break;
            case "DELETE":
                // Manejar solicitudes DELETE
                $this->handleDELETE();
                break;
            default:
                // Manejar solicitudes no encontradas
                $this->handleNotFound();
                break;
        }
    }

    // Método para manejar solicitudes GET
    private function handleGET() {
        if ($this->_complement == 0) {
            // Obtener todos los usuarios
            $user = UserModel::getUsers(0);
        } else {
            // Obtener un usuario específico
            $user = UserModel::getUsers($this->_complement);
        }

        // Enviar la respuesta en formato JSON
        echo json_encode($user, true);
    }

    // Método para manejar solicitudes POST
    private function handlePOST() {
        // Crear un nuevo usuario con salting
        $createUser = UserModel::createUser($this->generateSalting());

        // Enviar la respuesta en formato JSON
        $json = array("response" => $createUser);
        echo json_encode($json, true);
    }

    // Método para manejar solicitudes UPDATE
    private function handleUPDATE() {
        // Enviar una respuesta de actualización de usuario en formato JSON
        $json = array("response" => "update de user");
        echo json_encode($json, true);
    }

    // Método para manejar solicitudes DELETE
    private function handleDELETE() {
        // Enviar una respuesta de eliminación de usuario en formato JSON
        $json = array("response" => "delete de user");
        echo json_encode($json, true);
    }

    // Método para manejar solicitudes no encontradas
    private function handleNotFound() {
        // Enviar una respuesta de no encontrado en formato JSON
        $json = array("response" => "not found");
        echo json_encode($json, true);
    }

    // Método privado para generar el salting de los datos
    private function generateSalting() {
        $trimmedData = "";

        // Verificar si los datos no están vacíos
        if (($this->_data != "") || (!empty($this->_data))) {
            // Eliminar espacios en blanco de los datos
            $trimmedData = array_map('trim', $this->_data);

            // Aplicar MD5 al campo de contraseña
            $trimmedData['user_pss'] = md5($trimmedData['user_pss']);

            // Generar el salting para credenciales
            $identifier = str_replace("$", "ue3", crypt($trimmedData["user_mail"], 'u56'));
            $key = str_replace("$", "ue2023", crypt($trimmedData["user_mail"], '65ue'));

            // Actualizar las propiedades con salting
            $trimmedData['user_identifier'] = $identifier;
            $trimmedData['us_key'] = $key;
            return $trimmedData;
        }
    }
}
?>

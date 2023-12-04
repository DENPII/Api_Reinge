<?php

class UserController {
    private $_method;
    private $_complement;
    private $_data;

    // Constructor que inicializa las propiedades
    function __construct($method, $complement, $data) {
        $this->_method = $method;
        $this->_complement = $complement == null ? 0 : $complement;
        $this->_data = $data != 0 ? $data : "";
    }

    // Método principal que maneja las solicitudes según el método HTTP
    public function index() {
        switch ($this->_method) {
            case "GET":
                // Obtener usuarios según el complemento
                $user = $this->_complement == 0 ? UserModel::getUsers(0) : UserModel::getUsers($this->_complement);
                $json = $user;
                echo json_encode($json, true);
                return;

            case 'POST':
                // Crear un nuevo usuario con generación de salting
                $createUser = UserModel::createUser($this->generateSalting());
                $json = array(
                    "response: " => $createUser
                );
                echo json_encode($json, true);
                return;

            case "UPDATE":
                // Respuesta para la solicitud de actualización de usuario
                $json = array(
                    "response: " => "update de user"
                );
                echo json_encode($json, true);
                return;

            case "DELETE":
                // Respuesta para la solicitud de eliminación de usuario
                $json = array(
                    "response: " => "delete de user"
                );
                echo json_encode($json, true);
                return;

            default:
                // Respuesta para cualquier otro método no reconocido
                $json = array(
                    "response: " => "not found"
                );
                echo json_encode($json, true);
                return;
        }
    }

    // Método privado para generar salting de contraseñas
    private function generateSalting() {
        $trimmedData = "";

        // Verificar si hay datos y no están vacíos
        if (($this->_data != "") || (!empty($this->_data))) {
            $trimmedData = array_map('trim', $this->_data);

            // Hashear la contraseña con MD5
            $trimmedData['user_pss'] = md5($trimmedData['user_pss']);

            // Generar salting para credenciales
            $identifier = str_replace("$", "ue3", crypt($trimmedData["user_mail"], 'u56'));
            $key = str_replace("$", "ue2023", crypt($trimmedData["user_mail"], '65ue'));

            // Agregar salting a los datos del usuario
            $trimmedData['user_identifier'] = $identifier;
            $trimmedData['us_key'] = $key;

            return $trimmedData;
        }
    }
}

?>

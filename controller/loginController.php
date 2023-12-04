<?php

class LoginController {
    private $_method;
    private $_data;

    // Constructor que inicializa las propiedades
    function __construct($method, $data) {
        $this->_method = $method;
        $this->_data = $data;
    }

    // Método principal que maneja las solicitudes de login
    public function index() {
        switch ($this->_method) {
            case 'POST':
                // Llamar al método de login en UserModel
                $credentials = UserModel::login($this->_data);

                // Crear un arreglo para la respuesta
                $result = [];

                // Verificar si se obtuvieron credenciales
                if (!empty($credentials)) {
                    $result["credenciales"] = $credentials;
                    $result["mensaje"] = "OK";
                } else {
                    // Manejar el caso de credenciales no válidas
                    $result["credenciales"] = null;
                    $result["mensaje"] = "ERROR EN CREDENCIALES 3";
                    $header = "HTTP/1.1 400 FAIL";
                }

                // Enviar la respuesta en formato JSON
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
                return;

            default:
                // Responder en caso de que el método no sea reconocido
                $json = array(
                    "ruta: " => "not found"
                );
                echo json_encode($json, true);
                return;
        }
    }
}

?>

<?php

require_once("ConDB.php");

class UserModel {

    // Método para crear un nuevo usuario
    static public function createUser($data) {
        // Verificar si el correo ya está registrado
        $cantMail = self::getMail($data['user_mail']);

        if ($cantMail == 0) {
            // Query para insertar un nuevo usuario
            $query = "INSERT INTO users(user_id, user_mail, user_pss, user_dateCreate, user_identifier, user_key, user_status) VALUES (NULL, :user_mail, :user_pss, :user_dateCreate, :user_identifier, :us_key, :user_status)";
            
            // Estado predeterminado al crear un nuevo usuario
            $status = "0"; // 0 inactivo, 1 activo

            // Preparar la consulta
            $stament = Connection::connecction()->prepare($query);

            // Vincular parámetros
            $stament->bindParam(":user_mail", $data['user_mail'], PDO::PARAM_STR);
            $stament->bindParam(":user_pss", $data['user_pss'], PDO::PARAM_STR);
            $stament->bindParam(":user_dateCreate", $data['user_dateCreate'], PDO::PARAM_STR);
            $stament->bindParam(":user_identifier", $data['user_identifier'], PDO::PARAM_STR);
            $stament->bindParam(":us_key", $data['us_key'], PDO::PARAM_STR);
            $stament->bindParam(":user_status", $status, PDO::PARAM_STR);

            // Ejecutar la consulta y verificar el resultado
            $message = $stament->execute() ? "OK" : Connection::connecction()->errorInfo();

            // Cerrar el cursor y liberar recursos
            $stament->closeCursor();
            $stament = null;
            $query = "";
        } else {
            $message = "Usuario ya está registrado";
        }

        return $message;
    }

    // Método privado para obtener el número de registros de un correo en la base de datos
    static private function getMail($mail) {
        $query = "SELECT user_mail FROM users WHERE user_mail = '$mail'";
        
        // Preparar la consulta
        $stament = Connection::connecction()->prepare($query);

        // Ejecutar la consulta
        $stament->execute();

        // Obtener el número de filas afectadas
        $result = $stament->rowCount();

        return $result;
    }

    // Método para obtener la información de los usuarios
    static function getUsers($id) {
        $query = "SELECT user_id, user_mail, user_dateCreate FROM users";
        $id = is_numeric($id) ? $id : 0;
        
        // Componer la consulta según si se especifica un ID
        $query .= ($id > 0) ? " WHERE users.user_id = '$id' AND " : "";
        $query .= ($id > 0) ? " user_status = '1';" : " WHERE user_status = '1';";

        // Preparar la consulta
        $stament = Connection::connecction()->prepare($query);

        // Ejecutar la consulta
        $stament->execute();

        // Obtener los resultados
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    // Método para realizar la autenticación de usuarios
    static public function login($data) {
        $query = "";
        $user = $data['user_mail'];
        $pss = md5($data['user_pss']);

        if (!empty($user) && !empty($pss)) {
            // Consulta para obtener información del usuario en función de las credenciales
            $query = "SELECT user_id, user_identifier, user_key FROM users WHERE user_mail = '$user' AND user_pss = '$pss' AND user_status = '1'";
            
            // Preparar la consulta
            $stament = Connection::connecction()->prepare($query);

            // Ejecutar la consulta
            $result = $stament->execute();

            // Obtener los resultados
            $result = $stament->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } else {
            // Mensaje de error si las credenciales están vacías
            $mensaje = array(
                "COD" => "001",
                "MENSAJE" => ("ERROR EN CREDENCIALES 2")
            );

            return $mensaje;
        }
    }

    // Método para obtener las credenciales de usuarios activos
    static public function getUseAuth() {
        $query = "SELECT user_identifier, user_key FROM users WHERE user_status = '1'";
        
        // Preparar la consulta
        $stament = Connection::connecction()->prepare($query);

        // Ejecutar la consulta
        $stament->execute();

        // Obtener los resultados
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}

?>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

// se trae el archivo de la conexion a la base de datos
    include './../conexion/conexion_db.php';

// se decodifica el archivo JSON y se gurda en $datos
    $datos = json_decode(file_get_contents('php://input'), true);

    $documento = $datos['documento'];
    $clave = $datos['clave_usuario'];

    //Sentencia para saber si ya hay usuarios con el mismo documentov y que el estado sea activo 
    $sentenciaSql = "SELECT * FROM usuario_tbl WHERE usuario_tbl.num_documento = '$documento' and usuario_tbl.estado = 1";

    $resultado = $conexion->query($sentenciaSql);

    if ($resultado->num_rows > 0) {
    
        $consulta = $resultado-> fetch_assoc();
        $claveEncriptada = $consulta['clave_usuario'];

        if(password_verify($clave,$claveEncriptada)){
            $response['datos_usuario'] = [
                "numero_documento" => $consulta['num_documento'],
                "nombre_usuario" => $consulta['nombre_usuario'],
                "rol" => $consulta['fk_rol']
            ];
            $response['mensaje'] = "Sesión iniciada con exito";
            $response['estado'] = 200;
        }

        else{
            $response['mensaje'] = "Contraseña incorrecta";
            $response['estado'] = 500;
        }
    } else {
        $response['mensaje'] = "El usuario no existe o se encuentra inactivo";
        $response['estado'] = 500;
    }
    $conexion->close();
    
    echo json_encode($response);

} else {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}
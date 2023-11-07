<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: POST');

// se trae el archivo de la conexion a la base de datos
    include './../conexion/conexion_db.php';

// se decodifica el archivo JSON y se guarda en $datos
    $datos = json_decode(file_get_contents('php://input'), true);

    $documento = $datos['documento'];
    $estado = $datos['estado'];


    //Sentencia para editar un Usuario
    $sentenciaSql = "UPDATE `usuario_tbl` 
	                    SET 
                            `estado`='$estado'
                        WHERE `num_documento`='$documento'";

    $insert = $conexion->query($sentenciaSql);
    if ($insert === true) {
        $response['estado'] = 200;
        $response['mensaje'] = "Estado del usuario cambiado con exito";
    } else {
        $response['estado'] = 500;
        $response['mensaje'] = "Error al cambiar el estado del usuario" . $conexion->error;
    }

    $conexion->close();

    echo json_encode($response);

} else {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

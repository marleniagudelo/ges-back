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

    $nit = $datos['nit'];
    $razon_social = $datos['razon_social'];
    $representante = $datos['representante'];
    $direccion = $datos['direccion'];
    $telefono = $datos['telefono'];
    $correo = $datos['correo'];


    //Sentencia para editar un cliente
    $sentenciaSql = "UPDATE cliente_tbl SET 
                       razon_social='$razon_social',
                       representante_legal='$representante',
                       correo='$correo',
                       telefono='$telefono',
                       direccion='$direccion' 
                   WHERE nit = '$nit'";

    $insert = $conexion->query($sentenciaSql);
    if ($insert === true) {
        $response['mensaje'] = "Cliente editado con exito";
        $response['estado'] = 200;
    } else {
        $response['mensaje'] = "Error al editar el cliente" . $conexion->error;
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

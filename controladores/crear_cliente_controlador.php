<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

// se trae el archivo de la conexion a la base de datos
    include './../conexion/conexion_db.php';

// se decodifica el archivo JSON y se guarda en $datos
    $datos = json_decode(file_get_contents('php://input'), true);

    $razon_social = $datos['razon_social'];
    $nit = $datos['nit'];
    $representante = $datos['representante'];
    $direccion = $datos['direccion'];
    $telefono = $datos['telefono'];
    $correo= $datos['correo'];

    //Sentencia para saber si ya hay cliente con el mismo nit
    $sentenciaSql = "SELECT * FROM cliente_tbl WHERE cliente_tbl.nit = '$nit'";

    $resultado = $conexion->query($sentenciaSql);

    if ($resultado->num_rows > 0) {
        $response['mensaje'] = "Ya existe un cliente con ese nit";
        $response['estado'] = 500;
    } else {
        $sentenciaSql = "";

        //Sentencia para guardar un nuevo usuario
        $sentenciaSql = "INSERT INTO cliente_tbl VALUES ('$nit','$razon_social','$representante','$correo','$telefono','$direccion')";

        $insert = $conexion->query($sentenciaSql);
        if ($insert === true) {
            $response['mensaje'] = "Cliente creado con exito";
            $response['estado'] = 200;
        } else {
            $response['mensaje'] = "Error al crear el cliente" . $conexion->error;
            $response['estado'] = 500;
        }
    }
    $conexion->close();
    
    echo json_encode($response);

} else {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}

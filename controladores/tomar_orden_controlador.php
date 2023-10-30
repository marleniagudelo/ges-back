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

    $idOrden = $datos['id_orden'];
    date_default_timezone_set('America/Bogota');
    $fechaActual = date("Y-m-d");

    //Sentencia para editar una orden
    $sentenciaSql = "UPDATE orden_servicio_tbl 
                     SET 
                         fecha_Inicio='$fechaActual',
                         fk_id_estado=2
                     WHERE id_orden = '$idOrden'";

    $insert = $conexion->query($sentenciaSql);
    if ($insert === true) {
        $response['mensaje'] = "Orden tomada con exito";
        $response['estado'] = 200;
    } else {
        $response['mensaje'] = "Error al tomar la orden" . $conexion->error;
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

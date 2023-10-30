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

    $idCliente = $datos['fk_id_cliente'];
    $descripcionNovedad = $datos['descripcion_novedad'];
    $idTecnico = $datos['fk_id_usuario'];
    date_default_timezone_set('America/Bogota');
    $fecha_actual = date("Y-m-d H:i:s");


    //Sentencia para guardar un nueva orden
    $sentenciaSql = "
    INSERT INTO orden_servicio_tbl
        (`id_orden`,
        `descripcion_novedad`,
        `fecha_creacion`,
        `fecha_Inicio`,
        `fecha_final`,
        `observaciones_finales`,
        `fk_id_cliente`,
         `fk_id_usuario`,
         `fk_id_estado`)
    VALUES (null,
            '$descripcionNovedad',
            '$fecha_actual',
            '',
            '',
            '',
            '$idCliente',
            '$idTecnico',
            '1')";

    $insert = $conexion->query($sentenciaSql);
    if ($insert === true) {
        $response['mensaje'] = "Orden creada con exito";
        $response['estado'] = 200;
    } else {
        $response['mensaje'] = "Error al crear la orden" . $conexion->error;
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

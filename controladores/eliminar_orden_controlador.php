<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: POST');

// se trae el archivo de la conexion a la base de datos
    include './../conexion/conexion_db.php';

    $datos = json_decode(file_get_contents("php://input"), true);

//Datos recibido en json
    $idOrden = $datos['id_orden'];


    $sentenciaSql = "SELECT * FROM orden_servicio_tbl WHERE id_orden = '$idOrden'";

    $resultado = $conexion->query($sentenciaSql);

    $respuesta = array();

    if ($resultado->num_rows > 0) {

        $sql = "DELETE  FROM orden_servicio_tbl WHERE id_orden = '$idOrden'";

        if ($conexion->query($sql) === true) {
            $response['estado'] = 200;
            $response['mensaje'] = "Orden cancelada correctamente!";
        } else {
            $response['estado'] = 500;
            $response['mensaje'] = "Error al cancelar la orden: " . $conexion->error;;
        }

    } else {
        $response['estado'] = 500;
        $response['mensaje'] = "No hay ordenes para cancelar!";
    }


// Cerrar la conexión
    $conexion->close();

    echo json_encode($response);
} else {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}
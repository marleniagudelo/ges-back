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
    date_default_timezone_set('America/Bogota');
    $fechaFinal = date("Y-m-d H:i:s");
    $observacionFinal = $datos['observacion_final'];


    $sentenciaSql = "SELECT * FROM orden_servicio_tbl WHERE id_orden = '$idOrden'";

    $resultado = $conexion->query($sentenciaSql);

    $respuesta = array();

    if ($resultado->num_rows > 0) {

        $sql = "UPDATE orden_servicio_tbl
                    SET orden_servicio_tbl.fecha_final = '$fechaFinal',
                        orden_servicio_tbl.fk_id_estado = 3,
                        orden_servicio_tbl.observaciones_finales = '$observacionFinal'
                    WHERE orden_servicio_tbl.id_orden = '$idOrden'";

        if ($conexion->query($sql) === true) {
            $response['estado'] = 200;
            $response['mensaje'] = "Orden finalizada correctamente!";
        } else {
            $response['estado'] = 500;
            $response['mensaje'] = "Error al finalizar la orden: " . $conexion->error;;
        }

    } else {
        $response['estado'] = 500;
        $response['mensaje'] = "No hay ordenes para finalizar!";
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
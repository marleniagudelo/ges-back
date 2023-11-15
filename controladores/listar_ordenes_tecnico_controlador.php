<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

    // se trae el archivo de la conexion a la base de datos
    include './../conexion/conexion_db.php';

    $datos = json_decode(file_get_contents('php://input'), true);

    $documento = $datos['num_documento'];
    $estadoOrden = $datos['estado_orden'];

    //sentencia para listar los clientes
    $sentenciaSql = "
        SELECT 
            orden_servicio_tbl.id_orden,
            orden_servicio_tbl.descripcion_novedad,
            orden_servicio_tbl.fecha_creacion,
            orden_servicio_tbl.fecha_Inicio,
            orden_servicio_tbl.fecha_final,
            orden_servicio_tbl.fecha_creacion,
            orden_servicio_tbl.observaciones_finales,
            cliente_tbl.nit,
            cliente_tbl.razon_social,
            usuario_tbl.num_documento,
            usuario_tbl.nombre_usuario,
            estado_tbl.id_estado,
            estado_tbl.nombre_estado
        FROM orden_servicio_tbl
        INNER JOIN cliente_tbl ON orden_servicio_tbl.fk_id_cliente = cliente_tbl.nit 
        INNER JOIN estado_tbl ON orden_servicio_tbl.fk_id_estado = estado_tbl.id_estado
        INNER JOIN usuario_tbl ON orden_servicio_tbl.fk_id_usuario = usuario_tbl.num_documento
        WHERE orden_servicio_tbl.fk_id_usuario = '$documento' AND estado_tbl.id_estado = '$estadoOrden'";

    $response = array();

    $resultado = $conexion->query($sentenciaSql);

    if ($resultado->num_rows > 0) {

        $response ['estado'] = 200;
        $response ['mensaje'] = "";
        $response ['datos'] = array();

        while ($consulta = $resultado->fetch_assoc()) {

            $response ['datos'][] = $consulta;
        }
    } else {
        $response ['mensaje'] = "La tabla esta vacia";

    }

    $conexion->close();

    echo json_encode($response);

} else {

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}
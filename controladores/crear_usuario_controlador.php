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

    $documento = $datos['documento'];
    $nombreUsuario = $datos['nombre_usuario'];
    $clave = $datos['clave_usuario'];
    $fkRol = $datos['fk_rol'];

    //Sentencia para saber si ya hay usuarios con el mismo documento
    $sentenciaSql = "SELECT * FROM usuario_tbl WHERE usuario_tbl.num_documento = '$documento'";

    $resultado = $conexion->query($sentenciaSql);

    if ($resultado->num_rows > 0) {
        $response['mensaje'] = "Ya existe un usuario con ese documento";
        $response['estado'] = 500;
    } else {
        $sentenciaSql = "";

        $claveEncriptada = password_hash($clave,PASSWORD_BCRYPT);

        //Sentencia para guardar un nuevo usuario
        $sentenciaSql = "INSERT INTO usuario_tbl(`num_documento`, `nombre_usuario`, `clave_usuario`, `fk_rol`, `estado`) 
        VALUES ('$documento','$nombreUsuario','$claveEncriptada','$fkRol',1)";

        $insert = $conexion->query($sentenciaSql);
        if ($insert === true) {
            $response['mensaje'] = "Usuario creado con exito";
            $response['estado'] = 200;
        } else {
            $response['mensaje'] = "Error al crear el usuario" . $conexion->error;
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

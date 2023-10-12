<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: GET');
    header('Access-Control-Allow-Headers: Content-Type');

 // se trae el archivo de la conexion a la base de datos
    include './../conexion/conexion_db.php';

    //sentencia para listar los roles
    $sentenciaSql = "SELECT * FROM rol_tbl";
    
    $response = array();

    $resultado = $conexion->query($sentenciaSql);
    
if($resultado -> num_rows > 0){

    $response ['estado'] = 200;
    $response ['mensaje'] = "";
    $response ['datos'] = array();

 while ($consulta = $resultado -> fetch_assoc()){

       $response ['datos'][]= $consulta;
 }
}else{
    $response ['mensaje'] = "La tabla esta vacia";

 }
   
    $conexion->close();
    
    echo json_encode($response);

}
else{

   header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit;
}
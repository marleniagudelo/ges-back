<?php

// Aqui me conecto a la base de datos
$servidor = 'localhost';
$usuario = 'root';
$clave = '';
$bd = 'ges_db';

$conexion = new mysqli($servidor, $usuario, $clave, $bd);

//Verifico si hay un error
if($conexion ->connect_error){
    echo 'error en la conexion';
}
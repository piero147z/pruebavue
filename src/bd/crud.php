<?php
require_once 'conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$_POST = json_decode(file_get_contents("php://input"),true);

$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
$autor = (isset($_POST['autor'])) ? $_POST['autor'] : '';
$precio = (isset($_POST['precio'])) ? $_POST['precio'] : '';

    switch($opcion) {
        case 1:
            
            $consulta = "INSERT INTO libros (nombre, autor, precio) VALUES ('$nombre', '$autor',
            '$precio')";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
        break;
        case 2:

            $consulta = "UPDATE libros SET nombre='$nombre', autor='$autor', precio='$precio' WHERE
            id='$id'";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
        case 3:

            $consulta = "DELETE FROM libros WHERE id='$id' ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            
        break;
        case 4:

            $consulta = "SELECT id, nombre, autor, precio FROM libros ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $data=$resultado->fetchAll(PDO::FETCH_ASSOC);

        break;
    }

print json_encode($data, JSON_UNESCAPED_UNICODE);
$conexion = NULL;

?>
<?php

require_once "../../config/conexion.php";


$id = $_POST["id"];

$familia = trim($_POST["familia"]);
$descripcion = trim($_POST["descripcion"]);
$unidad = trim($_POST["unidad"]);

$precio_compra = $_POST["precio_compra"];
$precio_venta = $_POST["precio_venta"];

$estado = $_POST["estado"];



// Validación básica

if($familia == "" || $descripcion == "" || $unidad == ""){

    die("Complete todos los campos obligatorios");

}



$sql = "UPDATE articulos SET

familia = ?,
descripcion = ?,
unidad = ?,
precio_compra = ?,
precio_venta = ?,
estado = ?

WHERE id = ?";



$stmt = $conexion->prepare($sql);


$stmt->bind_param(
"sssddii",
$familia,
$descripcion,
$unidad,
$precio_compra,
$precio_venta,
$estado,
$id
);



if($stmt->execute()){


    header("Location: index.php");

    exit;


}else{


    echo "Error al actualizar: ".$conexion->error;


}

?>
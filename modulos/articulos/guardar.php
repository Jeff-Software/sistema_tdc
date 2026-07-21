<?php

require_once "../../config/conexion.php";


$familia = trim($_POST["familia"]);
$descripcion = trim($_POST["descripcion"]);
$unidad = trim($_POST["unidad"]);

$precio_compra = $_POST["precio_compra"];
$precio_venta = $_POST["precio_venta"];

$estado = $_POST["estado"];



// Validar campos obligatorios

if(
    $familia == "" ||
    $descripcion == "" ||
    $unidad == ""
){

    die("Complete todos los campos obligatorios");

}



// Insertar artículo

$sql = "INSERT INTO articulos
(
familia,
descripcion,
unidad,
precio_compra,
precio_venta,
estado
)

VALUES
(?,?,?,?,?,?)";



$stmt = $conexion->prepare($sql);



$stmt->bind_param(
"sssddi",
$familia,
$descripcion,
$unidad,
$precio_compra,
$precio_venta,
$estado
);



if($stmt->execute()){


    header("Location: index.php");

    exit;


}else{


    echo "Error al guardar: ".$conexion->error;


}


?>
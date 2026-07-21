<?php

require_once "../../config/conexion.php";
require_once "../../includes/funciones.php";


$id = intval($_POST["id"]);


$ruc = trim($_POST["ruc"]);

$razon_social = trim($_POST["razon_social"]);

$direccion = trim($_POST["direccion"]);

$contacto = trim($_POST["contacto"]);

$telefono = limpiarTelefono(
    $_POST["telefono"] ?? ""
);

$correo = trim($_POST["correo"]);



$sql = "

UPDATE clientes

SET

ruc = ?,

razon_social = ?,

direccion = ?,

contacto = ?,

telefono = ?,

correo = ?

WHERE id = ?

";



$stmt = $conexion->prepare($sql);



$stmt->bind_param(

"ssssssi",

$ruc,
$razon_social,
$direccion,
$contacto,
$telefono,
$correo,
$id

);



if($stmt->execute()){


header("Location:index.php");

exit;


}else{


echo "Error al actualizar cliente";


}


?>
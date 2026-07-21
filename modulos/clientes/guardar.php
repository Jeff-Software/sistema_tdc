<?php

require_once "../../config/conexion.php";
require_once "../../includes/funciones.php";


// =========================================
// RECIBIR DATOS
// =========================================

$ruc = trim($_POST["ruc"] ?? "");

$razon_social = trim($_POST["razon_social"] ?? "");

$direccion = trim($_POST["direccion"] ?? "");

$contacto = trim($_POST["contacto"] ?? "");

$telefono = limpiarTelefono(
    $_POST["telefono"] ?? ""
);

$correo = trim($_POST["correo"] ?? "");


// =========================================
// VALIDAR CAMPOS IMPORTANTES
// =========================================

if($ruc == "" || $razon_social == ""){

    die("RUC y Razón Social son obligatorios");

}


// =========================================
// INSERTAR CLIENTE
// =========================================

$sql = "

INSERT INTO clientes
(
ruc,
razon_social,
direccion,
contacto,
telefono,
correo,
estado
)

VALUES
(
?,
?,
?,
?,
?,
?,
1
)

";


$stmt = $conexion->prepare($sql);


$stmt->bind_param(

"ssssss",

$ruc,
$razon_social,
$direccion,
$contacto,
$telefono,
$correo

);



if($stmt->execute()){


    header("Location: index.php");

    exit;


}else{


    echo "Error al guardar cliente: ".$conexion->error;


}

?>
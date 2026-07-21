<?php

require_once "../../config/conexion.php";


$ruc = $_POST["ruc"];
$razon = $_POST["razon_social"];
$direccion = $_POST["direccion"];
$contacto = $_POST["contacto"];
$telefono = $_POST["telefono"];
$correo = $_POST["correo"];


$sql = "

INSERT INTO clientes
(
ruc,
razon_social,
direccion,
contacto,
telefono,
correo,
estado,
fecha_creacion
)

VALUES
(
?,
?,
?,
?,
?,
?,
1,
NOW()
)

";


$stmt = $conexion->prepare($sql);


$stmt->bind_param(
"ssssss",
$ruc,
$razon,
$direccion,
$contacto,
$telefono,
$correo
);



if($stmt->execute()){


echo json_encode([

"estado"=>true,

"id"=>$conexion->insert_id,

"razon_social"=>$razon

]);


}else{


echo json_encode([

"estado"=>false,

"mensaje"=>$conexion->error

]);


}

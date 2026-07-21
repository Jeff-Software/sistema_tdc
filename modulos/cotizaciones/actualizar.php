<?php

require_once "../../config/conexion.php";


if($_SERVER["REQUEST_METHOD"] != "POST"){

    header("Location:index.php");
    exit;

}



$id = (int)$_POST["id"];

// =====================================
// VALIDAR ESTADO DE COTIZACIÓN
// =====================================

$sqlEstado = "

SELECT estado

FROM cotizaciones

WHERE id = ?

";


$stmtEstado = $conexion->prepare($sqlEstado);

$stmtEstado->bind_param(
    "i",
    $id
);

$stmtEstado->execute();


$resultadoEstado = $stmtEstado->get_result();

$cotizacionEstado = $resultadoEstado->fetch_assoc();



if(
    $cotizacionEstado["estado"] != "BORRADOR"
    &&
    $cotizacionEstado["estado"] != "RECHAZADA"
){

    echo "
    <script>
    alert('La cotización no permite edición.');
    window.location='ver.php?id=$id';
    </script>";
    exit;

}


$cliente_id = (int)$_POST["cliente_id"];

$proyecto = $_POST["proyecto"];

$subtotal = $_POST["subtotal"];

$igv = $_POST["igv"];

$total = $_POST["total"];



// =====================================
// ACTUALIZAR CABECERA
// =====================================


$sql = "

UPDATE cotizaciones SET

cliente_id = ?,

proyecto = ?,

subtotal = ?,

igv = ?,

total = ?

WHERE id = ?

";



$stmt = $conexion->prepare($sql);


$stmt->bind_param(

"isdddi",

$cliente_id,

$proyecto,

$subtotal,

$igv,

$total,

$id

);


$stmt->execute();



// =====================================
// ELIMINAR DETALLE ANTERIOR
// =====================================


$sqlEliminar = "

DELETE FROM detalle_cotizacion

WHERE cotizacion_id = ?

";



$stmtEliminar = $conexion->prepare($sqlEliminar);


$stmtEliminar->bind_param(
"i",
$id
);


$stmtEliminar->execute();



// =====================================
// INSERTAR NUEVO DETALLE
// =====================================


if(isset($_POST["articulo_id"])){




$sqlDetalle = "

INSERT INTO detalle_cotizacion

(

cotizacion_id,

articulo_id,

descripcion,

unidad,

cantidad,

precio_venta,

importe

)

VALUES

(?,?,?,?,?,?,?)

";



$stmtDetalle = $conexion->prepare($sqlDetalle);




foreach($_POST["articulo_id"] as $key=>$articulo){



$stmtDetalle->bind_param(

"iissddd",

$id,

$_POST["articulo_id"][$key],

$_POST["descripcion"][$key],

$_POST["unidad"][$key],

$_POST["cantidad"][$key],

$_POST["precio_venta"][$key],

$_POST["importe"][$key]

);



$stmtDetalle->execute();



}



}



header("Location: ver.php?id=".$id);

exit;
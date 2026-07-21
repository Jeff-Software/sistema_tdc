<?php

require_once "../../config/conexion.php";

session_start();


// =========================================
// DATOS GENERALES
// =========================================

$cliente_id = $_POST["cliente_id"] ?? null;

$proyecto = trim($_POST["proyecto"] ?? "");

$subtotal = $_POST["subtotal"] ?? 0;

$igv = $_POST["igv"] ?? 0;

$total = $_POST["total"] ?? 0;

$usuario_id = $_SESSION["usuario_id"] ?? 1;

// =========================================
// VALIDACIONES
// =========================================

if(empty($cliente_id)){

    header("Location: nuevo.php?error=cliente");

    exit;

}

if(empty($_POST["articulo_id"])){

    header("Location: nuevo.php?error=articulos");

    exit;

}


// Datos del cliente para guardar copia en la cotización

$sqlCliente = "
SELECT 
razon_social,
ruc,
direccion
FROM clientes
WHERE id = ?
";


$stmtCliente = $conexion->prepare($sqlCliente);

$stmtCliente->bind_param(
    "i",
    $cliente_id
);

$stmtCliente->execute();


$datosCliente = $stmtCliente
->get_result()
->fetch_assoc();

if(!$datosCliente){

    die("El cliente seleccionado no existe.");

}


$cliente_nombre = $datosCliente["razon_social"];

$cliente_ruc = $datosCliente["ruc"];

$cliente_direccion = $datosCliente["direccion"];


// =========================================
// GENERAR NUMERO COTIZACION
// =========================================

$sqlNumero = "

SELECT MAX(numero) AS ultimo

FROM cotizaciones

WHERE serie='001'

";


$resultado = $conexion->query($sqlNumero);


$fila = $resultado->fetch_assoc();


$numero = ($fila["ultimo"] ?? 0) + 1;



// =========================================
// INSERTAR CABECERA
// =========================================

$conexion->begin_transaction();

try{

$sql = "

INSERT INTO cotizaciones
(
serie,
numero,
cliente_id,
cliente_nombre,
cliente_ruc,
cliente_direccion,
proyecto,
fecha,
subtotal,
igv,
total,
estado,
usuario_id
)

VALUES
(
'001',
?,
?,
?,
?,
?,
?,
CURDATE(),
?,
?,
?,
'BORRADOR',
?
)

";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(

"iissssdddi",

$numero,
$cliente_id,
$cliente_nombre,
$cliente_ruc,
$cliente_direccion,
$proyecto,
$subtotal,
$igv,
$total,
$usuario_id

);


$stmt->execute();

$cotizacion_id = $conexion->insert_id;

// =========================================
// INSERTAR DETALLE COTIZACION
// =========================================

$sqlDetalle = "

INSERT INTO detalle_cotizacion

(
cotizacion_id,
orden_item,
articulo_id,
descripcion,
unidad,
cantidad,
precio_compra,
precio_venta,
importe
)

VALUES

(
?,
?,
?,
?,
?,
?,
?,
?,
?

)

";


$stmtDetalle = $conexion->prepare($sqlDetalle);



foreach($_POST["articulo_id"] as $i => $articulo_id){


    $descripcion = $_POST["descripcion"][$i];

    $unidad = $_POST["unidad"][$i];

    $cantidad = $_POST["cantidad"][$i];

    $precio_compra = $_POST["precio_compra"][$i];

    $precio_venta = $_POST["precio_venta"][$i];


    $importe = $cantidad * $precio_venta;



    $orden = $i + 1;



    $stmtDetalle->bind_param(

        "iiissdddd",

        $cotizacion_id,
        $orden,
        $articulo_id,
        $descripcion,
        $unidad,
        $cantidad,
        $precio_compra,
        $precio_venta,
        $importe

    );


    $stmtDetalle->execute();


}

$conexion->commit();

header("Location: index.php?mensaje=creado");

exit;

}catch(Exception $e){

    $conexion->rollback();

    die("Error al guardar la cotización: " . $e->getMessage());

}

?>
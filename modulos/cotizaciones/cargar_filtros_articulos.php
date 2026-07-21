<?php

require_once "../../config/conexion.php";

$familias = [];
$unidades = [];

// Familias
$sql = "
SELECT DISTINCT familia
FROM articulos
WHERE estado = 1
ORDER BY familia
";

$resultado = $conexion->query($sql);

while($fila = $resultado->fetch_assoc()){

    $familias[] = $fila["familia"];

}

// Unidades
$sql = "
SELECT DISTINCT unidad
FROM articulos
WHERE estado = 1
ORDER BY unidad
";

$resultado = $conexion->query($sql);

while($fila = $resultado->fetch_assoc()){

    $unidades[] = $fila["unidad"];

}

echo json_encode([
    "familias" => $familias,
    "unidades" => $unidades
]);
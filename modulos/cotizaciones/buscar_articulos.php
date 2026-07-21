<?php

require_once "../../config/conexion.php";

$buscar = trim($_GET["buscar"] ?? "");
$familia = trim($_GET["familia"] ?? "");
$unidad = trim($_GET["unidad"] ?? "");

$pagina = intval($_GET["pagina"] ?? 1);

if($pagina < 1){
    $pagina = 1;
}

$porPagina = 6;

$inicio = ($pagina - 1) * $porPagina;

// ===============================
// TOTAL DE ARTÍCULOS
// ===============================

$sqlTotal = "
SELECT COUNT(*) AS total
FROM articulos
WHERE estado = 1
";

$parametrosTotal = [];
$tiposTotal = "";


if ($buscar != "") {

    $sqlTotal .= "
    AND (
        descripcion LIKE ?
        OR codigo LIKE ?
    )";

    $parametrosTotal[] = "%".$buscar."%";
    $parametrosTotal[] = "%".$buscar."%";

    $tiposTotal .= "ss";
}


if ($familia != "") {

    $sqlTotal .= "
    AND familia = ?";

    $parametrosTotal[] = $familia;

    $tiposTotal .= "s";

}

if ($unidad != "") {

    $sqlTotal .= "
    AND unidad = ?";

    $parametrosTotal[] = $unidad;

    $tiposTotal .= "s";

}


$stmtTotal = $conexion->prepare($sqlTotal);


if(count($parametrosTotal) > 0){

    $stmtTotal->bind_param($tiposTotal, ...$parametrosTotal);

}


$stmtTotal->execute();


$total = $stmtTotal
->get_result()
->fetch_assoc()["total"];


$totalPaginas = ceil($total / $porPagina);

$sql = "
SELECT
    id,
    codigo,
    familia,
    descripcion,
    unidad,
    precio_compra,
    precio_venta
FROM articulos
WHERE estado = 1
";

$parametros = [];
$tipos = "";

if ($buscar != "") {

    $sql .= "
    AND (
        descripcion LIKE ?
        OR codigo LIKE ?
    )";

    $texto = "%".$buscar."%";

    $parametros[] = $texto;
    $parametros[] = $texto;

    $tipos .= "ss";

}

if ($familia != "") {

    $sql .= "
    AND familia = ?";

    $parametros[] = $familia;

    $tipos .= "s";

}

if ($unidad != "") {

    $sql .= "
    AND unidad = ?";

    $parametros[] = $unidad;

    $tipos .= "s";

}

$sql .= "
ORDER BY descripcion
LIMIT ?, ?";

$stmt = $conexion->prepare($sql);

$parametros[] = $inicio;
$parametros[] = $porPagina;

$tipos .= "ii";

$stmt->bind_param($tipos, ...$parametros);

$stmt->execute();

$resultado = $stmt->get_result();

while($fila=$resultado->fetch_assoc()){



?>

<tr>

<td><?= $fila["codigo"] ?></td>

<td><?= $fila["descripcion"] ?></td>

<td><?= $fila["familia"] ?></td>

<td><?= $fila["unidad"] ?></td>

<td class="text-end">
S/ <?= number_format($fila["precio_venta"],2) ?>
</td>

<td class="text-center">

<button
type="button"
class="btn btn-success btn-sm agregarArticulo"

data-id="<?= $fila["id"] ?>"

data-codigo="<?= htmlspecialchars($fila["codigo"]) ?>"

data-descripcion="<?= htmlspecialchars($fila["descripcion"]) ?>"

data-unidad="<?= $fila["unidad"] ?>"

data-compra="<?= $fila["precio_compra"] ?>"

data-venta="<?= $fila["precio_venta"] ?>">

<i class="bi bi-plus-lg"></i>

</button>

</td>

</tr>


<?php

}

?>
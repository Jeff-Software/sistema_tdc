<?php

require_once "../../includes/layout_inicio.php";
require_once "../../config/conexion.php";

// =========================================
// FILTROS
// =========================================

$where = "WHERE 1=1";

// Buscar

if(!empty($_GET["buscar"])){

    $buscar = $conexion->real_escape_string($_GET["buscar"]);

    $where .= " AND (

        c.numero LIKE '%$buscar%'

        OR cl.razon_social LIKE '%$buscar%'

        OR c.proyecto LIKE '%$buscar%'

    )";

}

// Estado

if(!empty($_GET["estado"])){

    $estado = $conexion->real_escape_string($_GET["estado"]);

    $where .= " AND c.estado = '$estado'";

}

// Desde

if(!empty($_GET["desde"])){

    $desde = $conexion->real_escape_string($_GET["desde"]);

    $where .= " AND c.fecha >= '$desde'";

}

// Hasta

if(!empty($_GET["hasta"])){

    $hasta = $conexion->real_escape_string($_GET["hasta"]);

    $where .= " AND c.fecha <= '$hasta'";

}

// =========================================
// PAGINACIÓN
// =========================================

$registros_por_pagina = 3;

$pagina = isset($_GET["pagina"])
    ? (int)$_GET["pagina"]
    : 1;

if($pagina < 1){
    $pagina = 1;
}

$inicio = ($pagina - 1) * $registros_por_pagina;

$sqlTotal = "

SELECT COUNT(*) AS total

FROM cotizaciones c

INNER JOIN clientes cl
ON c.cliente_id = cl.id

INNER JOIN usuarios u
ON c.usuario_id = u.id

$where

";

$totalResultado = $conexion->query($sqlTotal);

$totalRegistros = $totalResultado->fetch_assoc()["total"];

$totalPaginas = ceil($totalRegistros / $registros_por_pagina);

$sql = "
SELECT

    c.id,

    c.serie,

    c.numero,

    c.fecha,

    c.total,

    c.estado,

    c.proyecto,

    cl.razon_social,

    u.nombre AS usuario

FROM cotizaciones c

INNER JOIN clientes cl
    ON c.cliente_id = cl.id

INNER JOIN usuarios u
    ON c.usuario_id = u.id

$where

ORDER BY c.numero DESC

LIMIT $inicio, $registros_por_pagina
";

// =========================================
// RESUMEN DE RESULTADOS
// =========================================

$sqlResumen = "

SELECT 

COUNT(*) AS cantidad,
SUM(c.total) AS monto

FROM cotizaciones c

INNER JOIN clientes cl
ON c.cliente_id = cl.id

INNER JOIN usuarios u
ON c.usuario_id = u.id

$where

";


$resumen = $conexion->query($sqlResumen);

$datosResumen = $resumen->fetch_assoc();


$cantidadCotizaciones = $datosResumen["cantidad"] ?? 0;

$montoTotal = $datosResumen["monto"] ?? 0;


$resultado = $conexion->query($sql);

?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/cotizaciones.css">

<!-- ========================================= -->
<!-- ENCABEZADO -->
<!-- ========================================= -->

<div class="cotizaciones-header">

    <div>

        <h2>

            <i class="bi bi-file-earmark-text"></i>

            Cotizaciones

        </h2>

        <p>

            Historial y administración de las cotizaciones registradas.

        </p>

    </div>

    <a href="nuevo.php" class="btn btn-success">

        <i class="bi bi-plus-circle"></i>

        Nueva Cotización

    </a>

</div>

<!-- ========================================= -->
<!-- FILTROS -->
<!-- ========================================= -->

<div class="cotizaciones-filtros">

    <div class="card-body">

        <form method="GET">

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="form-label">
                        Buscar
                    </label>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Número, cliente o proyecto..."
                        value="<?= $_GET["buscar"] ?? "" ?>">

                </div>

                <div class="col-md-2">

                    <label class="form-label">
                        Estado
                    </label>

                    <select name="estado" class="form-select">

                    <option value="">Todos</option>

                    <option value="BORRADOR"
                    <?= (($_GET["estado"] ?? "")=="BORRADOR") ? "selected" : "" ?>>
                    BORRADOR
                    </option>

                    <option value="ENVIADA"
                    <?= (($_GET["estado"] ?? "")=="ENVIADA") ? "selected" : "" ?>>
                    ENVIADA
                    </option>

                    <option value="APROBADA"
                    <?= (($_GET["estado"] ?? "")=="APROBADA") ? "selected" : "" ?>>
                    APROBADA
                    </option>

                    <option value="RECHAZADA"
                    <?= (($_GET["estado"] ?? "")=="RECHAZADA") ? "selected" : "" ?>>
                    RECHAZADA
                    </option>

                    <option value="PARCIAL"
                    <?= (($_GET["estado"] ?? "")=="PARCIAL") ? "selected" : "" ?>>
                    PARCIAL
                    </option>

                    <option value="FACTURADA"
                    <?= (($_GET["estado"] ?? "")=="FACTURADA") ? "selected" : "" ?>>
                    FACTURADA
                    </option>

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label">
                        Desde
                    </label>

                    <input
                        type="date"
                        name="desde"
                        class="form-control"
                        value="<?= $_GET["desde"] ?? "" ?>">

                </div>

                <div class="col-md-2">

                    <label class="form-label">
                        Hasta
                    </label>

                    <input
                        type="date"
                        name="hasta"
                        class="form-control"
                        value="<?= $_GET["hasta"] ?? "" ?>">

                </div>

                <div class="col-md-2 d-grid">

                    <label class="form-label invisible">
                        Buscar
                    </label>

                    <button
                        type="submit"
                        class="btn btn-buscar">

                        <i class="bi bi-search"></i>

                        Buscar

                    </button>

                </div>

                <div class="col-md-2">

                    <label class="form-label invisible">
                        Limpiar
                    </label>

                    <a
                        href="index.php"
                        class="btn btn-limpiar">

                        <i class="bi bi-arrow-clockwise"></i>

                        Limpiar

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

<!-- ========================================= -->
<!-- RESUMEN -->
<!-- ========================================= -->

<div class="row mb-3">


    <div class="col-md-4">

        <div class="resumen-card">

            <div class="card-body">

                <h6 class="text-muted">

                    Cotizaciones encontradas

                </h6>

                <h3 class="mb-0">

                    <?= $cantidadCotizaciones ?>

                </h3>

            </div>

        </div>

    </div>



    <div class="col-md-4">

        <div class="resumen-card">

            <div class="card-body">

                <h6 class="text-muted">

                    Total mostrado

                </h6>

                <h3 class="mb-0">

                    S/ <?= number_format($montoTotal,2) ?>

                </h3>

            </div>

        </div>

    </div>



    <div class="col-md-4">

        <div class="resumen-card">

            <div class="card-body">

                <h6 class="text-muted">

                    Última actualización

                </h6>

                <p class="mb-0">

                    <?= date("d/m/Y H:i") ?>

                </p>

            </div>

        </div>

    </div>


</div>

<!-- ========================================= -->
<!-- TABLA -->
<!-- ========================================= -->

<div class="cotizaciones-card">

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle tabla-cotizaciones">

    <thead class="tabla-verde">

        <tr>

            <th width="60" class="text-center">#</th>

            <th class="col-numero">
            Número
            </th>

            <th class="col-fecha">
            Fecha
            </th>

            <th class="col-cliente">
            Cliente
            </th>

            <th class="col-proyecto">
            Proyecto
            </th>

            <th class="col-total">
            Total
            </th>

            <th class="col-estado">
            Estado
            </th>

            <th class="col-guias">
            Guías
            </th>

            <th class="col-usuario">
            Usuario
            </th>

            <th class="col-acciones">
            Acciones
            </th>

        </tr>

    </thead>

    <tbody>

<?php

if($resultado->num_rows == 0){

?>

<tr>

    <td colspan="10" class="text-center text-muted py-4">

        <i class="bi bi-folder-x fs-4 d-block mb-2"></i>

        Todavía no existen cotizaciones registradas.

    </td>

</tr>

<?php

}else{

$n = $inicio + 1;


while($fila = $resultado->fetch_assoc()){

$color = "secondary";

switch($fila["estado"]){

    case "ENVIADA":
        $color = "primary";
        break;

    case "APROBADA":
        $color = "success";
        break;

    case "RECHAZADA":
        $color = "danger";
        break;

    case "PARCIAL":
        $color = "warning";
        break;

    case "FACTURADA":
        $color = "dark";
        break;

}

?>

<tr>

    <td class="text-center">

        <?= $n++ ?>

    </td>

    <td class="col-numero">

    001-<?= str_pad($fila["numero"], 6, "0", STR_PAD_LEFT) ?>

    </td>

    <td>

        <?= date("d/m/Y", strtotime($fila["fecha"])) ?>

    </td>

    <td class="col-cliente">

    <?= $fila["razon_social"] ?>

    </td>

    <td class="col-proyecto">

    <?= $fila["proyecto"] ?: "Sin proyecto" ?>

    </td>

    <td class="text-end fw-bold">

        S/ <?= number_format($fila["total"],2) ?>

    </td>

    <td class="text-center">

        <span class="badge bg-<?= $color ?>">

            <?= $fila["estado"] ?>

        </span>

    </td>

    <td class="text-center">

        0

    </td>

    <td>

        <?= $fila["usuario"] ?>

    </td>

    <td class="text-center">

        <a
            href="ver.php?id=<?= $fila["id"] ?>"
            class="btn btn-ver btn-sm"
            title="Ver cotización">

            <i class="bi bi-eye"></i>

        </a>

</td>

</tr>

<?php

}

}

?>

    </tbody>

</table>

<?php if($totalPaginas > 1){ ?>

<nav class="mt-4">

<ul class="pagination justify-content-center">

<?php if($pagina > 1){ ?>

<li class="page-item">

<a class="page-link"
href="?pagina=1&buscar=<?= urlencode($_GET["buscar"] ?? "") ?>&estado=<?= urlencode($_GET["estado"] ?? "") ?>&desde=<?= urlencode($_GET["desde"] ?? "") ?>&hasta=<?= urlencode($_GET["hasta"] ?? "") ?>">

&laquo; Primero

</a>

</li>

<li class="page-item">

<a class="page-link"
href="?pagina=<?= ($pagina-1) ?>&buscar=<?= urlencode($_GET["buscar"] ?? "") ?>&estado=<?= urlencode($_GET["estado"] ?? "") ?>&desde=<?= urlencode($_GET["desde"] ?? "") ?>&hasta=<?= urlencode($_GET["hasta"] ?? "") ?>">

Anterior

</a>

</li>

<?php } ?>

<?php
$inicioPag = max(1, $pagina - 2);
$finPag = min($totalPaginas, $pagina + 2);

for($i = $inicioPag; $i <= $finPag; $i++){
?>

<li class="page-item <?= ($i == $pagina) ? "active" : "" ?>">

<a class="page-link"
href="?pagina=<?= $i ?>&buscar=<?= urlencode($_GET["buscar"] ?? "") ?>&estado=<?= urlencode($_GET["estado"] ?? "") ?>&desde=<?= urlencode($_GET["desde"] ?? "") ?>&hasta=<?= urlencode($_GET["hasta"] ?? "") ?>">

<?= $i ?>

</a>

</li>

<?php } ?>

<?php if($pagina < $totalPaginas){ ?>

<li class="page-item">

<a class="page-link"
href="?pagina=<?= ($pagina+1) ?>&buscar=<?= urlencode($_GET["buscar"] ?? "") ?>&estado=<?= urlencode($_GET["estado"] ?? "") ?>&desde=<?= urlencode($_GET["desde"] ?? "") ?>&hasta=<?= urlencode($_GET["hasta"] ?? "") ?>">

Siguiente

</a>

</li>

<li class="page-item">

<a class="page-link"
href="?pagina=<?= $totalPaginas ?>&buscar=<?= urlencode($_GET["buscar"] ?? "") ?>&estado=<?= urlencode($_GET["estado"] ?? "") ?>&desde=<?= urlencode($_GET["desde"] ?? "") ?>&hasta=<?= urlencode($_GET["hasta"] ?? "") ?>">

Último &raquo;

</a>

</li>

<?php } ?>

</ul>

</nav>

<?php } ?>

</div>

</div>

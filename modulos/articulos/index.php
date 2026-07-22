<?php

require_once "../../includes/layout_inicio.php";

?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/articulos.css">

<?php

require_once "../../config/conexion.php";
require_once "../../includes/filtros.php";

// =====================================
// LIMPIAR FILTROS ARTÍCULOS
// =====================================

if(isset($_GET["limpiar"])){

    guardarFiltroArticulos(
        "",
        "recientes",
        1
    );

    header("Location:index.php");
    exit;

}

$filtros = obtenerFiltroArticulos();

if(empty($_GET)){

    $_GET["buscar"] = $filtros["buscar"];

    $_GET["orden"] = $filtros["orden"];

    $_GET["pagina"] = $filtros["pagina"];

}else{

    guardarFiltroArticulos(

        $_GET["buscar"] ?? "",

        $_GET["orden"] ?? "recientes",

        $_GET["pagina"] ?? 1

    );

}

?>

<div class="articulos-header">

<h2>
<i class="bi bi-box-seam"></i>
Artículos
</h2>

<div>

<a href="inactivos.php" class="btn btn-secondary me-2">

<i class="bi bi-eye-slash"></i>

Inactivos

</a>


<a href="nuevo.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Nuevo artículo

</a>

</div>


</div>

<form method="GET" class="mb-3">

<div class="row">

    <div class="col-md-8">

        <div class="input-group">

            <span class="input-group-text">

                <i class="bi bi-search"></i>

            </span>

            <input
                type="text"
                name="buscar"
                class="form-control"
                placeholder="Buscar por descripción o familia..."
                value="<?= htmlspecialchars($_GET["buscar"] ?? "") ?>">

            <button
                type="submit"
                class="btn btn-outline-secondary">

                <i class="bi bi-search"></i>

                Buscar

            </button>

            <a
            href="index.php?limpiar=1"
            class="btn btn-outline-danger">

            <i class="bi bi-x-circle"></i>

            Limpiar

            </a>

        </div>

    </div>

    <div class="col-md-4">

        <select
            name="orden"
            class="form-select"
            onchange="this.form.submit()">

            <option
                value="recientes"
                <?= ($_GET["orden"] ?? "recientes")=="recientes" ? "selected" : "" ?>>

                Más recientes

            </option>

            <option
                value="nombre"
                <?= ($_GET["orden"] ?? "")=="nombre" ? "selected" : "" ?>>

                Nombre A-Z

            </option>

            <option
                value="familia"
                <?= ($_GET["orden"] ?? "")=="familia" ? "selected" : "" ?>>

                Familia

            </option>

            <option
                value="mayor"
                <?= ($_GET["orden"] ?? "")=="mayor" ? "selected" : "" ?>>

                Mayor precio

            </option>

            <option
                value="menor"
                <?= ($_GET["orden"] ?? "")=="menor" ? "selected" : "" ?>>

                Menor precio

            </option>

        </select>

    </div>

</div>

</form>

<div class="articulos-card">

<div class="table-responsive">

<table class="table table-hover align-middle">

    <thead class="table-dark">

        <tr>

        <th class="text-center">#</th>
        <th>Familia</th>
        <th>Descripción</th>
        <th class="text-center">Unidad</th>
        <th class="text-end">Compra</th>
        <th class="text-end">Venta</th>
        <th class="text-center">Estado</th>
        <th class="text-center">Acciones</th>

        </tr>

    </thead>

    <tbody>
        

<?php

$buscar = trim($_GET["buscar"] ?? "");

$orden = $_GET["orden"] ?? "recientes";

$registros_por_pagina = 8;

$pagina = isset($_GET["pagina"]) ? (int)$_GET["pagina"] : 1;

if($pagina < 1){
    $pagina = 1;
}

$inicio = ($pagina - 1) * $registros_por_pagina;

// Total de registros

if ($buscar != "") {

    $sql_total = "SELECT COUNT(*) AS total
                  FROM articulos
                  WHERE estado = 1
                  AND (
                      descripcion LIKE ?
                      OR familia LIKE ?
                  )";

    $stmt_total = $conexion->prepare($sql_total);

    $texto = "%".$buscar."%";

    $stmt_total->bind_param("ss", $texto, $texto);

    $stmt_total->execute();

    $total = $stmt_total->get_result()->fetch_assoc()["total"];

} else {

    $sql_total = "SELECT COUNT(*) AS total
                  FROM articulos
                  WHERE estado = 1";

    $total = $conexion
        ->query($sql_total)
        ->fetch_assoc()["total"];

}

$total_paginas = ceil($total / $registros_por_pagina);

switch($orden){

    case "nombre":
        $orden_sql = "descripcion ASC";
        break;


    case "familia":
        $orden_sql = "familia ASC, descripcion ASC";
        break;


    case "mayor":
        $orden_sql = "precio_venta DESC";
        break;


    case "menor":
        $orden_sql = "precio_venta ASC";
        break;


    default:
        $orden_sql = "id DESC";
        break;

}

if ($buscar != "") {

$sql = "SELECT *
        FROM articulos
        WHERE estado = 1
        AND (
            descripcion LIKE ?
            OR familia LIKE ?
        )
        ORDER BY $orden_sql
        LIMIT ?, ?";

    $stmt = $conexion->prepare($sql);

    $texto = "%".$buscar."%";

    $stmt->bind_param("ssii", $texto, $texto, $inicio, $registros_por_pagina);

    $stmt->execute();

    $resultado = $stmt->get_result();

} else {

$sql = "SELECT *
        FROM articulos
        WHERE estado = 1
        ORDER BY $orden_sql
        LIMIT $inicio, $registros_por_pagina";

    $resultado = $conexion->query($sql);

}

$n = $inicio + 1;

?>

<p class="text-muted mb-3">

Se encontraron
<strong><?= $total ?></strong>
artículos.

</p>

<?php

if($resultado->num_rows == 0){
?>

<tr>

    <td colspan="8" class="text-center text-muted py-4">
        No se encontraron artículos.
    </td>

</tr>

<?php

}else{

while($fila = $resultado->fetch_assoc()){

?>

<tr>
<td class="text-center"><?=$n++?></td>
<td><?=$fila["familia"]?></td>

<td><?=$fila["descripcion"]?></td>

<td class="text-center"><?=$fila["unidad"]?></td>

<td class="text-end">
    S/ <?=number_format($fila["precio_compra"],2)?>
</td>

<td class="text-end">
    S/ <?=number_format($fila["precio_venta"],2)?>
</td>

<td class="text-center">
    
<?php

if ($fila["estado"]) {

    echo '<span class="badge bg-success">Activo</span>';

} else {

    echo '<span class="badge bg-danger">Inactivo</span>';

}

?>

</td>

<td class="text-center">

<a href="editar.php?id=<?=$fila["id"]?>" class="btn btn-warning btn-sm">
    <i class="bi bi-pencil-square"></i>
</a>

<a href="eliminar.php?id=<?=$fila["id"]?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('¿Desactivar este artículo?');">

<i class="bi bi-eye-slash"></i>

</a>

</td>

</tr>

<?php

}

?>

<?php

}


?>

</tbody>

</table>

</div>

</div>

<?php if($total_paginas > 1){ ?>

<nav class="mt-4">

<ul class="pagination justify-content-center">

<!-- Primero -->

<?php if($pagina > 1){ ?>

<li class="page-item">

<a class="page-link"
href="?pagina=1&buscar=<?=urlencode($buscar)?>&orden=<?=$orden?>">

&laquo; Primero

</a>

</li>

<?php } ?>

<!-- Anterior -->

<?php if($pagina > 1){ ?>

<li class="page-item">

<a class="page-link"
href="?pagina=<?=($pagina-1)?>&buscar=<?=urlencode($buscar)?>&orden=<?=$orden?>">

Anterior

</a>

</li>

<?php } ?>
<!-- Números de página -->

<?php

$inicio = max(2, $pagina - 1);
$fin = min($total_paginas - 1, $pagina + 1);

?>

<!-- Primera página -->

<li class="page-item <?= ($pagina == 1) ? 'active' : '' ?>">

<a class="page-link"
href="?pagina=1&buscar=<?= urlencode($buscar) ?>&orden=<?= $orden ?>">

1

</a>

</li>

<?php if($inicio > 2){ ?>

<li class="page-item disabled">

<span class="page-link">...</span>

</li>

<?php } ?>

<?php for($i = $inicio; $i <= $fin; $i++){ ?>

<li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">

<a class="page-link"
href="?pagina=<?= $i ?>&buscar=<?= urlencode($buscar) ?>&orden=<?= $orden ?>">

<?= $i ?>

</a>

</li>

<?php } ?>

<?php if($fin < $total_paginas - 1){ ?>

<li class="page-item disabled">

<span class="page-link">...</span>

</li>

<?php } ?>

<?php if($total_paginas > 1){ ?>

<li class="page-item <?= ($pagina == $total_paginas) ? 'active' : '' ?>">

<a class="page-link"
href="?pagina=<?= $total_paginas ?>&buscar=<?= urlencode($buscar) ?>&orden=<?= $orden ?>">

<?= $total_paginas ?>

</a>

</li>

<?php } ?>

<?php if($pagina < $total_paginas){ ?>

<li class="page-item">

<a class="page-link"
href="?pagina=<?=($pagina+1)?>&buscar=<?=urlencode($buscar)?>&orden=<?=$orden?>">

Siguiente

</a>

</li>

<?php } ?>

<!-- Último -->

<?php if($pagina < $total_paginas){ ?>

<li class="page-item">

<a class="page-link"
href="?pagina=<?=$total_paginas?>&buscar=<?=urlencode($buscar)?>&orden=<?=$orden?>">

Último &raquo;

</a>

</li>

<?php } ?>

</ul>

</nav>

<?php } ?>

<?php

require_once "../../includes/layout_fin.php";

?>
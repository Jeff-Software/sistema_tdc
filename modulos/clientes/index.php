<?php

require_once "../../includes/layout_inicio.php";

?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/clientes.css">

<?php

require_once "../../config/conexion.php";
require_once "../../includes/funciones.php";
require_once "../../includes/filtros.php";

$filtros = obtenerFiltroClientes();

$buscar = $filtros["buscar"];

$orden = $filtros["orden"];

$pagina = $filtros["pagina"];


// Total de clientes
$sqlTotal = "
SELECT COUNT(*) total
FROM clientes
WHERE estado = 1
";

$totalClientes =
$conexion
->query($sqlTotal)
->fetch_assoc()["total"];

// Primera página
$sql = "
SELECT *
FROM clientes
WHERE estado = 1
ORDER BY razon_social
LIMIT 0,6
";

$resultado = $conexion->query($sql);

?>

<div class="clientes-header">


<div>

<h2>

<i class="bi bi-people"></i>

Clientes

</h2>


<p>

Administración de clientes registrados.

</p>

</div>



<div>


<a href="inactivos.php"
class="btn btn-outline-secondary me-2">


<i class="bi bi-eye-slash"></i>

Inactivos


</a>



<a href="nuevo.php"
class="btn btn-clientes">


<i class="bi bi-person-plus"></i>

Nuevo Cliente


</a>


</div>


</div>

<!-- ========================================= -->
<!-- BUSCADOR -->
<!-- ========================================= -->

<div class="clientes-filtros mb-3">

    <div class="row g-2">

        <div class="col-md-8">

            <div class="input-group">

                <span class="input-group-text">

                    <i class="bi bi-search"></i>

                </span>

                <input
                type="text"
                id="buscarCliente"
                class="form-control"
                value="<?= htmlspecialchars($buscar) ?>"
                placeholder="Buscar por RUC, razón social, contacto o teléfono...">

            </div>

        </div>

        <div class="col-md-4">

            <select
            id="ordenCliente"
            class="form-select">

            <option
            value="az"
            <?= $orden=="az"?"selected":"" ?>>

            Razón Social (A-Z)

            </option>

            <option
            value="za"
            <?= $orden=="za"?"selected":"" ?>>

            Razón Social (Z-A)

            </option>

            <option
            value="nuevo"
            <?= $orden=="nuevo"?"selected":"" ?>>

            Últimos agregados

            </option>

            <option
            value="antiguo"
            <?= $orden=="antiguo"?"selected":"" ?>>

            Primeros agregados

            </option>

            </select>

        </div>

    </div>

</div>

<div class="d-flex justify-content-between align-items-center mb-3">

    <small
        id="contadorClientes"
        class="text-muted">

        <?= $totalClientes ?> cliente(s) registrado(s)

    </small>

</div>

<div class="clientes-card">

<table class="table table-hover align-middle">

    <thead>

        <tr>

            <th>RUC</th>
            <th>Razón Social</th>
            <th>Contacto</th>
            <th>Teléfono</th>
            <th>Acciones</th>

        </tr>

    </thead>

    <tbody id="tablaClientes">

<?php while($fila = $resultado->fetch_assoc()){ ?>

<tr>

<td><?= $fila["ruc"] ?></td>

<td><?= $fila["razon_social"] ?></td>

<td><?= $fila["contacto"] ?></td>

<td>

<?= formatearTelefono($fila["telefono"]) ?>

</td>

<td>

<a href="editar.php?id=<?= $fila["id"] ?>" class="btn btn-warning btn-sm">
Editar
</a>

<a 
href="eliminar.php?id=<?= $fila["id"] ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('¿Desea desactivar este cliente?');">

Eliminar

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>


<div 
id="paginacionClientes"
class="mt-4 text-center">

</div>


</div>

<script src="<?= BASE_URL ?>assets/js/clientes.js"></script>

<?php

require_once "../../includes/layout_fin.php";

?>
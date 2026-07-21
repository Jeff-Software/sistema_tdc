<?php

require_once "includes/layout_inicio.php";
require_once "config/conexion.php";

$sqlArticulos = "SELECT COUNT(*) AS total FROM articulos WHERE estado = 1";
$totalArticulos = $conexion->query($sqlArticulos)->fetch_assoc()["total"];

$sqlClientes = "SELECT COUNT(*) AS total FROM clientes WHERE estado = 1";
$totalClientes = $conexion->query($sqlClientes)->fetch_assoc()["total"];

$sqlCotizaciones = "SELECT COUNT(*) AS total FROM cotizaciones";
$totalCotizaciones = $conexion->query($sqlCotizaciones)->fetch_assoc()["total"];

$sqlGuias = "SELECT COUNT(*) AS total FROM guias";
$totalGuias = $conexion->query($sqlGuias)->fetch_assoc()["total"];


?>

<h2>Dashboard</h2>

<hr>

<div class="row">

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h5>Artículos</h5>

                <h2><?= $totalArticulos ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h5>Clientes</h5>

                <h2><?= $totalClientes ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h5>Cotizaciones</h5>

                <h2><?= $totalCotizaciones ?></h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h5>Guías</h5>

                <h2><?= $totalGuias ?></h2>

            </div>

        </div>

    </div>

</div>

<?php

require_once "includes/layout_fin.php";
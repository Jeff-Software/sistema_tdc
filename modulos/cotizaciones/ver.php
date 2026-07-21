<?php

require_once "../../includes/layout_inicio.php";
require_once "../../config/conexion.php";

if (!isset($_GET["id"])) {

    header("Location: index.php");
    exit;

}

$id = (int) $_GET["id"];

// =========================================
// CONSULTAR COTIZACIÓN
// =========================================

$sql = "

SELECT

    c.*,

    cl.razon_social,

    cl.ruc,

    cl.direccion,

    u.nombre AS usuario

FROM cotizaciones c

INNER JOIN clientes cl
    ON c.cliente_id = cl.id

INNER JOIN usuarios u
    ON c.usuario_id = u.id

WHERE c.id = $id

LIMIT 1

";

$resultado = $conexion->query($sql);

if($resultado->num_rows == 0){

    echo '

    <div class="alert alert-danger">

        La cotización no existe.

    </div>

    ';

    require_once "../../includes/layout_fin.php";

    exit;

}

$cotizacion = $resultado->fetch_assoc();

// =========================================
// CONSULTAR DETALLE
// =========================================

$sqlDetalle = "

SELECT

    orden_item,
    articulo_id,
    descripcion,
    unidad,
    cantidad,
    precio_venta,
    importe

FROM detalle_cotizacion

WHERE cotizacion_id = $id

ORDER BY orden_item

";

$resultadoDetalle = $conexion->query($sqlDetalle);

$sqlResumen = "

SELECT

    COUNT(*) AS items,
    SUM(cantidad) AS unidades

FROM detalle_cotizacion

WHERE cotizacion_id = $id

";

$resumenDetalle = $conexion->query($sqlResumen)->fetch_assoc();

?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/ver_cotizacion.css">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2>Ver Cotización</h2>

        <small class="text-muted">

            Información de la cotización.

        </small>

    </div>

    <a href="index.php" class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Volver

    </a>

</div>

<div class="cotizacion-card mb-4">

    <div class="cotizacion-card-header">

        <strong>

            Datos de la Cotización

        </strong>

    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-6">

                <label>Número</label>

                <div class="dato-cotizacion">

                    <?= $cotizacion["serie"] ?>-<?= str_pad($cotizacion["numero"],6,"0",STR_PAD_LEFT) ?>

                </div>

            </div>

            <div class="col-md-6">

                <label>Fecha</label>

                <div class="dato-cotizacion">

                    <?= date("d/m/Y", strtotime($cotizacion["fecha"])) ?>

                </div>

            </div>

            <div class="col-md-6">

                <label>Cliente</label>

                <div class="dato-cotizacion">

                    <?= $cotizacion["razon_social"] ?>

                </div>

            </div>

            <div class="col-md-6">

                <label>RUC</label>

                <div class="dato-cotizacion">

                    <?= $cotizacion["ruc"] ?>

                </div>

            </div>

            <div class="col-md-6">

                <label>Proyecto</label>

                <div class="dato-cotizacion">

                    <?= $cotizacion["proyecto"] ?: "Sin proyecto" ?>

                </div>

            </div>

            <div class="col-md-6">

                <label>Estado</label>

                <div>

                    <?php

                    $color = "secondary";

                    switch($cotizacion["estado"]){

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

                    <span class="badge bg-<?= $color ?> fs-6">

                        <?= $cotizacion["estado"] ?>

                    </span>

                </div>

            </div>

            <div class="col-md-6">

                <label>Usuario</label>

                <div class="dato-cotizacion">

                    <?= $cotizacion["usuario"] ?>

                </div>

            </div>

            <div class="col-md-6">

                <label>Total</label>

                <div class="dato-total">

                    S/ <?= number_format($cotizacion["total"],2) ?>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="cotizacion-card mt-4">

    <div class="cotizacion-card-header">

        <strong>

            Detalle de la Cotización

        </strong>

    </div>

    <div class="card-body">

        <div class="row align-items-center">

<!-- ========================================= -->
<!-- INFORMACIÓN -->
<!-- ========================================= -->

<div class="col-lg-8 text-center border-end">

    <i class="bi bi-box-seam fs-1 text-success"></i>

    <h5 class="mt-3">

        Artículos registrados

    </h5>

    <div class="row mt-4 mb-4 g-3">

        <div class="col-6">

            <div class="estadistica-articulo">

                <div class="estadistica-numero">

                    <?= $resumenDetalle["items"] ?>

                </div>

                <div class="estadistica-texto">

                    Ítems

                </div>

            </div>

        </div>

        <div class="col-6">

            <div class="estadistica-articulo">

                <div class="estadistica-numero">

                    <?= number_format($resumenDetalle["unidades"],2) ?>

                </div>

                <div class="estadistica-texto">

                    Unidades

                </div>

            </div>

        </div>

    </div>

    <button
        class="btn btn-success px-4"
        id="btnAbrirDetalle">

        <i class="bi bi-eye"></i>

        Ver detalle

    </button>

</div>

            <!-- ========================================= -->
            <!-- RESUMEN -->
            <!-- ========================================= -->

            <div class="col-lg-4">

                <h5 class="mb-3">

                    Resumen

                </h5>

                <table class="table table-borderless mb-0">

                    <tr>

                        <td>

                            Subtotal

                        </td>

                        <td class="text-end">

                            S/ <?= number_format($cotizacion["subtotal"],2) ?>

                        </td>

                    </tr>

                    <tr>

                        <td>

                            IGV

                        </td>

                        <td class="text-end">

                            S/ <?= number_format($cotizacion["igv"],2) ?>

                        </td>

                    </tr>

                    <tr class="border-top">

                        <th>

                            TOTAL

                        </th>

                        <th class="text-end text-success fs-4">

                            S/ <?= number_format($cotizacion["total"],2) ?>

                        </th>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- ========================================= -->
<!-- MODAL DETALLE COTIZACIÓN -->
<!-- ========================================= -->

<div
class="modal fade"
id="modalDetalleCotizacion"
tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header bg-success text-white">

                <h5 class="modal-title">

                    Detalle de la Cotización

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="60">#</th>
                                <th>Artículo</th>
                                <th>Unidad</th>
                                <th class="text-end">Cantidad</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end">Importe</th>

                            </tr>

                        </thead>

                        <tbody id="tablaDetalleCotizacion">

                        <?php

                        mysqli_data_seek($resultadoDetalle,0);

                        $numero = 1;

                        while($detalle = $resultadoDetalle->fetch_assoc()){

                        ?>

                        <tr>

                            <td class="text-center">

                                <?= $numero++ ?>

                            </td>

                            <td>

                                <?= $detalle["descripcion"] ?>

                            </td>

                            <td>

                                <?= $detalle["unidad"] ?>

                            </td>

                            <td class="text-end">

                                <?= number_format($detalle["cantidad"],2) ?>

                            </td>

                            <td class="text-end">

                                S/ <?= number_format($detalle["precio_venta"],2) ?>

                            </td>

                            <td class="text-end fw-bold">

                                S/ <?= number_format($detalle["importe"],2) ?>

                            </td>

                        </tr>


                        <?php } ?>


                        </tbody>

                    </table>

                    <div 
                    id="paginacionDetalle"
                    class="mt-3 text-center">

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="text-end mt-4">

    <a
        href="editar.php?id=<?= $cotizacion["id"] ?>"
        class="btn btn-warning">

        <i class="bi bi-pencil"></i>

        Editar

    </a>

    <a
        href="pdf.php?id=<?= $cotizacion["id"] ?>"
        class="btn btn-danger">

        <i class="bi bi-file-earmark-pdf"></i>

        PDF

    </a>

    <a
        href="../guias/nueva.php?cotizacion=<?= $cotizacion["id"] ?>"
        class="btn btn-info text-white">

        <i class="bi bi-truck"></i>

        Generar Guía

    </a>

</div>

<script src="<?= BASE_URL ?>assets/js/cotizacion_ver.js"></script>

<?php require_once "../../includes/layout_fin.php"; ?>
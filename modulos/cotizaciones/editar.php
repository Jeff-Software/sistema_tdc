<?php

require_once "../../includes/layout_inicio.php";
?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/editar_cotizacion.css">

<?php

require_once "../../config/conexion.php";

if (!isset($_GET["id"])) {

    header("Location: index.php");
    exit;

}

$id = (int)$_GET["id"];

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

$estado = $cotizacion["estado"];


$puedeEditar = false;


if(
    $estado == "BORRADOR"
    ||
    $estado == "RECHAZADA"
){

    $puedeEditar = true;

}

// =========================================
// CONSULTAR DETALLE
// =========================================

$sqlDetalle = "

SELECT

    *

FROM detalle_cotizacion

WHERE cotizacion_id = $id

ORDER BY orden_item

";

$resultadoDetalle = $conexion->query($sqlDetalle);

$cantidadArticulos = $resultadoDetalle->num_rows;

$sqlResumen = "

SELECT

    COUNT(*) AS items,
    SUM(cantidad) AS unidades

FROM detalle_cotizacion

WHERE cotizacion_id = $id

";

$resumenDetalle = $conexion->query($sqlResumen)->fetch_assoc();


// =========================================
// ENVIAR DETALLE A JAVASCRIPT
// =========================================

$detalleInicial = [];

while($fila = $resultadoDetalle->fetch_assoc()){

    $detalleInicial[] = [

        "id" => $fila["articulo_id"],

        "descripcion" => $fila["descripcion"],

        "unidad" => $fila["unidad"],

        "cantidad" => $fila["cantidad"],

        "precio_venta" => $fila["precio_venta"],

        "importe" => $fila["importe"]

    ];

}


// Volvemos a consultar porque el while consumió el resultado

$resultadoDetalle = $conexion->query($sqlDetalle);


?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2>Editar Cotización</h2>

        <small class="text-muted">

            Modifique la información de la cotización.

        </small>

        <div class="mt-2">

        Estado:

        <?php

        $colorEstado="secondary";


        switch($estado){

        case "BORRADOR":
        $colorEstado="secondary";
        break;


        case "ENVIADA":
        $colorEstado="primary";
        break;


        case "APROBADA":
        $colorEstado="success";
        break;


        case "RECHAZADA":
        $colorEstado="danger";
        break;


        case "PARCIAL":
        $colorEstado="warning";
        break;

        case "FACTURADA":
        $colorEstado="dark";
        break;


        }

        ?>

        <span class="badge bg-<?= $colorEstado ?>">

        <?= $estado ?>

        </span>


        </div>

    </div>

    <div>

    <a href="ver.php?id=<?= $id ?>" 
    class="btn btn-secondary me-2">

    <i class="bi bi-arrow-left"></i>

    Volver

    </a>


<?php if($puedeEditar){ ?>

    <button 
    type="submit"
    form="formEditarCotizacion"
    class="btn btn-success">

    <i class="bi bi-save"></i>

    Guardar Cambios

    </button>

<?php } ?>

    <button 
    type="button"
    class="btn btn-danger ms-2"
    id="btnCancelarEdicion">

    Cancelar

</button>


    </div>

</div>

<form id="formEditarCotizacion" method="POST" action="actualizar.php">


<input type="hidden" name="id" value="<?= $id ?>">


<div class="card shadow-sm">

    <div class="card-body">
<!-- ===================================================== -->
<!-- DATOS DE LA COTIZACIÓN -->
<!-- ===================================================== -->

<div class="card shadow-sm mb-4">

    <div class="card-header">

        <strong>

            Datos de la Cotización

        </strong>

    </div>


    <div class="card-body">


        <div class="row">


            <!-- NÚMERO -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Número

                </label>


                <input
                type="text"
                class="form-control"
                value="<?= $cotizacion["serie"] ?>-<?= str_pad($cotizacion["numero"],6,"0",STR_PAD_LEFT) ?>"
                readonly>

            </div>



            <!-- FECHA -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Fecha

                </label>


                <input
                type="date"
                class="form-control"
                value="<?= $cotizacion["fecha"] ?>"
                readonly>

            </div>




            <!-- CLIENTE -->

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Cliente

                </label>


                <div class="input-group">


                    <input
                    type="text"
                    id="clienteNombre"
                    class="form-control"
                    value="<?= $cotizacion["razon_social"] ?>"
                    readonly>


                    <?php if($puedeEditar){ ?>

                    <button
                    type="button"
                    class="btn btn-primary"
                    id="btnBuscarCliente">


                    <i class="bi bi-search"></i>


                    </button>


                    <?php } ?>


                </div>



                <input
                type="hidden"
                name="cliente_id"
                id="cliente_id"
                value="<?= $cotizacion["cliente_id"] ?>">



            </div>





            <!-- RUC -->

            <div class="col-md-6 mb-3">


                <label class="form-label">

                    RUC

                </label>


                <input
                type="text"
                id="clienteRuc"
                class="form-control"
                value="<?= $cotizacion["ruc"] ?>"
                readonly>


            </div>





            <!-- PROYECTO -->

            <div class="col-md-6 mb-3">


                <label class="form-label">

                    Proyecto

                </label>


                <input
                type="text"
                name="proyecto"
                class="form-control"
                value="<?= htmlspecialchars($cotizacion["proyecto"]) ?>"
                <?= !$puedeEditar ? "readonly disabled" : "" ?>
                >

            </div>


            <!-- USUARIO -->

            <div class="col-md-6 mb-3">


                <label class="form-label">

                    Usuario

                </label>


                <input
                type="text"
                class="form-control"
                value="<?= $cotizacion["usuario"] ?>"
                readonly>


            </div>



        </div>


    </div>

</div>

<!-- ===================================================== -->
<!-- DETALLE DE LA COTIZACIÓN -->
<!-- ===================================================== -->

<div class="card shadow-sm mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <strong>

            Detalle de la Cotización

        </strong>

        <div>

<?php if($puedeEditar){ ?>

        <button
        type="button"
        class="btn btn-success"
        id="btnAgregarArticulo">

        <i class="bi bi-plus-circle"></i>

        Agregar Artículo

        </button>

<?php } ?>

            <button
                type="button"
                class="btn btn-outline-primary ms-2"
                id="btnVerDetalle">

                <i class="bi bi-list-ul"></i>

                Ver Detalle

            </button>

        </div>

    </div>

    <div class="card-body">

        <div class="row align-items-center">

<div class="col-md-7 text-center border-end">

    <i class="bi bi-box-seam display-5 text-success"></i>

    <h5 class="mt-3">

        Artículos registrados

    </h5>

    <div class="row mt-4 mb-4 g-3">

        <div class="col-6">

            <div class="estadistica-articulo">

                <div
                    class="estadistica-numero"
                    id="cantidadArticulos">

                    <?= $resumenDetalle["items"] ?>

                </div>

                <div class="estadistica-texto">

                    Ítems

                </div>

            </div>

        </div>

        <div class="col-6">

            <div class="estadistica-articulo">

                <div
                    class="estadistica-numero"
                    id="cantidadUnidades">

                    <?= number_format($resumenDetalle["unidades"],2) ?>

                </div>

                <div class="estadistica-texto">

                    Unidades

                </div>

            </div>

        </div>

    </div>

    <p class="text-muted mb-0">

        Los artículos seleccionados se administran desde el detalle.

    </p>

</div>

            <div class="col-md-5">

                <table class="table table-sm mb-0">

                    <tr>

                        <th>Subtotal</th>

                        <td class="text-end">

                            S/
                            <span id="txtSubtotal">

                                <?= number_format($cotizacion["subtotal"],2) ?>

                            </span>

                        </td>

                    </tr>

                    <tr>

                        <th>IGV</th>

                        <td class="text-end">

                            S/
                            <span id="txtIGV">

                                <?= number_format($cotizacion["igv"],2) ?>

                            </span>

                        </td>

                    </tr>

                    <tr class="table-primary">

                        <th>TOTAL</th>

                        <th class="text-end">

                            S/
                            <span id="txtTotal">

                                <?= number_format($cotizacion["total"],2) ?>

                            </span>

                        </th>

                    </tr>

                </table>

            </div>

        </div>

    </div>

</div>

<div id="inputsDetalle"></div>

<input
    type="hidden"
    id="inputSubtotal"
    name="subtotal"
    value="<?= $cotizacion["subtotal"] ?>">

<input
    type="hidden"
    id="inputIGV"
    name="igv"
    value="<?= $cotizacion["igv"] ?>">

<input
    type="hidden"
    id="inputTotal"
    name="total"
    value="<?= $cotizacion["total"] ?>">

    </div>

</div>

<!-- ========================================= -->
<!-- MODAL DETALLE EDICIÓN -->
<!-- ========================================= -->

<div class="modal fade" id="modalEditarDetalle" tabindex="-1">

<div class="modal-dialog modal-xl modal-dialog-scrollable">

<div class="modal-content">


<div class="modal-header">

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


<table class="table table-bordered">


<thead>

<tr>

<th>#</th>

<th>Descripción</th>

<th>Unidad</th>

<th width="100">
Cantidad
</th>

<th width="120">
Precio
</th>

<th width="120">
Importe
</th>

<th width="50">
X
</th>

</tr>

</thead>


<tbody id="tablaEditarDetalle">

</tbody>

</table>

<div id="paginacionDetalle" class="mt-3"></div>


</div>


</div>


<div class="modal-footer">


<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">

Cerrar

</button>


</div>


</div>

</div>

</div>

<!-- ========================================= -->
<!-- MODAL CLIENTES -->
<!-- ========================================= -->

<div class="modal fade" id="modalClientes" tabindex="-1">

<div class="modal-dialog modal-xl">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">
    <i class="bi bi-people-fill me-2"></i>
    Seleccionar Cliente
</h5>

<button
type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<div class="row mb-3">

<div class="input-group">

    <span class="input-group-text">

        <i class="bi bi-search"></i>

    </span>

    <input
        type="text"
        id="buscarCliente"
        class="form-control"
        placeholder="Buscar por RUC o Razón Social">

</div>

</div>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>

<i class="bi bi-credit-card"></i>

RUC

</th>

<th>

<i class="bi bi-building"></i>

Razón Social

</th>

<th>

<i class="bi bi-check2-square"></i>

Acción

</th>

</thead>

<tbody id="listaClientes">

</tbody>

</table>

</div>

</div>

<div class="modal-footer">

<button 
type="button" class="btn btn-secondary" 
data-bs-dismiss="modal"> 
Cerrar 
</button>

</div>

</div>

</div>

</div>


<!-- ========================================= -->
<!-- MODAL ARTÍCULOS -->
<!-- ========================================= -->

<div class="modal fade" id="modalArticulos" tabindex="-1">


<div class="modal-dialog modal-xl">


<div class="modal-content">


<div class="modal-header modal-header-green">


<h5 class="modal-title">

Seleccionar Artículos

</h5>


<button
type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal">

</button>


</div>


<div class="modal-body">


<!-- BUSCADOR -->


<div class="row mb-3">

    <div class="col-md-6">

        <input
        type="text"
        id="buscarArticulo"
        class="form-control"
        placeholder="Buscar artículo...">

    </div>

    <div class="col-md-3">

        <select
        id="familiaArticulo"
        class="form-select">

            <option value="">
                Todas las familias
            </option>

        </select>

    </div>

    <div class="col-md-3">

        <select
        id="unidadArticulo"
        class="form-select">

            <option value="">
                Todas las unidades
            </option>

        </select>

    </div>

</div>



<!-- LISTA ARTICULOS -->


<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>Código</th>

<th>Descripción</th>

<th>Familia</th>

<th>UM</th>

<th>Precio</th>

<th width="5%">Agregar</th>

</tr>

</thead>


<tbody id="listaArticulos">


</tbody>


</table>

</div>


<!-- PAGINACIÓN -->


<div
id="paginacionArticulos"
class="mt-3">


</div>



</div>



<div class="modal-footer">


<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">

Cerrar

</button>


</div>


</div>


</div>


</div>

</form>

<script>
const detalleInicial = <?= json_encode($detalleInicial); ?>;

const idCotizacion = <?= $id ?>;

const puedeEditar = <?= $puedeEditar ? "true" : "false" ?>;
</script>


<?php
require_once "../../includes/layout_fin.php";
?>


<script src="<?= BASE_URL ?>assets/js/editar_cotizacion.js"></script>
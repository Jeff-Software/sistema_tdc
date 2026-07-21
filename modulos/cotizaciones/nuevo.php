<?php

require_once "../../includes/layout_inicio.php";
?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/cotizacion_nuevo.css">

<?php

require_once "../../config/conexion.php";

if(isset($_GET["error"])){

    if($_GET["error"] == "cliente"){

        echo '
        <div class="alert alert-danger">

            Debe seleccionar un cliente.

        </div>
        ';

    }

    if($_GET["error"] == "articulos"){

        echo '
        <div class="alert alert-danger">

            Debe agregar al menos un artículo.

        </div>
        ';

    }

}



// ================================
// CONSULTA DE CLIENTES
// ================================

$sqlClientes = "
SELECT
    id,
    razon_social,
    ruc,
    direccion
FROM clientes
WHERE estado = 1
ORDER BY razon_social
";

$clientes = $conexion->query($sqlClientes);

?>

<div class="cotizacion-header">

<div>

    <h2>

        <i class="bi bi-file-earmark-text"></i>

        Nueva Cotización

    </h2>

    <p>

        Cree una nueva cotización para un cliente.

    </p>

</div>

<a href="index.php" class="btn btn-outline-secondary">

    <i class="bi bi-arrow-left"></i>

    Volver

</a>

</div> 

<form
    id="formCotizacion"
    action="guardar.php"
    method="POST">

<input type="hidden" name="fecha" value="<?= date('Y-m-d') ?>">

<input type="hidden" name="usuario_id" value="1">

<!-- ===================================================== -->
<!-- DATOS DE LA COTIZACIÓN -->
<!-- ===================================================== -->

<div class="cotizacion-card mb-4">

    <div class="cotizacion-card-header">

        <strong>

            Datos de la Cotización

        </strong>

    </div>

    <div class="card-body">

        <div class="row">

<div class="col-md-6 mb-3">

    <label class="form-label">

        Cliente <span class="text-danger">*</span>

    </label>

    <div class="input-group">

        <input
            type="text"
            id="clienteNombre"
            class="form-control"
            placeholder="Seleccione un cliente..."
            readonly>

        <button
            type="button"
            class="btn btn-primary"
            id="btnSeleccionarCliente">

            <i class="bi bi-search"></i>

        </button>

    </div>

    <input
        type="hidden"
        name="cliente_id"
        id="cliente_id">

</div>

<div class="col-md-6 mb-3">

    <label class="form-label">

        Proyecto

    </label>

    <input
        type="text"
        id="proyecto"
        name="proyecto"
        class="form-control"
        placeholder="Ejemplo: Remodelación Oficina">

</div>

</div>

    </div>

</div>
<!-- ===================================================== -->
<!-- DETALLE COTIZACIÓN -->
<!-- ===================================================== -->

<div class="cotizacion-card mb-4">

    <div class="cotizacion-card-header d-flex justify-content-between align-items-center">

        <div class="detalle-titulo">

            <i class="bi bi-cart-check-fill me-2"></i>

            <strong>

                Detalle de la Cotización

            </strong>

        </div>

        <div>

            <button
                type="button"
                class="btn btn-success"
                id="btnAgregarArticulo">

                <i class="bi bi-plus-circle"></i>

                Agregar Artículo

            </button>

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


        <!-- MITAD IZQUIERDA -->

        <div class="col-md-7 resumen-articulos text-center border-end">


            <i class="bi bi-box-seam display-5"></i>


            <h4
            class="mt-3 mb-2"
            id="cantidadArticulos">

                0 artículos agregados

            </h4>

            <p class="text-muted mb-0">

                Presione <strong>Agregar Artículo</strong> para comenzar a construir la cotización.

            </p>


        </div>



        <!-- MITAD DERECHA -->

        <div class="col-md-5">


            <table class="table table-sm mb-0 tabla-resumen-cotizacion">


                <tr>

                    <th>
                        Subtotal
                    </th>

                    <td class="text-end">

                        S/
                        <span id="txtSubtotal">
                            0.00
                        </span>

                    </td>

                </tr>



                <tr>

                    <th>
                        IGV
                    </th>

                    <td class="text-end">

                        S/
                        <span id="txtIGV">
                            0.00
                        </span>

                    </td>

                </tr>



                <tr class="fila-total">

                    <th>
                        TOTAL
                    </th>

                    <th class="text-end">

                        S/
                        <span id="txtTotal">
                            0.00
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
    value="0">

<input
    type="hidden"
    id="inputIGV"
    name="igv"
    value="0">

<input
    type="hidden"
    id="inputTotal"
    name="total"
    value="0">

<!-- ========================================= -->
<!-- BOTÓN GUARDAR -->
<!-- ========================================= -->

<div class="acciones-cotizacion text-end mt-4">

<button 
type="submit"
class="btn btn-primary btn-lg">

<i class="bi bi-save"></i>

Guardar Cotización

</button>

</div>


</form>

<!-- ========================================= -->
<!-- MODAL CLIENTES -->
<!-- ========================================= -->

<div class="modal fade" id="modalClientes" tabindex="-1">


<div class="modal-dialog modal-lg">


<div class="modal-content">


<div class="modal-header modal-header-verde text-white">


<h5 class="modal-title">

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


<div class="col-md-8">


<input
type="text"
id="buscarCliente"
class="form-control"
placeholder="Buscar por RUC o razón social">


</div>


<div class="col-md-4">


<button
type="button"
class="btn btn-success w-100"
id="btnNuevoCliente">


<i class="bi bi-person-plus"></i>

Nuevo Cliente

</button>


</div>


</div>



<div class="table-responsive">


<table class="table table-bordered table-hover">


<thead class="table-light">


<tr>

<th>RUC</th>

<th>Razón Social</th>

<th>Dirección</th>

<th width="100">
Acción
</th>

</tr>


</thead>


<tbody id="listaClientes">


</tbody>


</table>


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
<!-- MODAL NUEVO CLIENTE -->
<!-- ========================================= -->

<div class="modal fade" id="modalNuevoCliente" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header modal-header-verde">

                <h5 class="modal-title">

                    <i class="bi bi-person-plus-fill me-2"></i>

                    Nuevo Cliente

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

<div class="modal-body">

    <div class="alert alert-light border border-success-subtle mb-4">

        <i class="bi bi-info-circle-fill text-success me-2"></i>

        Complete la información del cliente para registrarlo y seleccionarlo automáticamente en la cotización.

    </div>

    <div class="row">

        <div class="col-md-6 mb-3">

            <label class="form-label">
                RUC <span class="text-danger">*</span>
            </label>

            <input
                type="text"
                id="nuevoRuc"
                class="form-control"
                placeholder="20123456789">

        </div>

        <div class="col-md-6 mb-3">

            <label class="form-label">
                Teléfono
            </label>

            <input
                type="text"
                id="nuevoTelefono"
                class="form-control"
                placeholder="987654321">

        </div>

        <div class="col-12 mb-3">

            <label class="form-label">
                Razón Social <span class="text-danger">*</span>
            </label>

            <input
                type="text"
                id="nuevaRazon"
                class="form-control"
                placeholder="Ingrese la razón social">

        </div>

        <div class="col-12 mb-3">

            <label class="form-label">
                Dirección
            </label>

            <input
                type="text"
                id="nuevaDireccion"
                class="form-control"
                placeholder="Ingrese la dirección">

        </div>

        <div class="col-md-6 mb-3">

            <label class="form-label">
                Contacto
            </label>

            <input
                type="text"
                id="nuevoContacto"
                class="form-control"
                placeholder="Nombre del contacto">

        </div>

        <div class="col-md-6 mb-3">

            <label class="form-label">
                Correo
            </label>

            <input
                type="email"
                id="nuevoCorreo"
                class="form-control"
                placeholder="correo@empresa.com">

        </div>

    </div>

</div>


<div class="modal-footer">


<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">

Cancelar

</button>


<button
type="button"
class="btn btn-success"
id="guardarNuevoCliente">

Guardar Cliente

</button>


</div>


</div>


</div>


</div>

<!-- ========================================= -->
<!-- MODAL DETALLE COTIZACIÓN -->
<!-- ========================================= -->

<div class="modal fade" id="modalDetalleCotizacion" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header modal-header-verde">

                <h5 class="modal-title">

                    <i class="bi bi-list-check me-2"></i>

                    Detalle de la Cotización

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

            <div class="input-group mb-4">

                <span class="input-group-text bg-white">

                    <i class="bi bi-search text-success"></i>

                </span>

                <input
                    type="text"
                    id="buscarDetalle"
                    class="form-control"
                    placeholder="Buscar artículo agregado...">

            </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead class="table-success sticky-top">

                            <tr>

                                <th width="5%">#</th>

                                <th>Código</th>

                                <th>Descripción</th>

                                <th width="8%">UM</th>

                                <th width="10%">Cantidad</th>

                                <th width="12%">Venta</th>

                                <th width="12%">Importe</th>

                                <th width="5%">X</th>

                            </tr>

                        </thead>

                        <tbody id="detalleArticulos">

                        </tbody>

                    </table>


                <div 
                id="paginacionDetalle"
                class="mt-3 text-center">

                </div>

                </div> <!-- CIERRE DEL MODAL-BODY -->


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
</div>

<!-- ========================================= -->
<!-- MODAL ARTÍCULOS -->
<!-- ========================================= -->

<div class="modal fade" id="modalArticulos" tabindex="-1">


<div class="modal-dialog modal-xl">


<div class="modal-content">


<div class="modal-header modal-header-verde">

    <h5 class="modal-title">

        <i class="bi bi-box-seam me-2"></i>

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

<thead class="table-light">

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

<script src="<?= BASE_URL ?>assets/js/cotizaciones.js"></script>

<?php
require_once "../../includes/layout_fin.php";
?>
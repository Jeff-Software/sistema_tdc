<?php

require_once "../../includes/layout_inicio.php";

?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/articulo_nuevo.css">

<div class="nuevo-articulo-header">


    <h2>
        <i class="bi bi-box-seam"></i>
        Nuevo artículo
    </h2>


    <p>
        Registra un nuevo producto en el catálogo.
    </p>


</div>

<div class="articulo-form-card">


<form action="guardar.php" method="POST">


<div class="row">


<div class="col-md-6 mb-3">

<label>
<i class="bi bi-grid"></i>
Familia
</label>


<input 
type="text"
name="familia"
class="form-control"
placeholder="Ejemplo: EPPS"
required>

</div>



<div class="col-md-6 mb-3">


<label>
<i class="bi bi-box"></i>
Unidad
</label>


<input 
type="text"
name="unidad"
class="form-control"
placeholder="UND, KG, PAR..."
required>


</div>



</div>





<div class="mb-3">


<label>
<i class="bi bi-card-text"></i>
Descripción
</label>


<input 
type="text"
name="descripcion"
class="form-control"
placeholder="Descripción del artículo"
required>


</div>






<div class="row">


<div class="col-md-6 mb-3">


<label>
<i class="bi bi-cart"></i>
Precio compra
</label>


<div class="input-group">

<span class="input-group-text">
S/
</span>

<input 
type="number"
step="0.01"
name="precio_compra"
class="form-control"
value="0"
required>

</div>


</div>





<div class="col-md-6 mb-3">


<label>
<i class="bi bi-cash"></i>
Precio venta
</label>


<div class="input-group">

<span class="input-group-text">
S/
</span>


<input 
type="number"
step="0.01"
name="precio_venta"
class="form-control"
value="0"
required>

</div>


</div>


</div>






<div class="mb-4">


<label>

<i class="bi bi-toggle-on"></i>

Estado

</label>


<select name="estado" class="form-select">


<option value="1">
Activo
</option>


<option value="0">
Inactivo
</option>


</select>


</div>





<div class="form-actions">


<button class="btn btn-success">

<i class="bi bi-save"></i>

Guardar artículo

</button>


<a href="index.php" class="btn btn-outline-secondary">

Cancelar

</a>


</div>

</form>

</div>

<div class="nuevo-articulo-actions">

    <a href="index.php" class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>

        Volver

    </a>

</div>
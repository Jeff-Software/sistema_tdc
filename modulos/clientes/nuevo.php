<?php

require_once "../../includes/layout_inicio.php";

?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/cliente_nuevo.css">

<div class="nuevo-cliente-header">

<h2>
<i class="bi bi-person-plus"></i>
Nuevo Cliente
</h2>

<p>
Registra un nuevo cliente en el sistema comercial.
</p>

</div>


<form action="guardar.php" method="POST">

<div class="cliente-form-card">


<div class="row">


<div class="col-md-6 mb-3">

<label class="form-label">

RUC

</label>

<input
type="text"
name="ruc"
class="form-control"
required>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">

Razón Social

</label>

<input
type="text"
name="razon_social"
class="form-control"
required>

</div>



<div class="col-md-12 mb-3">

<label class="form-label">

Dirección

</label>

<input
type="text"
name="direccion"
class="form-control">

</div>



<div class="col-md-6 mb-3">

<label class="form-label">

Contacto

</label>

<input
type="text"
name="contacto"
class="form-control">

</div>



<div class="col-md-6 mb-3">

<label class="form-label">

Teléfono

</label>

<input
type="text"
name="telefono"
class="form-control"
maxlength="9"
inputmode="numeric"
pattern="[0-9]{9}"
placeholder="999999999">

</div>



<div class="col-md-12 mb-3">

<label class="form-label">

Correo

</label>

<input
type="email"
name="correo"
class="form-control">

</div>


</div>

<div class="cliente-actions">


<button
type="submit"
class="btn btn-guardar-cliente">

<i class="bi bi-save"></i>

Guardar Cliente

</button>


<a href="index.php"
class="btn btn-outline-secondary">

<i class="bi bi-x-circle"></i>

Cancelar

</a>


</div>

</div>


</div>


</form>

<script src="<?= BASE_URL ?>assets/js/clientes.js"></script>
<?php

require_once "../../includes/layout_fin.php";

?>
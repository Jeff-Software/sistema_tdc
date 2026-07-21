<?php

require_once "../../includes/layout_inicio.php";
?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/cliente_editar.css">

<?php

require_once "../../config/conexion.php";


$id = intval($_GET["id"] ?? 0);


$sql = "
SELECT *
FROM clientes
WHERE id = ?
";


$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();


$resultado = $stmt->get_result();


$cliente = $resultado->fetch_assoc();


if(!$cliente){

    die("Cliente no encontrado");

}


?>


<div class="editar-cliente-header">


<div>

<h2>

<i class="bi bi-pencil-square"></i>

Editar Cliente

</h2>

</div>



<a href="index.php" class="btn btn-outline-secondary btn-volver">

<i class="bi bi-arrow-left"></i>

Volver

</a>


</div>

<form action="actualizar.php" method="POST">


<input 
type="hidden"
name="id"
value="<?= $cliente["id"] ?>">



<div class="cliente-form-card">


<div class="row">



<div class="col-md-6 mb-3">


<label>

<i class="bi bi-card-text"></i>

RUC

</label>


<input
type="text"
name="ruc"
class="form-control"
value="<?= $cliente["ruc"] ?>"
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
value="<?= $cliente["razon_social"] ?>"
required>


</div>




<div class="col-md-12 mb-3">


<label class="form-label">

Dirección

</label>


<input
type="text"
name="direccion"
class="form-control"
value="<?= $cliente["direccion"] ?>">


</div>




<div class="col-md-6 mb-3">


<label class="form-label">

Contacto

</label>


<input
type="text"
name="contacto"
class="form-control"
value="<?= $cliente["contacto"] ?>">


</div>




<div class="col-md-6 mb-3">


<label class="form-label">

Teléfono

</label>


<input
type="text"
name="telefono"
class="form-control"
value="<?= $cliente["telefono"] ?>"
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
class="form-control"
value="<?= $cliente["correo"] ?>">


</div>



</div>



<div class="cliente-actions">


<button
type="submit"
class="btn btn-actualizar">

<i class="bi bi-save"></i>

Actualizar Cliente

</button>



<a href="index.php"
class="btn btn-outline-secondary btn-volver">

Cancelar

</a>


</div>

</div>

</form>


<script src="<?= BASE_URL ?>assets/js/clientes.js"></script>
<?php

require_once "../../includes/layout_fin.php";

?>
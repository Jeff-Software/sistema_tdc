<?php

require_once "../../includes/layout_inicio.php";

?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/articulo_editar.css">

<?php

require_once "../../config/conexion.php";


$id = $_GET["id"] ?? 0;


$sql = "SELECT * FROM articulos WHERE id = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();


$articulo = $resultado->fetch_assoc();


if(!$articulo){

    echo "
    <div class='alert alert-danger'>
        Artículo no encontrado.
    </div>";

    exit;

}

?>


<div class="editar-articulo-header">


<h2>

<i class="bi bi-pencil-square"></i>

Editar artículo

</h2>


<p>
Actualiza la información del producto.
</p>


</div>

<div class="articulo-editar-card">

<form action="actualizar.php" method="POST">


<input type="hidden" 
name="id" 
value="<?= $articulo["id"] ?>">



<div class="mb-3">

<label class="form-label">
Familia
</label>

<input 
type="text"
name="familia"
class="form-control"
value="<?= htmlspecialchars($articulo["familia"]) ?>"
required>

</div>



<div class="mb-3">

<label class="form-label">
Descripción
</label>

<input 
type="text"
name="descripcion"
class="form-control"
value="<?= htmlspecialchars($articulo["descripcion"]) ?>"
required>

</div>



<div class="mb-3">

<label class="form-label">
Unidad
</label>

<input 
type="text"
name="unidad"
class="form-control"
value="<?= htmlspecialchars($articulo["unidad"]) ?>"
required>

</div>




<div class="row">


<div class="col-md-6 mb-3">

<label class="form-label">
Precio compra
</label>

<input 
type="number"
step="0.01"
name="precio_compra"
class="form-control"
value="<?= $articulo["precio_compra"] ?>"
required>

</div>



<div class="col-md-6 mb-3">

<label class="form-label">
Precio venta
</label>

<input 
type="number"
step="0.01"
name="precio_venta"
class="form-control"
value="<?= $articulo["precio_venta"] ?>"
required>

</div>


</div>



<div class="mb-3">

<label class="form-label">
Estado
</label>


<select name="estado" class="form-select">


<option value="1"
<?= $articulo["estado"]==1?"selected":"" ?>>
Activo
</option>


<option value="0"
<?= $articulo["estado"]==0?"selected":"" ?>>
Inactivo
</option>


</select>


</div>




<button class="btn btn-primary">

Guardar cambios

</button>


<a href="index.php" 
class="btn btn-secondary">

Cancelar

</a>



</form>

</div>


<div class="editar-articulo-actions">

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Volver

</a>

</div>

<?php

require_once "../../includes/layout_fin.php";

?>
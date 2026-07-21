<?php

require_once "../../includes/layout_inicio.php";

require_once "../../config/conexion.php";
require_once "../../includes/funciones.php";

$sql = "
SELECT *
FROM clientes
WHERE estado = 0
ORDER BY razon_social
";

$resultado = $conexion->query($sql);

?>

<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/clientes_inactivos.css">

<div class="inactivos-header">

    <div>

        <h2>

            <i class="bi bi-person-x"></i>

            Clientes Inactivos

        </h2>

        <p class="text-muted mb-0">

            Listado de clientes desactivados.

        </p>

    </div>

    <a href="index.php" class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left"></i>

        Volver

    </a>

</div>


<div class="clientes-inactivos-card">

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead>

                <tr>

                    <th>RUC</th>
                    <th>Razón Social</th>
                    <th class="text-center">Acción</th>

                </tr>

            </thead>

            <tbody>

            <?php

            if($resultado->num_rows == 0){

            ?>

                <tr>

                    <td colspan="3" class="text-center py-4 text-muted">

                        <i class="bi bi-check-circle fs-3 d-block mb-2"></i>

                        No hay clientes inactivos.

                    </td>

                </tr>

            <?php

            }else{

                while($fila = $resultado->fetch_assoc()){

            ?>

                <tr>

                    <td><?= $fila["ruc"] ?></td>

                    <td><?= $fila["razon_social"] ?></td>

                    <td class="text-center">

                        <a
                        href="activar.php?id=<?= $fila["id"] ?>"
                        class="btn btn-restaurar btn-sm"
                        onclick="return confirm('¿Restaurar este cliente?');">

                            <i class="bi bi-arrow-counterclockwise"></i>

                            Restaurar

                        </a>

                    </td>

                </tr>

            <?php

                }

            }

            ?>

            </tbody>

        </table>

    </div>

</div>

<?php

require_once "../../includes/layout_fin.php";

?>
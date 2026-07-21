<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">

    <div class="container-fluid">

        <span class="navbar-brand">
            Bienvenido <?= $_SESSION["nombre"] ?>
        </span>

        <div class="ms-auto">

            <a href="logout.php" class="btn btn-danger btn-sm">
                Cerrar sesión
            </a>

        </div>

    </div>

</nav>
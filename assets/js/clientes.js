// =========================================
// TELÉFONO (MÁXIMO 9 DÍGITOS)
// =========================================

document.addEventListener("input", function(e){

    if(e.target.name !== "telefono"){
        return;
    }

    e.target.value = e.target.value
        .replace(/\D/g,"")
        .substring(0,9);

});

// =========================================
// CLIENTES
// =========================================

document.addEventListener("DOMContentLoaded", function(){

    const buscar =
    document.getElementById("buscarCliente");

    const orden =
    document.getElementById("ordenCliente");

    if(!buscar){
        return;
    }

    // Cargar primera página
    cargarClientes(1);

    // =====================================
    // BUSCADOR
    // =====================================

    buscar.addEventListener("input", function(){

        cargarClientes(1);

    });

    // =====================================
    // ORDEN
    // =====================================

    if(orden){

        orden.addEventListener("change", function(){

            cargarClientes(1);

        });

    }

});


// =========================================
// CARGAR CLIENTES
// =========================================

function cargarClientes(pagina){

    const buscar =
    document.getElementById("buscarCliente");

    const orden =
    document.getElementById("ordenCliente");

    fetch(

        "buscar_clientes.php?buscar=" +

        encodeURIComponent(buscar.value) +

        "&orden=" +

        (orden ? orden.value : "") +

        "&pagina=" +

        pagina

    )

    .then(res => res.text())

    .then(html => {

        document.getElementById("tablaClientes").innerHTML = html;

        cargarPaginacionClientes(pagina);

        guardarFiltros(pagina);

    });

}


// =========================================
// PAGINACIÓN
// =========================================

function cargarPaginacionClientes(pagina){

    const buscar =
    document.getElementById("buscarCliente");

    const orden =
    document.getElementById("ordenCliente");

    fetch(

        "paginacion_clientes.php?buscar=" +

        encodeURIComponent(buscar.value) +

        "&orden=" +

        (orden ? orden.value : "") +

        "&pagina=" +

        pagina

    )

    .then(res => res.text())

    .then(html => {

        document.getElementById("paginacionClientes").innerHTML = html;

    });

}


// =========================================
// CAMBIO DE PÁGINA
// =========================================

document.addEventListener("click", function(e){

    if(!e.target.classList.contains("paginaCliente")){
        return;
    }

    let pagina = e.target.dataset.pagina;
    cargarClientes(pagina);

});

// =========================================
// GUARDAR FILTROS
// =========================================

function guardarFiltros(pagina){

    const buscar =
    document.getElementById("buscarCliente");

    const orden =
    document.getElementById("ordenCliente");

    fetch(

        "guardar_filtros.php",

        {

            method:"POST",

            headers:{

                "Content-Type":
                "application/x-www-form-urlencoded"

            },

            body:

            "buscar=" +

            encodeURIComponent(buscar.value) +

            "&orden=" +

            encodeURIComponent(

                orden ? orden.value : "az"

            ) +

            "&pagina=" +

            pagina

        }

    );

}
document.addEventListener("DOMContentLoaded", function () {

    const modalArticuloElemento =
    document.getElementById("modalArticulos");


    let modal;


    if(modalArticuloElemento){

        modal = new bootstrap.Modal(
            modalArticuloElemento
        );

    }

    const btnAgregar = document.getElementById("btnAgregarArticulo");

    const btnVerDetalle = document.getElementById("btnVerDetalle");

    const lista = document.getElementById("listaArticulos");

    const buscar = document.getElementById("buscarArticulo");

    const familia = document.getElementById("familiaArticulo");

    const unidad = document.getElementById("unidadArticulo");

    // ==========================================
// MODAL CLIENTES
// ==========================================


    const btnSeleccionarCliente =
    document.getElementById("btnSeleccionarCliente");


    const modalClientesElemento =
    document.getElementById("modalClientes");


    let modalClientes;

    let modalNuevoCliente;


    if(modalClientesElemento){

        modalClientes = new bootstrap.Modal(
            modalClientesElemento
        );

    }

    const modalNuevoClienteElemento =
    document.getElementById("modalNuevoCliente");


if(modalNuevoClienteElemento){

    modalNuevoCliente = new bootstrap.Modal(
        modalNuevoClienteElemento
    );

}

// ==========================================
// FORMATO TELEFONO NUEVO CLIENTE
// ==========================================

const nuevoTelefono =
document.getElementById("nuevoTelefono");


if(nuevoTelefono){


    nuevoTelefono.addEventListener("input",function(){


        let valor = this.value;


        // quitar todo lo que no sea número

        valor = valor.replace(/\D/g,"");


        // máximo 9 números

        valor = valor.substring(0,9);



        // formato 999 999 999

        if(valor.length > 6){

            valor =
            valor.substring(0,3)
            + " "
            + valor.substring(3,6)
            + " "
            + valor.substring(6);

        }

        else if(valor.length > 3){

            valor =
            valor.substring(0,3)
            + " "
            + valor.substring(3);

        }



        this.value = valor;


    });


}

// ==========================================
// MODAL DETALLE COTIZACIÓN
// ==========================================

const modalDetalleElemento =
document.getElementById("modalDetalleCotizacion");

let modalDetalle;

let paginaDetalle = 1;

let limiteDetalle = 6;

let detalleProductos = [];

let clienteSeleccionado = null;

// ==========================================
// RENDERIZAR DETALLE COTIZACIÓN
// ==========================================

function renderizarDetalle(){

    const tbody = document.getElementById("detalleArticulos");


    tbody.innerHTML = "";


    if(detalleProductos.length === 0){


        tbody.innerHTML = `

        <tr>

            <td colspan="8" class="text-center text-muted">

                No hay artículos agregados

            </td>

        </tr>

        `;


        actualizarPaginacionDetalle();

        return;

    }



    let inicio = 
    (paginaDetalle - 1) * limiteDetalle;


    let fin =
    inicio + limiteDetalle;



    let productosMostrar =
    detalleProductos.slice(inicio, fin);



    productosMostrar.forEach(function(articulo,index){


        let numero = inicio + index + 1;



        tbody.innerHTML += `


        <tr>


        <td>

        ${numero}

        </td>



        <td>

        ${articulo.codigo}

        </td>



        <td>

        ${articulo.descripcion}

        </td>



        <td class="text-center">

        ${articulo.unidad}

        </td>



        <td>

        <input
        type="number"
        class="form-control cantidad"
        value="${articulo.cantidad}"
        min="1"
        data-id="${articulo.id}">

        </td>


        <td>

        <input
        type="number"
        class="form-control venta"
        value="${articulo.venta.toFixed(2)}"
        step="0.01"
        data-id="${articulo.id}">

        </td>


        <td class="text-end importe">

        ${(articulo.cantidad * articulo.venta).toFixed(2)}

        </td>



        <td class="text-center">


        <button
        type="button"
        class="btn btn-danger btn-sm eliminarDetalle"
        data-id="${articulo.id}">


        <i class="bi bi-trash"></i>


        </button>


        </td>


        </tr>


        `;


    });



    actualizarPaginacionDetalle();


}

// ==========================================
// PAGINACION DETALLE
// ==========================================

function actualizarPaginacionDetalle(){


    const contenedor =
    document.getElementById("paginacionDetalle");



    let totalPaginas =
    Math.ceil(
        detalleProductos.length / limiteDetalle
    );



    if(totalPaginas <= 1){

        contenedor.innerHTML="";

        return;

    }



    let html = "";



    for(let i=1;i<=totalPaginas;i++){


        html += `


        <button
        type="button"
        class="btn btn-sm btn-outline-primary mx-1 paginaDetalle"
        data-pagina="${i}">

        ${i}

        </button>


        `;


    }



    contenedor.innerHTML = html;


}

if(modalDetalleElemento){

    modalDetalle = new bootstrap.Modal(
        modalDetalleElemento
    );

}

// ==========================================
// ABRIR MODAL SELECCIONAR CLIENTE
// ==========================================

if(btnSeleccionarCliente){

    btnSeleccionarCliente.addEventListener("click",function(){

        console.log("BOTON BUSCAR CLIENTE");

        if(modalClientes){

            modalClientes.show();

            cargarClientes();

        }

    });

}
// ==========================================
// ABRIR MODAL NUEVO CLIENTE
// ==========================================

const btnNuevoCliente =
document.getElementById("btnNuevoCliente");


if(btnNuevoCliente){

    btnNuevoCliente.addEventListener("click",function(){


        // cerrar modal seleccionar cliente

        if(modalClientes){

            modalClientes.hide();

        }


        // abrir modal nuevo cliente

        if(modalNuevoCliente){

            modalNuevoCliente.show();

        }


    });

}

// ==========================================
// GUARDAR NUEVO CLIENTE
// ==========================================


const guardarNuevoCliente =
document.getElementById("guardarNuevoCliente");


if(guardarNuevoCliente){


    guardarNuevoCliente.addEventListener("click",function(){


        let datos = new FormData();


        datos.append(
            "ruc",
            document.getElementById("nuevoRuc").value
        );


        datos.append(
            "razon_social",
            document.getElementById("nuevaRazon").value
        );


        datos.append(
            "direccion",
            document.getElementById("nuevaDireccion").value
        );


        datos.append(
            "contacto",
            document.getElementById("nuevoContacto").value
        );


        datos.append(
            "telefono",
            document.getElementById("nuevoTelefono")
            .value
            .replace(/\s/g,"")
        );

        datos.append(
            "correo",
            document.getElementById("nuevoCorreo").value
        );



        fetch("guardar_cliente.php",{


            method:"POST",

            body:datos


        })


        .then(res=>res.json())


        .then(data=>{


            if(data.estado){


                alert("Cliente registrado correctamente");


                document.getElementById("clienteNombre").value =
                data.razon_social;


                document.getElementById("cliente_id").value =
                data.id;



                if(modalNuevoCliente){

                    modalNuevoCliente.hide();

                }


            }else{


                alert(data.mensaje);


            }


        });


    });


}
// ==========================================
// CARGAR CLIENTES
// ==========================================

function cargarClientes(pagina=1){


let buscarCliente =
document.getElementById("buscarCliente").value;


fetch(
"buscar_clientes.php?buscar="
+encodeURIComponent(buscarCliente)
+"&pagina="+pagina
)


.then(res=>res.text())


.then(html=>{


document.getElementById("listaClientes").innerHTML=html;


marcarClienteSeleccionado();


cargarPaginacionClientes(pagina);


});


}

function cargarPaginacionClientes(pagina){


let buscarCliente =
document.getElementById("buscarCliente").value;



fetch(
"paginacion_clientes_cotizacion.php?buscar="
+encodeURIComponent(buscarCliente)
+"&pagina="+pagina
)

.then(res=>res.text())

.then(html=>{


document.getElementById(
"paginacionClientesCotizacion"
)
.innerHTML=html;


});


}

document.addEventListener("click",function(e){


if(e.target.classList.contains("paginaClienteCotizacion")){


let pagina =
e.target.dataset.pagina;


cargarClientes(pagina);


}


});

// ==========================================
// CARGAR FILTROS ARTÍCULOS
// ==========================================

function cargarFiltros(){

    fetch("cargar_filtros_articulos.php")

    .then(res => res.json())

    .then(data => {

        familia.innerHTML =
        '<option value="">Todas las familias</option>';

        data.familias.forEach(function(item){

            familia.innerHTML +=
            `<option value="${item}">${item}</option>`;

        });

        unidad.innerHTML =
        '<option value="">Todas las unidades</option>';

        data.unidades.forEach(function(item){

            unidad.innerHTML +=
            `<option value="${item}">${item}</option>`;

        });

    });

}

function cargarArticulos(pagina = 1){

    fetch(
        "buscar_articulos.php?buscar=" +
        encodeURIComponent(buscar.value) +
        "&familia=" +
        encodeURIComponent(familia.value) +
        "&unidad=" +
        encodeURIComponent(unidad.value) +
        "&pagina=" + pagina
    )

    .then(res => res.text())

    .then(html => {

        lista.innerHTML = html;

        marcarArticulosAgregados();

        cargarPaginacion(pagina);

    });

}

function cargarPaginacion(pagina){

    fetch(
        "paginacion_articulos.php?buscar=" +
        encodeURIComponent(buscar.value) +
        "&familia=" +
        encodeURIComponent(familia.value) +
        "&unidad=" +
        encodeURIComponent(unidad.value) +
        "&pagina=" + pagina
    )

    .then(res => res.text())

    .then(html => {

        document.getElementById("paginacionArticulos").innerHTML = html;

    });

}
// ==========================================
// AGREGAR ARTÍCULO AL DETALLE
// ==========================================

function agregarFila(articulo){


    let existe = detalleProductos.find(function(item){

        return item.id == articulo.id;

    });



    if(existe){

        alert("Este artículo ya está agregado en la cotización");

        return false;

    }



    detalleProductos.push({

        id: articulo.id,
        codigo: articulo.codigo,
        descripcion: articulo.descripcion,
        unidad: articulo.unidad,
        cantidad:1,
        compra:parseFloat(articulo.compra),
        venta:parseFloat(articulo.venta)

    });


    renderizarDetalle();

    marcarArticulosAgregados();

    calcularTotales();


    return true;

} // <-- ESTA LLAVE FALTABA



// ==========================================
// MARCAR ARTÍCULOS YA AGREGADOS
// ==========================================

function marcarArticulosAgregados(){

    document.querySelectorAll(".agregarArticulo").forEach(function(boton){

        let id = boton.dataset.id;

        let existe = detalleProductos.some(function(item){

            return item.id == id;

        });

        if(existe){

            boton.classList.remove("btn-success");

            boton.classList.add("btn-secondary");

            boton.innerHTML =
            '<i class="bi bi-check-lg"></i> Agregado';

            boton.disabled = true;

        }else{

            boton.classList.remove("btn-secondary");

            boton.classList.add("btn-success");

            boton.innerHTML =
            '<i class="bi bi-plus-circle"></i> Agregar';

            boton.disabled = false;

        }

    });

}

function numerarFilas(){

    document.querySelectorAll("#detalleArticulos tr").forEach(function(fila,i){

        fila.cells[0].innerHTML=i+1;

    });

}

// ==========================================
// ELIMINAR ARTÍCULO DEL DETALLE
// ==========================================

document.addEventListener("click", function(e){


    if(e.target.closest(".eliminarDetalle")){


        const boton = e.target.closest(".eliminarDetalle");


        let id = boton.dataset.id;


        // eliminar del array

        detalleProductos = detalleProductos.filter(function(item){

            return item.id != id;

        });



        // volver a dibujar tabla

        renderizarDetalle();


        // actualizar totales

        calcularTotales();



        // liberar botón agregar

        marcarArticulosAgregados();


    }


});
// ==========================================
// RECALCULAR AL CAMBIAR CANTIDAD O PRECIO
// ==========================================

document.addEventListener("input",function(e){


    if(e.target.classList.contains("cantidad")){


        let id = e.target.dataset.id;


        let articulo = detalleProductos.find(function(item){

            return item.id == id;

        });


        if(articulo){

            articulo.cantidad = 
            parseInt(e.target.value) || 1;

        }


    }



    if(e.target.classList.contains("venta")){


        let id = e.target.dataset.id;


        let articulo = detalleProductos.find(function(item){

            return item.id == id;

        });


        if(articulo){

            articulo.venta =
            parseFloat(e.target.value) || 0;

        }


    }



    calcularTotales();


});
// ==========================================
// CALCULAR TOTALES
// ==========================================

function calcularTotales(){

    let subtotal = 0;


    detalleProductos.forEach(function(articulo){

        subtotal += articulo.cantidad * articulo.venta;

    });



    let igv = subtotal * 0.18;


    let total = subtotal + igv;



    // cantidad de artículos
    let unidades = 0;


    detalleProductos.forEach(function(articulo){

        unidades += articulo.cantidad;

    });



    document.getElementById("cantidadItems").innerHTML =
    detalleProductos.length;



    document.getElementById("cantidadUnidades").innerHTML =
    unidades.toFixed(2);



    // actualizar resumen principal

    document.getElementById("txtSubtotal").innerHTML =
    subtotal.toFixed(2);


    document.getElementById("txtIGV").innerHTML =
    igv.toFixed(2);


    document.getElementById("txtTotal").innerHTML =
    total.toFixed(2);



    // enviar a PHP

    document.getElementById("inputSubtotal").value =
    subtotal.toFixed(2);


    document.getElementById("inputIGV").value =
    igv.toFixed(2);


    document.getElementById("inputTotal").value =
    total.toFixed(2);

}

if(btnAgregar){

    btnAgregar.addEventListener("click",function(){

        console.log("ABRIENDO ARTICULOS");


        if(modal){

            modal.show();

            cargarFiltros();

            cargarArticulos();

        }


    });

}


// ==========================================
// ABRIR MODAL DETALLE
// ==========================================

if(btnVerDetalle){

    btnVerDetalle.addEventListener("click",function(){

        if(modalDetalle){

            modalDetalle.show();

            renderizarDetalle();

        }

    });

}

if(buscar){

    buscar.addEventListener("keyup",cargarArticulos);

}


if(familia){

    familia.addEventListener("change",cargarArticulos);

}

if(unidad){

    unidad.addEventListener("change",cargarArticulos);

}

// ==========================================
// BUSCAR CLIENTES
// ==========================================

const buscarCliente =
document.getElementById("buscarCliente");


if(buscarCliente){

    buscarCliente.addEventListener(
    "keyup",
    function(){

        cargarClientes(1);

    });

}

    document.addEventListener("click",function(e){

    if(e.target.classList.contains("paginaArticulo")){

        let pagina = e.target.dataset.pagina;

        cargarArticulos(pagina);

    }

});

// ==========================================
// SELECCIONAR CLIENTE
// ==========================================

document.addEventListener("click",function(e){

    if(e.target.closest(".seleccionarCliente")){


        let boton =
        e.target.closest(".seleccionarCliente");


        clienteSeleccionado = boton.dataset.id;


        document.getElementById("clienteNombre").value =
        boton.dataset.nombre;


        document.getElementById("cliente_id").value =
        boton.dataset.id;


        marcarClienteSeleccionado();


    }


});

function marcarClienteSeleccionado(){


document.querySelectorAll(".seleccionarCliente")
.forEach(function(boton){


    if(boton.dataset.id == clienteSeleccionado){


        boton.disabled=true;


        boton.classList.remove("btn-success");

        boton.classList.add("btn-secondary");


        boton.innerHTML =
        '<i class="bi bi-check-lg"></i> Seleccionado';


        boton.closest("tr")
        .classList.add("table-secondary");


    }else{


        boton.disabled=false;


        boton.classList.remove("btn-secondary");

        boton.classList.add("btn-success");


        boton.innerHTML =
        '<i class="bi bi-check"></i> Seleccionar';


        boton.closest("tr")
        .classList.remove("table-secondary");


    }


});


}

document.addEventListener("click",function(e){


    if(e.target.classList.contains("paginaDetalle")){


        paginaDetalle =
        parseInt(e.target.dataset.pagina);


        renderizarDetalle();


    }


});

document.addEventListener("click",function(e){


    if(!e.target.closest(".agregarArticulo")) return;


    const boton = e.target.closest(".agregarArticulo");


    let agregado = agregarFila({

        id: boton.dataset.id,
        codigo: boton.dataset.codigo,
        descripcion: boton.dataset.descripcion,
        unidad: boton.dataset.unidad,
        compra: boton.dataset.compra,
        venta: boton.dataset.venta

    });



    if(agregado){


        // CAMBIO INMEDIATO DEL BOTÓN

        boton.disabled = true;


        boton.classList.remove("btn-success");

        boton.classList.add("btn-secondary");


        boton.innerHTML =
        '<i class="bi bi-check-lg"></i> Agregado';



    }


});

const formulario =
document.getElementById("formCotizacion");

if(formulario){

    formulario.addEventListener("submit", function(e){

        console.log(detalleProductos);

        const contenedor =
        document.getElementById("inputsDetalle");

        contenedor.innerHTML = "";

        detalleProductos.forEach(function(item){

            contenedor.innerHTML += `
                <input type="hidden" name="articulo_id[]" value="${item.id}">
                <input type="hidden" name="descripcion[]" value="${item.descripcion}">
                <input type="hidden" name="unidad[]" value="${item.unidad}">
                <input type="hidden" name="cantidad[]" value="${item.cantidad}">
                <input type="hidden" name="precio_compra[]" value="${item.compra}">
                <input type="hidden" name="precio_venta[]" value="${item.venta}">
            `;
        });

        console.log(contenedor.innerHTML);
    });

}
});
let detalleEditar = [];

let paginaDetalle = 1;

const porPaginaDetalle = 6;


document.addEventListener("DOMContentLoaded", function(){


    detalleEditar = [...detalleInicial];


    console.table(detalleEditar);


    actualizarCantidadEditar();

    calcularResumenEditar();


    const btnVerDetalle =
    document.getElementById("btnVerDetalle");



    if(btnVerDetalle){


        btnVerDetalle.addEventListener("click",function(){


            cargarDetalleModal(paginaDetalle);


            let modal = new bootstrap.Modal(
                document.getElementById("modalEditarDetalle")
            );


            modal.show();


        });


    }


});




// =========================================
// CARGAR TABLA MODAL
// =========================================

function cargarDetalleModal(pagina = 1){


paginaDetalle = pagina;


let html="";


let inicio = 
(pagina - 1) * porPaginaDetalle;


let fin =
inicio + porPaginaDetalle;


let registros =
detalleEditar.slice(inicio, fin);



if(detalleEditar.length === 0){


html = `

<tr>

<td colspan="7" class="text-center text-muted">

<i class="bi bi-info-circle"></i>

No hay artículos agregados

</td>

</tr>

`;

document.getElementById("tablaEditarDetalle").innerHTML = html;

return;

}



registros.forEach((item,index)=>{


html += `


<tr data-id="${item.id}">


<td>
${inicio + index + 1}
</td>


<td>
${item.descripcion}
</td>


<td>
${item.unidad}
</td>


<td>

<input

type="number"

class="form-control cantidadEditar"

value="${item.cantidad}"

min="1"

${!puedeEditar ? "readonly" : ""}

>

</td>



<td>

<input

type="number"

class="form-control precioEditar"

value="${item.precio_venta}"

step="0.01"

${!puedeEditar ? "readonly" : ""}

>

</td>



<td class="importeEditar">

S/ ${parseFloat(item.importe).toFixed(2)}

</td>



<td>

${puedeEditar ? `

<button

class="btn btn-danger btn-sm eliminarEditar">

<i class="bi bi-trash"></i>

</button>

` : ""}


</td>


</tr>


`;


});


document.getElementById("tablaEditarDetalle").innerHTML = html;



crearPaginacionDetalle();


}

function crearPaginacionDetalle(){


let totalPaginas =
Math.ceil(
detalleEditar.length / porPaginaDetalle
);


let html="";


if(totalPaginas <= 1){

document.getElementById("paginacionDetalle").innerHTML="";

return;

}



html += `

<nav>

<ul class="pagination justify-content-center">

`;



for(let i=1;i<=totalPaginas;i++){


html += `

<li class="page-item 
${i==paginaDetalle?'active':''}">


<button

class="page-link paginaDetalle"

data-pagina="${i}">

${i}

</button>


</li>

`;


}



html += `

</ul>

</nav>

`;


document.getElementById("paginacionDetalle").innerHTML = html;


}
// =========================================
// ACTUALIZAR CANTIDAD ARTICULOS
// =========================================

function actualizarCantidadEditar(){

    let items = detalleEditar.length;

    let unidades = 0;

    detalleEditar.forEach(function(item){

        unidades += parseFloat(item.cantidad) || 0;

    });

    document.getElementById("cantidadArticulos").textContent = items;

    document.getElementById("cantidadUnidades").textContent =
        unidades.toFixed(2);

}





// =========================================
// CAMBIO CANTIDAD / PRECIO
// =========================================


document.addEventListener("input",function(e){


if(!puedeEditar){
    return;
}


if(

e.target.classList.contains("cantidadEditar")

||

e.target.classList.contains("precioEditar")

){


let fila = e.target.closest("tr");


let id = fila.dataset.id;



let item = detalleEditar.find(

x => x.id == id

);



if(item){



item.cantidad =

parseFloat(
fila.querySelector(".cantidadEditar").value
) || 1;



item.precio_venta =

parseFloat(
fila.querySelector(".precioEditar").value
) || 0;



item.importe =
item.cantidad * item.precio_venta;

fila.querySelector(".importeEditar").innerHTML =
"S/ " + item.importe.toFixed(2);

// Actualizar resumen
calcularResumenEditar();

// Actualizar Items / Unidades
actualizarCantidadEditar();



}



}



});





// =========================================
// CALCULAR TOTALES
// =========================================


function calcularResumenEditar(){


let subtotal = 0;


detalleEditar.forEach(function(item){

    subtotal += parseFloat(item.importe) || 0;

});


let igv = subtotal * 0.18;


let total = subtotal + igv;



document.getElementById("txtSubtotal").innerHTML =
subtotal.toFixed(2);


document.getElementById("txtIGV").innerHTML =
igv.toFixed(2);


document.getElementById("txtTotal").innerHTML =
total.toFixed(2);


// actualizar inputs para PHP

document.getElementById("inputSubtotal").value =
subtotal.toFixed(2);


document.getElementById("inputIGV").value =
igv.toFixed(2);


document.getElementById("inputTotal").value =
total.toFixed(2);


}


// =========================================
// ELIMINAR ARTICULO
// =========================================


document.addEventListener("click",function(e){

if(!puedeEditar){
    return;
}

if(e.target.closest(".eliminarEditar")){



let fila = e.target.closest("tr");



let id = fila.dataset.id;



detalleEditar = detalleEditar.filter(

item => item.id != id

);



// actualizar tabla

cargarDetalleModal();

// actualizar resumen

calcularResumenEditar();

// actualizar Items / Unidades

actualizarCantidadEditar();

// volver a habilitar botones del buscador

marcarArticulosAgregados();



}



});

// =========================================
// ENVIAR DETALLE AL FORMULARIO
// =========================================

const formularioEditar =
document.getElementById("formEditarCotizacion");


if(formularioEditar){


formularioEditar.addEventListener("submit",function(event){

const contenedor =
document.getElementById("inputsDetalle");

if(detalleEditar.length === 0){

    alert("Debe agregar al menos un artículo.");

    event.preventDefault();

    return;

}

contenedor.innerHTML="";



detalleEditar.forEach(function(item){


contenedor.innerHTML += `


<input type="hidden"
name="articulo_id[]"
value="${item.id}">


<input type="hidden"
name="descripcion[]"
value="${item.descripcion}">


<input type="hidden"
name="unidad[]"
value="${item.unidad}">


<input type="hidden"
name="cantidad[]"
value="${item.cantidad}">


<input type="hidden"
name="precio_venta[]"
value="${item.precio_venta}">


<input type="hidden"
name="importe[]"
value="${item.importe}">


`;


});


});


}

// =========================================
// CANCELAR EDICIÓN
// =========================================

const btnCancelarEdicion =
document.getElementById("btnCancelarEdicion");


if(btnCancelarEdicion){


    btnCancelarEdicion.addEventListener("click",function(){


        window.location.href =
            "ver.php?id=" + idCotizacion;


    });


}

// =========================================
// MODAL CLIENTES
// =========================================

const btnBuscarCliente =
document.getElementById("btnBuscarCliente");

const modalClientesElemento =
document.getElementById("modalClientes");

let modalClientes;

if(modalClientesElemento){

    modalClientes =
    new bootstrap.Modal(modalClientesElemento);

}

if(btnBuscarCliente){

    btnBuscarCliente.addEventListener("click",function(){

        cargarClientes();

        modalClientes.show();

    });

}
// =========================================
// CARGAR CLIENTES CON PAGINACIÓN
// =========================================

let paginaClientes = 1;


function cargarClientes(pagina = 1){


    paginaClientes = pagina;


    fetch(

        "buscar_clientes.php?buscar=" +

        encodeURIComponent(

            document.getElementById("buscarCliente").value

        )

        +

        "&pagina=" + pagina


    )

    .then(res => res.text())

    .then(html => {


        document.getElementById("listaClientes").innerHTML = html;


        cargarPaginacionClientes(pagina);


    });


}

// =========================================
// PAGINACIÓN CLIENTES
// =========================================


function cargarPaginacionClientes(pagina = 1){

    fetch(

        "paginacion_clientes_cotizacion.php?buscar=" +

        encodeURIComponent(

            document.getElementById("buscarCliente").value

        )

        +

        "&pagina=" + pagina

    )


    .then(res => res.text())


    .then(html => {


        document.getElementById(
            "paginacionClientesCotizacion"
        ).innerHTML = html;


    });


}

const buscarCliente =
document.getElementById("buscarCliente");

if(buscarCliente){

    buscarCliente.addEventListener("keyup",function(){

        cargarClientes(1);

    });

}

// =========================================
// SELECCIONAR CLIENTE
// =========================================

document.addEventListener("click",function(e){

    const boton =
    e.target.closest(".seleccionarCliente");


    if(!boton){
        return;
    }


    // ID CLIENTE

    document.getElementById("cliente_id").value =
    boton.dataset.id;



    // NOMBRE CLIENTE

    document.getElementById("clienteNombre").value =
    boton.dataset.nombre;



    // RUC CLIENTE

    document.getElementById("clienteRuc").value =
    boton.dataset.ruc;



    modalClientes.hide();


});




// =========================================
// MODAL ARTÍCULOS
// =========================================

const btnAgregar =
document.getElementById("btnAgregarArticulo");

const modalArticulosElemento =
document.getElementById("modalArticulos");

let modalArticulos;

if(modalArticulosElemento){

    modalArticulos =
    new bootstrap.Modal(modalArticulosElemento);

}

const buscarArticulo =
document.getElementById("buscarArticulo");

const familiaArticulo =
document.getElementById("familiaArticulo");

const unidadArticulo =
document.getElementById("unidadArticulo");

const listaArticulos =
document.getElementById("listaArticulos");

// =========================================
// CARGAR FILTROS
// =========================================

function cargarFiltros(){

    fetch("cargar_filtros_articulos.php")

    .then(res => res.json())

    .then(data => {

        familiaArticulo.innerHTML =
        '<option value="">Todas las familias</option>';

        data.familias.forEach(function(item){

            familiaArticulo.innerHTML +=
            `<option value="${item}">${item}</option>`;

        });

        unidadArticulo.innerHTML =
        '<option value="">Todas las unidades</option>';

        data.unidades.forEach(function(item){

            unidadArticulo.innerHTML +=
            `<option value="${item}">${item}</option>`;

        });

    });

}

// =========================================
// CARGAR ARTÍCULOS
// =========================================

function cargarArticulos(pagina = 1){

    fetch(

        "buscar_articulos.php?buscar=" +

        encodeURIComponent(buscarArticulo.value) +

        "&familia=" +

        encodeURIComponent(familiaArticulo.value) +

        "&unidad=" +

        encodeURIComponent(unidadArticulo.value) +

        "&pagina=" + pagina

    )

    .then(res => res.text())

    .then(html => {

        listaArticulos.innerHTML = html;

        marcarArticulosAgregados();

        cargarPaginacion(pagina);

    });

}

if(btnAgregar){

    btnAgregar.addEventListener("click", function(){

        cargarFiltros();

        cargarArticulos();

        modalArticulos.show();

    });

}

if(buscarArticulo){

    buscarArticulo.addEventListener("keyup", function(){

        cargarArticulos(1);

    });

}

if(familiaArticulo){

    familiaArticulo.addEventListener("change", function(){

        cargarArticulos(1);

    });

}

if(unidadArticulo){

    unidadArticulo.addEventListener("change", function(){

        cargarArticulos(1);

    });

}

document.addEventListener("click", function(e){

    const boton = e.target.closest(".paginaArticulo");

    if(!boton){
        return;
    }

    e.preventDefault();

    cargarArticulos(boton.dataset.pagina);

});

// =========================================
// CARGAR PAGINACIÓN
// =========================================

function cargarPaginacion(pagina = 1){

    fetch(

        "paginacion_articulos.php?buscar=" +

        encodeURIComponent(buscarArticulo.value) +

        "&familia=" +

        encodeURIComponent(familiaArticulo.value) +

        "&unidad=" +

        encodeURIComponent(unidadArticulo.value) +

        "&pagina=" + pagina

    )

    .then(res => res.text())

    .then(html => {

        document.getElementById("paginacionArticulos").innerHTML = html;

    });

}

// =========================================
// CLICK EN AGREGAR ARTÍCULO
// =========================================

document.addEventListener("click", function(e){

    if(!e.target.closest(".agregarArticulo")){

        return;

    }

    const boton = e.target.closest(".agregarArticulo");

    let agregado = agregarArticuloEditar({

        id: boton.dataset.id,

        codigo: boton.dataset.codigo,

        descripcion: boton.dataset.descripcion,

        unidad: boton.dataset.unidad,

        venta: boton.dataset.venta

    });

    if(agregado){

        marcarArticulosAgregados();

    }

});

// =========================================
// AGREGAR ARTÍCULO AL DETALLE
// =========================================

function agregarArticuloEditar(articulo){

    let existe = detalleEditar.find(function(item){

        return item.id == articulo.id;

    });

    // Si ya existe, aumenta la cantidad
if(existe){

    existe.cantidad++;

    existe.importe =
        existe.cantidad * existe.precio_venta;

    actualizarCantidadEditar();
    calcularResumenEditar();

    if(document.getElementById("modalEditarDetalle").classList.contains("show")){

        cargarDetalleModal(paginaDetalle);

    }

    // Pequeño feedback visual
    boton.classList.add("btn-warning");

    setTimeout(function(){

        boton.classList.remove("btn-warning");

    },300);

    return true;

}

    // Si es nuevo

    detalleEditar.push({

        id: articulo.id,
        codigo: articulo.codigo,
        descripcion: articulo.descripcion,
        unidad: articulo.unidad,
        cantidad:1,
        precio_venta:parseFloat(articulo.venta),
        importe:parseFloat(articulo.venta)

    });

    actualizarCantidadEditar();

    calcularResumenEditar();

    marcarArticulosAgregados();

    return true;

}

// =========================================
// MARCAR ARTÍCULOS YA AGREGADOS
// =========================================

function marcarArticulosAgregados(){

    document.querySelectorAll(".agregarArticulo").forEach(function(boton){

        let id = boton.dataset.id;

        let existe = detalleEditar.some(function(item){

            return item.id == id;

        });

        if(existe){

            boton.disabled = true;

            boton.classList.remove("btn-success");

            boton.classList.add("btn-secondary");

            boton.innerHTML =
            '<i class="bi bi-check-lg"></i>';

        }else{

            boton.disabled = false;

            boton.classList.remove("btn-secondary");

            boton.classList.add("btn-success");

            boton.innerHTML =
            '<i class="bi bi-plus-lg"></i>';

        }

    });

}

document.addEventListener("click",function(e){


let boton =
e.target.closest(".paginaDetalle");


if(!boton){
    return;
}


cargarDetalleModal(
    parseInt(boton.dataset.pagina)
);


});

// =========================================
// CLICK PAGINACIÓN CLIENTES
// =========================================

document.addEventListener("click",function(e){

    const boton = e.target.closest(".paginaClienteCotizacion");

    if(!boton){
        return;
    }

    e.preventDefault();

    e.stopPropagation();

    cargarClientes(parseInt(boton.dataset.pagina));

});
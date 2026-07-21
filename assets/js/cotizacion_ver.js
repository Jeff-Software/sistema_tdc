document.addEventListener("DOMContentLoaded", function () {


    const modalElemento =
        document.getElementById("modalDetalleCotizacion");


    const boton =
        document.getElementById("btnAbrirDetalle");


    if (!modalElemento || !boton) return;


    const modal = new bootstrap.Modal(modalElemento);



    boton.addEventListener("click", function () {

        modal.show();

    });



    // =====================================
    // PAGINACION DETALLE COTIZACION
    // =====================================


    const filas =
        document.querySelectorAll(
            "#tablaDetalleCotizacion tr"
        );


    const contenedor =
        document.getElementById(
            "paginacionDetalle"
        );


    if(!filas.length || !contenedor) return;



    let paginaActual = 1;


    const registrosPorPagina = 10;


    const totalPaginas =
        Math.ceil(
            filas.length / registrosPorPagina
        );



    function mostrarPagina(pagina){


        paginaActual = pagina;


        filas.forEach(function(fila,index){


            let inicio =
            (pagina - 1) * registrosPorPagina;


            let fin =
            inicio + registrosPorPagina;



            if(index >= inicio && index < fin){

                fila.style.display="";

            }else{

                fila.style.display="none";

            }


        });


        crearBotones();

    }



    function crearBotones(){


        contenedor.innerHTML="";


        for(let i=1;i<=totalPaginas;i++){


            let boton =
            document.createElement("button");


            boton.className =
            "btn btn-sm btn-outline-success mx-1";


            boton.textContent=i;



            boton.addEventListener(
                "click",
                function(){

                    mostrarPagina(i);

                }
            );



            contenedor.appendChild(boton);

        }


    }



    mostrarPagina(1);


});
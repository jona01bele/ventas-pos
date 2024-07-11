<script>


//configuracion de scroll para cuando existan muchos productos en el carrito
// esta clase : tblscroll esta agragada en la tabla.. ya que el scroll se va anexar a ella.
    $(.tblscroll).nicescroll({

        cursorcolor: "#515365",
        cursorwidth: "30px",
        background: "rgba(20,20,20,0.3)",
        cursorborder: "0px",
        corsorborderradius: 3
    })

    // comfirmacion de eliminar un poco mas dimanica a diferencia de categoria
    function Confirm(id , nombreEvento, texto) {
        swal({
            title: 'CONFIRMA',
            text: texto,
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            console.log('hola')
            if (result.value) {
                console.log(result)
                // Emitir el evento 'eliminarFila' con el ID de la categoría
                // @this.dispatchSelf('eliminarFila', id);
                @this.dispatch(nombreEvento, {id});
                swal.close();
            }

        });
    };
    

</script>
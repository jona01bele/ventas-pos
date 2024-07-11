{{-- <script>
    


    //libreria para escuchar todos los eventos del teclado--- tabaja a traves de listener
    //pagina oficial: https://github.com/dmauro/Keypress

    var listener = new window.keypress.Listener();

    //tecla f9 para guardar la venta

    listener.simple_combo("f9", function(){
        @this.dispatch('guardarVenta');
    })

    //tecla f8 limmpiar el contenido de la caja de efectivo y colocar el focus

    listener.simple_combo("f8", function(){
        document.getElementBYId('dinero').value =""
        ocument.getElementBYId('dinero').focus()
    })

    // tecla F4 para cancelar la
    listener.simple_combo("f4", function(){
        var total = parceFloat(document.getElementById('hiddenTotal').value)
        if(total > 0){

            // que tipo de parametros resive 
            // 1. id, 2)nombre del evento, 3) mensaje
            Confirm(0, 'limpiarCarrito', 'SEGURO DE ELIMINAR ESTE CARRITO' )
        }
        else{
            noty('AGREGA PRODUCTOS A LA VENTA')
        }
    })



</script> --}}
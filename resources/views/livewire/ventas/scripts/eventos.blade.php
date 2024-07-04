<script>

document.addEventListener('DOMContentLoaded', function(){
        console.log('Livewire is fully initialized');


        // Eventos personalizados de Livewire
        window.addEventListener('busqueda-ok', function(event) {
            noty(event)
        });
        // Eventos personalizados de Livewire
        //se coloca el 2 para que pueda emitir un mensaje de tipo alert o warning
        window.addEventListener('Producto-no-existe', function(event) {
            noty(event , 2)
        });
        // Eventos personalizados de Livewire
        window.addEventListener('No-stock', function(event) {
            noty(event, 2)
        });
        window.addEventListener('venta-error', function(event) {
            noty(event)
        });

        window.addEventListener('imprimir-tickect', function(ventaId) {
            window.open("print://" + ventaId, '_blank' )
        });

    });

</script>
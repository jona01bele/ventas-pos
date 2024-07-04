

    

<ul class="navbar-item flex-row search-ul">
    <li class="nav-item align-self-center search-animated">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-search toggle-search">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <form class="form-inline search-full form-inline search" role="search">
            <div class="search-bar">
                <input type="text" 
                    {{-- wire:keydown.enter="$dispatch('escanearCodigo')" --}}
                    
                   
                    {{-- wire:click="$dispatch('escanear-codigo', $('#codigo').val('hola-mundo'))" --}}
              
                     wire:keydown.enter="escanearCodigo($event.target.value)"
                    
                 {{-- wire:keydown.enter="$dispatch('escanearCodigo', $('#codigo').val())" --}}
                    class="form-control search-form-control  ml-lg-auto" placeholder="Search...">



                  

                    
            </div>
        </form>
    </li>
</ul>




<script>
    // document.addEventListener('livewire:initialized', () => {
    //         console.log('Livewire is fully initialized');


    //         // Eventos personalizados de Livewire
    //         window.addEventListener('show-modal', function(event) {
    //             $('#theModal').modal('show');
    //         });


    //     });

    //  //ecuchar el Evento para limpiar la caja de texto
    // document.addEventListener('DOMContentLoaded', function(){

    //      // Eventos personalizados de Livewire
    // //    livewire.on('escanearCodigo', actions => {
    // //         $('#codigo').val('');
    // //    });


    // })
    // window.addEventListener('escanearCodigo', function(event) {
    //         $('#codigo').val('');
    //     });

    //este evento es para que se limpie la caja de testo guando el buscador realice su funcion
    document.addEventListener('livewire:init', () => {
        Livewire.on('escanearCodigo', (event) => {
            $('#codigo').val('');
        });
    });
</script>

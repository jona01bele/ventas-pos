<!--este es un modal que ha sido cortado y modificado en dos partes el header y el footer-->

<!--wire:ignore.self este codigo busca que le componente no se cierre cada vez que se renderice-->
<div wire:ignore.self class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white">
            <b>{{$componentName}}</b> | {{$selected_id > 0 ? 'EDITAR' : 'CREAR'}}
          </h5>
          {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button> --}}
          <!--la idea de este codigo es para que cuando el servidor tarde en dar la respuesta y a traves de la directiva loades (wire:loading)  de livewire aparezca el mensaje-->
          <h6 class="text-center text warning" wire:loading >Cargandgo...</h6>
        </div>
        <div class="modal-body">
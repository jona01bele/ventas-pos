</div>
<div class="modal-footer">

    <button type="button" wire:click.prevent="limpiar()" class="btn btn-light close-btn text-info" data-dismiss="modal">Cerrar</button>

    @if($seleccionar_id < 1)
        <button type="button" wire:click.prevent="store()" class="btn btn-secondary close-modal">Registrar</button>    
    @else
        <button type="button" wire:click.prevent="update()" class="btn btn-secondary close-modal">Actualizar</button>
    @endif


</div>
</div>
</div>
</div>

<!--#5c1ac3-->
</div>
<div class="modal-footer">
    <button type="button" wire.click.prevent="resetUI()" class="btn btn-secondary close-btn text-info" data-dismiss="modal">Cerrar</button>

    @if($Selected_id < 1)
        <button type="button" wire.click.prevent="Store()" class="btn btn-secondary close-modal" >Registrar</button>    
    @else
        <button type="button" wire.click.prevent="Update()" class="btn btn-secondary close-modal" >Actualizar</button>
    @endif
</div>
</div>
</div>
</div>

<!--#5c1ac3-->
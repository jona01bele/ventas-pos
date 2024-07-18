<!--incluir el modal que esta en comun-->
@include('comun.modalhead')
<!--Aca se crea el cuerpo-->

    <div class="row">
        <div class="col-sm-12">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="far fa-edit" ></span>
                    </span>
                </div>
                <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
                <input type="text" wire:model.lazy="nombre" placeholder="ej: Administrador" class="form-control">
            </div>
            

        </div>
                
    </div>
    @error('nombre') <span class="text-danger er ">{{$message}}</span> @enderror


@include('comun.modalfooter')





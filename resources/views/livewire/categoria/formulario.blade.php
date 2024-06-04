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
                <input type="text" wire:model.lazy="nombre" placeholder="ej: Cursos" class="form-control">
            </div>
        </div>
        @error('nombre') <span class="text-danger er">{{$mensaje}}</span>  @enderror       
        
        <div class="col-sm-12 mt-3">
            <div class="form-group custom-file">
                <input type="file" class="custom-file-input form-control" wire:model="imagen" accept="image/x-png, image/gif, image/jpeg">
                <label for="" class="custom-file-label">Imagen{{$imagen}}</label>
                @error('imagen') <span class="text-danger er">{{$mensaje}}</span>@enderror
            </div>
        </div>
    </div>
   

@include('comun.modalfooter')
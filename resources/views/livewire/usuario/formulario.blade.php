<!--incluir el modal que esta en comun-->
@include('comun.modalhead')
<!--Aca se crea el cuerpo-->

<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="form-group">
            <label>Nombre</label>
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="text" wire:model.lazy="nombre" placeholder="ej: Jonathan..." class="form-control">

            {{-- esta directiva muestra o prueba el comportaminto de los errores-->>  @dump($errors->all()) --}}
            @error('nombre')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Telefono</label>
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="text" wire:model.lazy="telefono" maxlength="10" placeholder="ej: 3057895654" class="form-control">
            @error('telefono')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Email</label>
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="text" wire:model.lazy="email" placeholder="ej: jonathan@gmail.com"
                class="form-control">
            @error('email')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password"  wire:model.lazy="password" 
                class="form-control">
            @error('password')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

  

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Estado</label>
            <select wire:model="estado" class="form-control">
                {{-- <option value="Elegir" disabled>Elegir</option> --}}
               {{-- con el selected es para que la primera opcion este seleccionado por defecto --}}
                <option value="Elegir" selected>Elegir</option>
                <option value="ACTIVO" >Activo</option>
                <option value="BLOQUEADO">Bloqueado</option>
            </select>
            @error('estado')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!--Este select es par asignar el rol-->

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Asignar Rol</label>
            <select wire:model="perfil" class="form-control">
                {{-- <option value="Elegir" disabled>Elegir</option> --}}
               {{-- con el selected es para que la primera opcion este seleccionado por defecto --}}
                <option value="Elegir" selected>Elegir</option>
                @foreach ($roles as $rol)
                    <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                @endforeach
            </select>
            @error('role')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>



    <div class="col-sm-12 mt-8">
        <div class="form-group custom-file">
            <input type="file" class="custom-file-input form-control" wire:model="imagen" {{-- filtrado de tipo de archivo --}}
                accept="image/x-png, image/gif, image/jpeg">
            <label class="custom-file-label">Imagen{{ $imagen }}</label>
            @error('imagen')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@include('comun.modalfooter')

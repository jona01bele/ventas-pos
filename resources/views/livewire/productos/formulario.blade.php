<!--incluir el modal que esta en comun-->
@include('comun.modalhead')
<!--Aca se crea el cuerpo-->

<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="form-group">
            <label>Nombre</label>
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="text" wire:model.lazy="nombre" placeholder="ej: Cursos..." class="form-control">
            @error('nombre')
                <span class="text-danger er">{{ $mensaje }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Codigo</label>
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="text" wire:model.lazy="codigo" placeholder="ej: 985645512" class="form-control">
            @error('codigo')
                <span class="text-danger er">{{ $mensaje }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Costo</label>
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="text" data-type="currency" wire:model.lazy="costo" placeholder="ej: $ 0.00"
                class="form-control">
            @error('costo')
                <span class="text-danger er">{{ $mensaje }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Precio</label>
            <!--data-type="currency" para que el usuario solo agregue valores numericos ya que se dejo tipo texto-->
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="text" data-type="currency" wire:model.lazy="precio" placeholder="ej: $ 0.00"
                class="form-control">
            @error('precio')
                <span class="text-danger er">{{ $mensaje }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>stock</label>
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="number" wire:model.lazy="stock" placeholder="ej: 0" class="form-control">
            @error('stock')
                <span class="text-danger er">{{ $mensaje }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Alertas</label>
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="number" wire:model.lazy="alertas" placeholder="ej: 10" class="form-control">
            @error('alertas')
                <span class="text-danger er">{{ $mensaje }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Categorias</label>
            <select class="form-control">
                <option value="Elegir" disabled></option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="col-sm-12 mt-8">
        <div class="form-group custom-file">
            <input type="file" class="custom-file-input form-control" wire:model="imagen"
              {{-- filtrado de tipo de archivo --}}
                accept="image/x-png, image/gif, image/jpeg">
            <label class="custom-file-label">Imagen{{ $imagen }}</label>
            @error('imagen') <span class="text-danger er">{{ $mensaje }}</span>@enderror
        </div>
    </div>
</div>

@include('comun.modalfooter')

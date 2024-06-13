<!--incluir el modal que esta en comun-->
@include('comun.modalhead')
<!--Aca se crea el cuerpo-->

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>DENOMINACIÓN</label>
            <select wire:model="tipomoneda" class="form-control">
                <option value="Elegir" disabled>Elegir</option>
                <option value="BILLETE">BILLETE</option>   
                <option value="MONEDA">MONEDA</option>
                <option value="OTRO">OTRO</option>
                
            </select>
            {{-- esta directiva muestra o prueba el comportaminto de los errores-->>  @dump($errors->all()) --}}
            @error('tipomoneda')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm12 col-md-6">
        <div class="form-group">
            <label>VALOR</label>
            <!--el lazy busca que no se renderice sino hasta que se pierda el foco de la caja de texto-->
            <input type="number"  wire:model.lazy="valor" placeholder="ej: 100,00" maxlength="15"
                class="form-control">
            @error('valor')
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

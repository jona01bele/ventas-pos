<!--Buscador para utilizar en todos los componentes de nuestro proyecto-->
<div class="row justify-content-between">
    <!--para resoluciones largar 4 columnas , medianas 4 y pequeñas 12-->
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <span class="input-group-text inuput-gp">
                     <i class="fas fa-seach"></i>
                 </span>     
             </div>
             <input type="text" wire:modal="buscar" placeholder="Buscar" class="form-control">
        </div>
        
    </div>
</div>
<!--Buscador para utilizar en todos los componentes de nuestro proyecto-->
<div class="row justify-content-between">
    <!--para resoluciones largar 4 columnas , medianas 4 y pequeñas 12-->
    <div class="col-lg-4 col-md-4 col-sm-12" >
        <div class="input-group mb-4">
            <div class="input-group-prepend" >
                <span class="input-group-text input-gp"  style="background: #5c1ac3">
                    <svg xmlns="http://www.w3.org/2000/svg" style="color: aliceblue" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zoom-in"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg> 
                    
                 </span>     
             </div>
          
             <input type="text" wire:model.live="buscador" placeholder="Buscar" class="form-control">           
        </div>
        
    </div>
</div>
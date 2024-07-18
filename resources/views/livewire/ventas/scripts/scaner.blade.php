<script>
    try {

        // Initialize with options
        onScan.attachTo(document, {
            suffixKeyCodes: [13], // enter-key expected at the end of a scan // el 13 representa la tecla enter
            //reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)

            // funcion que va verificar el codigo de barras
            onScan: function(codigoBarras) { // Alternative to document.addEventListener('scan')
                console.log('Scanned: ' + codigoBarras);
                //emitir el evento
                @this.dispatch('escanearCodigo', codigoBarras);
            },
            // funcion que permite controlar los errores de la lectura
            onScanError: function(e) { // output all potentially relevant key events - great for debugging!
                console.log(e);
            }
        })
        console('Sacnner Listo')

    } catch(e){

        console('Error de lectura', e)
    }
</script>

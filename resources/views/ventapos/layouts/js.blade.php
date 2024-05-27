 <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
 <script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js')}}"></script>
 <!--Popper.js es una biblioteca que se utiliza para posicionar elementos dinámicos , como tooltips, popovers y menús desplegables.-->
 <script src="{{ asset('bootstrap/js/popper.min.js')}}"></script>
 <script src="{{ asset('bootstrap/js/bootstrap.min.js')}}"></script>
 <!--Personalizar las barras de desplazamiento: que se usan para navegar por contenido que no cabe en la pantalla-->
 <script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>

 <script src="{{ asset('assets/js/app.js')}}"></script>
 <script>
     $(document).ready(function() {
         App.init();
     });
 </script>
 <!--este escript si va utilizar mas adelante en el proyecto-->
 <script src="{{ asset('assets/js/custom.js')}}"></script>
 <!--Incluir tu código personalizado en un archivo separado como custom.js y entre otras-->
 <script src="{{ asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
 <script src="{{ asset('plugins/notification/snackbar/snackbar.min.js')}}"></script>
 <script src="{{ asset('plugins/nicescroll/nicescroll.js')}}"></script>
 <!--Es probable que currency.js permita dar formato a los números como valores monetarios-->
 <script src="{{ asset('plugins/currency/currency.js')}}"></script>

 <script src="{{asset('plugins/font-icons/fontawesome/js/all.min.js')}}"></script>

  <!-- END GLOBAL MANDATORY SCRIPTS -->
 
 <!-- END GLOBAL MANDATORY SCRIPTS -->

 <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
 <!-- Son scrpts para graficos que no se van a utilizar por lo pronto-->
 {{-- <script src="{{ asset('plugins/apex/apexcharts.min.js')}}"></script>
 <script src="{{ asset('assets/js/dashboard/dash_2.js')}}"></script> --}}
 <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
    // Esta funcion busca enviar notificaciones a lo largo del proyecto donde se requiera
    function noty(msg, option = 1){
        snackbar.show({
            text: msg.toUpperCase(),
            actionText: 'CERRAR',
            actionTextColor: '#fff',
            backgroundColor: option == 1 ? '#3b3f5c' : '#e7515a',
            pos: 'top-rigth'
        });
    }
  </script>

  @livewireScripts
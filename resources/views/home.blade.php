@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Panel de Inicio</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if(Auth::user()->hasRole('admin'))
                                <div>Acceso como administrador</div>
                        @else
                                <div >Acceso usuario</div>
                        @endif
                        Tu estás dentro!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">

        function realizaProceso(valorCaja1, valorCaja2){
            var parametros = {
                "valorCaja1" : valorCaja1,
                "valorCaja2" : valorCaja2
            };
            $.ajax({
                data:  parametros,
                url:   'ejemplo_ajax_proceso.php',
                type:  'post',
                beforeSend: function () {
                        $("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) {
                        $("#resultado").html(response);
                }
            });
        }
        
        $(document).ready(function() {
            $('.card').on( "click", function(){
                console.log('paso');       
            });
        });

    </script>
@endsection
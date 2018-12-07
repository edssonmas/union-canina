<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <link rel="stylesheet" href="../css/lightspeedin.css">
    <link rel="stylesheet" href="../css/fadein.css">
    <link rel="stylesheet" href="../css/fadeinright.css">
    <link rel="stylesheet" href="../css/fadeindown.css">
    <link rel="stylesheet" href="../css/bounceindown.css">
<style>
    #conversacion{
        background-color: #fff;
        margin:0;
        width: 100%;
        height: 100%;
       overflow-y: hidden;
    }
    #header{
        background-color: white;
        border-bottom: 1px solid #d9d9d9;
        margin: 0;
        width: 100%;
        height: 50px;
    }
    #back{
        background-color: #d9d9d9;
        float: left;
        width: 30px;
        height: 100%;
        color: white;
        align-items: center;
    }
    #back:hover{
        background-color: #4a4a4a;
    }
    #back p{
        position: relative;
        top: 35%;
        margin-left: 5px;
    }
    #usuario{
        float: left;;
        width: auto;
        padding: 3px;
        height: 100%;
    }
    #usuario img{
          width: 45px;
         height: 100%;
         border-radius: 50px;
         margin-right:10px;
         border:1px solid #d9d9d9;
    }
    #caja-texto{
        position: absolute;
        bottom: 0;
        left:0;
        background-color: #E6FFFD;
        border-top: 1px solid #d9d9d9;
        height: 50px;
        width: 100%;
        padding: 5px;
    }
    #mensajes{
        width: 100%;
        background-color: transparent;
        height: 82%;
        max-height: 82%;
        overflow-y: auto;
        padding: 10px;
    }
    #mensaje-remitente{
        display:flex;
        width: 100%;
        height: auto;
        margin: 0;
        padding: 2px;
        
    }
    #mensaje-destinatario{
        display:table;
        width: 100%;
        height: auto;
        margin: 0;
        padding: 0px;
        
    }
    #mensaje{
        background-color: #EAEAEA;
        width: auto;
        max-width: 70%;
        padding-left: 15px; 
        padding-right: 10px;
        padding-top: 7px;
        padding-bottom: 5px;
        border-radius: 7px;
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;
        border-bottom-right-radius: 25px;
        border-bottom-left-radius: 8px;
    }
    #respuesta{
        float: right;
        background-color: #0DACE3;
        padding-left: 15px;
        padding-right: 10px;
        padding-top: 7px;
        padding-bottom: 7px;
        color: white;
        width: auto;
        max-width: 70%;
        border-top-left-radius: 25px;
        border-top-right-radius: 25px;
        border-bottom-left-radius: 25px;
        border-bottom-right-radius: 8px;
        margin-bottom: 5px;
    }
     @media only screen and (max-width: 600px) {
    
     }
</style>

 <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script language="javascript">
    $(document).ready(function() {
        $.ajaxSetup({
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
            });

        function EnviarMensaje () {
            var usuario = $('#m-user').val();
            var conversacion = $('#m-conver').val();
            var text = $('#m-text').val();
                $("#m-text").val('');
                $.post('/enviarMensaje', { usuario: usuario, conversacion: conversacion, mensaje: text }, function(data){
                    $("#mensajes").html(data);
                    $("#mensajes").animate({ scrollTop: $('#mensajes')[0].scrollHeight}, 1000);
                });            
            }

         $("#BtnEnviar").click(function(){
                    EnviarMensaje();
                });

    });
</script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
@php
     use App\Modelos\Usuario;
     use App\Modelos\Mensaje;
    $usuario = Session::get('usuario');
@endphp
<body style="background-color: #EEEEEE; overflow-y: hidden;" onLoad="setInterval('VerificarChat()',1000);">
    <div id="conversacion" class="animated fadein">
        <div id="header">
            <a id="back" href="{{ url('messages') }}"><p><i class="fas fa-chevron-left"></i></p></a>

            <div id="usuario">               
                <p> <img  src="{{ '/imagen/'. Usuario::find($conversacion->first()->pivot->participante)->foto }}" alt=""><b>{{ Usuario::find($conversacion->first()->pivot->participante)->nombre }}</b></p>
            </div>
        </div>
        <div id="mensajes">
           @foreach ($conversacion->first()->mensajes as $mensaje)
            @php
                if($mensaje->leido == 0 && $mensaje->id_usuario != $usuario->id){
                    $m = Mensaje::find($mensaje->id);
                    $m->leido = 1;
                    $m->save();
                }             
            @endphp
               @if ($mensaje->id_usuario == $usuario->id)
                   <div id="mensaje-destinatario">
                        <div id="respuesta">
                            <p style="margin:0;">{{ $mensaje->mensaje}}</p>
                        </div>
                   </div>
                @else
                    <div id="mensaje-remitente">
                        <div id="mensaje">
                            <p style="margin:0;">{{ $mensaje->mensaje }}</p>     
                        </div>        
                    </div>
               @endif
           @endforeach             
        </div>
            <div id="caja-texto">        
               {{-- <form action="{{ url('enviarMensaje') }}" method="post" id="conver">
                 {{ csrf_field() }} --}}
                    <div class="input-group mb-3">
                        <input type="hidden" name="conversacion" value="{{ $conversacion->first()->id }}" id="m-conver">
                        <input type="hidden" name="usuario" value="{{ $usuario->id }}" id="m-user">
                        <input type="text" class="form-control" placeholder="Escribir..." aria-describedby="button-addon2" name="mensaje" id="m-text" autocomplete="off">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="submit" id="BtnEnviar"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
               {{-- </form>                --}}
        </div>

    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    {{-- <script src="../js/jquery-3.3.1.slim.min.js"></script> --}}
<script>
    $("#mensajes").animate({ scrollTop: $('#mensajes')[0].scrollHeight}, 1000);
    $("#m-text").keypress(function(e) {
       if(e.which == 13) {
          $('#BtnEnviar').click();
       }
    });

     function ActualizarChat () {
            var usuario = $('#m-user').val();
            var conversacion = $('#m-conver').val();
            var text = $('#m-text').val();
                $.get('/actualizarChat', { usuario: usuario, conversacion: conversacion, mensaje: text }, function(data){
                    $("#mensajes").html(data);
                    $("#mensajes").animate({ scrollTop: $('#mensajes')[0].scrollHeight}, 1000);
                });            
            }

    function VerificarChat () {
            var conversacion = $('#m-conver').val();
            var mensajes = {{ $conversacion->first()->mensajes->count() }};
                $.get('/verificarChat', { conversacion: conversacion}, function(data){
                    if(data > mensajes)
                        ActualizarChat();
                });            
            }
</script>
</body>
</html>
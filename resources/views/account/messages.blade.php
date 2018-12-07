<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <link rel="stylesheet" href="../css/fadein.css">
    <link rel="stylesheet" href="../css/fadeinright.css">
    <link rel="stylesheet" href="../css/fadeindown.css">
    <link rel="stylesheet" href="../css/bounceindown.css">
    <link rel="stylesheet" href="../css/hint.css">
    <link rel="stylesheet" href="../css/hover.css">
<style>
    #mensaje{
        background-color: white;
        height: 80px;
        width: 100%;
        border-bottom: 1px solid #efefef;
        cursor: pointer;
    }
    #liga:hover{
       background-color: red;
      
    }
    #foto-usuario{
        float: left;
        background-color: transparent;
        height: 100%;
        width: 80px;
        padding: 5px;
    }
     #foto-usuario img{
         width: 100%;
         height: 100%;
         border-radius: 50px;
        border: 1px solid #d9d9d9;
     }
     #info-usuario{
        float: left;
        background-color: transparent;
        height: 100%;
        width: auto;
        padding: 5px;
     }
     #info-usuario p{
         font-size: 12px;
         color:#6E6E6E;
     }
     #fecha{
        float: right;
        background-color: transparent;
        height: 100%;
        width: auto;
        padding: 5px;
        text-align: right;
        color: #b6b6b6;
        padding-right: 10px;
        font-size: 12px;
     }
     #eliminar{
         font-size: 15px;
         background-color: transparent;
            color:tomato;
     }
     #eliminar:hover{
         color:crimson;
     }
     @media only screen and (max-width: 600px) {
         #ultimo{
             display:none;
         }
     }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
@php
    use App\Modelos\Usuario;
    $usuario = Session::get('usuario');
@endphp
<body style="background-color: #E6FFFD;"class="animated fadein" onload="setInterval('VerificarConversaciones({{ $usuario->id }})',1000)">
    @if (Session::has('conversaciones'))
        @foreach (Session::get('conversaciones') as $conversacion)
        {{-- <form action="{{ url('conversacion/'.$conversacion->id.'') }}" method="post" style="margin-bottom:0;" id="verConversacion">
                          {{ csrf_field() }} --}}
              @php
              $color="#FFF";
              $nuevo = false;
              $font="";
                  if ($conversacion->mensajes->last()->id_usuario != $usuario->id && $conversacion->mensajes->last()->leido == 0)
                        {$color = "#FFF6CA"; $nuevo=true; $font="bold";}
              @endphp
            <a href="{{ url('conversacion/'.$conversacion->id.'') }}" style="text-decoration:none;" id="liga">
                <div id="mensaje" style="background-color:{{ $color }}">
                    <div id="foto-usuario">
                        <img  src="{{ '/imagen/'. Usuario::find($conversacion->pivot->participante)->foto }}" alt="">
                    </div>
                    <div id="info-usuario">
                        <h6><b>{{ Usuario::find($conversacion->pivot->participante)->nombre }}</b> 
                            @if ($nuevo)
                                <span class="badge badge-success" style="font-size:10px;">Nuevo mensaje</span>
                            @endif</h6>
                            <p id="ultimo" style="font-weight:{{ $font }}"> 
                            <b>
                                @php
                                    if($conversacion->mensajes->last()->id_usuario == $usuario->id)
                                        echo "Yo:";
                                    else
                                        echo Usuario::find($conversacion->mensajes->last()->id_usuario)->nombre.": ";
                                @endphp
                            </b>
                                {{ $conversacion->mensajes->last()->mensaje }}
                            </p>
                            </div>
                                <div id="fecha">
                                    <p style="color: #B0B0B0;"><i class="far fa-clock"></i> {{  $conversacion->mensajes->last()->fecha }}</p>
                                    <a class="hint--left hint--rounded hint--bounce" id="eliminar" aria-label="Eliminar conversacion"
                                        href="{{ url('eliminarConversacion/'.$conversacion->id.'') }}">
                                        <i class="fas fa-trash-alt"></i></a>
                                </div>
                            <input type="hidden" value="{{ $conversacion->id }}" name="id">                                   
                </div>
            </a>              
        {{-- </form>   --}}
        @endforeach
    @endif  
    <script src="../js/popper.min.js"></script>
    {{-- <script src="../js/jquery-3.3.1.slim.min.js"></script> --}}
    <script src="../js/bootstrap.js"></script>
<script>
         function VerificarConversaciones (usuario) {
                $.get('/VerificarConversaciones', { usuario: usuario}, function(data){
                    if(data != "")
                        $('body').html(data);
                });            
            }
</script>
</body>
</html>
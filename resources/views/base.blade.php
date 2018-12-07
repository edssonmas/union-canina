<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" 
    integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" 
    crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Anton|Merriweather+Sans|Oswald|Roboto" rel="stylesheet">
    @section('titulo')<title>Document</title>@show
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/hint.css">
    <link rel="stylesheet" href="css/hover.css">
    <link rel="stylesheet" href="css/fadeinup.css">
    <link rel="stylesheet" href="css/fadeindown.css">
     <link rel="stylesheet" href="css/ihover.css">
    @section('links')@show
</head>

<body style="overflow-x: auto;">

    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active-nav">
                        <a class="nav-link active-link" id="menu-link-active" href="/home"><i class="fas fa-home"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="menu-link" href="/pets"><i class="fas fa-dog"></i> Mascotas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="menu-link" href="" data-toggle="modal" data-target="#mensajesModal">
                            <i class="far fa-envelope"></i> Mensajes</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Buscar por codigo #" aria-label="Search">
                    <button class="btn btn-primary my-2 my-sm-0 Roboto" type="submit"><i class="fas fa-search"></i> Buscar</button>
                </form>
            </div>
            <a class="navbar-brand hint--bottom hint--rounded hint--bounce" aria-label="Inicio" href="#">
                <img src="/img/logo-light.png" alt="" width="30" height="30">
            </a>
        </div>
    </nav>
    <!-- Mensajes Modal -->
<div class="modal fade bd-example-modal-lg" id="mensajesModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b><i class="fas fa-inbox"></i> Mensajes</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="mensajes" style="padding:0; margin:0;">
                {{-- <iframe src="{{ url('pets') }}" style="width:100%; height:100%; border: none; margin: 0;"></iframe>
                --}}
                <object data="{{ url('messages') }}" type="" height="100%" width="100%" style="border-bottom-left-radius: 10px; 
            border-bottom-right-radius: 10px;"></object>
            </div>
        </div>
    </div>
</div>

    @section('contenido')
    @show

    <script src="js/jquery-3.3.1.slim.min.js"></script>
    <script src="js/jquery.min.js"></script> 
    <script src="js/jquery.Jcrop.js"></script>
    <script src="js/backstrech.js"></script>
    <script src="js/popper.min.js"></script>  
    <script src="js/bootstrap.min.js"></script>

    @section('scripts')
    @show
</body>

</html>

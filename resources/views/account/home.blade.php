@extends('base')

@section('titulo')<title>Union Canina: Home</title>@endsection
@section('links')
<link rel="stylesheet" href="../css/home.css">
<link rel="stylesheet" href="../css/jquery.Jcrop.css" type="text/css" />
@endsection

@php
use App\Modelos\Usuario;
use App\Modelos\Raza;
$usuario = Session::get('usuario');
$extravios = Session::get('extravios');
$conversaciones = Session::get('conversaciones');
$mascotas = Session::get('mascotas');
$razas = Raza::all();
@endphp

@section('contenido')
<!-- Modal para Editar informacion del usuario-->
<div class="modal fade" id="edit-perfil" tabindex="-1" role="dialog" aria-labelledby="m_editar" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="m_editar"><b><i class="fas fa-user-edit"></i> Editar Perfil</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form-editar">
                <form action="{{ url('editar-perfil') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <p>Nombre</p>
                    <input class="text-box" type="text" name="nom" value="{{ $usuario->nombre }}" autocomplete="off">
                    <p>Apellido Paterno</p>
                    <input class="text-box" type="text" name="apaterno" value="{{ $usuario->apat }}" autocomplete="off">
                    <p>Apellido Materno</p>
                    <input class="text-box" type="text" name="amaterno" value="{{ $usuario->amat }}" autocomplete="off">
                    <p>Correo Electrónico</p>
                    <input class="text-box" type="text" name="email" value="{{ $usuario->correo }}" autocomplete="off">
                    <p>Nueva Contraseña</p>
                    <input class="text-box" type="text" name="newpass" value="" autocomplete="off">
                    <p>Foto de Perfil</p>
                    <input class="text-editar" type="file" name="foto_perfil" style="font-size:12px;">
                    <hr>
                    <p>Confirmar cambios</p>
                    <input class="text-box" type="password" name="oldpass" value="" placeholder="Contraseña actual">
                    <button type="submit" class="btn btn-primary btn-guardar"><i class="fas fa-save" style="margin-right: 10px;"></i>
                        Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Contactar Modal -->
<div class="modal fade" id="contactarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dueño"><b>Contactar al dueño(a)</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('contactar') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" id="destinatario" name="destinatario">
                    <input type="hidden" id="remitente" name="remitente" value="{{ $usuario->id }}">
                    <input type="text" placeholder="mensaje" class="form-control" name="mensaje" style="margin-bottom:10px;">
                    <button type="submit" class="btn btn-primary float-right">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Modal para recortar foto de perfil-->
<div class="modal fade" id="cut_foto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    style=" text-align: center; padding: 0 !important;">
    <div class="modal-dialog" role="document" style="display:inline-table">
        <div class="modal-content" style="height: auto; width: max-content;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><b><i class="fas fa-crop"></i> Recortar Imagen</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 0; margin: 0; width: auto; height: auto;">
                <!-- This is the image we're attaching Jcrop to -->
                <img src="{{url('/imagen/'.$usuario->foto )}}" id="cropbox" class="table-responsive">

                <!-- This is the form that our event handler fills -->
                <form action="{{ url('/cutpic') }}" method="post" onsubmit="return checkCoords();" style="display:none;">
                    {{ csrf_field() }}
                    <input type="hidden" id="x" name="x" />
                    <input type="hidden" id="y" name="y" />
                    <input type="hidden" id="w" name="w" />
                    <input type="hidden" id="h" name="h" /><br>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-cut"></i> Recortar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Modal para ver foto de mascota-->
<div class="modal fade" id="verminiatura" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    style=" text-align: center; padding: 0 !important;">
    <div class="modal-dialog" role="document" style="display:inline-table">
        <div class="modal-content" style="height: auto; width: max-content; background-color: transparent; border:none;">
            <div class="modal-body" style="padding: 0; margin: 0; width: auto; height: auto;">
                <!-- This is the image we're attaching Jcrop to -->
                <img src="" id="cropbox" style="border-radius:10px;">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div id="main">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-3 noPad-noMarg">
                    <div id="left">
                        <div class="user-card">
                            <img class="user-card-pb" src="{{ '/imagen/'. $usuario->foto }}" alt="">
                            <div class="user-card-img"></div>
                            <div class="user-card-cont">
                                <div class="user-card-title Roboto">{{ $usuario->nombre .' '.$usuario->apat .'
                                    '.$usuario->amat }}</div>
                                <p class="user-email">{{ $usuario->correo }}</p>
                                <p class="user-card-text"><i class="fas fa-paw"></i> {{ $mascotas->count() }}
                                    mascotas registradas</p>

                                <p id="unido"><i class="far fa-calendar-alt"></i> Unido el: <br>{{
                                    $usuario->fecha_registro }}</p>
                            </div>
                            <div class="btn-row" id="eliminar">
                                <button class="hint--top hint--rounded hint--bounce" data-toggle="modal" data-target="#edit-perfil"
                                    aria-label="Editar perfil">
                                    <i class="fas fa-edit edit-btn"></i>
                                </button>

                                <button class="hint--top hint--rounded hint--bounce" data-toggle="modal" data-target="#cut_foto"
                                    aria-label="Recortar foto   ">
                                    <i class="fas fa-cut"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 noPad-noMarg">
                    <div id="center">
                        <div class="container noPad-noMarg">
                            @foreach ($extravios as $extravio)
                            <div class="row card-media">
                                <!-- media container -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-4 card-media-object-container noPad-noMarg">
                                    <div class="card-media-object" style="background-image: url({{ '/pppic/' . $extravio->mascota->foto }})"></div>
                                    <ul class="card-media-object-social-list">
                                        <li>
                                            <img src="{{ '/imagen/'. $extravio->mascota->usuario->foto }}" class="">
                                        </li>
                                    </ul>
                                </div>
                                <!-- body container -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-8 card-media-body noPad-noMarg">
                                    <div class="card-media-body-top" style="margin-bottom:0;">
                                        <span class="subtle"><i class="far fa-clock"></i> {{ $extravio->f_extrav }}</span>
                                        <div class="card-media-body-top-icons u-float-right">
                                            <span class="badge badge-success">{{ $extravio->mascota->nombre }}</span>
                                        </div>
                                    </div>
                                    <div style="margin-top:0; max-width: 90%; overflow-y: auto;">
                                        <span class="card-media-body-heading" style="margin-top:1px; float:left;">
                                            <p style="font-size:11px;">
                                                Raza: {{ $extravio->mascota->raza->nombre }} <br>
                                                Color: {{ $extravio->mascota->color }} <br>
                                                <a href=""><i class="fas fa-info-circle"></i> Más informacion</a>
                                            </p>
                                        </span>
                                        @forelse ($extravio->mascota->fotografias as $foto)

                                                <div class="foto" data-foto="{{ $foto }}" data-toggle="modal" data-target="#verminiatura">
                                                    <img src="{{ '/petpic/'.$foto->foto }}" alt="">
                                                </div>

                                        @empty
                                            <p style="color:#d9d9d9;font-size:12px; float:right; margin-top: 10px;">
                                                <i class="far fa-images"></i> No hay fotografías</p>
                                        @endforelse
                                    </div>
                                    <div class="card-media-body-supporting-bottom">
                                        <span class="card-media-body-supporting-bottom-text subtle">
                                            <i class="fas fa-map-marked-alt"></i> {{ $extravio->ciudad->nombre .', '.
                                            $extravio->colonia }}
                                        </span>
                                        <span class="card-media-body-supporting-bottom-text subtle u-float-right">
                                            <i class="far fa-arrow-alt-circle-right"></i>
                                        </span>
                                    </div>
                                    <div class="card-media-body-supporting-bottom card-media-body-supporting-bottom-reveal">
                                        <span class="card-media-body-supporting-bottom-text subtle" style="margin-top:5px;">
                                            <i class="fas fa-user"></i> Dueño: {{ $extravio->mascota->usuario->nombre
                                            .'
                                            '.$extravio->mascota->usuario->apat }}
                                        </span>
                                        <a href="#/" style="text-decoration: none;" onclick="pasarID({{ $extravio->mascota->id_usuario }})"
                                            data-toggle="modal" data-target="#contactarModal" class="card-media-body-supporting-bottom-text card-media-link u-float-right">
                                            <i class="fas fa-comment"></i> Contactar al dueño
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 col-lg-3  noPad-noMarg">
                    <div id="right">
                        <div id="opciones">
                            <h5>
                                <b><i class="fas fa-layer-group"></i> Opciones</b>
                                <a href="" style="font-size:12px; margin-top: 6px;" class="float-right hint--top hint--rounded hint--bounce"
                                    aria-label="No disponible por el momento">
                                    Mas</a>
                            </h5>
                            <hr>
                            <button href="" type="submit" class="btn btn-outline-info hvr-buzz-out" style="width:100%; margin-bottom:10px;">
                                <i class="fas fa-dog float-left"></i> Registrar mascota</button>
                            <button href="" type="submit" class="btn btn-danger hvr-buzz-out" style="width:100%; margin-bottom:10px;">
                                <i class="fas fa-exclamation-triangle float-left"></i> Reportar extravío</button>

                            <form action="{{ url('cerrar') }}" method="get">
                                <button href="" type="submit" class="btn btn-dark hvr-buzz-out" style="width:100%; margin-bottom:10px;">
                                    <i class="fas fa-sign-out-alt float-left"></i> Cerrar Sesion</button>
                            </form>
                        </div>
                        <div id="filtros">
                            <h5 style="margin-bottom:15px;"><b><i class="fas fa-filter"></i> Filtrar extravíos</b></h5>
                            <div class="select">
                                <select name="raza" id="slct">
                                    <option>Seleccionar raza</option>
                                    @foreach ($razas as $raza)
                                    <option value="{{ $raza->id }}">{{ $raza->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="select">
                                <select name="slct" id="slct">
                                    <option>Seleccionar sexo</option>
                                    <option value="1">Pure CSS</option>
                                    <option value="2">No JS</option>
                                    <option value="3">Nice!</option>
                                </select>
                            </div>
                            <div class="select">
                                <select name="slct" id="slct">
                                    <option>Seleccionar pais</option>
                                    <option value="1">Pure CSS</option>
                                    <option value="2">No JS</option>
                                    <option value="3">Nice!</option>
                                </select>
                            </div>
                            <div class="select">
                                <select name="slct" id="slct">
                                    <option>Seleccionar estado</option>
                                    <option value="1">Pure CSS</option>
                                    <option value="2">No JS</option>
                                    <option value="3">Nice!</option>
                                </select>
                            </div>
                            <div class="select">
                                <select name="slct" id="slct">
                                    <option>Seleccionar ciudad</option>
                                    <option value="1">Pure CSS</option>
                                    <option value="2">No JS</option>
                                    <option value="3">Nice!</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if (Session::get('inbox'))
<div class="alert alert-warning alert-dismissible fade show animated fadeindown" role="alert" style="
        width: 31%;
        z-index: 1;
        position: absolute;
        top: 40px;
        right: 35%">

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <strong><i class="far fa-envelope"></i> Revisa tu bandeja, </strong> tienes mensajes nuevos.
</div>
@endif

@endsection

@section('scripts')
<script>
    function pasarID(id) {
        document.querySelector('#destinatario').value = id;
    }

    $(document).ready(function(){
        let miniatura = $('.foto');
            miniatura.click(function(e){
                $img = $(this).data("foto");
                console.log($img.foto);
            	$('#verminiatura img').attr("src","/petpic/" + $img.foto);
        })
    })


    $(function () {

        $('#cropbox').Jcrop({
            aspectRatio: 1,
            onSelect: updateCoords
        });

    });

    function updateCoords(c) {
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    };

    function checkCoords() {
        if (parseInt($('#w').val())) return true;
        alert('Please select a crop region then press submit.');
        return false;
    };

</script>
@endsection

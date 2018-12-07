@extends('base')
@section('titulo')
<title>Editar mascota</title>
@endsection

@section('links')
<link href="https://fonts.googleapis.com/css?family=Anton|Merriweather+Sans|Oswald|Roboto" rel="stylesheet">
<link rel="stylesheet" href="../css/bootstrap.css">
<link rel="stylesheet" href="../css/base.css">
<link rel="stylesheet" href="../css/hint.css">
<link rel="stylesheet" href="../css/hover.css">
<link rel="stylesheet" href="../css/fadeinup.css">
<link rel="stylesheet" href="../css/fadeindown.css">
<link rel="stylesheet" href="../css/ihover.css">
<link rel="stylesheet" href="../css/edit_pet.css">
<link rel="stylesheet" href="../css/jquery.Jcrop.css">

@endsection

@section('contenido')
<!--Modal para ver foto de mascota-->
<div class="modal fade" id="cut_foto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
    style=" text-align: center; padding: 0 !important;">
    <div class="modal-dialog" role="document" style="display:inline-table">
        <div class="modal-content" style="height: auto; width: max-content;">
            <div class="modal-body" style="padding: 0; margin: 0; width: auto; height: auto;">
                <!-- This is the image we're attaching Jcrop to -->
                <img src="" id="cropbox">

                <!-- This is the form that our event handler fills -->
                <form action="{{ url('/eliminarFoto') }}" method="post" onsubmit="return checkCoor	ds();" style="display:none;">
                    {{ csrf_field() }}
                    <input type="hidden" id="id_foto" name="id_foto" />
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger"><i class="fas fa-eraser"></i> Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top:50px;">
    <div id="fotos">
        <form id="uploadimage" action="{{ url('upload') }}" method="post" enctype="multipart/form-data">
            <h4><i class="far fa-images"></i> <b>Fotografias</b>
                <a class="float-right" style="font-size:13px; margin-top:10px; cursor:pointer;" onclick="document.forms['uploadimage'].submit(); return false;">
                    Guardar cambios
                </a>
            </h4>
            <div id="galeria">
                @php
                $fotos = 0;
                @endphp
                @foreach ($mascota->fotografias as $foto)
                <div class="foto" data-toggle="modal" data-target="#cut_foto" data-foto="{{ $foto }}">
                    <img src="{{ '/petpic/'.$foto->foto }}" alt="">
                </div>
                @php
                $fotos++;
                @endphp
                @endforeach
                @if ($fotos <4) <div class="subir" id="cf1"><i class="add far fa-image"></i></div>
            @endif
    </div>
    {{ csrf_field() }}
    <input type="hidden" name="idmascota" value="{{$mascota->id}}">
    <input type="file" name="foto1" id="foto1">
    </form>
</div>

<div id="formulario">
    <h4><i class="far fa-list-alt"></i> <b>Informacion</b></h4><br>
    <form action="{{url('/editarmascota')}}" method="POST" role="form" enctype="multipart/form-data">
        <div class="form-group">

            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{$mascota->id}}">
            <div class="pp" id="cf1"><img src="{{ '/pppic/' . $mascota->foto}}" alt=""></div>
            <h2>{{ $mascota->nombre }}</h2>

            <input type="file" name="fotopp" id="fotopp">
            <input type="text" id="" class="form-control" name="nombre" id="nombre" value="{{$mascota->nombre}}">

            <p>Sexo</p>
            <select class="form-control">
                <option value="macho">Macho</option>
                <option value="hembra">Hembra</option>
            </select>

            <p>Color</p>
            <input type="text" name="color" value="{{$mascota->color}}" class="form-control">

            <p>Fecha de nacimiento</p>
            <input type="date" name="fecha_nac" class="form-control" value={{ $mascota->f_nac }}>

            <p>Estatus</p>
            <select class="form-control" name="estatus" id="estatus">
                <option value="en casa" @if ($mascota->estatus == 'en casa') {{ "selected" }} @endif>En casa</option>
                <option value="extraviado" @if ($mascota->estatus == 'extraviado') {{ "selected" }} @endif>Extraviado</option>
            </select>

            <p>Esterilizado</p>
            <select class="form-control" name="esterilizado" id="esterilizado">
                <option value="si"  @if ($mascota->esterilizado == 'si') {{ "selected" }} @endif>Si</option>
                <option value="no" @if ($mascota->esterilizado == 'no') {{ "selected" }} @endif>No</option>
            </select>

            <p>Enfermedades</p>
            @if($mascota->enfermedad==null)
            <input type="text" name="enfermedad" class="form-control" value="Ninguna...">
            @else
            <input type="text" name="enfermedad" class="form-control" value="{{$mascota->enfermedad}}">
            @endif

            <p>Raza</p>
            <select class="form-control" id="raza" name="raza">
                @foreach($razas as $ra)
                <option value="{{$ra->id}}">{{$ra->nombre}}</option>
                @endforeach
            </select>

            <p>Rasgos fisicos</p>
            @if($mascota->rasgos==null)
            <input type="text" name="rasgos" class="form-control" placeholder="Manchas,colores, gestos...">
            @else
            <input type="text" name="rasgos" class="form-control" value="{{$mascota->rasgos}}">
            @endif

            <p>Ciudad</p>
            <select name="ciudad" id="ciudad" class="form-control">
                @foreach($ciudades as $ciu)
                <option value="{{$ciu->id}}">{{$ciu->nombre}}</option>
                @endforeach
            </select>

        </div>
        <button type="submit" class="btn btn-dark btn-block">Guardar cambios</button>
    </form>
</div>
</div>
@endsection

@section('scripts')
<script src="../js/jquery-3.3.1.slim.min.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="../js/jquery.Jcrop.js"></script>
<script src="../js/backstrech.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>


<script>
    $(document).ready(function () {
        $('.nav-item:nth-child(2) > a').attr('id', 'menu-link-active'); //Se le agrega la clase active para resaltar
        $('.nav-item:nth-child(2)').addClass('active-nav');

        $('.nav-item:nth-child(1)').removeClass('active-nav'); //Se borra la clase del que ya tenia el active
        $('.nav-item:nth-child(1) > a').removeAttr('id').attr('id', 'menu-link');

        let subir = $('.subir');

        subir.click(function (e) {
            let id = $(this).attr("id");
            switch (id) {
                case "cf1":
                    $('#foto1').click();
                    break;
            }
        });


        let foto = $('.foto');
        foto.click(function (e) {
            $img = $(this).data("foto");
            $('#cropbox').attr("src", "/petpic/" + $img.foto);
            $('#id_foto').val($img.id);
        })
    });

</script>
@endsection

@extends('base')

@section('titulo')<title>Union Canina: Mis mascotas</title>@endsection
@section('links')
<link rel="stylesheet" href="../css/pets.css">
<!--Hoja de estilos para ésta vista-->
@endsection

@php
use App\Modelos\Usuario;
use App\Modelos\Raza;
use App\Modelos\Ciudad;
$usuario = Session::get('usuario');
$extravios = Session::get('extravios');
$mascotas = Session::get('mascotas');
$conversaciones = Session::get('conversaciones');
$razas = Raza::all();
$ciudades = Ciudad::all();
@endphp
@section('contenido')
<!-- Modal para registrar -->
<div class="modal fade" id="regmascota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">+<i class="fas fa-dog"></i><b> Registrar mascota</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('/registrarmascota')}}" method="POST" role="form">
                    <div class="form-group">

                        {{ csrf_field() }}


                        <p>Nombre</p>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="">
                        <input type="hidden" name="usuario" value="{{Session::get('usuario')->id}}">
                        <p>Sexo</p>
                        <select class="form-control" name="sexo" id="sexo">
                            <option value="macho">Macho</option>
                            <option value="hembra">Hembra</option>
                        </select>

                        <p>Color</p>
                        <input type="text" name="color" class="form-control">

                       <p>Fecha de nacimiento</p>
                        <input type="date" name="fecha_nac" class="form-control" placeholder="">

                        <p>Estatus</p>
                        <select class="form-control" name="estatus" id="estatus">
                            <option value="en casa">En casa</option>
                        </select>

                       <p>Esterilizado</p>
                        <select class="form-control" name="esterilizado" id="esterilizado">
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>

                        <p>Enfermedades</p>
                        <input type="text" name="enfermedad" class="form-control" placeholder="Ninguna...">

                       <p>Raza</p>
                        <select class="form-control" id="raza" name="raza">
                            @foreach($razas as $ra)
                            <option value="{{$ra->id}}">{{$ra->nombre}}</option>
                            @endforeach
                        </select>

                        <p>Rasgos fisicos</p>
                        <input type="text" name="rasgos" class="form-control" placeholder="Manchas,colores, gestos...">


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
    </div>
</div>
<!-- Modal para reportar-->
<div class="modal fade" id="reporteMascota" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-exclamation-triangle"></i> Reporte de Extravío</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-reporte">
                <form action="{{ url('/reportarExtravio') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="idmasc" id="id-masc" value="">
                    <p>Colonia</p>
                    <input type="text" class="text-box form-control" name="colonia">
                    <p>Ciudad</p>
                    <select name="ciudad" id="ciudad" class="form-control">
                        @foreach($ciudades as $ciu)
                        <option value="{{$ciu->id}}">{{$ciu->nombre}}</option>
                        @endforeach
                    </select>
                    <p>Fecha de extravío</p>
                    <input type="date" name="fecha_ext" class="form-control" placeholder="">
                    <p>Información Extra</p>
                    <input type="text" class="text-box form-control" name="info_ext">
                    <button type="submit" class="btn btn-danger btn-guardar btn-block">Reportar</button>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
<div class="container">
    <div id="main">
        <div id="head">
            <h6 class="Roboto"><b><i class="fas fa-dog"></i> Mis mascotas</b></h6>
        </div>
        <div id="mascotas">
            @forelse ($mascotas as $mascota)
            <div class="mascota">
                <img src="{{ '/pppic/' . $mascota->foto }}" alt="">
                <div class="info">
                    <h5 class="Roboto">
                        <b>{{ $mascota->nombre }}</b>
                        @if ($mascota->estatus == 'extraviado')
                        <span class="badge badge-warning" style="font-size:12px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ $mascota->estatus }}
                        </span>
                        @else
                        <span class="badge badge-success" style="font-size:12px;">
                            <i class="fas fa-home"></i>
                            {{ $mascota->estatus }}
                        </span>
                        @endif
                    </h5>
                    <a href="{{ url('editarmascota/'.$mascota->id.'') }}">Editar mascota</a>
                    <a id="mascota" class="extravioPerro" href="" data-toggle="modal" data-target="#reporteMascota" data-mascota="{{ $mascota->id }}">
                        Reportar como extraviado</a>
                    <a href="{{ url('eliminarmascota/'.$mascota->id.'') }}">Eliminar</a>
                </div>
                <div class="opciones ">
                    <p><b>Codigo:</b> AKL02MNU498ZV203PLYU41</p>
                </div>
            </div>
            <hr>
            @empty

            @endforelse
        </div>
        <a href="" class="addmascota" data-toggle="modal" data-target="#regmascota">+ Agregar mascota</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.nav-item:nth-child(2) > a').attr('id', 'menu-link-active'); //Se le agrega la clase active para resaltar
        $('.nav-item:nth-child(2)').addClass('active-nav');

        $('.nav-item:nth-child(1)').removeClass('active-nav'); //Se borra la clase del que ya tenia el active
        $('.nav-item:nth-child(1) > a').removeAttr('id').attr('id', 'menu-link');



        let botonExtravio=$('#a.extravioPerro');
        $('a.extravioPerro').click(function (e){
            let idMascota=$(this).data('mascota');
            $("#id-masc").val(idMascota);
        });
    })

</script>
@endsection

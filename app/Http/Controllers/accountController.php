<?php

namespace App\Http\Controllers;
// include composer autoload
// require 'vendor/autoload.php';
use App\Modelos\Usuario;
use App\Modelos\Mascota;
use App\Modelos\Extravio;
use App\Modelos\Estado;
use App\Modelos\Raza;
use App\Modelos\Mensaje;
use App\Modelos\Conversacion;
use App\Modelos\Ciudad;
use App\Modelos\Pais;
use App\Modelos\Fotografia;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Image;
use Session;
class accountController extends Controller
{
    function __construct(){
         $this->middleware("entrar", ["except" => ["iniciar", "registrar","vistasPublicas"]]);
    }

    function vistasPublicas($vista){
        session(['vistasPu' => $vistas = [
            'login' => 'account.login',
            'register' => 'account.register',
        ]]);
        return view(Session::get('vistasPu')[$vista]);
    }
    function vistasProtegidas($vista){
        $extravios = Extravio::all()->sortByDesc('f_extrav');
        $mascotas = Usuario::find(Session::get('usuario')->id)->mascotas;
        $conversaciones = Usuario::find(Session::get('usuario')->id)->conversaciones->sortByDesc('fecha_actividad')->values();
        $inbox = $this->mensajesNuevos();
        if($inbox)
             session(['inbox' => $inbox]);
        else
            session(['inbox' => false]);

        session(['extravios' => $extravios]);
        session(['mascotas' => $mascotas]);
        session(['conversaciones' => $conversaciones]);

        session(['vistasPro' => $vistas = [
            'home' => 'account.home',
            'pets' => 'account.pets',
            'messages' => 'account.messages',
            'conversation' => 'account.conversation',
            'recortar_img' => 'account.crop'
        ]]);
        return view(Session::get('vistasPro')[$vista]);
    }

    function iniciar(Request $form){
        $correo = $form->correo;
        $password = $form->password;
        $user = Usuario::where("correo", "=", $correo)->where("pwd", "=", $password)->first();
        if ($user) {
            session(['usuario' => $user]);
            return redirect("home");
        }

        return back()->with("error", "Algo salió mal...");
    }

    function registrar(Request $form){
        $nombre = $form->nombre;
        $apellidop = $form->apPaterno;
        $apellidom = $form->apMaterno;
        $correo = $form->correo;
        $password = $form->password;
        $usuario = new Usuario();

        $usuario->nombre = $nombre;
        $usuario->apat = $apellidop;
        $usuario->amat=$apellidom;
        $usuario->correo = $correo;
        $usuario->pwd=$password;
        $usuario->save();
        return redirect('login');
    }
    function contactar(Request $form){
        $texto = $form->mensaje;
        $destinatario = $form->destinatario;
        $remitente = $form->remitente;

        $conversacion = new Conversacion();
        $conversacion->save();

        $mensaje = new Mensaje();
        $mensaje->id_usuario = $remitente;
        $mensaje->mensaje = $texto;
        $mensaje->id_conversacion = $conversacion->id;
        $mensaje->save();

        $usuario = Usuario::find($remitente);
        $usuario->conversaciones()->save($conversacion, ["participante" => $destinatario]);
        $usuario = Usuario::find($destinatario);
        $usuario->conversaciones()->save($conversacion, ["participante" => $remitente]);

        return back();
    }
    function enviarMensaje(Request $form){
        $texto = $form->mensaje;
        $usuario = $form->usuario;
        $conversacion = $form->conversacion;

        $mensaje = new Mensaje();
        $mensaje->id_usuario = $usuario;
        $mensaje->mensaje = $texto;
        $mensaje->id_conversacion = $conversacion;
        $mensaje->save();

        $conversacion = Conversacion::find($form->conversacion);
        $mensaje = Mensaje::all()->last();
        $conversacion->fecha_actividad = $mensaje->fecha;
        $conversacion->save();

        $usuario = Usuario::find($form->usuario);

        $html="";
         foreach ($conversacion->mensajes as $mensaje){
               if ($mensaje->id_usuario == $usuario->id){

                   $html .= "<div id='mensaje-destinatario'>
                        <div id='respuesta'>
                            <p style='margin:0;'>". $mensaje->mensaje ."</p>
                        </div>
                   </div>";
               }
                else
                {
                     $html .= "<div id='mensaje-remitente'>
                        <div id='mensaje'>
                            <p style='margin:0;'>". $mensaje->mensaje ."</p>
                        </div>
                   </div>";
                }
            }
            return $html;
        // return view('account.conversation')->with('conversacion',$conversacion);
    }
    function actualizarChat(){
        $usuario_id = $_GET['usuario'];
        $conversacion_id = $_GET['conversacion'];
        $texto = $_GET['mensaje'];

        $conversacion = Conversacion::find($conversacion_id);
        $usuario = Usuario::find($usuario_id);
        $html="";

         foreach ($conversacion->mensajes as $mensaje){
               if ($mensaje->id_usuario == $usuario->id){

                   $html .= "<div id='mensaje-destinatario'>
                        <div id='respuesta'>
                            <p style='margin:0;'>". $mensaje->mensaje ."</p>
                        </div>
                   </div>";
               }
                else
                {
                     $html .= "<div id='mensaje-remitente'>
                        <div id='mensaje'>
                            <p style='margin:0;'>". $mensaje->mensaje ."</p>
                        </div>
                   </div>";
                }
            }
            return $html;
    }
    function verificarChat(){
         $conversacion_id = $_GET['conversacion'];
          $conversacion = Conversacion::find($conversacion_id);
          return $conversacion->mensajes->count();
    }
    function VerificarConversaciones(){
        $usuario = Usuario::find($_GET['usuario']);
        $conversaciones = $usuario->conversaciones->sortByDesc('fecha_actividad')->values();
        $nuevas = 0;
        $html ="";
        foreach ($conversaciones as $conversacion) {
                if($conversacion->mensajes->last()->leido == 0 && $conversacion->mensajes->last()->id_usuario != $usuario->id)
                    $nuevas++;
        }
        if($nuevas>0){
            foreach ($conversaciones as $conversacion) {
                $color="#FFF";
                $nuevo = false;
                $font="";
                  if ($conversacion->mensajes->last()->id_usuario != $usuario->id && $conversacion->mensajes->last()->leido == 0)
                        {
                            $color = "#FFF6CA";
                            $nuevo=true;
                            $font="bold";
                        }

                $html.=
            "<a href='".url('conversacion/'.$conversacion->id)."' id='liga'>
                <div id='mensaje' style='background-color:". $color."'>
                    <div id='foto-usuario'>
                         <img  src='/imagen/" . Usuario::find($conversacion->pivot->participante)->foto ."' alt=''>
                    </div>
                    <div id='info-usuario'>
                        <h6><b>".Usuario::find($conversacion->pivot->participante)->nombre."</b>";
                            if ($nuevo)
                                $html .= " <span class='badge badge-success' style='font-size:10px;'>Nuevo mensaje</span>";

                    $html.=
                        "</h6>
                            <p id='ultimo' style='font-weight:". $font."'>
                                <b>";

                                        if($conversacion->mensajes->last()->id_usuario == $usuario->id)
                                            $html.= "Yo";
                                        else
                                            $html .= Usuario::find($conversacion->mensajes->last()->id_usuario)->nombre;

                    $html.=": </b>" . $conversacion->mensajes->last()->mensaje ."</p>

                    </div>
                    <div id='fecha'>
                        <p style='color: #B0B0B0;'><i class='far fa-clock'></i>".' '.$conversacion->mensajes->last()->fecha."</p>
                        <a id='eliminar' class='hint--left hint--rounded hint--bounce' aria-label='Eliminar conversacion'
                        href='".url('eliminarConversacion/'.$conversacion->id)."'><i class='fas fa-trash-alt'></i></a>
                    </div>
                    <input type='hidden' value=".$conversacion->id." name='id'>
                </div>
            </a>";
                    }
        }

        return $html;
    }

    function verConversacion($id){
        // $id = $form->id;
        $conversacion = Conversacion::find($id);
        $c = Session::get('conversaciones')->where('id', $id);
        return view('account.conversation')->with('conversacion',$c);
    }

    function eliminarConversacion($id){
         $conversacion = Conversacion::find($id);
        foreach($conversacion->usuarios as $user){
            $usuario = $user->pivot->id_usuario;
            $participante = $user->pivot->participante;
            $conversacion->usuarios()->detach($usuario);
        }
         $conversacion->mensajes()->delete();
         $conversacion->delete();
         return back();
    }

     function mensajesNuevos(){
        $usuario = Usuario::find(Session::get('usuario')->id);
        $conversaciones = $usuario->conversaciones->sortByDesc('fecha_actividad')->values();
        $nuevas = 0;
        foreach ($conversaciones as $conversacion) {
                if($conversacion->mensajes->last()->leido == 0 && $conversacion->mensajes->last()->id_usuario != $usuario->id)
                    $nuevas++;
        }
        if($nuevas > 0)
           return true;

        return false;
    }

    function editar_perfil(Request $form){
        $nombre= $form->nom;
        $apaterno= $form->apaterno;
        $amaterno= $form->amaterno;
        $correo= $form->email;
        $newpassword= $form->newpass;
        $user= Session::get('usuario');

        if($form->foto_perfil){
            $photo = $form->file('foto_perfil')->getClientOriginalName();
            $image = Image::make( $form->file('foto_perfil'))->resize(null, 400, function($c){
                $c->aspectRatio();
            })->save(base_path() . '/storage/app/profilepics/' . $photo);
            $user->foto=$photo;
        }

        if($user->pwd == $form->oldpass){
            $user->nombre=$nombre;
            $user->apat=$apaterno;
            $user->amat=$amaterno;
            $user->correo=$correo;
            $user->save();
            return redirect('home');
        }
        return redirect('home');
    }

    function cut_foto(){
        $usuario= Session::get("usuario");
        $targ_w = $targ_h = 500;
        $jpeg_quality = 90;
        $src = url('/imagen/'.$usuario->foto );
        $img_r = imagecreatefromjpeg($src);
        $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

        imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
        $targ_w,$targ_h,$_POST['w'],$_POST['h']);

        $pic=$usuario->foto;
        $destination = base_path() . '/storage/app/profilepics/'.$pic;
        header('Content-type: image/jpeg');
        //imagejpeg($dst_r,$destination,$jpeg_quality);
        imagejpeg($dst_r, $destination, $jpeg_quality);
        $save='_'.$pic;
        //$foto=$usuario->update();
        return back();
    }


    function viewregistrarmascota(){
        //Session::get('usuario')->id
        $razas=Raza::all();
        $ciudades=Ciudad::all();
        return view("account.RegistrarMascota",compact("razas","ciudades"));
    }

    function registrarmascota(Request $r){

        $newmascota= new Mascota();
        $newmascota->nombre=$r->nombre;
        $newmascota->id_usuario=$r->usuario;
        $newmascota->f_nac=$r->fecha_nac;
        $newmascota->estatus=$r->estatus;
        $newmascota->esterilizado=$r->esterilizado;
        $newmascota->enfermedad=$r->enfermedad;
        $newmascota->id_raza=$r->raza;
        $newmascota->rasgos=$r->rasgos;
        $newmascota->id_ciudad=$r->ciudad;
        $newmascota->sexo=$r->sexo;
        $newmascota->color=$r->color;
        $newmascota->save();
        return redirect('pets');
    }

    //metodo get llena el formulario con los datos actuales de la mascota
    function editarmascota(Request $r){
        $mascota=Mascota::find($r->id);
        $razas=Raza::all();
        $ciudades=Ciudad::all();
        $estados=Estado::all();
        //return view("registrarCarro", compact("alumnos", "carros"));

        return view("account.edit_pet",compact("mascota","razas","ciudades","estados"));
    }
    //metodo post
    function actualizarmascota(Request $r){
        $id=$r->id;
        $mascota=Mascota::find($id);
        $mascota->nombre=$r->nombre;
        $mascota->f_nac=$r->fecha_nac;
        $mascota->estatus=$r->estatus;
        $mascota->esterilizado=$r->esterilizado;
        $mascota->enfermedad=$r->enfermedad;
        $mascota->id_raza=$r->raza;
        $mascota->rasgos=$r->rasgos;
        $mascota->id_ciudad=$r->ciudad;

        if($r->fotopp){
            $photo = $r->file('fotopp')->getClientOriginalName();
            $image = Image::make( $r->file('fotopp'))->resize(null, 400, function($c){
                $c->aspectRatio();
            })->save(base_path() . '/storage/app/petspp/' . $photo);
            $mascota->foto = $photo;
        }
        $mascota->update();
        return back();
    }

    function subirFoto(Request $form){
        $f1 = $form->foto1;
        $f2 = $form->foto2;
        $f3 = $form->foto3;
        $f4 = $form->foto4;

        $photo = $form->file('foto1')->getClientOriginalName();
        $image = Image::make( $form->file('foto1'))->resize(null, 400, function($c){
            $c->aspectRatio();
        })->save(base_path() . '/storage/app/petspics/' . $photo);

        $fotografia = new Fotografia();
        $fotografia->id_mascota = $form->idmascota;
        $fotografia->foto = $photo;
        $fotografia->save();
        return back();
    }
    function eliminarFoto(Request $form){
        $foto = Fotografia::find($form->id_foto);
        $foto->delete();
        return back();
    }
    function eliminarMascota($id){
        $mascota = Mascota::find($id);
        $extravios = Extravio::all();
        $fotografias = Fotografia::all();

        $fotos = collect([$fotografias->where('id_mascota',$id)]);
        $mascota->fotografias()->delete($fotos);
        $extx = collect([$extravios->where('id_mascota',$id)]);
        $mascota->extravios()->delete($extx);
        $mascota->delete();
        return back();
    }

    function extravios(){
      $mascota = Mascota::Find(3);
      return $mascota->fotografias[0]->mascota;
    }

}

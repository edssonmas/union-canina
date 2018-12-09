<?php
use Illuminate\Support\Facades\Request;
use Intervention\Image\ImageManager;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//BACK-END
Route::post('registrarmascota','accountController@registrarmascota');
Route::get('registrarmascota','accountcontroller@viewregistrarmascota');
Route::post('editarmascota','accountController@actualizarmascota');
Route::get('editarmascota/{id}','accountController@editarmascota');


Route::post('/reportarExtravio', 'accountController@reporteExtravio'); //reportar extravio de mascota desde 'Mascotas'
Route::post('/buscarcodigo', 'accountController@buscaCodigo'); //buscar por codigo
Route::get('cerrarBusqueda','\App\Http\Controllers\accountController@cerrarBusqueda'); //borrar variable de sesion de perro encontrado por codigo
Route::post('reportarEncontrado','\App\Http\Controllers\accountController@RepEncontrado'); //reportar como encontrado

Route::post('/cutpic', "accountController@cut_foto"); //recortar foto
Route::get('conversacion/{id}','accountController@verConversacion'); //Ver conversacion
Route::get('eliminarConversacion/{id}','accountController@eliminarConversacion'); //eliminar conversacion
Route::post('enviarMensaje','accountController@enviarMensaje'); //enviar mensaje
Route::get('verificarChat','accountController@verificarChat'); //verificar chat
Route::get('actualizarChat','accountController@actualizarChat'); //actualizar chat
Route::get('VerificarConversaciones','accountController@VerificarConversaciones'); //verificar conversaciones

Route::get('extravios','accountController@extravios');
Route::post('contactar','accountController@contactar');

Route::get('/', function () {
    if (session()->has('usuario')) //VISTA DE BIENVENIDA
        return view('account.home');
    return view('account.login');
});

Route::get('/eliminarmascota/{id}','accountController@eliminarMascota');


Route::post('upload','accountController@subirFoto'); //para foto de perros
Route::post('eliminarFoto','accountController@eliminarFoto');

Route::post('/iniciar','accountController@iniciar'); //iniciar sesion
Route::post('/registrar','accountController@registrar'); //registrar usuario

Route::post('/editar-perfil', 'accountController@editar_perfil'); //editar informacion del usuario

Route::get('/imagen/{img}', function($img){
    $path=storage_path('app/profilepics/'.$img);
    if(!File::exists($path)){
        abort(404);
    }

    $file=File::get($path);
    $type=File::mimeType($path);
    $response= Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});
Route::get('/petpic/{img}', function($img){
    $path=storage_path('app/petspics/'.$img);
    if(!File::exists($path)){
        abort(404);
    }

    $file=File::get($path);
    $type=File::mimeType($path);
    $response= Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});
Route::get('/pppic/{img}', function($img){
    $path=storage_path('app/petspp/'.$img);
    if(!File::exists($path)){
        abort(404);
    }

    $file=File::get($path);
    $type=File::mimeType($path);
    $response= Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

Route::get("cerrar", function () {session()->flush(); return redirect("/e");});//Cerrar Sesion
Route::get('/public/{vista}','accountController@vistasPublicas'); //RUTA PARA SELECCION DE VISTAS DE ACCOUNT
Route::get('/{vista}','accountController@vistasProtegidas'); //RUTA PARA SELECCION DE VISTAS DE ACCOUNT

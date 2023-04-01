<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)// todas las funciones store van acompañadas de request para validacion
    {
        // Modificar el request
        $request->request->add(['username' => Str::slug($request->username)]); // crea usuario como url

        $this->validate($request,[
            'username' => ['required', 'unique:users,username,'.auth()->user()->id,'min:3','max:20', 
            'not_in:twitter,editar-perfil,something else'],
            'email' => ['required','unique:users,email,'.auth()->user()->id, 'email', 'max:60']
        ]);

        if($request->imagen){
            $imagen = $request->file('imagen');// ver todos los request

        $nombreImagen = Str::uuid(). "." . $imagen->extension(); // str:uui va a generar id unicos para las imagenes

        $imagenServidor = Image::make($imagen);
        $imagenServidor->fit(1000,1000);// se guardan id no imagenes

        $imagenPath = public_path('perfiles') . '/'. $nombreImagen;// para guardar la imagen ya que el servidor cada cierto tiempo la elimina
        $imagenServidor->save($imagenPath);
        }

        //Guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->email = $request->email;

        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? '';
        if($request->oldpassword || $request->password) {
            $this->validate($request, [
                'password' => 'required|confirmed|confirmed|different:oldpassword',
            ]);
 
            if (Hash::check($request->oldpassword, auth()->user()->password)) {
                $usuario->password = Hash::make($request->password) ?? auth()->user()->password;
                $usuario->save();
            } else {
                return back()->with('mensaje', 'La Contraseña Actual no Coincide');
            }
        }
        $usuario->save();
        //Redireccionar usuario
        return redirect()->route('posts.index', $usuario->username);

    }
}

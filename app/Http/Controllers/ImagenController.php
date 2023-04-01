<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        $imagen = $request->file('file');// ver todos los request

        $nombreImagen = Str::uuid(). "." . $imagen->extension(); // str:uui va a generar id unicos para las imagenes

        $imagenServidor = Image::make($imagen);
        $imagenServidor->fit(1000,1000);// se guardan id no imagenes

        $imagenPath = public_path('uploads') . '/'. $nombreImagen;// para guardar la imagen ya que el servidor cada cierto tiempo la elimina
        $imagenServidor->save($imagenPath);

        return response()->json(['imagen' =>$nombreImagen]); // tranforma datos del frontend al backent y viceversa


    }
}

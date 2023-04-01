<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('show', 'index');
    }
    public function index(User $user)
    {
        $posts = Post::where('user_id', $user->id)->latest()->paginate(20);//Consulta de tabla de post y filtrar por este usario, ademas de usar la paginacion en los posts

        
        
        return view('dashboard', [
           'user' => $user, // para pasar el usuario al dashboard y en la url se refleje
           'posts' => $posts
        ]);
    }
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'imagen' => 'required'
        ]);

        // Post::create([
        //     'titulo' => $request->titulo,
        //     'descripcion' => $request->descripcion,
        //     'imagen' => $request->imagen,
        //     'user_id' => auth()->user()->id
        // ]);

        //Otro forma de crear registros
        // $post = new Post;
        // $post->descripcion = $request->descripcion;
        // $post->save();
        // asi con las demas

        // Creacion de registros con eloquent
        
        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => auth()->user()->id
        ]);


        return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user , Post $post)// se llaman ambos modelos debido a la utilizacion en las rutas 
    {
        return view('posts.show',[
            'post' => $post,
            'user' => $user
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete',$post);
        $post->delete();

        // Eliminar la imagen

        $imagen_path = public_path('uploads/'. $post->imagen);

        if(File::exists($imagen_path)) {
            unlink($imagen_path);// en caso de existir la imagen se borrara, funcion php
    
        }

        return redirect()->route('posts.index', auth()->user()->username);
    }
}

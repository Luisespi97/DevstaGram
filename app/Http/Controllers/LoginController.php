<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(!auth()->attempt($request->only('email','password'), $request->remember)){// en caso de no autenticarse !auth
            return back()->with('mensaje', 'Credenciales Incorrectas');
        }

        //Rescribir el nuevo password

        return redirect()->route('posts.index', auth()->user()->username );
    }
}

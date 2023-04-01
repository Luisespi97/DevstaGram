<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function store(User $user)
    {
        $user->followers()->attach( auth()->user()->id );// metodo attach usado para relaciones muchos a muchos, cuando no se usa un modelo directamente
        return back();
    }
    public function destroy(User $user)
    {
        $user->followers()->detach( auth()->user()->id );// metodo attach usado para relaciones muchos a muchos, cuando no se usa un modelo directamente
        return back();
    }
}

<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->select([// un post solo puede tener un autor
            'name',
            'username'
        ]);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);// Mostrar comentarios en el post
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function checkLike(User $user)// verificacion de likes
    {
        return $this ->likes->contains('user_id', $user->id); // contains busca en las columnas
    }
}

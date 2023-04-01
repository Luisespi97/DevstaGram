@extends('layouts.app')

@section('titulo')

    Página Principal    
@endsection

@section('contenido')

    <x-listar-post :posts="$posts"/>{{-- Agregando la variable de componente a php --}}
@endsection

@extends('layouts.app')

@section('title', 'Estados de cuenta')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Detalle del Usuario</h2>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Nombre:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Teléfono:</strong> {{ $user->phone ?? 'N/A' }}</p>
            <p><strong>Dirección:</strong> {{ $user->address ?? 'N/A' }}</p>
            <p><strong>Creado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Actualizado:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Editar</a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
@endsection
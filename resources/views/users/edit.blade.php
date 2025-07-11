@extends('layouts.app')

@section('title', 'Estados de cuenta')

@section('content')

    <div class="page">
        <div class="page-wrapper">
            <div class="page-header">
                <div class="container-xl">
                    <div class="row align-items-center mw-100">
                        <div class="col">
                            <div class="page-pretitle">
                                <ol class="breadcrumb" aria-label="breadcrumbs">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Estados de
                                            cuenta</a>
                                    </li>
                                </ol>
                            </div>
                            <h2 class="page-title">
                                Estados de cuenta - {{ auth()->user()->name }}
                            </h2>
                        </div>
                       
                    </div>
                </div>
            </div>

            {{-- Cuerpo --}}
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards">

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Editar Cliente</h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ $user->name }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ $user->email }}" required>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="password" class="form-label">Nueva Contraseña (dejar en blanco
                                                    para
                                                    no cambiar)</label>
                                                <input type="password" class="form-control" id="password" name="password">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="password_confirmation" class="form-label">Confirmar Nueva
                                                    Contraseña</label>
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="phone" class="form-label">Codigo Cliente</label>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                    value="{{ $user->codigo_cliente }}">
                                            </div>

                                        </div>

                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary">Actualizar</button>
                                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')



@endsection
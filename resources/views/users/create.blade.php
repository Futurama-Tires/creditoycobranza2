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
                                Estados de cuenta - Crear Cliente
                            </h2>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>¡Ups! Algo salió mal.</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="d-flex">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cuerpo --}}
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards center">

                        <div class="col-lg-6 mx-auto">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Crear Cliente</h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('users.store') }}" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="name" name="name" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Contraseña</label>
                                                <input type="password" class="form-control" id="password" name="password"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Confirmar
                                                    Contraseña</label>
                                                <input type="password" class="form-control" id="password_confirmation"
                                                    name="password_confirmation" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Codigo Cliente</label>
                                                <input type="text" class="form-control" id="codigo_cliente"
                                                    name="codigo_cliente">
                                            </div>

                                        </div>

                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');

            // Crea un elemento de feedback
            const feedback = document.createElement('div');
            feedback.style.marginTop = '5px';

            confirmPassword.parentNode.appendChild(feedback);

            function checkPasswords() {
                if (confirmPassword.value === '' && password.value === '') {
                    feedback.textContent = '';
                    return;
                }

                if (password.value.length < 8) {
                    feedback.textContent = '❌ La contraseña debe tener al menos 8 caracteres.';
                    feedback.style.color = 'red';
                    return;
                }

                if (confirmPassword.value === '') {
                    feedback.textContent = '';
                    return;
                }

                if (password.value === confirmPassword.value) {
                    feedback.textContent = '✅ Las contraseñas coinciden.';
                    feedback.style.color = 'green';
                } else {
                    feedback.textContent = '❌ Las contraseñas no coinciden.';
                    feedback.style.color = 'red';
                }
            }

            password.addEventListener('input', checkPasswords);
            confirmPassword.addEventListener('input', checkPasswords);
        });
    </script>

@endsection
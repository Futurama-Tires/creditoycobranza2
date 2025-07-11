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
                                Estados de cuenta - Gestion de Clientes
                            </h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="d-flex">
                                <div class="me-3">
                                    <a href="{{ route('users.create') }}" class="btn btn-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        Ingresar Cliente</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cuerpo --}}
            <div class="page-body">
                <div class="container-xl">
                    <div class="row row-deck row-cards">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

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



                        {{-- TABLA FACTURAS --}}
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Consultar Clientes</h3>
                                </div>
                                <div class="table-responsive">
                                    <table id="clientesTable"
                                        class="table table-selectable card-table table-vcenter table-nowrap table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nombre</th>
                                                <th>Email</th>
                                                <th>Código Cliente</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                <tr>
                                                    <td>{{ $user->id }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->codigo_cliente ?? 'N/A' }}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <a href="#" class="btn dropdown-toggle"
                                                                data-bs-toggle="dropdown">Acciones</a>
                                                            <div class="dropdown-menu">
                                                                <span class="dropdown-header">Elige una opción</span>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('users.show', $user->id) }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon dropdown-item-icon icon-tabler icon-tabler-settings"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                        </path>
                                                                        <path
                                                                            d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                                                        </path>
                                                                        <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0">
                                                                        </path>
                                                                    </svg>
                                                                    Visualizar
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('users.edit', $user->id) }}">


                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="icon dropdown-item-icon icon-tabler icon-tabler-pencil"
                                                                        width="24" height="24" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none">
                                                                        </path>
                                                                        <path
                                                                            d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4">
                                                                        </path>
                                                                        <path d="M13.5 6.5l4 4"></path>
                                                                    </svg>
                                                                    Editar
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
        $(document).ready(function () {
            $('#clientesTable').DataTable({
                // Optional: customize language to Spanish
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                },
                // Optional: adjust other settings as needed
                "pageLength": 5,
                "order": [],

            });
        });
    </script>

@endsection
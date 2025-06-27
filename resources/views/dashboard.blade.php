@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page">
        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <span class="avatar avatar-md"
                                style="background-image: url({{ asset('img/' . Auth::user()->foto) }})"></span>
                        </div>
                        <div class="col">
                            <h2 class="page-title"> {{ $greeting }}, {{ Auth::user()->name }}
                                {{ Auth::user()->apellido }}!
                            </h2>
                            <div class="page-subtitle">
                                <div class="row">
                                    <div class="col-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <line x1="3" y1="21" x2="21" y2="21" />
                                            <path d="M5 21v-14l8 -4v18" />
                                            <path d="M19 21v-10l-6 -4" />
                                            <line x1="9" y1="9" x2="9" y2="9.01" />
                                            <line x1="9" y1="12" x2="9" y2="12.01" />
                                            <line x1="9" y1="15" x2="9" y2="15.01" />
                                            <line x1="9" y1="18" x2="9" y2="18.01" />
                                        </svg>
                                        {{ Auth::user()->puesto }}
                                    </div>
                                    <div class="col-auto text-blue">

                                        ¿Qué haremos hoy?
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto d-none d-md-flex">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                                Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <div class="row  row-cards">
                        {{-- BBVA --}}
                        <div class="col-md-4 col-12">
                            <div class="card">
                                <div class="card-status-start bg-orange"></div>
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-orange">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-list-check">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3.5 5.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M3.5 11.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M3.5 17.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M11 6l9 0" />
                                            <path d="M11 12l9 0" />
                                            <path d="M11 18l9 0" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <a href="#" class="btn dropdown-toggle btn-ghost-orange fs-3"
                                        data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-list-check">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3.5 5.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M3.5 11.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M3.5 17.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M11 6l9 0" />
                                            <path d="M11 12l9 0" />
                                            <path d="M11 18l9 0" />
                                        </svg>
                                        Identificación BBVA</a>
                                    <div class="dropdown-menu dropdown-menu-arrow">
                                        <span class="dropdown-header">Selecciona una</span>
                                        <a class="dropdown-item" href="{{ route('clientes.importar') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon dropdown-item-icon icon-tabler icon-tabler-user-check">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                <path d="M15 19l2 2l4 -4" />
                                            </svg>
                                            Registrar clientes
                                        </a>
                                        <a class="dropdown-item" href="{{ route('clientes.procesar') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon dropdown-item-icon icon-tabler icon-tabler-file-upload">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                <path
                                                    d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                                <path d="M12 11v6" />
                                                <path d="M9.5 13.5l2.5 -2.5l2.5 2.5" />
                                            </svg>
                                            Identificar clientes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Conciliación pagos --}}
                        <div class="col-md-4 col-12">
                            <div class="card">
                                <div class="card-status-start bg-green"></div>
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-database-dollar">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" />
                                            <path d="M4 6v6c0 1.657 3.582 3 8 3c.415 0 .822 -.012 1.22 -.035" />
                                            <path d="M20 10v-4" />
                                            <path d="M4 12v6c0 1.657 3.582 3 8 3c.352 0 .698 -.009 1.037 -.025" />
                                            <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                            <path d="M19 21v1m0 -8v1" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <a href="#" class="btn dropdown-toggle btn-ghost-green fs-3"
                                        data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-database-dollar">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" />
                                            <path d="M4 6v6c0 1.657 3.582 3 8 3c.415 0 .822 -.012 1.22 -.035" />
                                            <path d="M20 10v-4" />
                                            <path d="M4 12v6c0 1.657 3.582 3 8 3c.352 0 .698 -.009 1.037 -.025" />
                                            <path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                            <path d="M19 21v1m0 -8v1" />
                                        </svg>
                                        Conciliación de pagos</a>
                                    <div class="dropdown-menu dropdown-menu-arrow">
                                        <span class="dropdown-header">Selecciona una</span>
                                        <a class="dropdown-item" href="{{ route('conciliacion_pagos.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon dropdown-item-icon icon-tabler icons-tabler-outline icon-tabler-coins">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" />
                                                <path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" />
                                                <path
                                                    d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" />
                                                <path d="M3 6v10c0 .888 .772 1.45 2 2" />
                                                <path d="M3 11c0 .888 .772 1.45 2 2" />
                                            </svg>
                                            Conciliar pagos
                                        </a>
                                        <a class="dropdown-item" href="{{ route('importar-facturas-usuario-vista') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon dropdown-item-icon icon-tabler icons-tabler-outline icon-tabler-receipt">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2" />
                                            </svg>
                                            Importar facturas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="card">
                                <div class="card-status-start bg-green"></div>
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-diff">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M11 16h10" />
                                            <path d="M11 16l4 4" />
                                            <path d="M11 16l4 -4" />
                                            <path d="M13 8h-10" />
                                            <path d="M13 8l-4 4" />
                                            <path d="M13 8l-4 -4" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <a href="{{ route('estados_de_cuenta.index') }}" class="btn btn-ghost-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-diff">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M11 16h10" />
                                            <path d="M11 16l4 4" />
                                            <path d="M11 16l4 -4" />
                                            <path d="M13 8h-10" />
                                            <path d="M13 8l-4 4" />
                                            <path d="M13 8l-4 -4" />
                                        </svg>
                                        <h3 class="card-title mb-0">Estados de cuenta</h3>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-4 col-12">
                            <div class="card">
                                <div class="card-status-start bg-blue"></div>
                                <div class="card-stamp">
                                    <div class="card-stamp-icon bg-blue">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-box">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                            <path d="M12 12l8 -4.5" />
                                            <path d="M12 12l0 9" />
                                            <path d="M12 12l-8 -4.5" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <a href="#" class="btn btn-ghost-blue">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-box">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                            <path d="M12 12l8 -4.5" />
                                            <path d="M12 12l0 9" />
                                            <path d="M12 12l-8 -4.5" />
                                        </svg>
                                        <h3 class="card-title mb-0">Item 3</h3>
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    <div @endsection

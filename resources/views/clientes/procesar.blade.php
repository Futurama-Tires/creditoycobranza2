@extends('layouts.app')

@section('title', 'Procesar archivo de excel')

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
                                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Identificación
                                            BBVA</a>
                                    </li>
                                </ol>
                            </div>
                            <h2 class="page-title">
                                Identificar clientes
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="btn-list">
                                <a href="{{ route('clientes.importar') }}" class="btn d-none d-lg-inline-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon dropdown-item-icon icon-tabler icon-tabler-user-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                        <path d="M15 19l2 2l4 -4" />
                                    </svg>
                                    Registrar clientes
                                </a>

                                <a href="{{ route('clientes.importar') }}" class="btn d-lg-none btn-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon dropdown-item-icon icon-tabler icon-tabler-user-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                        <path d="M15 19l2 2l4 -4" />
                                    </svg>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <div class="card col-8 mx-auto">
                        <div class="card-header">
                            <h3 class="card-title">Selecciona el archivo para la identificación de cuenta</h3>
                        </div>
                        <div class="card-body">
                            <form action="/process-excel" method="POST" enctype="multipart/form-data"
                                class="needs-validation" novalidate>
                                @csrf
                                <div class="space-y">
                                    <div class="mb-3">
                                        <label class="form-label required">Seleccionar archivo</label>
                                        <input type="file" class="form-control" name="file" id="file"
                                            accept=".xlsx,.xls" required>
                                        <div class="invalid-feedback">Debes seleccionar un archivo</div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-4 w-100">
                                            Cargar archivo
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-right icon-2">
                                                <path d="M5 12l14 0"></path>
                                                <path d="M13 18l6 -6"></path>
                                                <path d="M13 6l6 6"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

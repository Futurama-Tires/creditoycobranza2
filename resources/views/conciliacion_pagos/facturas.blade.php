@extends('layouts.app')

@section('title', 'Importar facturas')

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
                                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Conciliación de
                                            pagos</a>
                                    </li>
                                </ol>
                            </div>
                            <h2 class="page-title">
                                Importar facturas
                            </h2>
                        </div>
                        {{-- <div class="col-auto">
                            <div class="btn-list">
                                <a href="{{ route('clientes.procesar') }}" class="btn d-none d-lg-inline-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon dropdown-item-icon icon-tabler icon-tabler-file-upload">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M12 11v6" />
                                        <path d="M9.5 13.5l2.5 -2.5l2.5 2.5" />
                                    </svg>
                                    Identificar clientes
                                </a>

                                <a href="#" class="btn d-lg-none btn-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon dropdown-item-icon icon-tabler icon-tabler-file-upload">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                                        <path d="M12 11v6" />
                                        <path d="M9.5 13.5l2.5 -2.5l2.5 2.5" />
                                    </svg>

                                </a>

                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <div class="card col-8 mx-auto">
                        <div class="card-header">
                            <h3 class="card-title">Importa tu archivo de facturas</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('importar-facturas-usuario') }}" method="POST"
                                enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                <div class="space-y">
                                    <div class="mb-3">
                                        <label class="form-label required">Seleccionar archivo</label>
                                        <input type="file" class="form-control" name="facturas" id="facturas"
                                            accept=".xlsx,.xls" required>
                                        <div class="invalid-feedback">Debes seleccionar un archivo</div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-4 w-100">
                                            Importar
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

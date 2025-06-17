@extends('layouts.app')

@section('title', 'Conciliación de pagos')

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
                                Conciliar
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="btn-list">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalCargarConciliaciones">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-upload">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                        <path d="M7 9l5 -5l5 5" />
                                        <path d="M12 4l0 12" />
                                    </svg>
                                    Importar archivos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    {{-- Success message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Error message --}}
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Validation errors (if using Validator::make and back()->withErrors()) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="btn-list justify-content-end">
                                    <a href="{{ route('conciliacion-pagos-export') }}" class="btn">Conciliar</a>
                                    <a href="{{ route('pendientes-conciliacion-pagos-export') }}"
                                        class="btn btn-primary">Conciliar depósitos pendientes</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header">
                                        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                                            <li class="nav-item">
                                                <a href="#tabs-home-ex1" class="nav-link active"
                                                    data-bs-toggle="tab">Bancos</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#tabs-profile-ex1" class="nav-link"
                                                    data-bs-toggle="tab">NetSuite</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="tabs-home-ex1">
                                                <h4>Archivo de bancos</h4>
                                                <div class="table-responsive">
                                                    <table id="myTable" class="table table-vcenter">
                                                        <thead>
                                                            <tr>
                                                                <th>Fecha</th>
                                                                <th>Concepto / Referencia</th>
                                                                <th>Abonos</th>
                                                                <th>Cliente</th>
                                                                <th>Pago</th>
                                                                <th>Forma de Pago</th>
                                                                <th>Banco</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($pagos as $pago)
                                                                <tr>
                                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $pago->fecha)->format('d/m/y') }}
                                                                    </td>
                                                                    <td>{{ $pago->concepto_referencia }}</td>
                                                                    <td>{{ $pago->abonos }}</td>
                                                                    <td>{{ $pago->cliente }}</td>
                                                                    <td>{{ $pago->pago }}</td>
                                                                    <td>{{ $pago->forma_pago }}</td>
                                                                    <td>{{ $pago->banco }}</td>
                                                                </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tabs-profile-ex1">
                                                <h4>Archivo de NetSuite</h4>
                                                <div>
                                                    <div class="table-responsive">
                                                        <table id="myTable2" class="table table-vcenter">
                                                            <thead>
                                                                <tr>
                                                                    <th>Fecha</th>
                                                                    <th>Creado Desde</th>
                                                                    <th>Numero de Documento</th>
                                                                    <th>Nombre</th>
                                                                    <th>Cuenta</th>
                                                                    <th>Nota</th>
                                                                    <th>Importe</th>
                                                                    <th>Estado</th>
                                                                    <th>Folio SAT</th>
                                                                    <th>RFC</th>
                                                                    <th>Metodo de Pago</th>
                                                                    <th>Forma de Pago</th>
                                                                    <th>Uso del CFDi</th>
                                                                    <th>Creado Por</th>
                                                                    <th>Representante de Ventas</th>
                                                                    <th>Metodo de Pago</th>
                                                                    <th>RFC</th>
                                                                    <th>Uso del CFDI para el Pago</th>

                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($fut_historial_pagos as $fut_historial_pago)
                                                                    <tr>
                                                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $fut_historial_pago->fecha)->format('d/m/y') }}
                                                                        </td>
                                                                        <td>{{ $fut_historial_pago->creado_desde }}</td>
                                                                        <td>{{ $fut_historial_pago->numero_documento }}
                                                                        </td>
                                                                        <td>{{ $fut_historial_pago->nombre }}</td>
                                                                        <td>{{ $fut_historial_pago->cuenta }}</td>
                                                                        <td>{{ $fut_historial_pago->nota }}</td>
                                                                        <td>{{ $fut_historial_pago->importe }}</td>
                                                                        <td>{{ $fut_historial_pago->estado }}</td>
                                                                        <td>{{ $fut_historial_pago->folio_SAT_1 }}</td>
                                                                        <td>{{ $fut_historial_pago->rfc_1 }}</td>
                                                                        <td>{{ $fut_historial_pago->forma_pago }}</td>
                                                                        <td>{{ $fut_historial_pago->metodo_pago }}</td>
                                                                        <td>{{ $fut_historial_pago->uso_del_cfdi }}</td>
                                                                        <td>{{ $fut_historial_pago->creado_por }}</td>
                                                                        <td>{{ $fut_historial_pago->representante_ventas }}
                                                                        </td>
                                                                        <td>{{ $fut_historial_pago->metodo_pago_2 }}</td>
                                                                        <td>{{ $fut_historial_pago->rfc_2 }}</td>
                                                                        <td>{{ $fut_historial_pago->uso_de_cfdi_para_pago }}
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de cargar archivos --}}
    <div class="modal modal-blur" id="modalCargarConciliaciones" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Importar archivos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('conciliacion_pagos-importBancosAndNetsuiteReporte') }}" method="POST"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="space-y">
                            <div class="mb-3">
                                <label class="form-label required">Cargar archivo de excel de bancos</label>
                                <input type="file" class="form-control" name="bancos" id="bancos"
                                    accept=".xlsx,.xls" required>
                                <div class="invalid-feedback">Debes seleccionar un archivo</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Cargar archivo de NetSuite</label>
                                <input type="file" class="form-control" name="fut_historial_pagos"
                                    id="fut_historial_pagos" accept=".xlsx,.xls" required>
                                <div class="invalid-feedback">Debes seleccionar un archivo</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Cargar archivo de información primaria</label>
                                <input type="file" class="form-control" name="primaria" id="primaria"
                                    accept=".xlsx,.xls" required>
                                <div class="invalid-feedback">Debes seleccionar un archivo</div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary btn-4 w-100">
                                    Importar
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-right icon-2">
                                        <path d="M5 12l14 0"></path>
                                        <path d="M13 18l6 -6"></path>
                                        <path d="M13 6l6 6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>


                {{-- <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                </div> --}}
            </div>
        </div>
    </div>

    @include('conciliacion_pagos.indexjs')
@endsection

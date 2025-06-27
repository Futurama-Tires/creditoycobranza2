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
                                Estados de cuenta
                            </h2>
                        </div>
                        <div class="col-auto">
                            {{-- <div class="btn-list">
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
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <div class="mb-6">
                        <label class="form-label required">Seleccionar Empleado</label>
                        <select class="form-select" name="empleado_id" id="empleados" multiple required></select>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        new TomSelect("#empleados", {
            placeholder: "Busca y selecciona empleados...",
            maxItems: 1, // Permite selección múltiple
            maxOptions: 10, // Máximo número de opciones visibles
            valueField: "id",
            labelField: "nombre",
            searchField: ['nombre', 'paterno', 'materno'],
            sortField: "nombre",
            create: false, // No permitir agregar nuevos valores
            load: function(query, callback) {
                fetch(`/obtenerEmpleados?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => callback(data
                        .data)) // Asegurarse de usar la clave correcta del JSON
                    .catch(() => callback());
            },
            render: {
                option: function(data, escape) {
                    return `
                                    <div>
                                        <span class="title">${escape(data.nombre)} ${escape(data.paterno)} ${escape(data.materno)}</span>
                                        <br>
                                    </div>
                                `;
                },
                item: function(data, escape) {
                    return `
                                    <div title="${escape(data.categoria)}">
                                        ${escape(data.nombre)} ${escape(data.paterno)} ${escape(data.materno)}
                                    </div>
                                `;
                }
            }
        });
    </script>
@endsection

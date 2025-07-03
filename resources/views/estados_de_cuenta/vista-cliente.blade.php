@extends('layouts.app')

@section('title', 'Vista Cliente')

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
                                    <li class="breadcrumb-item active" aria-current="page"><a href="#">Estado de
                                            cuenta</a>
                                    </li>
                                </ol>
                            </div>
                            <h2 class="page-title">
                                Estado de cuenta
                            </h2>
                        </div>
                        <div class="col-auto">
                            {{-- <div class="btn-list">
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalCargarConciliaciones">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
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

            <a href="#" class="btn btn-2 ms-auto" data-bs-toggle="modal" data-bs-target="#modal-consultar_estado">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-circles-relation">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9.183 6.117a6 6 0 1 0 4.511 3.986" />
                    <path d="M14.813 17.883a6 6 0 1 0 -4.496 -3.954" />
                </svg>
                Agregar Responsiva
            </a>
        </div>
    </div>
@endsection
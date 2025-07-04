@extends('layouts.app')

@section('title', 'Estados de cuenta')

@section('content')
    <style>
        /* Asegura que el dropdown de TomSelect se muestre fuera del modal */
        .modal {
            overflow: visible !important;
        }

        .ts-dropdown {
            position: absolute !important;
            z-index: 10000 !important;
            /* Mayor que el z-index del modal */
            width: 100%;
        }

        /* Asegura que el contenedor del select tenga posición relativa */
        .form-select.z-index-2 {
            position: relative;
            z-index: 2;
        }
    </style>
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
                                Estados de cuenta - Marcelo Gutiérrez Briones
                            </h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="d-flex">
                                <div class="me-3">
                                    <form action="{{ route('getFacturasPagosNDC') }}" method="POST" id="addResponsivaForm">
                                        @csrf
                                        <div class="input-icon">
                                            <select class="form-select z-index-2" name="customer_id" id="select-beast-empty"
                                                required></select>
                                            <span class="input-icon-addon">
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/search -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                    <path d="M21 21l-6 -6"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </form>
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

                        <div class="col-sm-12 col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row gy-3">
                                        <div class="col-12 col-sm d-flex flex-column">
                                            <h3 class="h2">Resúmen General</h3>
                                            <p class="text-muted">Procentaje de facturas No Vencidas y Vencidas</p>

                                            <!-- This pushes everything below down -->
                                            <div class="mt-auto">
                                                <div class="progress progress-separated mb-3">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: 50%" aria-label="No Vencido"></div>
                                                    <div class="progress-bar bg-danger" role="progressbar"
                                                        style="width: 50%" aria-label="Vencidas"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-auto d-flex align-items-center pe-2">
                                                        <span class="legend me-2 bg-primary"></span>
                                                        <span>No Vencido</span>
                                                        <span class="d-none d-md-inline ms-2 text-secondary">5</span>

                                                    </div>
                                                    <div class="col-auto d-flex align-items-center px-2">
                                                        <span class="legend me-2 bg-danger"></span>
                                                        <span>Vencidas</span>
                                                        <span class="d-none d-md-inline ms-2 text-secondary">5</span>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="subheader">Sales</div>
                                        <div class="ms-auto lh-1">
                                            <div class="dropdown">
                                                <a class="dropdown-toggle text-secondary" id="sales-dropdown" href="#"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    aria-label="Select time range for sales data">Last 7 days</a>
                                                <div class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="sales-dropdown">
                                                    <a class="dropdown-item active" href="#" aria-current="true">Last 7
                                                        days</a>
                                                    <a class="dropdown-item" href="#">Last 30 days</a>
                                                    <a class="dropdown-item" href="#">Last 3 months</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="h1 mb-3">75%</div>
                                    <div class="d-flex mb-2">
                                        <div>Conversion rate</div>
                                        <div class="ms-auto">
                                            <span class="text-green d-inline-flex align-items-center lh-1">
                                                7%
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/trending-up -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" class="icon ms-1 icon-2">
                                                    <path d="M3 17l6 -6l4 4l8 -8"></path>
                                                    <path d="M14 7l7 0l0 7"></path>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-primary" style="width: 75%" role="progressbar"
                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                            aria-label="75% Complete">
                                            <span class="visually-hidden">75% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card">
                                <div class="card-body">

                                    <div class="d-flex align-items-center">
                                        <div class="subheader">Saldo a Favor</div>

                                    </div>
                                    <div class="h1 mb-0 me-2">$4,300</div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="subheader">Total Saldos Vencidos</div>
                                    <div class="d-flex align-items-baseline mb-2">
                                        <div class="h1 mb-0 me-2">$25,782.01</div>
                                        <div class="me-auto">
                                            <span class="text-red d-inline-flex align-items-center lh-1">

                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <canvas id="saldosVencidos"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="subheader">Total Saldos a Favor</div>
                                    <div class="d-flex align-items-baseline mb-2">
                                        <div class="h1 mb-0 me-2">$25,782.01</div>
                                        <div class="me-auto">
                                            <span class="text-red d-inline-flex align-items-center lh-1">

                                            </span>
                                        </div>
                                    </div>
                                    <div class="progressbg">
                                        <div class="progress progressbg-progress">
                                            <div class="progress-bar bg-primary-lt" style="width: 65%"></div>
                                        </div>
                                        <div class="progressbg-text">Poland</div>
                                        <div class="progressbg-value">65%</div>
                                    </div>
                                    <div class="progressbg">
                                        <div class="progress progressbg-progress">
                                            <div class="progress-bar bg-primary-lt" style="width: 35%"></div>
                                        </div>
                                        <div class="progressbg-text">Germany</div>
                                        <div class="progressbg-value">35%</div>
                                    </div>
                                    <div class="progressbg">
                                        <div class="progress progressbg-progress">
                                            <div class="progress-bar bg-primary-lt" style="width: 28%"></div>
                                        </div>
                                        <div class="progressbg-text">United Stated</div>
                                        <div class="progressbg-value">28%</div>
                                    </div>
                                    <div class="progressbg">
                                        <div class="progress progressbg-progress">
                                            <div class="progress-bar bg-primary-lt" style="width: 20%"></div>
                                        </div>
                                        <div class="progressbg-text">United Kingdom</div>
                                        <div class="progressbg-value">20%</div>
                                    </div>
                                    <div class="progressbg">
                                        <div class="progress progressbg-progress">
                                            <div class="progress-bar bg-primary-lt" style="width: 15%"></div>
                                        </div>
                                        <div class="progressbg-text">France</div>
                                        <div class="progressbg-value">15%</div>
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

    @include('estados_de_cuenta.cliente_estado_tom')

@endsection

@section('scripts')
    {{-- Saldos Vencidos --}}
    <script>
        const ctx = document.getElementById('saldosVencidos');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    '1-30',
                    '31-60',
                    '61-90',
                    '91-120',
                    '>120'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [300, 50, 100, 30, 40],
                    backgroundColor: [
                        'rgb(127, 28, 145)',
                        'rgb(42, 163, 33)',
                        'rgb(209, 215, 11)',
                        'rgb(17, 42, 236)',
                        'rgb(220, 36, 16)',
                    ],
                    hoverOffset: 4
                }]
            },
        });
    </script>
    {{-- Saldos a Favor --}}
    <script>
        const ctx1 = document.getElementById('saldosAFavor');

        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: [
                    '1-30',
                    '31-60',
                    '61-90',
                    '91-120',
                    '>120'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [300, 50, 100, 30, 40],
                    backgroundColor: [
                        'rgb(127, 28, 145)',
                        'rgb(42, 163, 33)',
                        'rgb(209, 215, 11)',
                        'rgb(17, 42, 236)',
                        'rgb(220, 36, 16)',
                    ],
                    hoverOffset: 4
                }]
            },
        });
    </script>


@endsection
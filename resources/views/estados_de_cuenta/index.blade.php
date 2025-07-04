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

                        {{-- RESUMEN GENERAL --}}
                        <div class="col-sm-12 col-lg-6">
                            <div class="row row-deck row-cards">
                                <div class="col-sm-6 col-lg-12">
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
                                                                <span
                                                                    class="d-none d-md-inline ms-2 text-secondary">5</span>

                                                            </div>
                                                            <div class="col-auto d-flex align-items-center px-2">
                                                                <span class="legend me-2 bg-danger"></span>
                                                                <span>Vencidas</span>
                                                                <span
                                                                    class="d-none d-md-inline ms-2 text-secondary">5</span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- TOTAL SALDO VENCIDOS --}}
                                <div class="col-sm-12 col-lg-6">
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
                                {{-- TOTAL SALDOS A FAVOR --}}
                                <div class="col-sm-12 col-lg-6">
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
                                                    <div class="progress-bar bg-primary-lt" style="width: 65%">
                                                    </div>
                                                </div>
                                                <div class="progressbg-text">Poland</div>
                                                <div class="progressbg-value">65%</div>
                                            </div>
                                            <div class="progressbg">
                                                <div class="progress progressbg-progress">
                                                    <div class="progress-bar bg-primary-lt" style="width: 35%">
                                                    </div>
                                                </div>
                                                <div class="progressbg-text">Germany</div>
                                                <div class="progressbg-value">35%</div>
                                            </div>
                                            <div class="progressbg">
                                                <div class="progress progressbg-progress">
                                                    <div class="progress-bar bg-primary-lt" style="width: 28%">
                                                    </div>
                                                </div>
                                                <div class="progressbg-text">United Stated</div>
                                                <div class="progressbg-value">28%</div>
                                            </div>
                                            <div class="progressbg">
                                                <div class="progress progressbg-progress">
                                                    <div class="progress-bar bg-primary-lt" style="width: 20%">
                                                    </div>
                                                </div>
                                                <div class="progressbg-text">United Kingdom</div>
                                                <div class="progressbg-value">20%</div>
                                            </div>
                                            <div class="progressbg">
                                                <div class="progress progressbg-progress">
                                                    <div class="progress-bar bg-primary-lt" style="width: 15%">
                                                    </div>
                                                </div>
                                                <div class="progressbg-text">France</div>
                                                <div class="progressbg-value">15%</div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SALDOS EN GENERAL --}}
                        <div class="col-md-12 col-lg-6 d-block">
                            <div class="row row-deck row-cards">
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="subheader">Subtotal vencido</div>
                                            </div>
                                            <div class="d-flex align-items-baseline">
                                                <div class="h1 me-2 text-yellow">$6,782</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="subheader">Subtotal no vencido</div>
                                            </div>
                                            <div class="d-flex align-items-baseline">
                                                <div class="h1 me-2 text-green">$2,986</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="subheader">Saldo total</div>
                                            </div>
                                            <div class="d-flex align-items-baseline">
                                                <div class="h1 me-2 text-primary">$6,782</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">Saldo del cliente</h3>
                                </div>
                                <table class="table card-table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Días de vencimiento</th>
                                            <th class="text-end" style="width: 1%">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="text-secondary">Más de 120 días</div>
                                            </td>
                                            <td class="text-end">$1.800,00</td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <div class="text-secondary">De 91 a 120 días</div>
                                            </td>
                                            <td class="text-end">$1.800,00</td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <div class="text-secondary">De 61 a 90 días</div>
                                            </td>
                                            <td class="text-end">$1.800,00</td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <div class="text-secondary">De 31 a 60 días</div>
                                            </td>
                                            <td class="text-end">$1.800,00</td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <div class="text-secondary">De 01 a 30 días</div>
                                            </td>
                                            <td class="text-end">$1.800,00</td>
                                        </tr>
                                        {{-- <tr>
                                            <td colspan="1" class="text-end">Subtotal vencido</td>
                                            <td class="text-end text-red">$25.000,00</td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="text-end">Subtotal no vencido</td>
                                            <td class="text-end text-green">$25.000,00</td>
                                        </tr>

                                        <tr>
                                            <td colspan="1" class="strong text-uppercase text-end">
                                                Saldo total</td>
                                            <td class="strong text-end text-primary">$30.000,00</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>


                        </div>



                        {{-- TABLA FACTURAS Y SALDOS A FAVOR --}}
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Saldos pendientes</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-selectable card-table table-vcenter ">
                                        <thead>
                                            <tr>
                                                <th>Fecha de transacción</th>
                                                <th>Tipo de transacción</th>
                                                <th>No. Documento</th>
                                                <th>Folio SAT</th>
                                                <th>Fecha de vencimiento</th>
                                                <th>Días vencidos</th>
                                                <th>Importe total</th>
                                                <th>Saldo pendiente</th>
                                                <th>Moneda</th>
                                                <th>Estatus</th>
                                                <th>Nota de factura</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>25/06/2025</td>

                                                <td><span class="text-secondary">FACTURA</span></td>

                                                <td><a href="invoice.html" class="text-reset" tabindex="-1">FAC-IXT78944</a>
                                                </td>

                                                <td>694780</td>

                                                <td>25/06/2025</td>

                                                <td>2</td>

                                                <td>$4,923.72</td>

                                                <td>$4,923.72</td>

                                                <td>
                                                    <span class="flag flag-xs flag-country-mx me-2"></span>
                                                    MEX
                                                </td>
                                                <td><span class="badge bg-success me-1"></span>Factura abierta</td>
                                                <td>
                                                    PONIENTE
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>25/06/2025</td>

                                                <td><span class="text-secondary">FACTURA</span></td>

                                                <td><a href="invoice.html" class="text-reset" tabindex="-1">FAC-IXT78944</a>
                                                </td>

                                                <td>694780</td>

                                                <td>25/06/2025</td>

                                                <td>2</td>

                                                <td>$4,923.72</td>

                                                <td>$4,923.72</td>

                                                <td>
                                                    <span class="flag flag-xs flag-country-mx me-2"></span>
                                                    MEX
                                                </td>
                                                <td><span class="badge bg-success me-1"></span>Factura abierta</td>
                                                <td>
                                                    PONIENTE
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>25/06/2025</td>

                                                <td><span class="text-secondary">FACTURA</span></td>

                                                <td><a href="invoice.html" class="text-reset" tabindex="-1">FAC-IXT78944</a>
                                                </td>

                                                <td>694780</td>

                                                <td>25/06/2025</td>

                                                <td>2</td>

                                                <td>$4,923.72</td>

                                                <td>$4,923.72</td>

                                                <td>
                                                    <span class="flag flag-xs flag-country-mx me-2"></span>
                                                    MEX
                                                </td>
                                                <td><span class="badge bg-success me-1"></span>Factura abierta</td>
                                                <td>
                                                    PONIENTE
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>




                            </div>
                        </div>

                        {{-- TABLA SALDOS A FAVOR --}}
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Saldos a favor</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-selectable card-table table-vcenter table-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Fecha de transacción</th>
                                                <th>Tipo de transacción</th>
                                                <th>No. Documento</th>
                                                <th>Folio SAT</th>
                                                <th>Fecha de vencimiento</th>
                                                <th>Días vencidos</th>
                                                <th>Importe total</th>
                                                <th>Saldo pendiente</th>
                                                <th>Moneda</th>
                                                <th>Estatus</th>
                                                <th>Nota</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>25/06/2025</td>

                                                <td><span class="text-secondary">FACTURA</span></td>

                                                <td><a href="invoice.html" class="text-reset" tabindex="-1">FAC-IXT78944</a>
                                                </td>

                                                <td>694780</td>

                                                <td>25/06/2025</td>

                                                <td>2</td>

                                                <td>$4,923.72</td>

                                                <td>$4,923.72</td>

                                                <td>
                                                    <span class="flag flag-xs flag-country-mx me-2"></span>
                                                    MEX
                                                </td>
                                                <td><span class="badge bg-success me-1"></span>Factura abierta</td>
                                                <td>
                                                    PONIENTE
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>25/06/2025</td>

                                                <td><span class="text-secondary">FACTURA</span></td>

                                                <td><a href="invoice.html" class="text-reset" tabindex="-1">FAC-IXT78944</a>
                                                </td>

                                                <td>694780</td>

                                                <td>25/06/2025</td>

                                                <td>2</td>

                                                <td>$4,923.72</td>

                                                <td>$4,923.72</td>

                                                <td>
                                                    <span class="flag flag-xs flag-country-mx me-2"></span>
                                                    MEX
                                                </td>
                                                <td><span class="badge bg-success me-1"></span>Factura abierta</td>
                                                <td>
                                                    PONIENTE
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>25/06/2025</td>

                                                <td><span class="text-secondary">FACTURA</span></td>

                                                <td><a href="invoice.html" class="text-reset" tabindex="-1">FAC-IXT78944</a>
                                                </td>

                                                <td>694780</td>

                                                <td>25/06/2025</td>

                                                <td>2</td>

                                                <td>$4,923.72</td>

                                                <td>$4,923.72</td>

                                                <td>
                                                    <span class="flag flag-xs flag-country-mx me-2"></span>
                                                    MEX
                                                </td>
                                                <td><span class="badge bg-success me-1"></span>Factura abierta</td>
                                                <td>
                                                    PONIENTE
                                                </td>
                                            </tr>
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

    @include('estados_de_cuenta.cliente_estado_tom')

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new TomSelect("#select-beast-empty", {
                placeholder: "Selecciona un cliente",
                maxItems: 1,
                allowEmptyOption: true,
                create: false,
                dropdownParent: 'body',
                valueField: 'customer_id', // Usar el ID como valor
                labelField: 'altname', // Usar altname para mostrar
                searchField: 'altname', // Buscar por altname
                load: function (query, callback) {
                    $.ajax({
                        url: "/netsuite/get-customers",
                        data: {
                            query: query
                        },
                        success: function (response) {
                            console.log('Data received:', response);

                            // Verificar si la respuesta tiene la estructura esperada
                            if (response.items && Array.isArray(response.items)) {
                                // Mapear los items al formato que TomSelect espera
                                const formattedData = response.items.map(item => ({
                                    customer_id: item.customer_id,
                                    altname: item.altname
                                }));

                                console.log('Formatted data:', formattedData);
                                callback(formattedData);
                            } else {
                                console.error('Unexpected response structure');
                                callback([]);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error:', error);
                            callback([]);
                        }
                    });
                },
                render: {
                    option: function (item, escape) {
                        return '<div>' + escape(item.altname) + '</div>';
                    },
                    item: function (item, escape) {
                        return '<div>' + escape(item.altname) + '</div>';
                    }
                },
                maxOptions: 100,

                onInitialize: function () {
                    console.log('TomSelect Initialized');
                }
            });
        });
    </script>
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
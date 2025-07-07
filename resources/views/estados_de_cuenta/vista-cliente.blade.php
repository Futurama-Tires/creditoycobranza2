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
                                Estados de cuenta - Marcelo Gutiérrez Briones
                            </h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="d-flex">
                                <div class="me-3">
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
                                                                style="width: 0%"
                                                                data-width="{{ $porcentajes[0]['no_vencido'] }}"
                                                                aria-label="No Vencido"></div>

                                                            <div class="progress-bar bg-danger" role="progressbar"
                                                                style="width: 0%"
                                                                data-width="{{ $porcentajes[0]['vencido'] }}"
                                                                aria-label="Vencidas"></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-auto d-flex align-items-center pe-2">
                                                                <span class="legend me-2 bg-primary"></span>
                                                                <span>No Vencido</span>
                                                                <span
                                                                    class="d-none d-md-inline ms-2 text-secondary">{{$countNoVencidos}}</span>

                                                            </div>
                                                            <div class="col-auto d-flex align-items-center px-2">
                                                                <span class="legend me-2 bg-danger"></span>
                                                                <span>Vencidas</span>
                                                                <span
                                                                    class="d-none d-md-inline ms-2 text-secondary">{{$countVencidos}}</span>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- TOTAL SALDO VENCIDOS --}}
                                <div class="col-sm-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="subheader">Total Saldos Vencidos</div>
                                            <div class="d-flex align-items-baseline mb-2">
                                                <div class="h1 mb-0 me-2">
                                                    {{'$' . number_format($saldos['totalVencidas'], 2, '.', ',')}}
                                                </div>
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
                                                <div class="h1 me-2 text-yellow">
                                                    {{'$' . number_format($saldos['totalVencidas'], 2, '.', ',')}}
                                                </div>
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
                                                <div class="h1 me-2 text-green">
                                                    {{'$' . number_format($saldos['totalNoVencidas'], 2, '.', ',')}}
                                                </div>
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
                                                <div class="h1 me-2 text-primary">
                                                    {{'$' . number_format($saldos['saldo_total'], 2, '.', ',')}}
                                                </div>
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
                                            <td class="text-end">
                                                {{'$' . number_format($saldos['mayor_a_120'], 2, '.', ',')}}
                                            </td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <div class="text-secondary">De 91 a 120 días</div>
                                            </td>
                                            <td class="text-end">
                                                {{'$' . number_format($saldos['91_120'], 2, '.', ',')}}
                                            </td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <div class="text-secondary">De 61 a 90 días</div>
                                            </td>
                                            <td class="text-end">
                                                {{'$' . number_format($saldos['61_90'], 2, '.', ',')}}
                                            </td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <div class="text-secondary">De 31 a 60 días</div>
                                            </td>
                                            <td class="text-end">
                                                {{'$' . number_format($saldos['31_60'], 2, '.', ',')}}
                                            </td>
                                        </tr>
                                        <tr>

                                            <td>
                                                <div class="text-secondary">De 01 a 30 días</div>
                                            </td>
                                            <td class="text-end">
                                                {{'$' . number_format($saldos['1_30'], 2, '.', ',')}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                        </div>



                        {{-- TABLA FACTURAS --}}
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Saldos pendientes</h3>
                                </div>
                                <div class="table-responsive">
                                    <table id="facturasTable"
                                        class="table table-selectable card-table table-vcenter table-nowrap">
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($facturas as $factura)
                                                <tr>
                                                    <td>{{ $factura['transaction_date'] }}</td>

                                                    <td><span class="text-secondary">
                                                            @if(Str::startsWith($factura['document_number'], 'FAC'))
                                                                FACTURA
                                                            @else
                                                                ERROR
                                                            @endif
                                                        </span></td>

                                                    <td><a href="invoice.html" class="text-reset"
                                                            tabindex="-1">{{ $factura['document_number'] }}</a>
                                                    </td>

                                                    <td>{{ $factura['folio_sat'] }}</td>

                                                    <td>{{ $factura['due_date'] }}</td>

                                                    <td>{{ $factura['dias_vencidos'] }}</td>

                                                    <td>{{ $factura['total_amount'] }}</td>

                                                    <td>{{ $factura['amount_unpaid'] }}</td>

                                                    <td>
                                                        <span class="flag flag-xs flag-country-mx me-2"></span>
                                                        {{ $factura['currency_name'] }}
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

    <style>
        .progress-bar {
            transition: width 1.5s ease;
            /* Adjust duration as you like */
        }
    </style>
@endsection

@section('scripts')

    {{-- animacion barrita --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const bars = document.querySelectorAll('.progress-bar');
            bars.forEach(bar => {
                const width = bar.getAttribute('data-width');
                bar.style.width = width + '%';
            });
        });
    </script>
    {{-- Saldos Vencidos --}}
    <script>
        // Definir etiquetas de los meses
        const labels = ['1-30', '31-60', '61-90', '91-120', '>120'];

        // Datos del gráfico
        const data = {
            labels: labels,
            datasets: [{
                label: 'Saldo Vencido',
                data: [{{$saldos['1_30']}}, {{$saldos['31_60']}}, {{$saldos['61_90']}}, {{$saldos['91_120']}}, {{$saldos['mayor_a_120']}}],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)'
                ],
                borderWidth: 1
            }]
        };

        // Configuración del gráfico
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };

        // Crear el gráfico
        const saldosVencidos = new Chart(
            document.getElementById('saldosVencidos'),
            config
        );
    </script>


    <script>
        $(document).ready(function () {
            $('#facturasTable').DataTable({
                // Optional: customize language to Spanish
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                },
                // Optional: adjust other settings as needed
                "pageLength": 5,
                "order": [],
                "columnDefs": [
                    {
                        // Column indexes are zero-based: column 7 is index 6
                        "targets": [6, 7],
                        "render": $.fn.dataTable.render.number(',', '.', 2, '$')
                    },
                    {
                        // Tell column 0 to sort as European date (DD/MM/YYYY)
                        "targets": [0, 4],
                        "type": "date-eu"
                    }

                ],
            });
        });
    </script>

@endsection
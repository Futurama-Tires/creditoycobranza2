<div class="page">
    {{-- Loader --}}
    <div class="fullscreenDiv" id="loader" wire:loading wire:target="selectCliente">
        <div class="spinner">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="page-header">
            <div class="container-xl">
                <div class="row align-items-center mw-100">
                    <div class="col">
                        <div class="page-pretitle">
                            <ol class="breadcrumb" aria-label="breadcrumbs">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#">Estados de
                                        cuenta de clientes</a></li>
                            </ol>
                        </div>
                        <h2 class="page-title">
                            Estados de cuenta de clientes
                        </h2>
                    </div>
                    <div class="col-7 col-md-6">
                        <div class="col-auto ms-auto d-print-none">
                            <div class="d-flex">

                                <a href="#" wire:click="descargarEstadoCuenta"
                                    class="btn btn-primary me-2 d-none d-md-inline-block {{ $codigoCliente ? '' : 'disabled' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                        <path d="M7 11l5 5l5 -5" />
                                        <path d="M12 4l0 12" />
                                    </svg>
                                    Descargar estado de cuenta
                                </a>

                                <a href="#" wire:click="descargarEstadoCuenta"
                                    class="btn btn-primary me-1 d-md-none btn-icon {{ $codigoCliente ? '' : 'disabled' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                        <path d="M7 11l5 5l5 -5" />
                                        <path d="M12 4l0 12" />
                                    </svg>
                                </a>

                                <div class="position-relative w-100 ">
                                    <div class="input-icon">
                                        <input type="text" class="form-control w-100" placeholder="Buscar cliente…"
                                            wire:model.live.debounce.1000ms="search">
                                        <span class="input-icon-addon">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-1">
                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                <path d="M21 21l-6 -6"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    @if ($search)
                                        {{-- Contenedor flotante en la misma posición del input --}}
                                        <div class="dropdown-menu show w-100 shadow-none p-0"
                                            style="max-height:18rem;overflow-y:auto;z-index:1000;">

                                            @if (count($suggestions) > 0)
                                                @foreach ($suggestions as $c)
                                                    {{-- Cada fila: enlace clicable que actúa como item --}}
                                                    <a href="#" wire:loading.class="disabled"
                                                        wire:click.prevent="selectCliente({{ $c['customer_id'] }})"
                                                        class="dropdown-item d-flex align-items-center gap-2 py-2">

                                                        {{-- Avatar (iniciales) con color aleatorio según ID --}}
                                                        <span
                                                            class="avatar avatar-sm rounded d-inline-flex align-items-center justify-content-center
                                                        {{ [
                                                            'bg-primary-lt',
                                                            'bg-success-lt',
                                                            'bg-info-lt',
                                                            'bg-warning-lt',
                                                            'bg-danger-lt',
                                                            'bg-secondary-lt',
                                                            'bg-dark-lt',
                                                        ][$c['customer_id'] % 7] }}
                                                        text-white">
                                                            {{ Str::substr($c['altname'], 0, 2) }}
                                                        </span>

                                                        {{-- Texto (nombre + subtexto) --}}
                                                        <div class="flex-fill lh-sm">
                                                            <div class="fw-bold">{{ $c['altname'] }}</div>
                                                            <small
                                                                class="text-secondary">ID #{{ $c['customer_id'] }}</small>
                                                        </div>

                                                        {{-- Indicador de estado --}}
                                                        <span class="status-dot status-dot-animated bg-green"></span>
                                                    </a>
                                                @endforeach
                                            @else
                                                {{-- Mensaje si no se encontraron resultados --}}
                                                <div class="dropdown-item text-center text-muted py-3">
                                                    <div flex-fill lh-sm>
                                                        <div class="fw-semibold">Cliente no encontrado</div>
                                                        <small>Intenta con otro nombre</small>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Botón para cerrar el buscador --}}
                                            <div class="dropdown-item text-center border-top">
                                                <button wire:click="$set('search', '')"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    Cerrar
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>


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
                    <div class="col-md-12 col-lg-6">
                        <div class="row row-deck row-cards">
                            <div class="col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row gy-3">
                                            <div class="col-12 col-sm d-flex flex-column">
                                                <h3 class="h2">RESÚMEN GENERAL - {{ $clienteNombre }} </h3>
                                                <p class="text-muted">Procentaje de facturas No Vencidas y Vencidas</p>

                                                <!-- This pushes everything below down -->
                                                <div class="mt-auto">
                                                    <div class="progress progress-separated mb-3">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            style="width: {{ $porcentajesFacturas['porcentajeNoVencido'] ?? 0 }}%"
                                                            aria-label="No Vencido"></div>
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: {{ $porcentajesFacturas['porcentajeVencido'] ?? 0 }}%"
                                                            aria-label="Vencidas"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-auto d-flex align-items-center pe-2">
                                                            <span class="legend me-2 bg-primary"></span>
                                                            <span>Por vencer</span>
                                                            <span
                                                                class="d-none d-md-inline ms-2 text-secondary">{{ $facturasNoVencidas }}</span>

                                                        </div>
                                                        <div class="col-auto d-flex align-items-center px-2">
                                                            <span class="legend me-2 bg-danger"></span>
                                                            <span>Vencidas</span>
                                                            <span
                                                                class="d-none d-md-inline ms-2 text-secondary">{{ $facturasVencidas }}</span>
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
                                        <div class="d-flex align-items-baseline mb-2 text-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-caret-down">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M18 9c.852 0 1.297 .986 .783 1.623l-.076 .084l-6 6a1 1 0 0 1 -1.32 .083l-.094 -.083l-6 -6l-.083 -.094l-.054 -.077l-.054 -.096l-.017 -.036l-.027 -.067l-.032 -.108l-.01 -.053l-.01 -.06l-.004 -.057v-.118l.005 -.058l.009 -.06l.01 -.052l.032 -.108l.027 -.067l.07 -.132l.065 -.09l.073 -.081l.094 -.083l.077 -.054l.096 -.054l.036 -.017l.067 -.027l.108 -.032l.053 -.01l.06 -.01l.057 -.004l12.059 -.002z" />
                                            </svg>
                                            <div class="h1 mb-0 me-2">

                                                ${{ $saldosFacturas['subtotalVencido'] ?? '0.00' }}
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
                                        <div class="d-flex align-items-baseline mb-2 text-green">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="currentColor"
                                                class="icon icon-tabler icons-tabler-filled icon-tabler-caret-up">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M11.293 7.293a1 1 0 0 1 1.32 -.083l.094 .083l6 6l.083 .094l.054 .077l.054 .096l.017 .036l.027 .067l.032 .108l.01 .053l.01 .06l.004 .057l.002 .059l-.002 .059l-.005 .058l-.009 .06l-.01 .052l-.032 .108l-.027 .067l-.07 .132l-.065 .09l-.073 .081l-.094 .083l-.077 .054l-.096 .054l-.036 .017l-.067 .027l-.108 .032l-.053 .01l-.06 .01l-.057 .004l-.059 .002h-12c-.852 0 -1.297 -.986 -.783 -1.623l.076 -.084l6 -6z" />
                                            </svg>
                                            <div class="h1 mb-0 me-2">
                                                ${{ number_format($totalSaldoAFavor, 2, '.', ',') }}</div>
                                        </div>

                                        @php $counter = 0; @endphp
                                        @forelse($datosSaldoAFavorProgress as $saldoAFavor)
                                            <div class="progressbg mb-1">
                                                <div class="progress progressbg-progress">
                                                    <div class="progress-bar bg-primary-lt"
                                                        style="width: {{ $saldoAFavor['porcentaje'] }}%">
                                                    </div>
                                                </div>
                                                <div class="progressbg-text">
                                                    <div class="fs-4">
                                                        {{ $saldoAFavor['document_number'] }}
                                                    </div>
                                                    <div class="text-secondary fs-6">
                                                        (${{ number_format($saldoAFavor['payment_amount_unused'], 2) }})
                                                    </div>
                                                </div>
                                                <div class="progressbg-value">
                                                    {{ number_format($saldoAFavor['porcentaje'], 2) }}%</div>
                                            </div>
                                            @php $counter++; @endphp

                                        @empty
                                            <div class="empty-icon mx-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-click">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 12l3 0" />
                                                    <path d="M12 3l0 3" />
                                                    <path d="M7.8 7.8l-2.2 -2.2" />
                                                    <path d="M16.2 7.8l2.2 -2.2" />
                                                    <path d="M7.8 16.2l-2.2 2.2" />
                                                    <path d="M12 12l9 3l-4 2l-2 4l-3 -9" />
                                                </svg>
                                                </svg>
                                            </div>
                                            <p class="empty-title text-center">Sin resultados</p>
                                            <p class="empty-subtitle text-secondary text-center">
                                                No hay información disponible.
                                            </p>
                                        @endforelse
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
                                                ${{ $saldosFacturas['subtotalVencido'] ?? '0.00' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="subheader">Subtotal por vencer</div>
                                        </div>
                                        <div class="d-flex align-items-baseline">
                                            <div class="h1 me-2 text-green">
                                                ${{ $saldosFacturas['subtotalNoVencido'] ?? '0.00' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="subheader">Saldo total</div>
                                        </div>
                                        <div class="d-flex align-items-baseline">
                                            <div class="h1 me-2 text-primary">
                                                ${{ $saldosFacturas['saldoTotal'] ?? '0.00' }}
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

                                    @forelse ($facturasVencidasTabla ?? [] as $rango => $monto)
                                        <tr>
                                            <td>
                                                <div class="text-secondary">
                                                    @php
                                                        $textoRango = match ($rango) {
                                                            'noPagado_1_30' => 'De 01 a 30 días',
                                                            'noPagado_31_60' => 'De 31 a 60 días',
                                                            'noPagado_61_90' => 'De 61 a 90 días',
                                                            'noPagado_91_120' => 'De 91 a 120 días',
                                                            'noPagado_mas_120' => 'Más de 120 días',
                                                            default => $rango, // Por si hay una clave no esperada
                                                        };
                                                    @endphp
                                                    {{ $textoRango }}
                                                </div>
                                            </td>
                                            <td class="text-end">${{ $monto }}</td>
                                        </tr>
                                    @empty
                                        <div class="empty">
                                            <div class="empty-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-click">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 12l3 0" />
                                                    <path d="M12 3l0 3" />
                                                    <path d="M7.8 7.8l-2.2 -2.2" />
                                                    <path d="M16.2 7.8l2.2 -2.2" />
                                                    <path d="M7.8 16.2l-2.2 2.2" />
                                                    <path d="M12 12l9 3l-4 2l-2 4l-3 -9" />
                                                </svg>
                                                </svg>
                                            </div>
                                            <p class="empty-title">Sin resultados</p>
                                            <p class="empty-subtitle text-secondary">
                                                Selecciona un cliente para obtener su información.
                                            </p>
                                        </div>
                                    @endforelse
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
                            <div wire:ignore>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-selectable card-table table-vcenter table-nowrap"
                                            id="tabla-facturas">
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
                                                    <th>% Pagado</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Saldos a favor</h3>
                            </div>
                            <div wire:ignore>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-selectable card-table table-vcenter table-nowrap"
                                            id="tabla-saldos-a-favor">
                                            <thead>
                                                <tr>
                                                    <th>Fecha de transacción</th>
                                                    <th>Tipo de transacción</th>
                                                    <th>No. Documento</th>
                                                    <th>Folio SAT</th>
                                                    <th>Importe total</th>
                                                    <th>Saldo sin usar</th>
                                                    <th>Moneda</th>
                                                    <th>Estatus</th>
                                                </tr>
                                            </thead>
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

@section('scripts')
    {{-- Script para cargar gráfico --}}
    <script>
        document.addEventListener('livewire:init', () => {
            let chartFacVencidas; // referencia global a la instancia Chart.js

            Livewire.on('actualizar-grafico-fac-vencidas', rangos => {
                // ── 1. Datos en el orden de las etiquetas ─────────────────────────
                const dataset = [
                    rangos[0].noPagado_1_30,
                    rangos[0].noPagado_31_60,
                    rangos[0].noPagado_61_90,
                    rangos[0].noPagado_91_120,
                    rangos[0].noPagado_mas_120,
                ];

                // ── 2. Si el gráfico existe, solo actualiza; si no, créalo ─────────
                if (chartFacVencidas) {
                    chartFacVencidas.data.datasets[0].data = dataset;
                    chartFacVencidas.update();
                    return; // ← listo, no creamos nada nuevo
                }

                // Primera vez: instanciar
                const ctx = document.getElementById('saldosVencidos').getContext('2d');
                chartFacVencidas = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['1‑30', '31‑60', '61‑90', '91‑120', '>120'],
                        datasets: [{
                            data: dataset,
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
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            });
        });
    </script>

    {{-- DataTables --}}
    {{-- Factuas pendientes --}}
    <script>
        document.addEventListener('livewire:init', () => {

            const table = $('#tabla-facturas').DataTable({
                data: [], // Inicialmente vacío
                ordering: true, // Permite ordenamiento manual
                "pageLength": 5,
                columns: [{
                        data: 'transaction_date',
                        render: function(data, type, row) {
                            if (type === "sort" || type === "type") {
                                const [day, month, year] = data.split("/");
                                // Asegurar que día y mes tengan 2 dígitos
                                const d = day.padStart(2, '0');
                                const m = month.padStart(2, '0');
                                return `${year}${m}${d}`; // Formato YYYYMMDD para ordenar
                            }
                            return data;
                        }
                    },
                    {
                        data: null,
                        render: () => 'FACTURA',
                    },
                    {
                        data: 'document_number'
                    },
                    {
                        data: 'folio_sat'
                    },
                    {
                        data: 'due_date',
                        render(data, type, row) {
                            if (data) {
                                if (type === "sort" || type === "type") {
                                    const [day, month, year] = data.split("/");
                                    // Asegurar que día y mes tengan 2 dígitos
                                    const d = day.padStart(2, '0');
                                    const m = month.padStart(2, '0');
                                    return `${year}${m}${d}`; // Formato YYYYMMDD para ordenar
                                }
                                return data;
                            }

                            return "SIN FECHA";
                        }
                    },
                    {
                        data: 'days_overdue',
                        render: function(data, type, row) {
                            if (data) {
                                return data;
                            }

                            return "-";
                        }
                    },
                    {
                        data: 'total_amount',
                        render: function(data, type, row) {
                            // Para visualización: formatear como moneda
                            if (type === 'display') {
                                return '$' + parseFloat(data).toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }

                            // Para ordenamiento: mantener el valor numérico
                            return parseFloat(data);
                        }
                    },
                    {
                        data: 'amount_unpaid',
                        render: function(data, type, row) {
                            // Para visualización
                            if (type === 'display') {
                                return '$' + parseFloat(data).toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }

                            // Para ordenamiento
                            return parseFloat(data);
                        }
                    },
                    {
                        data: 'currency_name',
                        render: (data) => `
                <span class="flag flag-xs flag-country-${data === 'MEX' ? 'mx' : 'us'} me-2"></span>${data}`
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            return `<span class="badge bg-green-lt text-green-lt-fg">${data}</span>`;
                        }
                    },
                    {
                        data: 'memo',
                        render: function(data, type, row) {
                            if (data) {
                                return data;
                            }

                            return "-";
                        }
                    },
                    {
                        data: 'porcentaje_pagado',
                        render: function(data, type, row) {
                            // Usar parseFloat para asegurar formato numérico
                            const porcentaje = parseFloat(data).toFixed(2);

                            return `
                            <div class="d-flex flex-column">
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-success" style="width:${porcentaje}%"></div>
                                </div>
                                <small class="text-success fw-bold mt-1">${porcentaje}% pagado</small>
                            </div>`;
                        }
                    },


                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json",
                    "oPaginate": {
                        "sFirst": "«",
                        "sLast": "»",
                        "sNext": "Sig.",
                        "sPrevious": "Ant."
                    },
                    emptyTable: `<div class="empty">
                                            <div class="empty-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-click">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 12l3 0" />
                                                    <path d="M12 3l0 3" />
                                                    <path d="M7.8 7.8l-2.2 -2.2" />
                                                    <path d="M16.2 7.8l2.2 -2.2" />
                                                    <path d="M7.8 16.2l-2.2 2.2" />
                                                    <path d="M12 12l9 3l-4 2l-2 4l-3 -9" />
                                                </svg>
                                                </svg>
                                            </div>
                                            <p class="empty-title">Sin resultados</p>
                                            <p class="empty-subtitle text-secondary">
                                                Selecciona un cliente para obtener su información.
                                            </p>
                                        </div>
                    `
                },
            });

            Livewire.on('tabla-detalle-facturas', ({
                datos
            }) => {
                const formatted = datos.map(row => ({
                    ...row,
                    transaction_date: formatDate(row.transaction_date),
                    due_date: formatDate(row.due_date),
                }));
                //console.log(datos);
                table.clear().rows.add(datos).draw();
            });

            // Función para formatear fechas (opcional)
            function formatDate(dmy) {
                if (!dmy) return '';
                const [d, m, y] = dmy.split('/');
                return `${y}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`; // ISO ⇒ ordena bien
            }
        });
    </script>

    {{-- Factuas saldos a favor --}}
    <script>
        document.addEventListener('livewire:init', () => {

            const table2 = $('#tabla-saldos-a-favor').DataTable({
                data: [], // Inicialmente vacío
                ordering: true, // Permite ordenamiento manual
                "pageLength": 5,
                columns: [{
                        data: 'transaction_date',
                        render: function(data, type, row) {
                            if (type === "sort" || type === "type") {
                                const [day, month, year] = data.split("/");
                                // Asegurar que día y mes tengan 2 dígitos
                                const d = day.padStart(2, '0');
                                const m = month.padStart(2, '0');
                                return `${year}${m}${d}`; // Formato YYYYMMDD para ordenar
                            }
                            return data;
                        }
                    },
                    {
                        data: 'document_number',
                        render: function(data, type, row) {
                            let typeTransation = data.substring(0, 3);
                            if (typeTransation == "PAG") {
                                return "PAGO";
                            } else if (typeTransation == "NDC") {
                                return "NOTA DE CRÉDITO";
                            } else {
                                return "POLIZA";
                            }
                        }
                    },
                    {
                        data: 'document_number'
                    },
                    {
                        data: 'folio_sat'
                    },
                    {
                        data: 'total_amount',
                        render: function(data, type, row) {
                            // Para visualización: formatear como moneda
                            if (type === 'display') {
                                return '$' + parseFloat(data).toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }

                            // Para ordenamiento: mantener el valor numérico
                            return parseFloat(data);
                        }
                    },
                    {
                        data: 'payment_amount_unused',
                        render: function(data, type, row) {
                            // Para visualización
                            if (type === 'display') {
                                return '$' + parseFloat(data).toLocaleString('es-MX', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }

                            // Para ordenamiento
                            return parseFloat(data);
                        }
                    },
                    {
                        data: 'currency_name',
                        render: (data) => `
                <span class="flag flag-xs flag-country-${data === 'MEX' ? 'mx' : 'us'} me-2"></span>${data}`
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            return `<span class="badge bg-green-lt text-green-lt-fg">${data}</span>`;
                        }
                    },
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-MX.json",
                    "oPaginate": {
                        "sFirst": "«",
                        "sLast": "»",
                        "sNext": "Sig.",
                        "sPrevious": "Ant."
                    },
                    emptyTable: `<div class="empty">
                                            <div class="empty-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-click">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 12l3 0" />
                                                    <path d="M12 3l0 3" />
                                                    <path d="M7.8 7.8l-2.2 -2.2" />
                                                    <path d="M16.2 7.8l2.2 -2.2" />
                                                    <path d="M7.8 16.2l-2.2 2.2" />
                                                    <path d="M12 12l9 3l-4 2l-2 4l-3 -9" />
                                                </svg>
                                                </svg>
                                            </div>
                                            <p class="empty-title">Sin resultados</p>
                                            <p class="empty-subtitle text-secondary">
                                                Selecciona un cliente para obtener su información.
                                            </p>
                                        </div>
                    `
                },
            });

            Livewire.on('tabla-detalle-saldos-a-favor', ({
                datos
            }) => {
                const formatted = datos.map(row => ({
                    ...row,
                    transaction_date: formatDate(row.transaction_date),
                    due_date: formatDate(row.due_date),
                }));
                console.log(datos);
                table2.clear().rows.add(datos).draw();
            });

            // Función para formatear fechas (opcional)
            function formatDate(dmy) {
                if (!dmy) return '';
                const [d, m, y] = dmy.split('/');
                return `${y}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`; // ISO ⇒ ordena bien
            }
        });
    </script>
@endsection

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
                                        cuenta</a></li>
                            </ol>
                        </div>
                        <h2 class="page-title">
                            Estados de cuenta - {{ $search }}
                        </h2>
                    </div>
                    <div class="col-4">
                        <div class="btn-list">
                            <div class="position-relative w-100 ">
                                <div class="input-icon mb-1">
                                    <input type="text" value="" class="form-control w-100"
                                        placeholder="Buscar cliente…" wire:model.live.debounce.1000ms="search">
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

                                @if ($search)
                                    {{-- Contenedor flotante en la misma posición del input --}}
                                    <div class="dropdown-menu show w-100 shadow-none p-0"
                                        style="max-height:18rem;overflow-y:auto;z-index:1000;">

                                        @if (count($suggestions) > 0)
                                            @foreach ($suggestions as $c)
                                                {{-- Cada fila: enlace clicable que actúa como item --}}
                                                <a href="#"
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

                            <div class="col-sm-12 col-lg-4">
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
                                            <th>Nota de factura</th>
                                            <th>% Pagado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>25/06/2025</td>

                                            <td><span class="text-secondary">FACTURA</span></td>

                                            <td><a href="invoice.html" class="text-reset"
                                                    tabindex="-1">FAC-IXT78944</a>
                                            </td>

                                            <td>694780</td>

                                            <td>25/06/2025</td>

                                            <td>2</td>

                                            <td>$4,923.72</td>

                                            <td>$4,923.72


                                            </td>

                                            <td>
                                                <span class="flag flag-xs flag-country-mx me-2"></span>
                                                MEX
                                            </td>
                                            <td><span class="badge bg-green-lt text-green-lt-fg">Factura:
                                                    abierta</span>
                                            <td>
                                                PONIENTE
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex flex-column">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: 57%" aria-valuenow="57" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-1">
                                                        <small class="text-success fw-bold">57% pagado</small>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>25/06/2025</td>

                                            <td><span class="text-secondary">FACTURA</span></td>

                                            <td><a href="invoice.html" class="text-reset"
                                                    tabindex="-1">FAC-IXT78944</a>
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
                                            <td><span class="badge bg-green-lt text-green-lt-fg">Factura:
                                                    abierta</span>
                                            <td>
                                                PONIENTE
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex flex-column">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: 57%" aria-valuenow="57" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-1">
                                                        <small class="text-success fw-bold">57% pagado</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>25/06/2025</td>

                                            <td><span class="text-secondary">FACTURA</span></td>

                                            <td><a href="invoice.html" class="text-reset"
                                                    tabindex="-1">FAC-IXT78944</a>
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
                                            <td><span class="badge bg-green-lt text-green-lt-fg">Factura:
                                                    abierta</span>
                                            <td>
                                                PONIENTE
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex flex-column">
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: 57%" aria-valuenow="57" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-1">
                                                        <small class="text-success fw-bold">57% pagado</small>
                                                    </div>
                                                </div>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>25/06/2025</td>

                                            <td><span class="text-secondary">FACTURA</span></td>

                                            <td><a href="invoice.html" class="text-reset"
                                                    tabindex="-1">FAC-IXT78944</a>
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
                                            <td><span class="badge bg-green-lt text-green-lt-fg">Factura:
                                                    abierta</span>
                                            <td>
                                                PONIENTE
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>25/06/2025</td>

                                            <td><span class="text-secondary">FACTURA</span></td>

                                            <td><a href="invoice.html" class="text-reset"
                                                    tabindex="-1">FAC-IXT78944</a>
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
                                            <td><span class="badge bg-green-lt text-green-lt-fg">Factura:
                                                    abierta</span>
                                            <td>
                                                PONIENTE
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>25/06/2025</td>

                                            <td><span class="text-secondary">FACTURA</span></td>

                                            <td><a href="invoice.html" class="text-reset"
                                                    tabindex="-1">FAC-IXT78944</a>
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
                                            <td><span class="badge bg-green-lt text-green-lt-fg">Factura:
                                                    abierta</span>
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

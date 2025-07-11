<header class="navbar navbar-expand-md navbar-light bg-light sticky-top">
    <div class="container-xl">
        <!-- Logo -->


        <!-- Navbar toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbar-menu">
            @auth
                @if (auth()->user()->tipo === 'empleado')
                    <a href="{{ route('dashboard') }}" class="navbar-brand me-3">
                        <img src="{{ asset('img/futurama_logo2.png') }}" width="110" alt="Tabler Logo">
                    </a>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <span>Inicio</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title">Identificación BBVA</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route('clientes.importar') }}">Regitrar clientes </a>
                                <a class="dropdown-item" href="{{ route('clientes.procesar') }}">Identificar clientes</a>
                            </div>
                        </li>
                        <li
                            class="nav-item dropdown {{ request()->routeIs('conciliacion_pagos.*') || request()->routeIs('importar-facturas-usuario-vista') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title">Conciliación de pagos</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route('conciliacion_pagos.index') }}">Conciliar </a>
                                <a class="dropdown-item" href="{{ route('importar-facturas-usuario-vista') }}">Importar
                                    facturas</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown {{ request()->routeIs('estados_de_cuenta.*') || request()->routeIs('users.*') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#navbar-third" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title">Estados de cuenta</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow">
                                <a class="dropdown-item" href="{{ route('estados_de_cuenta.index') }}">Estado de cuenta</a>
                                <a class="dropdown-item" href="{{ route('users.index') }}">Gestionar Clientes</a>
                            </div>
                        </li>
                    </ul>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                            aria-label="Open user menu">
                            {{-- <span class="avatar avatar-sm"
                                style="background-image: url({{ asset('img/' . Auth::user()->foto) }})"></span> --}}
                            <span class="avatar rounded {{ session('avatar_class', 'bg-primary-lt') }}">
                                {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->apellido, 0, 1) }}
                                <span class="badge bg-success"></span>
                            </span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ Auth::user()->name }} {{ Auth::user()->apellido }}</div>
                                <div class="mt-1 small text-secondary">{{ Auth::user()->puesto }}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            {{-- <a href="#" class="dropdown-item">Status</a> --}}
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">Perfil</a>
                            {{-- <a href="#" class="dropdown-item">Feedback</a> --}}
                            <div class="dropdown-divider"></div>
                            {{-- <a href="./settings.html" class="dropdown-item">Settings</a> --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar
                                    sesión</a>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth


            {{-- navbar cliente --}}
            @auth
                @if (auth()->user()->tipo === 'cliente')
                    <div class="container-fluid d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="navbar-brand me-3">
                            <img src="{{ asset('img/futurama_logo2.png') }}" width="110" alt="Tabler Logo">
                        </a>

                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                                aria-label="Open user menu">
                                <span class="avatar rounded {{ session('avatar_class', 'bg-primary-lt') }}">
                                    {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->apellido, 0, 1) }}
                                    <span class="badge bg-success"></span>
                                </span>
                                <div class="d-none d-xl-block ps-2">
                                    <div>{{ Auth::user()->name }} </div>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" onclick="event.preventDefault(); this.closest('form').submit();">
                                        Cerrar sesión
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

        </div>
    </div>
</header>
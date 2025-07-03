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
                                Estados de cuenta
                            </h2>
                        </div>
                        <div class="col-auto">
                            <div class="btn-list">
                                <a href="#" class="btn btn-2 ms-auto" data-bs-toggle="modal"
                                    data-bs-target="#modal-consultar-cliente">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-search">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h1.5" />
                                        <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                        <path d="M20.2 20.2l1.8 1.8" />
                                    </svg>
                                    Buscar cliente
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-body">
                <div class="container-xl">
                    <div class="row g-4">
                        <div class="card p-3">
                            <div class="col-lg-12">
                                <!-- Contenedor para cargar los datos del cliente -->
                                <div id="customer-data-container" class="container-xl">
                                    @if (isset($selectedCustomer))
                                        @include('pagina-con-datos', ['customer' => $selectedCustomer])
                                    @endif
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
        document.addEventListener("DOMContentLoaded", function() {
            const tomSelect = new TomSelect("#select-beast-empty", {
                placeholder: "Selecciona un cliente",
                maxItems: 1,
                allowEmptyOption: true,
                create: false,
                dropdownParent: 'body',
                valueField: 'customer_id',
                labelField: 'altname',
                searchField: 'altname',
                load: function(query, callback) {
                    $.ajax({
                        url: "/netsuite/get-customers",
                        data: {
                            query: query
                        },
                        success: function(response) {
                            if (response.items && Array.isArray(response.items)) {
                                callback(response.items);
                            } else {
                                callback([]);
                            }
                        },
                        error: function() {
                            callback([]);
                        }
                    });
                },
                render: {
                    option: function(item, escape) {
                        return '<div>' + escape(item.altname) + '</div>';
                    },
                    item: function(item, escape) {
                        return '<div>' + escape(item.altname) + '</div>';
                    }
                },
                maxOptions: 100,
                onChange: function(customer_id) {
                    if (customer_id) {
                        loadCustomerData(customer_id);
                        // Cierra el modal después de seleccionar
                        bootstrap.Modal.getInstance(document.getElementById('modal-consultar-cliente'))
                            .hide();
                    } else {
                        // Limpia el contenedor si no hay cliente seleccionado
                        $('#customer-data-container').empty();
                    }
                }
            });

            function loadCustomerData(customer_id) {
                // Mostrar loader mientras carga
                $('#customer-data-container').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando datos del cliente...</p>
            </div>
        `);

                $.ajax({
                    url: "/load-customer-data", // Nueva ruta que crearemos
                    method: 'GET',
                    data: {
                        customer_id: customer_id
                    },
                    success: function(response) {
                        $('#customer-data-container').html(response);
                    },
                    error: function(xhr, status, error) {
                        $('#customer-data-container').html(`
                    <div class="alert alert-danger">
                        Error al cargar los datos del cliente. Intente nuevamente.
                    </div>
                `);
                        console.error('Error:', error);
                    }
                });
            }
        });
    </script>
@endsection

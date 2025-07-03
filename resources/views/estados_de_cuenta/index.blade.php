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
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h1.5" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M20.2 20.2l1.8 1.8" /></svg>
                                    Buscar cliente
                                </a>
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
            new TomSelect("#select-beast-empty", {
                placeholder: "Selecciona un cliente",
                maxItems: 1,
                allowEmptyOption: true,
                create: false,
                dropdownParent: 'body',
                valueField: 'customer_id', // Usar el ID como valor
                labelField: 'altname', // Usar altname para mostrar
                searchField: 'altname', // Buscar por altname
                load: function(query, callback) {
                    $.ajax({
                        url: "/netsuite/get-customers",
                        data: {
                            query: query
                        },
                        success: function(response) {
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
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
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

                onInitialize: function() {
                    console.log('TomSelect Initialized');
                }
            });
        });
    </script>
@endsection
